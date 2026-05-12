# Plano: Melhoria de Manutenibilidade e Análise Estática — br.tag

## Contexto

O projeto roda PHP 8.3 mas foi construído sobre Yii 1.1.31 (era PHP 5.4), sem namespaces na grande maioria das classes. Isso cria três problemas concretos:

1. **Análise estática limitada** — sem PHPStan configurado, erros de tipo e métodos inexistentes passam despercebidos em tempo de desenvolvimento.
2. **Classes em escopo global** — ~767 arquivos PHP em `app/` e ~535 em módulos sem namespace, dificultando rastrear de onde uma classe vem e impedindo que o autocomplete/análise entenda dependências.
3. **Contexto insuficiente para IA** — agentes de IA não têm uma visão estruturada da arquitetura, dificultando sugestões corretas.

**Ferramentas já existentes que precisam ser melhor aproveitadas:**
- `rector.php` — configurado com `TYPE_DECLARATION` + `UP_TO_PHP_83`, mas sem composer script
- `.php-cs-fixer.php` — configurado, mas sem CI step
- `ruleset.xml` (PHPMD) — configurado
- SonarCloud — rodando no CI (`.github/workflows/sonarcloud-analysis.yml`)
- `app/domain/` e `app/repository/` — código moderno, candidatos a namespaces PSR-4

---

## Estratégia

Migração total de namespaces é inviável sem risco alto (Yii::import() path-based + 1300+ arquivos). A abordagem é **incremental e não destrutiva**:

1. Adicionar PHPStan (ganho imediato sem tocar em código legado)
2. Habilitar PSR-4 apenas para `app/domain/` e `app/repository/` (código já moderno)
3. Criar composer scripts + Makefile para facilitar a execução de ferramentas
4. Integrar PHPStan no CI
5. Documentar arquitetura no CLAUDE.md para IA e desenvolvedores

---

## Fase 1 — PHPStan com stubs para Yii 1.x

**Objetivo:** detectar erros de tipo em código novo sem quebrar o build do legado.

### 1.1 Instalar pacotes

```bash
composer require --dev phpstan/phpstan
```

### 1.2 Criar `phpstan.neon`

```neon
parameters:
    level: 3
    paths:
        - app/domain
        - app/repository
        - app/components
    excludePaths:
        - app/vendor
        - app/runtime
    bootstrapFiles:
        - vendor/yiisoft/yii/framework/yii.php
        - phpstan-bootstrap.php
    ignoreErrors: []
    reportUnmatchedIgnoredErrors: false
includes:
    - phpstan-baseline.neon
```

### 1.3 Criar `phpstan-bootstrap.php`

Carrega Yii e a config do console para que PHPStan conheça `Yii::app()`, `CActiveRecord`, etc.:

```php
<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
$config = require __DIR__ . '/app/config/console.php';
Yii::createConsoleApplication($config);
```

### 1.4 Gerar baseline

Rodar `vendor/bin/phpstan analyse --generate-baseline` para criar `phpstan-baseline.neon`, isolando os erros pré-existentes. CI passará, mas novos erros serão barrados.

**Arquivos criados/modificados:**
- `phpstan.neon` (novo)
- `phpstan-baseline.neon` (gerado pelo comando)
- `phpstan-bootstrap.php` (novo)
- `composer.json` — adicionar `phpstan/phpstan` em `require-dev`

---

## Fase 2 — PSR-4 para `app/domain/` e `app/repository/`

**Objetivo:** dar namespaces ao código mais moderno, habilitando análise completa e autocomplete preciso nesses arquivos.

### 2.1 Adicionar autoload PSR-4 no `composer.json`

```json
"autoload": {
    "psr-4": {
        "BrTag\\Domain\\": "app/domain/",
        "BrTag\\Repository\\": "app/repository/"
    }
}
```

### 2.2 Adicionar namespaces nos arquivos de `app/domain/`

Estrutura atual → namespace proposto:

| Diretório | Namespace |
|-----------|-----------|
| `app/domain/admin/usecases/` | `BrTag\Domain\Admin\Usecases` |
| `app/domain/admin/exceptions/` | `BrTag\Domain\Admin\Exceptions` |
| `app/domain/grades/usecases/` | `BrTag\Domain\Grades\Usecases` |
| `app/domain/grades/exceptions/` | `BrTag\Domain\Grades\Exceptions` |

Cada arquivo ganha `namespace BrTag\Domain\...;` na primeira linha.
Classes Yii sem namespace que são usadas nesses arquivos (ex.: `GradeRules`, `Yii`) ficam acessíveis como classes globais — não requerem `use`.

### 2.3 Atualizar `app/config/main.php` — seção `import`

Remover as linhas:
```php
'application.domain.admin.exceptions.*',
'application.domain.admin.usecases.*',
'application.domain.grades.exceptions.*',
'application.domain.grades.usecases.*',
```

O Composer PSR-4 passa a ser o mecanismo de carregamento dessas classes.

### 2.4 Atualizar os controllers que instanciam essas classes

Trocar `Yii::import('application.domain...')` + `new ClassName()` por `use BrTag\Domain\...\ClassName;` + `new ClassName()`.

Identificar com:
```bash
grep -r "Yii::import.*domain" app/
grep -r "new CopyGradeStructUsecase\|new CalculateGrade" app/
```

**Arquivos críticos a modificar:**
- `app/domain/**/*.php` — todos (~8–15 arquivos)
- `app/repository/FormsRepository.php`
- `app/repository/ReportsRepository.php`
- `app/config/main.php`
- Controllers que fazem `Yii::import('application.domain.*')`
- `composer.json`

---

## Fase 3 — Composer Scripts + Makefile

**Objetivo:** um comando único para cada ferramenta de qualidade.

### 3.1 Adicionar seção `scripts` no `composer.json`

```json
"scripts": {
    "lint": "php-cs-fixer fix --dry-run --diff",
    "fix": "php-cs-fixer fix",
    "analyse": "phpstan analyse",
    "rector": "rector process",
    "mess": "phpmd app/ text ruleset.xml"
}
```

### 3.2 Criar `Makefile`

```makefile
lint:
	composer lint

fix:
	composer fix

analyse:
	composer analyse

rector-dry:
	vendor/bin/rector process --dry-run

rector-run:
	vendor/bin/rector process

mess:
	composer mess
```

**Arquivo criado:** `Makefile`

---

## Fase 4 — PHPStan no CI (GitHub Actions)

Adicionar step no `.github/workflows/sonarcloud-analysis.yml`, dentro do job `Quality`:

```yaml
- name: Setup PHP
  uses: shivammathur/setup-php@v2
  with:
    php-version: '8.3'
    tools: composer

- name: Install dependencies
  run: composer install --no-interaction --prefer-dist

- name: Run PHPStan
  run: vendor/bin/phpstan analyse --no-progress --error-format=github
```

**Arquivo modificado:** `.github/workflows/sonarcloud-analysis.yml`

---

## Fase 5 — CLAUDE.md: documentação de arquitetura para IA

Criar ou atualizar `CLAUDE.md` na raiz do projeto com:

- **Arquitetura**: Yii 1.1.31, PHP 8.3, padrão MVC com módulos
- **Dois mundos de código**:
  - Legado (`app/models/`, `app/controllers/`, `app/modules/`): sem namespace, usa `Yii::import()`
  - Moderno (`app/domain/`, `app/repository/`): namespace `BrTag\*`, carregado via Composer PSR-4
- **Regra para código novo**: qualquer nova classe em `app/domain/` ou `app/repository/` DEVE ter namespace PSR-4; novos módulos devem ser discutidos antes de escolher abordagem
- **Type hints**: obrigatório em código novo, Rector aplica automaticamente
- **Como rodar análises**: `make analyse`, `make fix`, `make rector-dry`
- **Estrutura de módulos**: cada módulo em `app/modules/[nome]/` tem controllers, models, views próprios
- **Models**: estendem `TagModel` → `CActiveRecord`; acessados via `ModelName::model()->find()`

**Arquivo criado/modificado:** `CLAUDE.md`

---

## Ordem de execução recomendada

1. Fase 1 (PHPStan) — valor imediato, zero risco, pode ser feito em PR isolado
2. Fase 3 (scripts) — melhoria de DX, PR isolado
3. Fase 4 (CI) — depende da Fase 1
4. Fase 2 (PSR-4 domain/) — maior impacto, requer atenção nos callers
5. Fase 5 (CLAUDE.md) — pode ser feito junto com qualquer fase

---

## Verificação (como testar)

- **PHPStan**: `composer analyse` deve terminar sem erros novos (baseline cobre os antigos)
- **PSR-4**: `composer dump-autoload` sem erros; `new BrTag\Domain\Admin\Usecases\CopyGradeStructUsecase()` funciona sem `Yii::import()`
- **CI**: abrir um PR e verificar que o job `Quality` passa incluindo o step PHPStan
- **Aplicação**: `php index.php` (console) e a aplicação web devem funcionar normalmente após cada fase
- **Rector (dry-run)**: `make rector-dry` não deve propor mudanças que quebrem a aplicação

---

## O que este plano NÃO faz (e por quê)

- **Não migra `app/models/`, `app/controllers/`, `app/modules/` para namespaces** — risco muito alto, retorno baixo; o SonarCloud + PHPStan em `app/domain/` já cobre o código que mais cresce
- **Não troca `Yii::import()` por autoload globalmente** — quebraria o framework sem ganho proporcional
- **Não atualiza o Yii para v2/v3** — fora de escopo; seria uma reescrita


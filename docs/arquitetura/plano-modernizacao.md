# Plano de Modernização do TAG

> Segurança · Fundação (namespaces e análise estática) · Services · Testes · API JSON/JWT · JS modular · CSS com fonte única · Execução por vários agentes de IA

| | |
|---|---|
| **Status** | Aprovado em 2026-11-27 |
| **Backlog** | [`.agents/tasks/`](../../.agents/tasks/README.md), lançamentos R0 a R8 |
| **Agentes especialistas** | [`.agents/agents/`](../../.agents/agents/): guardião do plano, refatorador e QA (seção 8.7) |
| **Substitui** | `docs/namespaces.md` e `docs/php83_yii1_static_analysis_plan.md` |

Este documento é a referência para pessoas e agentes de IA. Quando uma decisão muda, registre um ADR em `docs/adrs/` e atualize este plano no mesmo PR.

## 1. Contexto

O TAG roda PHP 8.3, mas está construído sobre o Yii 1.1, um framework da era PHP 5. Os números de hoje:

- **Namespaces e autoload:** 625 das 650 classes estão no namespace global, e o Composer não carrega o código próprio do projeto. Por isso nem o PHPStan nem uma IA conseguem pegar import faltando ou assinatura errada.
- **Controllers gordos:** são 66 controllers, 663 actions e 28,4 mil linhas. Dessas actions, 221 leem `$_POST`/`$_GET`, 73 têm SQL cru e 158 montam o JSON à mão.
- **Testes:** não há nenhum teste unitário. Existem só 14 testes de aceitação, frágeis e fora do CI.
- **JS:** cerca de 24,5 mil linhas procedurais, com 300 funções globais, 212 URLs `?r=` escritas à mão e até 3 versões de jQuery na mesma página.
- **CSS:** cada página carrega cerca de 17 folhas. Bootstrap 2, AdminKIT (183 KB) e arquivos órfãos convivem com o design system em SASS.

**O que foi pedido:**
1. Controllers de API com JSON + JWT.
2. Uma camada de services.
3. Testes unitários.
4. Uma arquitetura melhor para o JS.
5. Remover o CSS sem uso e ficar com uma fonte única.
6. Do pedido anterior: reduzir os problemas da falta de namespaces (análise estática, imports, assinaturas) e facilitar a evolução do código com IA.

**Perguntas feitas na revisão deste plano:**
- O namespace traz ganho real, mesmo com os models no namespace global?
- Vale a pena trocar o framework? Se sim, quando?
- Dá para quebrar o trabalho em tarefas isoladas, com instruções claras e skills próprias, para vários agentes ou provedores de IA executarem?

**Decisões já tomadas:**
- A API atende **integrações entre sistemas** (client credentials com escopos) **e** um **app mobile/SPA** (login → JWT de vida curta + refresh rotativo). O frontend web continua com sessão e cookie.
- Código novo com namespace mora em **`app/src/`**, com PSR-4 `Tag\`, organizado por contexto de negócio. Os módulos Yii (web e API) ficam só como camada HTTP.
- JS: **módulos ES empacotados com Vite**, mantendo o jQuery e os plugins, migrando página a página.
- Ferramentas de IA: **Claude Code, Codex e Antigravity**, com três agentes especialistas (guardião do plano, refatorador e QA; seção 8.7).

**Resultado esperado:**
- Código novo tipado, testado e analisado no nível máximo.
- Uma API segura e multi-tenant que usa os mesmos services da web.
- Front modular com um único pipeline de assets.
- Trabalho dividido em cards e skills que qualquer agente de IA executa, todos julgados pelos mesmos gates de CI.

| Pedido | Onde está no plano |
|---|---|
| API JSON + JWT | B4, B5 (pré-requisitos: B0–B2) |
| Camada de services | B3 e ondas seguintes |
| Testes unitários | B1 (infraestrutura), B3 (primeiros testes), F1/F3 (JS) |
| Arquitetura do JS | F1–F4, F6 |
| CSS sem sobras e com fonte única | F0 (limpeza), F5 |
| Namespaces, análise estática e IA | B1, seção 3.1, seção 10 |
| Namespaces × models globais | seção 3.1 |
| Trocar de framework? | seção 9 |
| Tarefas isoladas para vários agentes/provedores | seção 8 |
| Agentes especialistas (guardião, refatorador, QA) | seção 8.7 |

## 2. ⚠️ Achados críticos — resolver antes de tudo

Todos foram verificados no código.

| # | Achado | Onde |
|---|---|---|
| 1 | **Um visitante não logado consegue criar usuário com qualquer papel, inclusive superuser.** `CreateUser` está liberado para `'*'` e o papel vem de `$_POST['Role']`. | `app/modules/systemadmin/controllers/DefaultController.php` (accessRules ~L23; `actionCreateUser` L877) |
| 2 | Qualquer usuário logado vê o `phpinfo()`, que expõe as variáveis de ambiente, inclusive a senha do banco (a mesma para todos os tenants). | mesmo arquivo, `actionPHPConfig` L946 |
| 3 | 24 dos 66 controllers não têm controle de acesso que funcione: 6 definem `accessRules()` mas não `filters()`, e 18 não definem nada. Exemplo: `AdministratorController::actionCreateUser` e `actionImport`, que executa SQL vindo de upload. | `app/controllers/AdministratorController.php`, `CensoController`, `SiteController`, sedsp, sagres, quiz, lunch, gestaopresente… |
| 4 | **SQL injection:** valores de `$_GET`/`$_POST` e de `user->school`/`user->year` vão concatenados no SQL. `ChangeSchool` e `ChangeYear` gravam o que vem no POST sem validar, o que permite trocar para escola alheia e abre injeção de segunda ordem. | `app/controllers/SiteController.php` L140–158, L352, L433; `AdministratorController.php` L59–83. Candidatos: grades `DefaultController` L56, `EnrollmentController` L117, `ManagementschoolController` L148–236, `SchoolController` L98, schoolreport `DefaultController` L150, classroom `DefaultController` L380, wizard `ConfigurationController` L189 |
| 5 | **Sessão e CSRF:** a proteção CSRF está desligada. A sessão guarda o registro `Users` inteiro, com o hash da senha, e não fica amarrada ao tenant. | `app/config/main.php`; `app/components/UserIdentity.php` L42 |
| 6 | `Yii::app()->user->checkAccess()` sempre retorna falso, porque `user->id` é o username e `auth_assignment.userid` é numérico. | inventory: `MovementController` L32/44/104, `InventoryMovement`, `InventoryStock` |
| 7 | **Ferramentas e credenciais expostas:** gii com `ipFilters ['*']`, `db2` com credencial fixa, Bearer fixo no código (foods) e basic auth fixo (SED). | `main.php` L80–87 e ~L210; `FoodrequestController` L168–272; `FoodnoticeController` L243/270; `sedsp/datasources/sed/UsuarioSEDDataSource.php` L9 |
| 8 | **Provavelmente** o nginx expõe o repositório inteiro: o Dockerfile troca o root para `/app`, o que deixa acessíveis logs, exports JSON com dados de alunos e o `index-test.php`. **Confirmar com curl.** | `Dockerfile` (sed no `default.conf`) |
| 9 | Em produção `YII_ENABLE_EXCEPTION_HANDLER=false`, então todo 400/403/404 vira 500 em branco. | `config.php` |

**Bugs de negócio encontrados no caminho.** Cada um vira uma issue com decisão de produto. Nenhum é corrigido em silêncio durante o refactor.
- `grades/DefaultController.php` L374–379 passa `$frequency` na posição de `$stage` do `ChageStudentStatusByGradeUsecase`. Na L28 desse usecase há `$this->$stage = …`. Resultado: a frequência é ignorada nesse fluxo.
- `CalculateNumericGradeUsecase` reimplementa as fórmulas com um arredondamento diferente do `ApplyFormulaOnGradesUsecase`.
- Frequência de 0% é tratada como "sem frequência".
- `actionCalculateFinalMedia` engole as exceções e responde 200.

## 3. Arquitetura alvo

```
 Navegador (sessão + CSRF)                      App mobile/SPA · Integrações (JWT)
          │                                                 │
 index.php → app/config/main.php                api.php → app/config/api.php
 módulos Yii web: controllers magros            app/modules/api: controllers v1 magros
          └────────────────────────┬────────────────────────┘
                                   ▼
  app/src   (PSR-4 Tag\, strict_types, PHPStan nível max, sem baseline)
   ├─ <Contexto>/Application         um caso de uso por classe: Input DTO → Result DTO
   ├─ <Contexto>/Domain              regras puras (sem Yii, sem banco)    → testes unitários
   ├─ <Contexto>/Infrastructure/Yii  repositórios sobre ActiveRecord/SQL  → testes de integração
   └─ Shared/  Auth (UserContext, JWT) · Db (TransactionManager) · Http (ExceptionMapper)
               Frontend (ViteManifest) · Infrastructure/Yii (WebUser, HttpRequest, container)
                                   ▼
  app/models (ActiveRecord legados, globais)  ──►  MySQL por tenant
```

**Regras do anel `app/src`** (o PHPStan barra quem desrespeitar):
- `Domain/` e `Application/` não usam `Yii::app()`, superglobais, `echo`/`exit` nem ActiveRecord.
- Dependências entram pelo construtor (PHP-DI).
- Cada caso de uso é uma classe: `VerboObjetoService` com um único método `handle()`.

### 3.1 Namespaces × models globais: o ganho e a convivência

**O ganho vem do trio namespace + autoload do Composer + PHPStan estrito.** Namespace sozinho só organiza. Um exemplo do próprio código:

```php
// Hoje (grades/DefaultController.php L374–379): a frequência cai no parâmetro $stage e nada acusa
new ChageStudentStatusByGradeUsecase($gradeResult, $gradeRules, count($std['grades']), $frequency);

// Em app/src, com tipos: o PHPStan barra no CI — "Parameter #4 $stage expects Stage, float given"
public function __construct(GradeResultData $result, GradeRulesData $rules, int $units, Stage $stage, ?float $frequency = null)
```

**Os outros ganhos**
- **Dependências explícitas.** Os `use` deixam à vista de onde vem cada classe, para pessoa e para IA. Hoje há 120 `Yii::import` com `*` e 9 nomes de classe repetidos: o `FoodMenu` de `app/models` e o de `foods/models`, por exemplo, só são diferenciados pela ordem de import.
- **Falha no CI, não em produção.** Classe inexistente, nome com caixa errada (passa no Windows e quebra no Linux) e método inexistente são barrados antes do deploy.

**Models globais funcionam sem problema.** O código em `app/src` importa o model legado:

```php
namespace Tag\Grades\Infrastructure\Yii;

use GradeRules;                        // model legado: continua global, só é importado
use Tag\Grades\Domain\GradeRulesData;

final class GradeRulesRepository
{
    public function find(int $id): ?GradeRulesData
    {
        $record = GradeRules::model()->findByPk($id);
        return $record === null ? null : GradeRulesData::fromRecord($record);
    }
}
```

**O único cuidado:** sem o `use`, o PHP procura a classe dentro do namespace atual (`Tag\…\GradeRules`) e falha. Duas ferramentas cobrem isso: o php-cs-fixer (`global_namespace_import`) acrescenta os imports, e o PHPStan acusa os que faltarem.

**Como o legado é carregado**
- **Em execução:** o autoloader do Yii continua carregando os models. Na API, no console e nos testes, o arquivo `app/config/parts/imports.php` replica os imports da web.
- **Depois de renomear os 9 nomes duplicados:** um `classmap` do Composer para `app/models` e `app/components` torna esse carregamento determinístico.
- **Na análise estática:** `scanDirectories` mais o PHPDoc `@property`, que 86 dos 91 models já têm.

**Os ActiveRecords *não* devem ganhar namespace.**
- **Formulários:** o Yii monta os nomes dos campos a partir da classe. `CHtml::modelName` troca `\` por `_`, então `StudentIdentification[name]` viraria `Tag_Models_StudentIdentification[name]`.
  - O código lê `$_POST['NomeDoModel']` em 224 pontos, espalhados por 36 arquivos.
  - Os IDs dos campos (`StudentIdentification_name`) também mudariam, e o JS seleciona elementos por esses IDs.
- **Relações e `::model()`** usam o nome da classe como string, e o Gii gera código nesse padrão.

O custo seria alto para pouco ganho. Os models ficam encapsulados nos repositórios de `Infrastructure/Yii`, e a regra de negócio migra aos poucos para `Domain/`.

## 4. Princípios de execução

- **Estrangulamento incremental.** Cada fase vai para produção sozinha. Código novo já nasce no padrão novo; o legado migra quando for tocado ou quando virar piloto.
- **Primeiro registrar, depois bloquear (log → enforce).** Mudanças de comportamento arriscadas, como controle de acesso e CSRF, começam só registrando o que bloqueariam. Dá para voltar atrás por variável de ambiente.
- **Golden master antes de refatorar regra crítica** (notas, frequência, validadores): grava-se a saída atual e o código novo tem de reproduzi-la.
- **Catraca no CI.** Os contadores de dívida só podem cair: `?r=` escrito à mão, globais JS, `<script>` e `style=` inline, classes do Bootstrap 2, `alert(` e erros do PHPStan no legado.
- **Fundação em série, módulos em paralelo.** Contratos e implementações de referência vêm primeiro, com dono único. Só depois o trabalho se abre para vários agentes (seção 8).
- **Lição de 2024.** A tentativa anterior com Vite (branch `refact/rollupjs`, maio de 2024) empacotou jQuery, plugins e legado num bundle ES único e não foi adiante. Agora o Vite cuida só de código novo. Bibliotecas e legado continuam como scripts clássicos até serem migrados.

## 5. Roadmap

```
Backend:  B0 Hotfixes ─► B1 Fundação ─┬─► B2 Segurança estrutural (log → enforce) ───────┐
                                      └─► B3 Services + testes (grades → foods → …) ─────┴─► B4 API infra + M2M ─► B5 API app
Front:    F0 Rede visual + limpeza ─► F1 Vite + qualidade ─► F2 jQuery único ─► F3 Núcleo + ponte + piloto ─► F4 Páginas ─► F6 jQuery 3
                                      └─► F5 CSS fonte única (contínuo)
Agentes:  agentes + backlog (`.agents/`) ─► R1-AGT skills e scripts ─► fan-out por módulo depois dos pilotos (seção 8)
Transversal: ADRs · regras em .agents · catracas no CI · checkpoint de framework em ~12 meses (seção 9)
```

| Fase | Tamanho | Depende de |
|---|---|---|
| B0 Hotfixes de segurança | P | — |
| AGT0 Skills + cards (esqueleto) | P | — |
| B1 Fundação | M (o PR de autoload entra primeiro e é P) | B0 |
| B2 Segurança estrutural | M (+ ~2 semanas em modo log) | B1 (autoload) |
| B3 Services + testes (piloto grades) | M–G, depois em ondas | B1 |
| B4 API: infraestrutura + piloto M2M | M | B2; services de foods (onda da B3) |
| B5 API: app mobile/SPA | M | B4 |
| F0 Rede de segurança + limpeza | M | — |
| F1 Vite + qualidade | M | F0; Dockerfile multi-stage (B1) |
| F2 jQuery único | P–M | F0 |
| F3 Núcleo + ponte + piloto | M | F1, F2 |
| F4 Migração de páginas | contínuo | F3 |
| F5 CSS fonte única | M + contínuo | F1 |
| F6 jQuery 3 | M | F4 avançada |

Legenda: P ≈ dias · M ≈ 1–4 semanas · G ≈ mais de 1 mês, para 1–2 pessoas. Com vários agentes, as fases contínuas (F4, F5, ondas da B3) encurtam; as de fundação não.

**Fases × lançamentos** (backlog em `.agents/tasks/README.md`). Os lançamentos são como as fases chegam à produção:

| Lançamento | Fases |
|---|---|
| R0 | B0 |
| R1 | B1, F0 e as skills |
| R2 | B2 em modo log, piloto da B3, F1 e F2 |
| R3 | B2 bloqueando, B4, F3 e início da F5 |
| R4–R7 | ondas por módulo (B3, B5, F4 e F5) |
| R8 | F6 e fim da F5 |

**Onde as trilhas se encontram:**
- O CSRF (B2) precisa que o front envie o token. Até a F3, isso vem de uma `<meta name="csrf-token">` no layout mais um `$.ajaxSetup` provisório no `common.js`.
- O nginx versionado (B0) também passa a atender `/api/` (B4) e o cache de `/build/` (F1).
- O Dockerfile multi-stage (B1) ganha o estágio Node na F1.
- A suíte Playwright (F0) é a rede de segurança para ligar o bloqueio da B2.

## 6. Backend

### B0 — Hotfixes de segurança (imediato, PRs pequenos)

**0.1 Correções urgentes (dias)**
- systemadmin: `CreateUser` e as demais actions passam a exigir admin ou superuser. Remover `actionPHPConfig`.
- `AdministratorController::actionCreateUser`: lista dos papéis que cada perfil pode atribuir.
- `actionImport`: só superuser.

**0.2 SQL injection** — dá para paralelizar: um card por controller, com a skill `tag-secure-controller`.
- Corrigir os itens 4 da seção 2 com `bindValue`, `addInCondition` ou cast.
- `ChangeSchool` passa a validar contra `usersSchools`; `ChangeYear` só aceita inteiro.
- Semgrep com regras de SQL injection para PHP no CI, rodando nos arquivos alterados.

**0.3 Segredos e infraestrutura**
- gii só com `YII_DEBUG && GII_ENABLED` e acesso de IP local.
- `db2`, Bearer do foods e basic auth do SED vão para env **e são rotacionados**, porque já estão no histórico do git.
- nginx versionado em `docker/nginx/default.conf`:
  - só `index.php` e `api.php` executam PHP;
  - `deny` para `app/`, `vendor/`, `tests/`, `docker/`, `scripts/`, `db/`, dotfiles e `*.lock|*.neon|*.yml|*.md|*.sql|*.log`.
- Remover o `index-test.php`.
- Exports saem do web root e passam a ser baixados por uma action autenticada.
- Ligar o exception handler do Yii em produção. Com `YII_DEBUG=false` ele não vaza stack trace.

### B1 — Fundação: autoload, análise estática, CI e infraestrutura de testes

**`composer.json`.** O primeiro PR desta fase é pequeno: autoload e `yiic`.
- Declarar `"php": "^8.3"`, `config.platform.php`, `"prefer-stable": true` e `sort-packages`.
- `autoload.psr-4`: `Tag\` → `app/src/`, e `SagresEdu\` e `Genericos\` para o sagres.
- `classmap` para o `SagresConsultModel.php`. Com isso saem os 13 `require_once` do `index.php`.
- `autoload-dev`: `Tag\Tests\` → `tests/`.
- Num PR separado, sair das versões dev: remover `phpunit/phpunit-selenium` e a trava de `php-code-coverage`; passar o Rector para `^2`; atualizar o php-cs-fixer.

**Yii e PHP**
- Atualizar o `yiisoft/yii` de 1.1.31 para **1.1.32**, a versão que dá suporte ao PHP 8.4.
- Preparar a subida para o **PHP 8.4** antes de 31/12/2027, quando acaba o suporte de segurança do PHP 8.3 (seção 9).

**Bootstrap e injeção de dependência**
- `app/yiic.php` passa a carregar `vendor/autoload.php`.
- Os imports compartilhados vão para `app/config/parts/imports.php`, usado por main, console, api e test.
- PHP-DI 7 vira o componente Yii `container`, compilado em produção.
- Controllers pegam services com `$this->service(X::class)`. O service locator fica só na borda HTTP.
- Um teste resolve todos os services de `app/src`, para pegar dependência quebrada.

**PHPStan em dois níveis.** Substitui o `phpstan.neon` atual, cujos caminhos `app/domain` e `app/repository` não existem mais e cuja baseline é 85% de entradas mortas.

| | `phpstan.src.neon` | `phpstan.legacy.neon` |
|---|---|---|
| Caminhos | `app/src`, `app/modules/api`, `tests` | components, models, controllers, modules (sem views), commands |
| Nível | max | 2, subindo 1 por trimestre |
| Extras | strict-rules, deprecation-rules, phpstan-phpunit, `spaze/phpstan-disallowed-calls` (aplica as regras do anel) | — |
| Baseline | nenhuma | regenerada, **uma por módulo** (evita conflito entre agentes), com `reportUnmatchedIgnoredErrors: true` |
| Símbolos do legado | `scanDirectories` em models e components | — |

O stub `phpstan/stubs/yii.stub` descreve `Yii::app()` com `@property-read` para cada componente (user, db, authManager, features, notifier…) e o `WebUser` com `school`, `year`, `loginInfos` e `usersSchools`. É isso que permite apontar classe ou método inexistente e assinatura errada **também quando o código novo chama o legado**.

**Rector e php-cs-fixer**
- Rector só em `app/src` e `tests`, sem o set NAMING. `composer rector` passa a ser `--dry-run`.
- php-cs-fixer com uma configuração própria para `app/src`: `@PER-CS2.0`, `declare_strict_types`, `ordered_imports`, `no_unused_imports` e `global_namespace_import`.
- Tirar o `.php-cs-fixer.cache` do git.

**Higiene que prepara o autoload futuro**
- Renomear os 5 arquivos cujo primeiro tipo declarado não bate com o nome do arquivo (por exemplo, `app/models/Classroom.php` começa com `enum PeriodOptions`).
- Separar os arquivos que declaram várias classes.
- Renomear os 9 nomes de classe duplicados, para liberar o `classmap`.
- Proibir novas classes globais com nome repetido.

**Docker multi-stage**
- Estágio `dev`: pcov, xdebug e dependências de desenvolvimento.
- Estágio `prod`: `composer install --no-dev -o`, sem `tests/`, `docker/` e `scripts/`.
- Remover o `use PHPUnit\…` de `student/views/studentimc/studentIndex.php`.

**CI** (`.github/workflows/ci.yml`)
- Ordem dos jobs:
  1. lint: parallel-lint e php-cs-fixer nos arquivos alterados;
  2. PHPStan src e legacy;
  3. testes unitários, com cobertura via pcov;
  4. testes de integração, com serviço mysql:8.0;
  5. Sonar com cobertura;
  6. build e push, com `needs:` nos anteriores.
- Hoje o `docker-build-push.yml` publica a imagem sem esperar a qualidade.

**Infraestrutura de testes**
- Remover `app/tests/` (PHPUnit-Selenium quebrado) e o teste órfão do sedsp.
- Codeception 5 + PHPUnit 11 ou 12. Validar antes com `composer require --dev -W --dry-run …`.
- `phpunit.xml.dist` com duas suítes:
  - **Unit:** sem banco e sem aplicação Yii.
  - **Integration:** `CConsoleApplication` com `app/config/test.php` reescrito (hoje está quebrado: depende de `INSTANCE` e `TAG_VERSION`), banco via `TEST_DB_*` e transação desfeita ao fim de cada teste.
- Banco de teste:
  - `scripts/dump-test-schema.sh` gera `tests/_data/schema.sql`, versionado, com a estrutura e as tabelas de referência (`edcenso_*`, `auth_*`, `grade_calculation`).
  - Serviço `mysql-test` com tmpfs no docker-compose.
  - O CI aplica as migrations da versão corrente sobre esse schema, o que valida o SQL antes do release.
- Os builders vão para `tests/Support/Builders`, sem fazer bootstrap do Yii. Reaproveitar `tests/providers/CustomProvider.php`.
- Testes de aceitação: credenciais por env, `sleep()` trocado por `waitFor*`, execução noturna fora do gate de PR.
- Novos scripts no Composer: `test:unit`, `test:integration` e `analyse` (roda as duas configurações), mais `ci:*` sem `docker exec`.

### B2 — Segurança estrutural (log → enforce)

As classes novas desta fase ficam em `app/src/Shared/Infrastructure/Yii/` e entram no config via `::class`, padrão que o `main.php` já usa com o Sentry.

**Sessão e identidade: `WebUser extends CWebUser`**
- O prefixo de estado passa a incluir o `INSTANCE`, e o `Controller::init()` confere um estado `tenant`. Assim uma sessão não vale em outro tenant.
- `checkAccess()` passa a usar o id numérico. Isso muda o comportamento do inventory, então vai com nota de release e teste.
- `loginInfos` vira o objeto de valor `LoginInfo{id, username, name}`. Antes, confirmar por grep quais campos são lidos. Com isso o hash da senha sai da sessão.
- Componente `session` com cookie `httponly`, `secure` e `SameSite=Lax`.

**Negar por padrão**
- `Controller::runActionWithFilters()` passa a consultar uma `AccessPolicy`. É o único gancho que nenhum controller sobrescreve.
- Rotas públicas ficam listadas por módulo em `app/config/public-routes/<módulo>.php`, carregados por glob: login, pré-matrícula online, cadastro de agricultor e outras.
- `ACCESS_POLICY_MODE=log|enforce`: cerca de 2 semanas só registrando no Sentry, depois bloqueando de fato.
- Em seguida, adicionar `filters()` aos 6 controllers cujas regras nunca rodam (um card por controller).
- Um teste garante que toda action ou está na lista pública ou está protegida.

**CSRF: `TagHttpRequest extends CHttpRequest`**
- `enableCsrfValidation=true`.
- Aceita o token também no header `X-CSRF-Token`.
- `CSRF_MODE=log|enforce` e isenção por rota.
- Sequência: modo log → corrigir os formulários escritos à mão → bloqueio.

### B3 — Camada de services + testes unitários (piloto: grades)

**Estrutura**
```
app/src/Grades/
├─ Domain/               FormulaCalculator, FinalMediaPolicy, SituationPolicy, GradeFormula (enum)
├─ Application/          RecalculateFinalMediaService + RecalculateFinalMediaInput / …Result
└─ Infrastructure/Yii/   GradeRulesRepository, GradeResultRepository (ActiveRecord → DTO)
app/src/Shared/          UserContext, TransactionManager, ExceptionMapper e as exceções
                         NotFound, ValidationFailed, AccessDenied, Conflict, BusinessRule
```

**Convenções**
- `Input::fromArray()` valida a entrada e lança `ValidationFailed` com os erros por campo.
- Services recebem um `UserContext` (userId, isMachine, school, year, roles, scopes) montado a partir de `Yii::app()->user`. O mesmo objeto serve para web e API, e o service nunca lê a sessão diretamente.
- O `ExceptionMapper` traduz exceções em 404, 422, 403 ou 409. O resto vira 500 e vai para o Sentry.
- `YiiTransactionManager::transactional()` reaproveita a transação já aberta, porque o Yii 1 não aninha transações.

**Como fica uma action magra**
```php
public function actionCalculateFinalMedia(): void
{
    $result = $this->service(RecalculateFinalMediaService::class)->handle(
        RecalculateFinalMediaInput::fromArray($_POST),
        $this->userContext(),
    );
    $this->renderJson($result); // falha vira o status HTTP correto, nunca "200 vazio"
}
```

**Passos do piloto** (é a implementação de referência das skills `tag-golden-master`, `tag-extract-service` e `tag-write-tests`)
1. **Golden master.**
   - Montar um dataset anonimizado com uma turma para cada regra: média, peso, média semestral com recuperação parcial, recuperação final e conceito.
   - Rodar os usecases atuais e gravar um snapshot de `grade_results`.
   - Um teste de integração compara o cálculo novo com esse snapshot.
2. **Extrair nesta ordem:**
   1. `FormulaCalculator`, a partir do `ApplyFormulaOnGradesUsecase`, que já é quase puro. É o primeiro teste unitário.
   2. `SituationPolicy`.
   3. `FinalMediaPolicy`.
   4. `RecalculateFinalMedia`.
   5. `SaveGradesRelease`.
   6. Fechamento de turma.

   Os usecases antigos viram fachadas que delegam ao código novo até serem removidos.
3. **Bugs da seção 2** viram issues. Opcional: um modo sombra via `Yii::app()->features`, em que os dois motores calculam e as divergências são registradas em log.
4. **Próximas ondas,** em paralelo e por módulo (seção 8.6): foods (necessário para a B4), classdiary (o módulo com mais PRs de correção em 24 meses), student/matrícula e timesheet.

**Meta:** `app/src` no PHPStan max sem baseline, e cobertura ≥ 80% no código novo. O gate do Sonar vale só para `app/src`.

### B4 — API v1: infraestrutura + piloto M2M (foods ↔ br-nham)

**Como hospedar a API no Yii 1**
- **`api.php`, entrada própria:** o nginx encaminha `/api/` para ele. Carrega o autoload, `config.php` e `instance.php` (o tenant continua vindo do host) e cria uma `ApiWebApplication` com `app/config/api.php`.
- **`app/config/api.php`:**
  - herda o `main.php`;
  - urlManager em formato `path`, com `useStrictParsing` e um `routeVar` neutro (o `CUrlManager` devolve `$_GET['r']` mesmo quando alguma regra casa);
  - regras REST com verbo HTTP;
  - sem CSRF, sem sessão e sem gii;
  - componente `user` = `ApiWebUser`.
- **`ApiWebApplication`:**
  - só aceita rotas `api/...`, então um JWT nunca chega a um controller web;
  - trata CORS e OPTIONS;
  - devolve erros como `application/problem+json`.
- **`ApiWebUser extends WebUser`:** os estados ficam em memória, preenchidos pelas claims (`loginInfos`, `school`, `year`, `usersSchools`). Assim `TagModel`, `TLog`, `TagUtils::is*` e `user->school`/`user->year` funcionam sem sessão.
- **Módulo `app/modules/api`:**
  - `controllerNamespace = 'Tag\Api\Controllers'`, que o Yii 1.1.31 suporta inclusive com a subpasta `controllers/v1/`.
  - PSR-4 `Tag\Api\Controllers\` → `app/modules/api/controllers/`.
  - Isso evita colisão com os 25 `DefaultController` globais.
  - `ApiController extends CController`: lê JSON via `getRawBody()`, oferece `requireScope()` e `requireRole()` e nunca chama `Yii::app()->end()`.

**JWT e credenciais**
- **Biblioteca e chaves:**
  - `lcobucci/jwt ^5` com HS256.
  - A chave é **derivada por tenant** (HKDF do segredo mestre com o `INSTANCE`), então um token emitido para um tenant não é aceito em outro.
  - `JWT_KEYS` e `JWT_ACTIVE_KID` ficam no env; a rotação é feita por `kid`.
- **Claims:**
  - `iss` com o host, `aud` com o `INSTANCE`;
  - `sub` no formato `user:<id>` ou `client:<id>`;
  - `jti`, `exp` de 15 minutos, `scope` e `sid`;
  - `school` e `year` para tokens de pessoas.

  Papéis **não** vão no token: são consultados na hora no `authManager`.
- **Refresh token:**
  - opaco, de 256 bits, guardado como SHA-256;
  - rotaciona a cada uso, e o reuso de um token já trocado revoga toda a família;
  - expira com 30 dias de inatividade ou 90 dias no total;
  - é revogado quando o usuário troca a senha ou é desativado.
- **Integrações (M2M):**
  - `POST /api/v1/oauth/token` com `client_credentials` via Basic.
  - Cada `api_client` tem um usuário de serviço (senha inutilizável, sem papel RBAC), usado para registrar a autoria no log.
  - A autorização é por escopo e por `allowed_schools`.
- **Tabelas e cadastro de clientes:**
  - `api_client`, `api_refresh_token` e `api_auth_attempt`, criadas por SQL idempotente em `app/migrations/<versão>/` e aplicadas em todos os tenants pelo `SqlMigrationCommand`.
  - Comando `yiic apiclient create --scopes=…`: o segredo aparece uma única vez e é guardado com `password_hash`.
- **Limite de tentativas** nas rotas de token: tabela de tentativas mais `limit_req` no nginx.
- **Documentação e contrato:**
  - Especificação OpenAPI escrita antes do código, em `app/modules/api/openapi/v1.yaml`, com lint no CI.
  - Testes de contrato em `tests/Api`, usando o servidor embutido do PHP roteado para `api.php`.

**Piloto M2M** (é a implementação de referência da skill `tag-add-api-endpoint`)
- `POST /api/v1/food-requests/{id}/accepted-items` e `/received-items`, com escopo `foods.requests:write`.
- Usam services extraídos de `FoodrequestController` L168–272. O controller legado passa a chamar os mesmos services.
- Os endpoints antigos continuam funcionando, com o Bearer vindo do env, até o br-nham migrar. Depois são removidos em data combinada.

### B5 — API v1: app mobile/SPA
- `POST auth/login`: extrai um `PasswordAuthenticator` do `UserIdentity`, mantendo o upgrade de MD5 para bcrypt e corrigindo o uso de `$record` antes do teste de null.
- `POST auth/refresh`, `auth/logout` e `auth/context`. Este último troca escola ou ano, valida e emite um token novo.
- `GET me` e `GET me/classrooms`.
- Depois: notas e frequência, sobre os services das ondas da B3. O fluxo é sempre spec → DTO → service → controller → teste de contrato, com um card por endpoint.

## 7. Frontend

**Decisões de base**
- **JS novo:** fica em `frontend/`, na raiz, que é o root do Vite. Subpastas: `core/`, `ui/`, `components/`, `validators/`, `pages/<módulo>/<página>.js`, `entries/`, `legacy-bridge/`, `routes/` (gerado) e `types/`.
- **Saída do build:** `build/`, fora do git, com o manifest em `build/.vite/`. O build roda num estágio Node da imagem Docker e nada compilado é commitado (hoje o `sass/css/main.css` é uma linha só e conflita em todo PR).
- **SCSS:** compilado pelo próprio Vite, com `sass/scss/` como fonte única. `composer sass:build` continua existindo como atalho.
- **jQuery nos módulos:** o alias `jquery` aponta para um shim que exporta `window.jQuery`, então legado e módulos usam a mesma instância.
- **Integração com o PHP:**
  - `Tag\Shared\Frontend\ViteManifest` é puro e tem teste unitário. A fachada global `TFrontend` segue o estilo do `TUrlBridge` e expõe `registerEntry()`, `registerStyle()`, `jsonScript()` e `layoutConfig()`.
  - Módulos ES entram por `registerScriptFile` com `type=module`, que o `CClientScript` do Yii suporta.
  - O JSON de configuração sai por `CHtml::tag` com `JSON_HEX_*`. `CHtml::script` não serve porque envolve o conteúdo em CDATA.
  - Em desenvolvimento, o arquivo `build/hot` aponta para o dev server.

### F0 — Rede de segurança + limpeza sem risco

**Playwright**
- Roda contra o docker-compose, com um tenant demo semeado a partir de um dump fixo.
- Cobre cerca de 25 páginas: login, home, listas, formulários de aluno, professor, escola e turma, notas, frequência, diário, plano de curso, MACETE, cardápio, almoxarifado, horários, calendário, relatórios (`reports` e `reportsclean`), notificações, systemadmin e as ilhas.
- Comparação visual com `toHaveScreenshot`, mascarando datas e contadores.
- O smoke falha em qualquer `pageerror` ou `console.error`.
- Para rodar localmente, pedir antes URL, credenciais e aprovação, conforme a regra de E2E do repositório. No CI, roda numa stack descartável.
- Os 14 Cests do Codeception continuam até serem portados. A meta é ficar com um único framework de E2E.

**Catraca:** `scripts/frontend-metrics`, com baseline por módulo em `metrics/baseline/<módulo>.json`. Nasce em PHP, para rodar no container `tag-app`, e pode virar Node depois da F1.
- O total não pode subir.
- Arquivo novo nasce com contagem zero.
- Arquivo alterado não pode ganhar classes do Bootstrap 2.

**Limpeza.** Antes de cada remoção, grep pelo nome e pelo caminho, inclusive em `$.getScript` e em caminhos montados por concatenação.
- **CSS Blueprint:** `css/{screen,ie,print,main}.css` e os layouts `main`, `column1` e `column2`.
  - Antes, confirmar que todos os controllers e módulos trocam o layout.
  - No mesmo PR, o padrão de `Controller::$layout` passa a ser `fullmenu`.
- **CSS órfão:** `css/scss/main.css`, `sass/css/main.map`, `template.min.css`, `select2-bootstrap.css`, `jquery.dataTables.min.css`, `dataTables.fontAwesome.css` e o `style.css` duplicado do schoolreport (169 KB).
- **Bibliotecas órfãs:** Semantic UI do lunch, as 5 cópias de jQuery em `resources/lib`, o DataTables 1.10.7 e as builds do FullCalendar que não são usadas.
- **JS órfão:** `js/courseplan/form/*`, `js/datatables/config.js` e 4 arquivos de `js/reports`.
- **`main.scss`:**
  - apagar os parciais de 0 B;
  - nos imports duplicados (`_accordeon`, `reports`/`_reports` e o re-import dentro de `_field-multiselect`), manter a **última** ocorrência, que é a que vale na cascata, e comparar os seletores compilados antes e depois;
  - remover as 5 views que registram o `main.css` uma segunda vez.
- **Arquivos JS duplicados:** os 6 grupos de arquivos idênticos ficam com uma cópia só.
- **Ajustes pontuais:**
  - checar `window.Raven` antes de usá-lo no `common.js`;
  - a cópia do BBQ que o tema carrega só sai depois de um grep por `$.bbq`, `$.deparam` e `hashchange`.

### F1 — Toolchain Vite + qualidade do JS

**Vite**
- Node na versão LTS, fixada na imagem.
- `root: 'frontend'`, `base: '/build/'` e `manifest: true`.
- Entries encontradas por glob em `pages/**`, `entries/*` e `styles/*`, sem arquivo de registro central (evita conflito entre agentes).
- `frontend/styles/main.scss` faz `@use '../../sass/scss/main'`.
- A ponte legada é gerada num build clássico (IIFE) à parte.

**Docker e Composer**
- Um serviço `node` (volume nomeado para `node_modules`, porta `127.0.0.1:5173`) substitui o serviço `sass`.
- Novos scripts no Composer: `front:dev`, `front:build` e `front:test`.
- O Dockerfile ganha um estágio `node AS frontend`, e o `build/` resultante é copiado para a imagem.
- O nginx serve `/build/assets/` com cache `immutable`. Os nomes com hash substituem o `?v=TAG_VERSION`.

**Troca do CSS do design system**
- Os `registerCssFile('/sass/css/main.css…')` viram `TFrontend::registerStyle('styles/main.scss')`.
- Depois disso, apagar o CSS compilado e o serviço `sass`.

**Qualidade**
- **ESLint (flat config) em `frontend/`:**
  - `no-alert`;
  - proíbe `$` e `jQuery` globais (é preciso importar);
  - proíbe `?r=` escrito à mão e atribuições a `window.*` fora da ponte;
  - `no-unsanitized` em `.html()` e `.append()`;
  - `no-jquery` para APIs depreciadas.
- **ESLint no legado:** as violações atuais ficam congeladas com bulk suppressions; violações novas falham.
- **Prettier.**
- **Checagem de tipos:** `tsconfig.json` com `checkJs`, `strict` e `noEmit`. JSDoc obrigatório nas funções exportadas e `types/plugins.d.ts` para select2, mask, DataTables e Bootstrap 2. É a análise estática do JS, que também ajuda a IA.
- **Testes:** Vitest com jsdom, enviando cobertura lcov ao Sonar.

**CI** (`.github/workflows/frontend.yml`): lint, checagem de tipos, testes, build, catraca e `entries:check`, que confere se toda entry usada no PHP existe no manifest.

### F2 — Um jQuery por página
- **Pacote do jQuery:** `clientScript.packages['jquery']` no `main.php` passa a apontar para jQuery 1.12.4 + Migrate 1.4.1.
  - Os layouts usam `registerCoreScript('jquery')` em vez de carregar o 1.8.2 por tag direta.
  - Hoje, nas páginas com CGridView ou CJui, o 1.12.4 já é carregado depois do 1.8.2.
- **Ilhas e enrollmentonline:** as ilhas trocam o jQuery 1.11.3 do CDN pelo arquivo local; o `enrollmentonline` passa a carregar o jQuery explicitamente.
- **jQuery UI e datepicker:**
  - Uma única build do jQuery UI 1.12.1, sem tooltip e button, carregada antes do `bootstrap.js`.
  - `CJuiDatePicker` com `scriptFile=false`.
  - `$.fn.bsDatepicker = $.fn.datepicker.noConflict()`, ajustando as 9 chamadas existentes.

  Isso resolve a colisão atual entre jQuery UI, Bootstrap e bootstrap-datepicker.
- Remover o `scriptMap` do `layouts/reports.php`, que nunca casa com nada.
- Validação: suíte visual e smoke completos. Atenção ao FullCalendar 1.5.4 e às páginas que até agora só viam o 1.8.2.

### F3 — Núcleo, ponte legada e piloto

**Convenção de página**
- A view chama `TFrontend::registerEntry('pages/macete/lesson-plan-form.js')` e renderiza `<div data-page="…">` com `TFrontend::jsonScript($config)`. Isso substitui o `var x = <?= json_encode ?>`.
- No JS: `onPage('macete/lesson-plan-form', (root, config) => …)`.
  - Roda via `$(fn)`, depois do código legado.
  - Os eventos são delegados a partir de `root`.
  - Partials carregados por AJAX usam `initWithin(el)`.

**Núcleo, aproveitando o que já existe**
- **`core/http.js`:**
  - usa fetch e envia `X-Requested-With` (sem esse header o `Controller::init()` não devolve o 401 em JSON) e `X-CSRF-Token`;
  - manda o corpo urlencoded, porque as actions leem `$_POST`;
  - trata o 401 com `{redirect}`;
  - nos demais erros, lança `HttpError`, mostra um toast e registra no Sentry, sem quebrar se o SDK não estiver carregado.
- **`routes/`:** gerado estendendo o `scripts/generate-routes.php`. O `url()` reproduz o `?r=` do `CUrlManager` e acaba com os 212 literais.
- **`core/html.js`:** tagged template que escapa tudo por padrão, mais DOMPurify. Substitui os 3 `escapeHtml` duplicados.
- **`ui/`:**
  - `toast.js` e `dialog.js`, sobre `<dialog>`, com confirm que devolve Promise;
  - `form-errors.js`, derivado do `addError`/`removeError`;
  - o SCSS desses componentes vai para o design system.
- **`validators/`:** CPF, data, CEP, NIS, CNS e regras do Educacenso, portados do `util.js` com **testes de caracterização** (código antigo e novo rodam lado a lado no jsdom). Bug encontrado vira issue.
- **`components/`:** inicializadores de select2, mask, datepicker e DataTable, com o pt-BR que hoje está em `datatablesptbr.js`.

**Ponte legada:** um script clássico que substitui `common.js` e `util.js` nos layouts.
- Reexpõe a mesma API global, com os mesmos nomes, mais `window.TAG`.
- Instala um `$.ajaxPrefilter` que acrescenta o token CSRF aos 193 `$.ajax` existentes, sem editá-los.
- Remove os globais `origin` e `pathname`.
- Troca o `$("*").keypress`, que bloqueia ENTER até dentro de textarea, por um listener delegado. Validar com o produto.

**Piloto: MACETE** (é a implementação de referência da skill `tag-js-page-migration`)
- 557 linhas, em desenvolvimento ativo, e já usa Quill e DOMPurify, que passam a vir do npm.
- Somem o `window.Macete`, o `MaceteFormConfig` e o forceCopy a cada request.
- Em seguida, `entries/app.js` absorve `js/layout/functions.js` e `notifications.js`.

### F4 — Migração página a página (contínua, um card por página)

**Prioridade:** tickets abertos e erros `[AJAX ERROR]` no Sentry, criticidade do fluxo, duplicação e frequência de alteração.

**Ordem sugerida**
1. Notas: `gradesRelease` e `reportCard`, que estão duplicados.
2. `js/datatables/init.js`, que vira `components/datatable.js`, com as opções vindas do `DataTableGridView`.
3. Formulário de aluno (1.327 linhas), usando os validators já testados.
4. classdiary, courseplan, foods, instructor e `grades-structure`.

**Roteiro por página** (está na skill `tag-js-page-migration`)
1. Inventariar os globais com o script `legacy:who-uses <nome>`.
2. Trocar `registerScriptFile` por `registerEntry`.
3. Trocar os `on*=` por `data-action` com delegação de eventos.
4. Trocar `var x = <?=…?>` por `jsonScript`.
5. Escrever o smoke da página e baixar a catraca.

**Meta final:** CSP com `script-src 'self'`, começando em modo Report-Only.

### F5 — CSS com fonte única (`sass/scss` → um bundle principal + print)

**Trazer o CSS solto para o SCSS**
- O layout passa a emitir `<body class="tag-m-<módulo> tag-p-<módulo>-<controller>-<action>">`.
- `notifications.css` vira `components/_notifications.scss`; `form.css` vira `legacy/_yii-form.scss`.
- `grades`, `lunch`, `sagres`, `sedsp`, `reports` e `admin.css`, mais os `layout.css` dos módulos, vão para `pages/_<módulo>.scss`, escopados pela classe do body.
  - O coordenador cria antes o índice `pages/_index.scss`, com uma linha por módulo, para que os cards só editem a parcial do próprio módulo.
- `print-reports.css` e `electronic-diary.css` viram `styles/print.scss`.
- A ordem da cascata muda, porque hoje o CSS da view entra antes do CSS do layout. Por isso a suíte visual é obrigatória.

**Tokens e `@use`**
- `_tokens.scss` passa a emitir CSS custom properties (`:root{--t-…}`).
- Migrar de `@import` para `@use`/`@forward` com o `sass-migrator module`, já que o `@import` está depreciado no Dart Sass.

**Detectar CSS sem uso**
- PurgeCSS só em modo relatório (`rejected`), varrendo views PHP, widgets e JS.
- Safelist para classes geradas dinamicamente:
  - prefixos de plugin: `select2-`, `dataTables`/`dt-`, `ui-`, `fc-`, `datepicker`, `ql-`;
  - modificadores `t-*`;
  - estados como `active`, `in`, `open` e `collapse`.
- Primeiro no design system. Depois no AdminKIT (`template.css`, 183 KB), removendo só seções 100% sem uso, confirmadas por grep e pela suíte visual.

**Saída do Bootstrap 2 e do AdminKIT**
- A catraca congela o uso atual: `spanN` 428, `row-fluid` 259, `control-group` 487, `widget`/`innerLR` 327.
- A skill `tag-css-cleanup`, derivada do `ui-refactor.md`, guia cada troca. O CSS do fornecedor sai de um layout quando o uso ali chega a zero.
- Um único conjunto de ícones genéricos, o Font Awesome 4.7, mais os `t-icon-*`. Os glyphicons (99 usos) saem primeiro.

**`style=` inline (2.055) e blocos `<style>` (129):** vão para SCSS quando a view for tocada, controlados pela catraca.

**Ilhas em quarentena:** resultsmanagement (Bootstrap 3 + AdminKIT v2) e `schoolreport/default` (Semantic UI) só recebem o jQuery local e passam a servir localmente os assets que hoje vêm de CDN. Medir o uso por tenant antes de reescrever ou aposentar.

### F6 — jQuery 3
- **Codemods:**
  - `.success`/`.error`/`.complete` (88 usos) → `.done`/`.fail`/`.always`;
  - `$.parseJSON` (21) → `JSON.parse`;
  - `.size()` (7) → `.length`.
- **Novas versões:** jQuery 3.7, Migrate 3 em desenvolvimento e jQuery UI 1.13.
- **Plugins a testar:** select2 3.4.5 (plano B: 4.0.13) e qrcode.
- **Bibliotecas de terceiros:** passam a vir do npm, o que as coloca sob o Snyk.

## 8. Execução com vários agentes e provedores de IA

**Ideia central.** A fundação é feita em série, por um dono e com revisão humana. Depois o trabalho se abre em pacotes por módulo, e qualquer um dos agentes (Claude Code, Codex ou Antigravity) pode executá-los seguindo skills e cards de tarefa versionados no repositório. O CI é o árbitro: todo PR passa pelos mesmos gates, venha do provedor que vier.

### 8.1 Por que não paralelizar desde o início
Sem os contratos prontos (`UserContext`, `ExceptionMapper`, `TransactionManager`, `TFrontend`, `onPage`/`http`) e sem uma implementação de referência, cada agente inventaria o próprio padrão.

- **Em série, com dono único:**
  - B0, B1, o núcleo da B2 e de F0–F3.
  - Os três pilotos de referência: `app/src/Grades` (B3), MACETE (F3) e o M2M de foods (B4).
- **Em paralelo:** tudo o que vem depois, por módulo, seguindo a skill e copiando o piloto ("faça como em `app/src/Grades`").
- **Exceção que já pode ser paralela cedo:** correções de SQL injection e de controle de acesso, um card por controller (B0.2 e `filters()` da B2). São pequenas, independentes e têm checklist objetivo, sempre com revisão humana.

### 8.2 Skills de refatoração (padrão aberto Agent Skills)

**Formato e onde moram**
- **Formato:** `SKILL.md`, com frontmatter `name` + `description`, passo a passo e scripts de apoio. É o padrão aberto Agent Skills.
- **Fonte única:** `.agents/skills/<skill>/SKILL.md`.
  - **Codex e Antigravity** leem essa pasta nativamente.
  - Os playbooks atuais viram skills, e `.agents/playbooks/` fica só com ponteiros.
  - Os workflows de `.agents/workflows/` também migram: o Antigravity os depreciou e recomenda skills no lugar.
- **Claude Code** não lê `.agents/skills/`.
  - O `scripts/sync-agent-skills` copia as skills para `.claude/skills/`.
  - São cópias, não symlinks, porque symlink no git do Windows é instável.
  - Um job do CI falha se as cópias divergirem da fonte.
- **Os caminhos mudam com frequência:** conferir na documentação de cada ferramenta a cada atualização.

**Conteúdo de toda skill:** quando usar, entradas, passo a passo, o que é proibido tocar, comandos de verificação, modelo de descrição do PR e link para a implementação de referência.

| Skill | O que faz | Referência |
|---|---|---|
| `tag-task-runner` | Executa um card: confere dependências, cria branch/worktree, respeita o escopo, roda os gates, abre o PR e preenche o handoff | — |
| `tag-secure-controller` | Controle de acesso (rotas públicas, `filters()`), bind de SQL, CSRF nos formulários | PRs da B0/B2 |
| `tag-golden-master` | Testes de caracterização de um fluxo legado (dataset + snapshot) | golden master de notas (B3) |
| `tag-extract-service` | Action gorda → service em `app/src` (Domain/Application/Infrastructure), DTOs, action magra | `app/src/Grades` |
| `tag-write-tests` | Convenções de teste unitário e de integração | `tests/Unit/Grades` |
| `tag-add-api-endpoint` | Spec OpenAPI → DTO → service → controller v1 → teste de contrato | M2M de foods (B4) |
| `tag-js-page-migration` | Página jQuery procedural → entry em `frontend/pages` | MACETE (F3) |
| `tag-css-cleanup` | CSS solto → parcial SCSS escopada; Bootstrap 2 → classes TAG | primeiras páginas da F5 |
| `tag-module-migration` | Controller raiz → módulo (o playbook atual) | `docs/migracao_modulos/` |
| `tag-review` | Revisão cruzada: checklist de PR, segurança e regras do anel | — |

### 8.3 Card de tarefa (autocontido)
Cada tarefa é um arquivo em `.agents/tasks/<lançamento>/<ID>.md`, legível por qualquer agente mesmo sem histórico de conversa. Pode ser espelhado num ticket do Jira; o time já usa chaves `TCDA-…`.

```markdown
---
id: R2-GRADES-03
title: "Extrair SituationPolicy do ChageStudentStatusByGradeUsecase"
release: R2
module: grades
type: refactor            # seguranca | fundacao | refactor | testes | api | frontend | css | docs | epico | refinamento
skill: tag-extract-service   # ou o playbook atual, enquanto a skill não existir
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto            # rascunho | pronto | em-andamento | em-revisao | concluido | bloqueado
depends_on: [R2-GRADES-01, R2-GRADES-02]   # golden master + FormulaCalculator
size: M                   # P | M | G
risk: alto                # muda a aprovação de aluno
human_review: obrigatoria # obrigatoria | recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md — seção 6, B3"
write_scope: [app/src/Grades/**, tests/Unit/Grades/**, app/modules/grades/usecases/ChageStudentStatusByGradeUsecase.php]
read_only: [app/models/**, app/modules/grades/controllers/**]
forbidden: [composer.json, app/config/main.php, themes/**, "phpstan*.neon"]
acceptance:
  - composer analyse
  - composer test:unit -- --filter Grades
  - composer test:integration -- --filter GradesGoldenMaster
---
## Contexto          (links para as regras, o piloto e os trechos de código relevantes)
## Objetivo
## Passos
## Fora de escopo
## Critérios de aceite
## Como verificar
## Handoff           (quem executa preenche ao terminar: decisões, pendências e riscos)
```

Épicos (`type: epico`, `status: rascunho`) seguem o mesmo cabeçalho. O corpo traz o inventário do módulo, os pacotes SEC → GM → SVC → API → JS → CSS como checklist e o critério de "módulo pronto". O card de refinamento da onda é quem os transforma em cards executáveis.

**Quando um card pode começar:** as dependências já estão na `main`, a skill e a implementação de referência existem e, se for refactor, há golden master.

**Quando um card está pronto:**
- gates verdes;
- nenhum arquivo alterado fora do `write_scope`, conferido por `scripts/check-task-scope`, que lê o ID do card no nome da branch;
- PR no modelo da skill;
- handoff preenchido.

**Cards gerados a partir de inventário:** `scripts/inventory-actions.php` gera um JSON por controller/action, com superglobais, SQL cru, saída JSON, linhas de código e controle de acesso. Esse JSON alimenta a criação dos cards de cada onda, feita por um agente e revisada por uma pessoa.

### 8.4 Regras para os agentes não conflitarem
- **Um card, uma branch:** cada card tem sua branch ou worktree (`task/<ID>`), com PR pequeno e rebase na `main`.
- **Arquivos compartilhados têm dono (o coordenador):** `composer.json`, `app/config/main.php`, layouts, `sass/scss/main.scss`, workflows do CI e models compartilhados em `app/models/`. Cards de módulo não editam esses arquivos; quando precisam de mudança ali, abrem um card de contrato.
- **Registros por módulo, em vez de arquivo único.** O coordenador cria uma vez, e os cards só acrescentam arquivos:
  - `app/config/public-routes/<módulo>.php`;
  - `sass/scss/pages/_<módulo>.scss`;
  - baselines do PHPStan legado e da catraca por módulo;
  - entries do Vite e rotas JS descobertas por glob ou geradas;
  - DI por autowiring, sem arquivo de registro.
- **Dois cards no mesmo módulo** só rodam ao mesmo tempo se os `write_scope` não se cruzarem.

### 8.5 Gates e revisão, iguais para todo provedor
- **CI obrigatório:**
  - lint;
  - PHPStan src no nível máximo e legado sem erro novo;
  - testes unitários, de integração e golden master;
  - contrato da API;
  - catracas;
  - build do front;
  - smoke Playwright das páginas tocadas.
- **Revisão cruzada:** o PR de um provedor é revisado por outro, com a skill `tag-review`, e depois aprovado por uma pessoa. Modelos diferentes erram em lugares diferentes.
- **Sempre com uma pessoa:** segurança e autenticação, regras de nota, frequência e aprovação, migrations SQL, remoção de arquivos e qualquer divergência do golden master.
- **Agente não muda regra de negócio.** Se o golden master divergir, ele para e registra no handoff.

### 8.6 Ondas de pacotes por módulo
Cada módulo recebe, nesta ordem:
1. **SEC:** controle de acesso e SQL.
2. **GM:** golden master dos fluxos críticos.
3. **SVC-n:** extração de services, um card por grupo de actions.
4. **API-n:** endpoints da API, só onde o app precisa.
5. **JS-n:** migração do JS, um card por página.
6. **CSS:** limpeza do CSS do módulo.

| Onda | Módulos | Observação |
|---|---|---|
| Referência (em série) | grades, macete, foods (M2M) | Os pilotos que servem de exemplo para as skills |
| 1 | classdiary, student, classroom | Mais PRs de correção; base do app mobile (frequência, turmas) |
| 2 | school, instructor, timesheet, courseplan, enrollmentonline | |
| 3 | inventory, schoolreport, calendar, curricularmatrix, farmer, lunch, quiz, professional, notifications e os demais | |
| Alto risco (uma pessoa lidera, o agente apoia) | `CensoController` (3.120 linhas, regras do Educacenso), `AdministratorController`, systemadmin, sedsp, sagres | Integrações externas e dados em massa |

### 8.7 Agentes especialistas: guardião, refatorador e QA

São três papéis neutros de provedor. A definição canônica de cada um fica em `.agents/agents/`, e há adaptadores para o Claude Code (`.claude/agents/`) e para o Codex (`.codex/agents/`).

| Papel | Missão | Pode editar | Entrega |
|---|---|---|---|
| **Guardião do plano** (`tag-plan-guardian`) | Verificar se um card, branch ou PR segue o plano: escopo do card, fase e dependências, regras do anel `app/src`, segurança, comportamento (golden master), testes, frontend, catracas e documentação | Nada. Só lê e roda comandos de verificação | Relatório com veredito (APROVADO, COM RESSALVAS ou REPROVADO). Cada achado traz severidade, `arquivo:linha`, a seção do plano violada e a correção sugerida. Lista também o que exige decisão humana |
| **Refatorador** (`tag-refactorer`) | Executar cards de refatoração seguindo a skill e o piloto de referência: extrair service, proteger controller, migrar JS, limpar CSS, migrar módulo. Sem mudar comportamento | Só o `write_scope` do card, na branch `task/<ID>` | Código com gates verdes, PR no modelo da skill e handoff preenchido |
| **QA** (`tag-qa`) | Montar a rede de proteção antes do refactor e validar depois: golden master, testes unitários, de integração e de contrato, Vitest e Playwright. Roda os gates e reporta bugs | Só testes, fixtures anonimizadas e infraestrutura de teste. Nunca código de produção | Testes, relatório por gate (comando e resultado), riscos não cobertos e bugs com issue sugerida |

**Fluxo de um card**
```
card pronto → QA: caracterização / golden master → Refatorador: implementa → QA: testes novos + gates
            → Guardião: conformidade → pessoa aprova e faz o merge
```

**Regras comuns aos três**
- **Fontes de verdade, nesta ordem:** este plano → `.agents/rules/` → o card → ADRs → skills.
- **Regra de negócio:** nenhum deles muda uma regra por conta própria. Se o golden master divergir, a decisão é de uma pessoa.
- **Testes no navegador:** seguem a regra do repositório e pedem URL, credenciais e aprovação antes de rodar.
- **Revisão cruzada:** o guardião roda num provedor diferente do refatorador. Por exemplo, refatorar no Codex e revisar no Claude, ou refatorar no Claude e revisar no Antigravity. O QA pode ficar com o terceiro.

**Como acionar**

| Ferramenta | Como chamar |
|---|---|
| Claude Code | Pedir em linguagem natural ("use o subagente `tag-refactorer` no card B0-SQL-03") ou mencionar com `@`. O `/agents` lista e edita os subagentes |
| Codex | Pedir explicitamente ("spawn the `tag_refactorer` agent for card B0-SQL-03"); o Codex não cria subagentes sozinho. O `/subagents` alterna entre as threads |
| Antigravity | `@tag-refactorer` ou o painel `/agents`. O agente principal também pode delegar por conta própria |

## 9. Framework: manter o Yii 1.1 agora e decidir com gatilhos

**Fatos (novembro de 2026)**
- O suporte de segurança do Yii 1.1 foi estendido até **31/12/2029**, num anúncio oficial de 25/11/2026. Cobre correções de segurança e compatibilidade com as versões atuais do PHP.
- O Yii 1.1.32 (dezembro de 2025) já suporta PHP 8.4. O projeto está no 1.1.31 e sobe para o 1.1.32 na B1.
- O suporte de segurança do PHP 8.3 termina em 31/12/2027, então a subida para o PHP 8.4 tem de acontecer até lá.

**Recomendação: não trocar de framework agora.**
- O framework não é a causa dos problemas principais: regra de negócio em controller, falta de testes e falhas de segurança. Trocar agora levaria esses problemas para o framework novo, competiria com as correções de segurança e seria uma troca de uma vez só, sem rede de proteção.
- O plano já deixa a troca futura barata:
  - `Domain/` e `Application/` não dependem do Yii, e o PHPStan garante isso.
  - O acoplamento fica restrito aos controllers magros e a `Infrastructure/Yii`.
  - Golden masters e testes de contrato provam que o comportamento continua o mesmo depois da troca.
- **Alternativa considerada: escrever a API (B4) direto num framework novo.** Não recomendo. Para reaproveitar models e regras, seria preciso subir o Yii dentro do framework novo, e manter dois frameworks ao mesmo tempo pesa para o time e para os agentes. Como os controllers da API são finos, portá-los depois é rápido.

**Gatilhos.** Um ADR de checkpoint em cerca de 12 meses reavalia a decisão, ou antes, se um destes acontecer:
1. Os fluxos críticos (notas, frequência, matrícula, diário) estão em `app/src` com testes, e a maioria das actions já é magra.
2. O prazo aperta: a migração precisa começar até o início de 2028 para terminar antes de 31/12/2029. Ou o Yii 1.1 atrasa o suporte a uma versão de PHP que o projeto precisa.
3. Surge uma necessidade que o Yii 1 atende mal: filas, tempo real ou um crescimento grande da API.

**Como migrar, quando chegar a hora: estrangulamento pela borda HTTP**
- O framework novo roda lado a lado com o Yii, e o nginx divide o tráfego por rota.
- **A API vai primeiro,** porque é stateless (JWT) e não depende da sessão web.
- Depois vem a web, módulo a módulo, com autenticação compartilhada (ponte de sessão ou token).
- Os repositórios ganham uma segunda implementação (Eloquent ou Doctrine) atrás das mesmas interfaces. `Domain/` e `Application/` não mudam.

**Destino:** escolher por ADR.
- **Laravel, a minha inclinação.** O Eloquent também é ActiveRecord, como o do Yii, então a curva do time é menor. Tem o maior ecossistema e mercado de trabalho no Brasil e, por ser o framework PHP mais usado, é o que as IAs conhecem melhor.
- **Symfony** é a alternativa, se o time preferir injeção de dependência e componentes mais explícitos.
- **Yii 2** seria uma reescrita de qualquer forma, sem vantagem sobre essas duas opções.

## 10. Transversal: documentação e IA

**`.agents/rules/`**
- Atualizar `system-overview.md`, `php-standards.md`, `design-system-usage.md`, `legacy-routing-and-assets.md` e `e2e-testing.md`.
- Manter o frontmatter `trigger` do Antigravity em toda regra, nova ou existente (`always_on`, `glob`, `model_decision` ou `manual`). Sem ele, o Antigravity descarta a regra em silêncio. As 6 regras atuais já têm `trigger: always_on`.
- Criar:
  - `service-layer.md`;
  - `security.md`: rotas públicas, bind de SQL, CSRF, segredos e tenant;
  - `api.md`;
  - `frontend-architecture.md`;
  - `multi-agent.md`: cards, escopo, arquivos com dono e gates.

**`.agents/agents/`:** a definição canônica dos três agentes especialistas (seção 8.7). Os adaptadores ficam em `.claude/agents/` e `.codex/agents/`.

**`.agents/skills/`:** as skills da seção 8.2. Codex e Antigravity leem essa pasta nativamente; para o Claude, as skills são copiadas para `.claude/skills/`. Os playbooks atuais (`bugfix`, `code-quality`, `database-migration`, `module-migration`, `ui-refactor`, `release`, `e2e-testing`) e os workflows (depreciados no Antigravity) também viram skills, e `.agents/playbooks/` fica só com ponteiros.

**`.agents/tasks/`:** o template de card e os cards de cada onda.

**`.agents/checklists/`**
- Criar `api-endpoint.md` e `frontend-change.md`.
- Atualizar `task-done.md` para exigir o `phpstan-src` limpo, testes novos e o escopo do card respeitado.

**Índices:** atualizar as tabelas do `CLAUDE.md` e do `AGENTS.md` (skills, cards e regras novas).

**ADRs em `docs/adrs/`, a partir do 4**
- 4: PSR-4 `Tag\`, contextos e models globais
- 5: PHPStan em dois níveis
- 6: services, DTOs e PHP-DI
- 7: estratégia de testes
- 8: segurança (negar por padrão, CSRF, sessão por tenant)
- 9: API (JWT, refresh, M2M)
- 10: Vite + módulos ES, registrando a lição de 2024
- 11: jQuery único
- 12: CSS com fonte única e build na imagem
- 13: testes visuais
- 14: execução multiagente (skills, cards, gates)
- 15: framework (manter o Yii 1.1, com gatilhos e destino preferencial)

**Documentos existentes**
- Preencher a seção de decisão do ADR 1, hoje vazia.
- Marcar `docs/namespaces.md` e `docs/php83_yii1_static_analysis_plan.md` como substituídos.
- Transformar `docs/sass_commands.md` em `docs/frontend.md`.
- Salvar este plano em `docs/arquitetura/plano-modernizacao.md`.

## 11. Código existente a reaproveitar

| Arquivo | Uso no plano |
|---|---|
| `app/components/UserIdentity.php`, `app/components/PasswordHasher.php` | login e upgrade de MD5 para bcrypt |
| `app/components/auth/` (`TRole`, `TTask`, `TFeature`, `RbacSeeder`) | papéis e permissões já existentes |
| `app/modules/macete/services/MaceteAccessService.php` | escopo de escola, ano e professor |
| `app/modules/grades/usecases/ApplyFormulaOnGradesUsecase.php` | base do `FormulaCalculator` |
| `app/components/FeaturesComponent.php` | modo sombra e feature flags |
| `app/components/utils/TLog.php` | log de ações |
| `app/commands/SqlMigrationCommand.php` | criar as tabelas da API em todos os tenants |
| `scripts/generate-routes.php`, `app/components/utils/TUrlBridge.php`, `*Routes.php` | rotas geradas para o JS |
| `themes/default/js/{util,common,datatablesptbr}.js`, `app/components/widgets/DataTableGridView.php` | fonte do núcleo e da ponte legada |
| `sass/scss/_tokens.scss` e componentes `t-*` | design system |
| `tests/providers/CustomProvider.php`, `tests/builders/*` | dados de teste |
| `.agents/` (rules, playbooks, checklists), `AGENTS.md`, `CLAUDE.md`, `docs/migracao_modulos/` | base das skills e dos cards |
| `ramsey/uuid`, Guzzle | já instalados |

## 12. Arquivos críticos

**Backend**
- Entrada e config: `composer.json`, `index.php`, `app/yiic.php`, `config.php`, `instance.php`.
- `app/config/main.php` e os novos `api.php`, `test.php`, `parts/imports.php` e `public-routes/`.
- Base: `app/components/Controller.php`, `app/components/UserIdentity.php`, `app/models/TagModel.php`.
- Controllers dos achados críticos: `app/modules/systemadmin/controllers/DefaultController.php`, `app/controllers/SiteController.php`, `app/controllers/AdministratorController.php`.
- Pilotos: `app/modules/grades/{controllers,usecases}/` e `app/modules/foods/controllers/FoodrequestController.php`.

**Infra e qualidade**
- `Dockerfile`, `docker-compose.yml` e o novo `docker/nginx/default.conf`.
- `.github/workflows/*`.
- `phpstan*.neon` (+ `phpstan/baseline/`), `rector.php`, `.php-cs-fixer*.php` e `phpunit.xml.dist`.

**Frontend**
- Layouts: `themes/default/views/layouts/{fullmenu,reports,reportsclean,login}.php`.
- `themes/default/js/{common,util}.js` e `sass/scss/main.scss`.
- Novos: `package.json`, `vite.config.js` e `frontend/`.

**Agentes**
- Novos: `.agents/agents/`, `.claude/agents/`, `.codex/agents/`, `.agents/skills/`, `.agents/tasks/`, `scripts/sync-agent-skills`, `scripts/check-task-scope` e `scripts/inventory-actions.php`.
- Atualizados: `AGENTS.md`, `CLAUDE.md` e `.agents/README.md`.

## 13. Riscos e mitigação

| Risco | Mitigação |
|---|---|
| Negar por padrão ou CSRF quebrarem fluxos não mapeados | Modo log, lista pública por módulo, rollback por env, suíte Playwright |
| Correção do `checkAccess` mudar o comportamento do inventory | Nota de release e teste |
| Saída das versões dev no Composer quebrar integrações | PR isolado; smoke do sedsp (Guzzle) e do sagres (JMS) |
| Refactor de notas mudar quem é aprovado | Golden master, modo sombra e decisão de produto para cada bug |
| Agentes divergirem do padrão ou entrarem em conflito | Fundação em série, pilotos de referência, skills, `write_scope` checado no CI, arquivos com dono, revisão cruzada |
| Agente "corrigir" uma regra de negócio por conta própria | Golden master obrigatório; divergência exige uma pessoa |
| Global JS removido ainda usado em handler inline | Script `legacy:who-uses`; ponte com os mesmos nomes; smoke falha em `pageerror` |
| Ordem de execução entre módulo ES e script clássico | Ponte clássica no lugar do `util.js`; `onPage` via `$(fn)` |
| Cascata CSS mudar ao mover ou remover regras | Manter a última ocorrência; diff de seletores; suíte visual em cada PR |
| Deploy que não passe pela imagem Docker | Confirmar antes da F1 (pergunta em aberto) |
| Navegadores antigos nas escolas | Consultar o GA4 antes de fixar o `build.target` |
| Mesma credencial MySQL em todos os tenants amplia o estrago de qualquer falha | Médio prazo: um usuário por tenant, começando pela API |
| Yii 1.1 depende de mantenedores voluntários | Gatilhos da seção 9; `app/src` independente do framework |

## 14. Verificação

**B0 / B2**
- Smoke com `curl`: rota pública → 200; rota protegida → 302 ou 401; arquivo sensível → 403 ou 404.
- Nenhum evento legítimo de "would deny" ou "csrf fail" no log antes de ligar o bloqueio.
- Uma sessão do tenant A usada no host B vira visitante.
- Playwright nos fluxos de login, troca de escola/ano e criação de usuário.

**B1**
- `composer validate --strict`.
- `composer analyse` verde nas duas configurações.
- `composer test:unit` e `test:integration` rodando no CI.
- Telas do sagres funcionando sem os `require_once`.
- `composer migrate:dry` sem erro.
- O build não publica a imagem se a qualidade falhar.

**B3**
- Saída igual ao golden master, ou divergências aprovadas pelo produto.
- Cobertura ≥ 80% em `app/src`.
- Telas de notas validadas no Playwright.

**B4 / B5**
- Contrato validado contra o OpenAPI.
- Token do tenant A no host B → 401.
- `?r=administrator/...` via `api.php` → 404.
- Reuso de refresh token revoga a família.
- br-nham homologado no endpoint novo.

**F0–F6**
- `npm run lint && npm run typecheck && npm test && npm run build`.
- Suíte visual sem diferenças não aprovadas.
- Catraca verde e `entries:check` ok.
- Relatório do JQMIGRATE zerado antes da F6.

**Agentes**
- Cópias das skills sincronizadas (checado no CI).
- `scripts/check-task-scope` verde em todo PR de card.
- Ensaio antes do fan-out: dois provedores diferentes executam o mesmo card de baixo risco, por exemplo um `tag-secure-controller`. Os dois PRs precisam passar pelos gates e ser equivalentes.

**Regra do repositório:** antes de rodar testes de navegador, pedir URL base, credenciais e aprovação do plano de teste.

## 15. Fora de escopo, por ora
- Trocar de framework agora (ver a seção 9) ou trocar o ActiveRecord por outro ORM.
- Pôr namespace nos controllers e models legados (ver a seção 3.1).
- Migrar o AJAX da web para JWT.
- SPA completa, Vue ou htmx: a escolha foi jQuery organizado em módulos.
- Reescrever as ilhas resultsmanagement e schoolreport, o que só deve ser avaliado depois de medir o uso.

## 16. Perguntas em aberto (não impedem o início)
1. Existe algum deploy que não passe pela imagem Docker? Isso afeta o build de assets dentro da imagem.
2. O GA4 tem dados de navegador e versão dos usuários?
3. Quais tenants usam resultsmanagement e schoolreport?
4. Quem vai manter o dump do tenant demo usado nos testes?
5. Os cards ficam só no repositório ou também viram tickets no Jira (TCDA)?
6. Decisões de produto pendentes: os bugs de notas da seção 2, o bloqueio global de ENTER e o login de responsáveis que usa CPF + iniciais do nome como senha.

## 17. Primeiros passos
1. **Hotfix:** R0-SYSADMIN-01 e R0-ADMIN-01, junto com a rotação de segredos do R0-INFRA-01.
2. **R0-SQL-01 a R0-SQL-09 em paralelo,** um card por controller. É o primeiro ensaio multiagente e passa por revisão humana.
3. **R0-INFRA-02 e R0-INFRA-03.**
4. **R1 em duas frentes paralelas:**
   - backend: R1-BASE-01 → R1-BASE-05 → R1-TEST-01 → R1-CI-01;
   - front: R1-FRONT-01 → R1-FRONT-03/04/05.

   As skills (R1-AGT-01) entram cedo, porque os cards seguintes dependem delas.

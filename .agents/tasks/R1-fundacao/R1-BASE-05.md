---
id: R1-BASE-05
title: "PHPStan em dois níveis: phpstan.src.neon (max, sem baseline) e phpstan.legacy.neon (nível 2, baseline por módulo)"
release: R1
module: plataforma
type: fundacao
skill: .agents/playbooks/code-quality.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-01]
size: M
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco PHPStan em dois níveis) e seção 3 (regras do anel app/src)"
write_scope:
  - phpstan.src.neon
  - phpstan.legacy.neon
  - phpstan.neon
  - phpstan-baseline.neon
  - phpstan-bootstrap.php
  - phpstan/**
  - composer.json
  - composer.lock
  - .github/workflows/sonarcloud-analysis.yml
read_only:
  - app/models/**
  - app/components/**
  - app/config/main.php
  - app/components/utils/TagUtils.php
forbidden:
  - app/**
  - themes/**
  - Dockerfile
  - docker-compose.yml
acceptance:
  - "composer analyse"
  - "docker exec tag-app ./vendor/bin/phpstan analyse -c phpstan.src.neon --no-progress"
  - "docker exec tag-app ./vendor/bin/phpstan analyse -c phpstan.legacy.neon --no-progress --memory-limit=1G"
  - "Teste negativo: um arquivo temporário em app/src com Yii::app() ou $_POST faz o phpstan.src.neon falhar (registrar a saída no Handoff e apagar o arquivo)"
---

# R1-BASE-05: PHPStan em dois níveis: phpstan.src.neon (max, sem baseline) e phpstan.legacy.neon (nível 2, baseline por módulo)

## Contexto
**Card do coordenador:** mexe em `composer.json` (dependências de desenvolvimento) e no workflow `.github/workflows/sonarcloud-analysis.yml` (arquivos com dono).

Estado atual, conferido no código:
- `phpstan.neon` tem `level: 3` e `paths: app/components` (só isso), `scanDirectories` com `app/models` e `app/modules/curricularmatrix/models`, `bootstrapFiles: phpstan-bootstrap.php` e `reportUnmatchedIgnoredErrors: false`.
- `phpstan-baseline.neon` tem **751 erros** (402 entradas), dos quais **640 (85%) apontam para `app/domain/*` e `app/repository/*`, que não existem mais**. Só 111 erros são de código vivo (`app/components/*`).
- `phpstan-bootstrap.php` define as constantes do Yii e carrega `vendor/yiisoft/yii/framework/yii.php`. Serve para os dois arquivos novos.
- `composer.json`: `"analyse": "docker exec tag-app ./vendor/bin/phpstan analyse --memory-limit=512M"`.
- O CI roda `vendor/bin/phpstan analyse --no-progress --error-format=github` no job `Quality` de `.github/workflows/sonarcloud-analysis.yml` (≈L32–33), que lê o `phpstan.neon` por padrão. Se o arquivo sumir sem ajuste, o passo quebra. O R1-CI-01 vai substituir esse workflow; aqui só se faz o ajuste mínimo para não deixar o CI vermelho.
- `app/src/` nasce vazio (R1-BASE-01) e `app/modules/api` e `tests/Unit` ainda **não existem**. O PHPStan reclama de caminho inexistente, então o `phpstan.src.neon` só lista diretórios que existem; os cards de API (R3) e de testes (R1-TEST-01) acrescentam os seus. Se o PHPStan falhar por "nenhum arquivo para analisar" em `app/src` vazio, confirme a opção para caminhos vazios (`--allow-empty-paths` ou equivalente da versão instalada) e **não** crie classe falsa em `app/src`.
- `tests/` contém Codeception legado (`acceptance`, `robots`, `builders`, `_support`, `scripts`) que não deve entrar no nível max.

## Objetivo
`composer analyse` roda dois PHPStan: `src` (nível max, regras do anel, sem baseline) e `legacy` (nível 2, uma baseline por módulo), e o `phpstan.neon` antigo some.

## Passos
1. **Dependências.** Valide com `composer require --dev --dry-run` e instale: `phpstan/phpstan-strict-rules`, `phpstan/phpstan-deprecation-rules`, `phpstan/phpstan-phpunit` e `spaze/phpstan-disallowed-calls` (versões compatíveis com PHPStan 2; confirmar). Inclua cada `extension.neon` explicitamente em `includes:`; não use o `phpstan/extension-installer` (exigiria novo `allow-plugins`).
2. **Stub do Yii.** Crie `phpstan/stubs/yii.stub` e liste-o em `stubFiles`. Ele descreve:
   - `Yii::app()` retornando `CWebApplication` com `@property-read` para os componentes de `main.php` (≈L124–): `utils`, `notifier`, `features`, `assetManager`, `user`, `cache`, `urlManager`, `db`, `db2`, `authManager`, `errorHandler`, `sentry`;
   - o `user` com `@property` para `school`, `year`, `loginInfos`, `usersSchools`, `hardfoot`, `version` e `authTimeout` (gravados em `app/components/UserIdentity.php` e em `SiteController::actionChangeSchool/ChangeYear`).
3. **`phpstan.src.neon`:** `level: max`; paths só dos diretórios existentes (`app/src` e, quando houver, `tests/Unit`, `tests/Integration`, `tests/Support`), com `excludePaths` para `tests/acceptance`, `tests/robots`, `tests/builders`, `tests/_support`, `tests/_output` e `tests/scripts`; `scanDirectories` com `app/models`, `app/components` e os `models/` dos módulos; **sem baseline**. Regras do anel com `spaze/phpstan-disallowed-calls`, valendo só em `*/Domain/*` e `*/Application/*` (use `allowIn` para liberar `Infrastructure`, `Shared/Infrastructure`, `tests`; confirmar a sintaxe da versão instalada):
   - chamada estática `Yii::app()`;
   - superglobais (`$_GET`, `$_POST`, `$_REQUEST`, `$_FILES`, `$_SERVER`, `$_SESSION`, `$_COOKIE`);
   - construções `exit`, `die`, `echo`, `print`;
   - ActiveRecord (`CActiveRecord`, `CDbCommand`, `CDbConnection`). Confirmar se a regra casa subclasses (os models legados estendem `CActiveRecord`); se não casar, use a lista explícita ou outra regra.
4. **`phpstan.legacy.neon`:** `level: 2`; paths `app/components`, `app/models`, `app/controllers`, `app/commands` e `app/modules` (excluindo `*/views`, `*/resources`, `*/messages` e `app/modules/sagres/soap`); `reportUnmatchedIgnoredErrors: true`; mesmo stub, mesmo bootstrap e `scanDirectories`.
5. **Baseline por módulo.** `includes` do PHPStan não aceita glob. Crie `phpstan/baseline/_load.php`, que retorna `['includes' => glob(__DIR__ . '/*.neon')]`, e inclua esse arquivo no `phpstan.legacy.neon` (confirmar que a versão instalada aceita include PHP; plano B: lista explícita). Gere uma baseline por módulo passando o caminho na linha de comando:
   `phpstan analyse -c phpstan.legacy.neon app/modules/<m> --generate-baseline phpstan/baseline/<m>.neon`, e mais `components.neon`, `models.neon`, `controllers.neon`, `commands.neon`. Use `--memory-limit=1G` ou mais (confirmar).
6. **Composer.** `analyse` passa a rodar as duas configurações (por exemplo `analyse:src` e `analyse:legacy`, ambos com `docker exec tag-app`). Remova `phpstan.neon` e `phpstan-baseline.neon`.
7. **CI (ajuste mínimo).** No passo `Run PHPStan` de `sonarcloud-analysis.yml`, rode as duas configurações com `-c`. O R1-CI-01 reorganiza o resto.

## Fora de escopo
- Corrigir os erros do legado: só entram na baseline. Subir o nível do legado é trabalho trimestral.
- Criar `app/modules/api`, o container ou qualquer classe em `app/src` (R1-BASE-07 e R3).
- Reescrever Rector e php-cs-fixer (R1-BASE-06) e criar o job completo de CI (R1-CI-01).

## Critérios de aceite
- [ ] `phpstan.src.neon` e `phpstan.legacy.neon` existem; `phpstan.neon` e `phpstan-baseline.neon` foram removidos.
- [ ] Existe uma baseline por módulo em `phpstan/baseline/` e `reportUnmatchedIgnoredErrors` está `true` no legado.
- [ ] `composer analyse` termina com sucesso nas duas configurações.
- [ ] O teste negativo prova que `Yii::app()`, superglobal e `echo` em `*/Domain` ou `*/Application` quebram o `src`.
- [ ] O CI (job `Quality`) roda as duas configurações.
- [ ] Não há mudança de comportamento: nenhum arquivo de `app/` foi alterado.

## Como verificar
```bash
composer analyse
docker exec tag-app ./vendor/bin/phpstan analyse -c phpstan.src.neon --no-progress
docker exec tag-app ./vendor/bin/phpstan analyse -c phpstan.legacy.neon --no-progress --memory-limit=1G
ls phpstan/baseline phpstan/stubs
git ls-files phpstan.neon phpstan-baseline.neon   # esperado: vazio
# teste negativo (não commitar): app/src/Teste/Application/X.php com Yii::app(); deve falhar
```

## Handoff
_Preenchido por quem executar._

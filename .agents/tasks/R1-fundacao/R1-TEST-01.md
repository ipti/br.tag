---
id: R1-TEST-01
title: "Infraestrutura de testes: PHPUnit e Codeception atuais, suítes Unit e Integration e banco de teste"
release: R1
module: plataforma
type: testes
skill: tag-task-runner
agents: [tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-01, R1-BASE-02]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco Infraestrutura de testes) e seção 14"
write_scope:
  - composer.json
  - composer.lock
  - phpunit.xml.dist
  - codeception.yml
  - docker-compose.yml
  - app/config/test.php
  - app/tests/**
  - app/modules/sedsp/usecases/tests/**
  - tests/bootstrap.php
  - tests/Unit/**
  - tests/Integration/**
  - tests/Support/**
  - tests/_data/**
  - tests/_support/**
  - tests/unit.suite.yml
  - tests/functional.suite.yml
  - scripts/dump-test-schema.sh
read_only:
  - tests/providers/CustomProvider.php
  - tests/acceptance/**
  - config.php
  - app/config/main.php
  - app/config/console.php
forbidden:
  - app/models/**
  - app/components/**
  - themes/**
  - .github/**
  - Dockerfile
acceptance:
  - "composer test:unit"
  - "composer test:integration"
  - "docker exec tag-app ./vendor/bin/codecept build   (a suíte de aceitação continua carregando)"
  - "git ls-files app/tests   (sem resultados)"
  - "composer lint"
---

# R1-TEST-01: Infraestrutura de testes: PHPUnit e Codeception atuais, suítes Unit e Integration e banco de teste

## Contexto
**Card do coordenador:** `composer.json`, `docker-compose.yml` e `codeception.yml` estão no escopo. Hoje **não existe nenhum teste unitário** e o único teste PHPUnit é um órfão.

Fatos:
- `composer.json`: `"test": "./vendor/bin/codecept run acceptance --steps"` (roda no host); o lock tem PHPUnit 8.5 e Codeception 4.2 (R1-BASE-02 deixa ambos estáveis).
- `app/tests/` (PHPUnit-Selenium, quebrado): `bootstrap.php` carrega `yiit.php` com caminho de Windows (`'\..\vendor\yiisoft\yii\framework\yiit.php'`), `CWebTestCaseCustom.php` estende `PHPUnit\Extensions\Selenium2TestCase`, mais `WebTestCase.php`, `phpunit.xml` e `functional/SiteTest.php`.
- `app/modules/sedsp/usecases/tests/GetEscolasFromSEDUseCaseTest.php` é órfão: faz `require_once 'C:\br.tag\app\modules\sedsp\...'` (caminho fixo de máquina) e só afirma que um `OutEscola` vazio devolve `false`.
- `app/config/test.php` está quebrado: define `DBNAME` e `DBCONFIG` (`localhost:3306`, `root`/`root`, `demo.tag.ong.br`) e depois faz merge do `main.php`, que usa `INSTANCE` (L3 e `environment` do Sentry ≈L241) e `TAG_VERSION` (≈L240), constantes que só existem depois de `instance.php` e `config.php`. `INSTANCE` ainda é lido em `TagUtils::isInstance`.
- `instance.php` deriva o tenant de `$_SERVER['HTTP_HOST']`; `localhost` vira `demo.tag.ong.br`. O `SqlMigrationCommand` (≈L180) só enxerga bancos que casam `%.tag.ong.br`: o banco de teste deve se chamar, por exemplo, `test.tag.ong.br`, para o CI aplicar as migrations nele.
- `tests/unit.suite.yml` e `tests/functional.suite.yml` não têm testes (a pasta `tests/unit/` está vazia e `tests/functional/` não existe); `tests/acceptance.suite.yml` usa WebDriver em `http://localhost/`. `codeception.yml` aponta o suporte para `tests/_support`.
- `tests/providers/CustomProvider.php` (classe global, estende o `Base` do Faker) gera CNS, certidão, NIS e INEP; os builders atuais (`tests/builders/*`) dependem do Codeception.
- O `docker-compose.yml` tem os serviços `tag`, `mysql` (com `--sql_mode=""`), `adminer`, `sass` e `bytebase`; não há `mysql-test`.

## Objetivo
`composer test:unit` (sem banco e sem Yii) e `composer test:integration` (MySQL de teste com transação desfeita) funcionam, com Codeception e PHPUnit atuais, e o lixo antigo de testes sai.

## Passos
1. Valide as versões antes de instalar: `composer require --dev -W --dry-run codeception/codeception:^5 phpunit/phpunit:^11` e a variante com PHPUnit 12 (e os módulos `module-asserts`, `module-webdriver` e `module-phpbrowser` compatíveis). Escolha 11 ou 12 e registre o motivo. Confirme que o `phpstan/phpstan-phpunit` do R1-BASE-05 aceita a escolha.
2. Remova `app/tests/` e `app/modules/sedsp/usecases/tests/` (ou porte o teste órfão para `tests/Unit/Sedsp/` se for trivial; por padrão remova e registre). Remova `tests/unit.suite.yml`, `tests/functional.suite.yml`, `tests/_support/UnitTester.php`, `FunctionalTester.php` e `Helper/Unit.php`, `Helper/Functional.php`.
3. `phpunit.xml.dist` com duas suítes, `Unit` (`tests/Unit`) e `Integration` (`tests/Integration`), `bootstrap="tests/bootstrap.php"`, cobertura de `app/src` (pcov) e falha em warning/deprecation.
4. `tests/bootstrap.php`: carrega `vendor/autoload.php`; **para a suíte Unit não carrega o Yii**. Para Integration, carrega `yii.php`, define `INSTANCE`, `GLOGALGROUP` e as constantes de `YII_*`, carrega `config.php` (que define `TAG_VERSION`) **sem** `instance.php`, e cria `Yii::createConsoleApplication` com `app/config/test.php`.
5. Reescreva `app/config/test.php` **sem** merge do `main.php`: `basePath`, `import` mínimo (quando o R1-BASE-04 estiver na `main`, use `parts/imports.php`; senão registre a pendência), `db` por `TEST_DB_HOST`, `TEST_DB_PORT`, `TEST_DB_NAME`, `TEST_DB_USER` e `TEST_DB_PASSWORD`, `cache` com `CDummyCache`, `authManager` (`CDbAuthManager`, mesmas tabelas do `main.php`). Sem `sentry`, sem `user`.
6. Um `IntegrationTestCase` em `tests/Support/` abre uma transação no `setUp` e faz rollback no `tearDown`.
7. `scripts/dump-test-schema.sh`: gera `tests/_data/schema.sql` a partir do tenant demo (`mysqldump --no-data`, mais os dados das tabelas de referência `edcenso_*`, `auth_*` e `grade_calculation`), executando no container `mysql`. Versione o resultado e documente o uso no cabeçalho do script. O dump **nunca** pode conter dado de aluno ou usuário.
8. `docker-compose.yml`: serviço `mysql-test` (`mysql:8.0`, `--sql_mode=""`, `tmpfs` em `/var/lib/mysql`, banco `test.tag.ong.br`) e as variáveis `TEST_DB_*` no serviço `tag`.
9. `composer.json`: scripts `test:unit` e `test:integration` (com `docker exec tag-app ./vendor/bin/phpunit --testsuite ...`), mantendo `test` (aceitação) como está; `autoload-dev` ganha `classmap` para `tests/providers/`.
10. Builders em `tests/Support/Builders/` **sem** bootstrap do Yii, usando Faker pt_BR e o `CustomProvider`. Escreva um teste smoke em cada suíte (por exemplo, o builder gera CNS válido; a conexão de teste responde `SELECT 1` e enxerga `auth_item`).
11. Rode `codecept build` e `codecept dry-run acceptance`: a aceitação legada precisa continuar carregando (a limpeza fica no R1-TEST-02).

## Fora de escopo
- Corrigir ou portar os 14 Cests de aceitação (R1-TEST-02).
- Golden master de notas e demais testes de negócio (R2).
- Job de CI e cobertura no Sonar (R1-CI-01).

## Critérios de aceite
- [ ] `app/tests/` e o teste órfão do sedsp saíram; as suítes Codeception `unit` e `functional` também.
- [ ] `phpunit.xml.dist` tem Unit e Integration; `composer test:unit` roda sem banco e sem Yii; `composer test:integration` roda no `mysql-test` e desfaz tudo ao fim de cada teste.
- [ ] `app/config/test.php` não depende mais de `INSTANCE`/`TAG_VERSION` do merge e lê `TEST_DB_*`.
- [ ] `tests/_data/schema.sql` foi gerado por `scripts/dump-test-schema.sh`, sem dados pessoais.
- [ ] **O comportamento do sistema não muda:** nenhum código de produção foi alterado; em `app/` só mudam `app/config/test.php` e as remoções de `app/tests/` e do teste órfão do sedsp.
- [ ] A suíte de aceitação continua carregando (`codecept build` e `dry-run`).

## Como verificar
```bash
docker compose up -d mysql-test
composer test:unit
composer test:integration
docker exec tag-app ./vendor/bin/codecept build
docker exec tag-app ./vendor/bin/codecept dry-run acceptance
git ls-files app/tests   # esperado: vazio
composer lint
```

## Handoff
_Preenchido por quem executar._

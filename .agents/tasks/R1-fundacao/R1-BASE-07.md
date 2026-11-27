---
id: R1-BASE-07
title: "Criar o núcleo Shared em app/src: container PHP-DI, UserContext, exceções, ExceptionMapper e transações"
release: R1
module: plataforma
type: fundacao
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-01, R1-TEST-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 3 (arquitetura alvo), seção 6 (B1, bloco Bootstrap e injeção de dependência; B3, estrutura app/src/Shared)"
write_scope:
  - app/src/Shared/**
  - tests/Unit/Shared/**
  - tests/Integration/Shared/**
  - app/components/Controller.php
  - app/config/main.php
  - composer.json
  - composer.lock
read_only:
  - app/components/UserIdentity.php
  - app/components/utils/TagUtils.php
  - app/controllers/SiteController.php
  - phpstan.src.neon
forbidden:
  - app/modules/**
  - app/models/**
  - themes/**
  - .github/**
  - Dockerfile
acceptance:
  - "composer analyse"
  - "composer test:unit"
  - "composer test:integration"
  - "composer lint"
  - "Smoke: login, home e uma tela com JSON (troca de escola) funcionam como antes"
---

# R1-BASE-07: Criar o núcleo Shared em app/src: container PHP-DI, UserContext, exceções, ExceptionMapper e transações

## Contexto
**Card do coordenador:** edita `app/components/Controller.php`, `app/config/main.php` e `composer.json` (arquivos com dono). Este é o contrato que os cards de service (R2 em diante) vão usar; o código já nasce sob o PHPStan `src` (nível max, regras do anel, R1-BASE-05) e com testes (R1-TEST-01).

Fatos do código atual:
- `app/components/Controller.php` (≈L12) estende `CController`; hoje não tem helper de service, de usuário nem de JSON. Respostas JSON são feitas à mão, por exemplo `echo CJSON::encode($result)` (`grades/controllers/DefaultController.php` ≈L187) e `Controller::init` (≈L45–48) com `header(...)`, `echo json_encode(...)` e `Yii::app()->end()`.
- O usuário não tem classe própria: `main.php` declara `user` só com `allowAutoLogin` e `authTimeout`, ou seja, `CWebUser`. `UserIdentity::authenticate` (`app/components/UserIdentity.php`) grava os estados `loginInfos` (o registro `Users` inteiro), `usersSchools`, `school` e `hardfoot`; o `year` é gravado em `SiteController::actionChangeYear` (≈L154). Os papéis vêm de `Yii::app()->authManager->getRoles($loginInfos->id)` (usado em `Controller::init`).
- Transações hoje são abertas na mão: `beginTransaction` aparece em cerca de 36 pontos de `app/` e `getCurrentTransaction` em apenas 1 (contagem por grep, linhas), então o aninhamento não é tratado de forma uniforme.
- O Sentry já está configurado (`main.php` ≈L229) e o SDK expõe `\Sentry\captureException()` (`vendor/sentry/sentry/src/functions.php`).
- `app/runtime/` é ignorado pelo git e o `Dockerfile` dá permissão de escrita ao `www-data` nele: serve para o container compilado do PHP-DI.
- `Domain/` e `Application/` **não** podem usar `Yii::app()`, superglobais, `echo`/`exit` nem ActiveRecord (plano, seção 3); por isso o que depende do Yii vai para `Shared/Infrastructure/Yii/`.

## Objetivo
Existe o conjunto mínimo de `Tag\Shared`, pronto para os services: container injetável, contexto do usuário, exceções de negócio, mapeamento para HTTP e gerenciador de transações, tudo coberto por testes e sem mudar o comportamento do sistema.

## Passos
1. **Dependência.** `composer require php-di/php-di:^7` (confirmar compatibilidade com o PHP da imagem). É card com revisão humana por mexer em dependência.
2. **Container.** `Tag\Shared\Infrastructure\Yii\ContainerComponent extends CApplicationComponent`, que monta o `DI\Container` com autowiring, compilação em `app/runtime/di` quando `!YII_DEBUG` e as definições de `Shared` (por exemplo `TransactionManager` → `YiiTransactionManager`, `UserContext` → fábrica). Registre-o em `main.php` como componente `container`, usando `::class` (mesmo padrão do Sentry).
3. **UserContext.** `Tag\Shared\Auth\UserContext`, `final readonly`, com `userId`, `isMachine`, `school`, `year`, `roles` e `scopes`. A fábrica `Shared\Infrastructure\Yii\UserContextFactory` monta a partir de `Yii::app()->user` e do `authManager` (sessão: `isMachine = false`, `scopes = []`). Confirme os tipos reais de `school` (`inep_id`) e `year` antes de tipar. A senha (hash em `loginInfos`) **nunca** entra no contexto.
4. **Exceções.** Em `Tag\Shared\Exception`: `NotFoundException`, `ValidationFailedException` (erros por campo), `AccessDeniedException`, `ConflictException`, `BusinessRuleException`, com uma base comum.
5. **Http/ExceptionMapper.** Converte exceção em `status` + corpo: NotFound 404, ValidationFailed 422, AccessDenied 403, Conflict 409, BusinessRule 422 (decisão deste card; registre no Handoff). Qualquer outra vira 500 genérico (sem vazar mensagem) e chama `\Sentry\captureException()`.
6. **Transações.** Interface `Tag\Shared\Db\TransactionManager` com `transactional(callable $work): mixed` e a implementação `YiiTransactionManager`, que **reaproveita a transação ativa** (`getCurrentTransaction()`) ou abre uma nova, com commit e rollback.
7. **Controller.** Acrescente a `Controller`: `service(string $class)` (resolve no container), `userContext()` e `renderJson(mixed $data, int $status = 200)` (cabeçalho JSON, `http_response_code`, `echo` e `Yii::app()->end()`). Não altere o resto do arquivo (`init`, `beforeAction`).
8. **Testes.** Unitários em `tests/Unit/Shared/` para `UserContext`, `ExceptionMapper` e um `FakeTransactionManager`. Integração em `tests/Integration/Shared/` para o `YiiTransactionManager` (commit, rollback e reaproveitamento de transação ativa) e um teste que **resolve no container todos os services de `app/src`** (classes `*Service` em `Application/`), para pegar dependência quebrada. Hoje não há nenhum service: o teste deve passar vazio e já cobrir os próximos.
9. Rode `composer analyse` (o `src` roda em nível max), `composer lint` e os testes.

## Fora de escopo
- `WebUser`, `AccessPolicy`, CSRF e sessão por tenant (R2-SEC).
- JWT, `ApiController` e `UserContext` de máquina (R3-API).
- Qualquer service de negócio (grades etc.) e qualquer mudança em `app/modules/**`.
- `ViteManifest` e `TFrontend` (F1).

## Critérios de aceite
- [ ] `app/src/Shared/` tem container, `UserContext`, as cinco exceções, `ExceptionMapper` e `TransactionManager` com implementação Yii.
- [ ] `Controller` ganhou `service()`, `userContext()` e `renderJson()` sem alterar o comportamento existente.
- [ ] O PHPStan `src` passa em nível max, sem baseline e sem violar as regras do anel.
- [ ] Testes unitários e de integração passam; o teste do container resolve todos os services de `app/src`.
- [ ] **O comportamento não muda:** login, home e a troca de escola (`site/changeSchool`) continuam iguais; o componente `container` só é criado quando usado.

## Como verificar
```bash
composer analyse
composer test:unit
composer test:integration
composer lint
docker exec tag-app php -r 'echo class_exists("DI\\ContainerBuilder") ? "di-ok\n" : "falta\n";'
git diff --stat app/components/Controller.php app/config/main.php   # só acréscimos
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes): login, home, troca de escola e de ano, e uma tela de formulário qualquer, conferindo o console do navegador e `app/runtime/<INSTANCE>/` por erros novos.

## Handoff
_Preenchido por quem executar._

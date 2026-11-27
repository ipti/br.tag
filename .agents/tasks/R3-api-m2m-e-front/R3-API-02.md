---
id: R3-API-02
title: "API: ApiWebUser sem sessão, módulo app/modules/api e ApiController"
release: R3
module: plataforma
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-API-01]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B4: ApiWebUser, módulo app/modules/api, ApiController)"
write_scope:
  - app/src/Shared/Infrastructure/Yii/ApiWebUser.php
  - app/src/Shared/Auth/**
  - app/modules/api/**
  - app/config/api.php
  - composer.json
  - tests/Unit/Shared/**
  - tests/Integration/Api/**
read_only:
  - app/src/Shared/Infrastructure/Yii/WebUser.php
  - app/models/TagModel.php
  - app/components/utils/TagUtils.php
  - app/components/utils/TLog.php
  - app/components/UserIdentity.php
  - vendor/yiisoft/yii/framework/web/auth/CWebUser.php
forbidden:
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer validate --strict"
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter ApiWebUser"
  - "composer test:integration -- --filter Api"
  - "GET /api/v1/ping sem token → 401 problem+json; com claims de teste → 200; nenhum PHPSESSID emitido"
---

# R3-API-02: API: ApiWebUser sem sessão, módulo app/modules/api e ApiController

## Contexto
**Card do coordenador:** edita `composer.json` (PSR-4 do módulo). Também altera o `app/config/api.php` criado no R3-API-01 (registra o módulo).

O código legado que a API reaproveita depende do `Yii::app()->user`, que no web vem da sessão. Fatos verificados:
- `TagModel::save()` e `delete()` (`app/models/TagModel.php` ≈L19 e ≈L33) chamam `user->isGuest` e, via `isReadOnlyUser()` (≈L47–L54), `user->loginInfos->id` com `authManager->checkAccess('reader', …)`.
- `TagUtils::isAdmin()`, `isSuperuser()` e `isInstructor()` (`app/components/utils/TagUtils.php` ≈L5–L20) leem `Yii::app()->user->loginInfos->id`.
- `TLog` (`app/components/utils/TLog.php` ≈L7–L11) usa `Yii::app()->controller->module->id`, `controller->id` e `controller->action->id`: o `ApiController` precisa estar dentro de uma action normal do Yii.
- O login web grava os estados `loginInfos` (registro `Users`), `usersSchools`, `school` e `version` (`UserIdentity.php` ≈L32–L44); `year` é gravado depois (troca de ano em `SiteController`). Os campos de `loginInfos` lidos no código são `id`, `username` e `name` (confirmar com grep antes de implementar). O R2-SEC-01 trocou o registro por um objeto de valor `LoginInfo{id, username, name}`; **confirmar** a classe.
- `CWebUser` lê estados por `__get` (`user->school`) e `getIsGuest()` é `getState('__id') === null` (`CWebUser.php` ≈L141–L147 e ≈L288–L291). `loginRequired()` (≈L372–L397) redireciona ou lança 403, e `init()` abre a sessão. Tudo isso precisa ser neutralizado.
- `CWebApplication::createController` monta a classe com `controllerNamespace` e o id com subpasta (`v1/...`) (`CWebApplication.php` ≈L348–L349); o arquivo é `app/modules/api/controllers/v1/<Id>Controller.php`, com a classe em `Tag\Api\Controllers\v1`.
- `CHttpRequest::getRawBody()` existe (`CHttpRequest.php` ≈L316).

## Objetivo
`Yii::app()->user` e o restante do legado funcionam na API sem sessão, a partir de claims, e existe um módulo `api` com um `ApiController` base seguro.

## Passos
1. `Tag\Shared\Infrastructure\Yii\ApiWebUser extends WebUser` (classe do R2-SEC-01):
   - estados só em memória (`array`), sem `$_SESSION`, sem `session_start` e sem cookie; `init()` não abre sessão;
   - método `authenticateFromClaims(Claims $claims, LoginInfo $login, array $usersSchools): void` preenche `__id`, `loginInfos`, `school`, `year`, `usersSchools` e, se a política do legado exigir, `hardfoot` (`UserIdentity.php` ≈L32 e ≈L37; só `resultsmanagement` e `SchoolIdentification` o leem; confirmar);
   - `login()`/`logout()` lançam `LogicException`; `loginRequired()` lança `AccessDenied` mapeada para **401** (não 403 nem redirect).
2. `Tag\Shared\Auth\Claims` (DTO `final readonly`, puro) com `subject`, `kind` (`user` ou `client`), `scopes`, `school`, `year`, `sessionId`. O parse do JWT vem no R3-API-03; aqui o DTO e o uso.
3. Módulo `app/modules/api/ApiModule.php` com `controllerNamespace = 'Tag\Api\Controllers'` e `defaultController`. No `composer.json`: `"Tag\\Api\\Controllers\\": "app/modules/api/controllers/"`. Registre o módulo em `app/config/api.php` (não em `main.php`).
4. `Tag\Api\Controllers\ApiController extends CController`:
   - `readJson(): array` lê `Yii::app()->request->getRawBody()` com `json_decode(..., JSON_THROW_ON_ERROR)` e exige `Content-Type: application/json` quando há corpo;
   - `requireScope(string $scope)` e `requireRole(TRole $role)` (papel consultado na hora no `authManager`; papéis não vão no token);
   - `renderJson(mixed $data, int $status = 200)` e `renderProblem(...)`;
   - **nunca** chama `Yii::app()->end()` nem `echo`/`exit`; a resposta sai pelo retorno e pela `ApiWebApplication`.
5. Um controller de verificação `v1/PingController` (`GET /api/v1/ping`, exige autenticação) só para provar o encadeamento; ele é removido ou mantido como health autenticado a critério do Handoff.
6. `tag-qa`: testes unitários do `ApiWebUser` (estados, `isGuest`, `loginRequired` → 401, `TagUtils::isAdmin()` com `authManager` falso) e de integração do `ping` com claims de teste injetadas por um autenticador falso.

## Fora de escopo
- Emissão e validação de JWT (R3-API-03), `POST oauth/token` (R3-API-04), `auth/login` e `auth/refresh` do app (R4-API-01).
- Alterar `TagModel`, `TagUtils`, `TLog` ou `UserIdentity`: eles funcionam com o usuário em memória sem mudança.

## Critérios de aceite
- [ ] `Yii::app()->user->school`, `->year`, `->loginInfos->id` e `TagUtils::isAdmin()` funcionam num request de API com claims, sem sessão.
- [ ] Nenhum `PHPSESSID` e nenhum acesso a `$_SESSION` em request de API (teste de integração).
- [ ] `loginRequired()` produz 401 `problem+json`.
- [ ] `composer validate --strict` passa com o novo PSR-4; o módulo e o controller carregam pelo autoload do Composer.
- [ ] Nenhum `Yii::app()->end()` em `app/modules/api` (`git grep`).
- [ ] `app/src` e `app/modules/api` no PHPStan nível max, sem baseline.

## Como verificar
```bash
composer validate --strict && composer dump-autoload
git grep -n "Yii::app()->end()" -- app/modules/api app/src      # sem resultados
curl -i "http://localhost/api/v1/ping"                          # 401 application/problem+json
composer lint && composer analyse
composer test:unit -- --filter ApiWebUser && composer test:integration -- --filter Api
```

## Handoff
_Preenchido por quem executar._

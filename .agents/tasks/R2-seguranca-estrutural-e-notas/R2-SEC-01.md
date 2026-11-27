---
id: R2-SEC-01
title: "Criar o WebUser com sessão por tenant, checkAccess pelo id numérico e LoginInfo no lugar do registro Users"
release: R2
module: plataforma
type: seguranca
skill: tag-secure-controller
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-07]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achados 5 e 6) e seção 6 (B2, Sessão e identidade)"
write_scope:
  - app/src/Shared/Infrastructure/Yii/WebUser.php
  - app/src/Shared/Auth/LoginInfo.php
  - app/components/UserIdentity.php
  - app/components/Controller.php
  - app/config/main.php
  - app/modules/schoolreport/SchoolreportModule.php
  - tests/Unit/Shared/**
  - tests/Integration/Shared/**
  - CHANGELOG.md
read_only:
  - app/components/utils/TagUtils.php
  - app/models/TagModel.php
  - app/models/LoginForm.php
  - app/modules/inventory/**
  - config.php
  - instance.php
forbidden:
  - composer.json
  - themes/default/views/layouts/**
  - app/models/**
  - app/modules/inventory/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter Shared"
  - "composer test:integration -- --filter Shared"
  - "Smoke da seção Como verificar: cookie PHPSESSID com HttpOnly e SameSite=Lax; sessão de um tenant não vale em outro"
---

# R2-SEC-01: Criar o WebUser com sessão por tenant, checkAccess pelo id numérico e LoginInfo no lugar do registro Users

## Contexto
Este é um **card do coordenador**: edita `app/config/main.php` e `app/components/Controller.php`, que têm dono (README do backlog).

Fatos verificados no código:
- **Sessão sem tenant:** o `user` em `app/config/main.php` (≈L138–141) é o `CWebUser` padrão, e não existe componente `session`. O prefixo das chaves de estado é `md5('Yii.' . get_class($this) . '.' . Yii::app()->getId())` (`CWebUser::getStateKeyPrefix`, em `vendor/yiisoft/yii/framework/web/auth/CWebUser.php` ≈L544), que **não** depende do `INSTANCE`. O tenant sai do host (`instance.php` ≈L14–17 e L42).
- **Hash da senha na sessão:** `UserIdentity::authenticate()` (`app/components/UserIdentity.php` ≈L42) faz `setState('loginInfos', $record)`, o registro `Users` inteiro. `LoginForm::login()` (≈L72–77) chama `user->login($identity)` sem duração, então não há cookie de "lembrar-me".
- **`checkAccess` quebrado:** `user->id` é o username e `auth_assignment.userid` é numérico, então `Yii::app()->user->checkAccess()` sempre falha. Usos: `inventory/controllers/MovementController.php` ≈L32, L44 e L104; `inventory/models/InventoryMovement.php` ≈L108; `inventory/models/InventoryStock.php` ≈L93; `inventory/views/movement/createExit.php` ≈L12. Nenhum `accessRules()` usa `'roles'` (grep), então o resto do sistema não é afetado. Já `TagUtils` e `TagModel::isReadOnlyUser` (≈L54) usam `authManager->checkAccess(..., loginInfos->id)` e estão certos.
- **Campos de `loginInfos` lidos (grep):** apenas `id` (≈170 leituras), `username` (`Controller.php` ≈L42, `main.php` ≈L247, `fullmenu.php`, controllers do enrollmentonline) e `name` (`macete/LessonsplanController.php`). Também há `isset(user->loginInfos)` em `SiteController.php` ≈L109 e `TagUtils::checkAccess()` com `loginInfos` como padrão (≈L22).
- **Ilha schoolreport:** `SchoolreportModule::beforeControllerAction` (≈L24–41) troca o `user` por `CWebUser` e usa `setStateKeyPrefix('_schoolreport')`, sem tenant.

## Objetivo
Uma sessão só vale no tenant onde foi criada, `checkAccess()` passa a funcionar, e a sessão deixa de guardar o hash da senha.

## Passos
1. Antes de mexer, repita o grep de `loginInfos` em `app`, `themes` e nos `.js` (inclusive `->name`, `getState('loginInfos')` e variáveis que recebem `loginInfos`). Se aparecer campo além de `id`, `username` e `name`, pare e registre.
2. Crie `Tag\Shared\Auth\LoginInfo` (`final readonly`, `declare(strict_types=1)`) com `id`, `username` e `name` e a fábrica `fromRecord(Users)`. Teste o ciclo `serialize`/`unserialize`, porque a sessão serializa o objeto.
3. Em `UserIdentity::authenticate()`, grave `LoginInfo::fromRecord($record)`. Mantenha `$record` para o `TagUtils::checkAccess()` da ≈L30.
4. Crie `Tag\Shared\Infrastructure\Yii\WebUser extends CWebUser`:
   - `getStateKeyPrefix()` inclui o tenant, via um método protegido `tenantId(): string` que devolve `INSTANCE` (assim o teste injeta outro valor);
   - `afterLogin()` grava o estado `tenant`;
   - `checkAccess($operation, $params = [], $allowCaching = true)` usa `loginInfos->id` e devolve `false` para visitante. Não mexa em `getId()`, porque `'users' => ['admin']` das `accessRules` compara pelo username.
5. Em `main.php` (coordenador): `user.class` passa a ser o `WebUser::class` (padrão `::class` já usado com o Sentry) e crie o componente `session` com `cookieParams` `httponly=true`, `samesite=Lax` e `secure` conforme HTTPS (`CHttpSession::setCookieParams` aceita `samesite`). Se o TLS termina no proxy, o `secure` precisa olhar `X-Forwarded-Proto`; registre a decisão.
6. Em `Controller::init()` (≈L33–54), para usuário logado cujo `getState('tenant') !== INSTANCE`: `logout()` e responda como já se faz para sessão expirada (AJAX → 401 JSON, ≈L48–53; senão redireciona ao login). Aplique só quando `Yii::app()->user instanceof WebUser`. Os dois controllers do enrollmentonline sobrescrevem `init()` sem `parent::init()` e ficam fora desse check; o prefixo já os isola. Registre no Handoff.
7. `SchoolreportModule` (≈L37): o prefixo vira `'_schoolreport_' . INSTANCE`.
8. Inventory (mudança de comportamento): hoje, como `checkAccess('manager')` é sempre falso, o filtro por escola nunca é aplicado e o gerente vê o estoque de todas as escolas. Depois do conserto, o gerente vê só a própria escola. Escreva o teste de integração (usuário com papel `manager`, id numérico) e acrescente a nota de release no `CHANGELOG.md`, avisando também que **todas as sessões ativas caem no deploy** (prefixo novo).

## Fora de escopo
- `TagUtils::generateCacheKey()` (≈L162) usa `user->id` com `getRoles()`, então o papel cai sempre em `default_role`. Corrigir muda as chaves de cache; registre como card sugerido.
- `FoodrequestController` ≈L251 faz `setState('loginInfos', null)` num endpoint sem sessão. Não mexa.
- O estado `usersSchools` continua guardando registros do ActiveRecord.
- Negar por padrão (R2-SEC-02) e CSRF (R2-SEC-03).

## Critérios de aceite
- [ ] A sessão serializada não contém o hash da senha (teste com `serialize()` do `LoginInfo`).
- [ ] Dois `WebUser` com `tenantId()` diferentes geram prefixos diferentes e não enxergam o estado um do outro.
- [ ] `Yii::app()->user->checkAccess('manager')` devolve `true` para gerente e `false` para visitante e para quem não tem o papel.
- [ ] O cookie de sessão sai com `HttpOnly` e `SameSite=Lax` (e `Secure` em HTTPS).
- [ ] O login, a troca de escola e de ano e o menu seguem funcionando.
- [ ] A nota de release descreve a mudança do inventory e a queda das sessões.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Shared
composer test:integration -- --filter Shared

# cookie: depois de logar, o login responde com Set-Cookie
curl -i -c /tmp/c.txt -d "LoginForm[username]=<usuário>&LoginForm[password]=<senha>&LoginForm[year]=0" "http://localhost/?r=site/login"
# esperado: Set-Cookie: PHPSESSID=...; HttpOnly; SameSite=Lax
```
- Confirmar: como uma sessão chega a valer em outro tenant. O cookie sem `Domain` é por host, então verifique se o proxy ou o ingress repassa o mesmo host. Em homologação com dois tenants, reproduza antes e depois: a sessão do tenant A, enviada ao host do tenant B, deve ir ao login.
- Confirmar: se o `allowAutoLogin` de `main.php` é usado por algum fluxo (hoje o login não grava cookie).
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

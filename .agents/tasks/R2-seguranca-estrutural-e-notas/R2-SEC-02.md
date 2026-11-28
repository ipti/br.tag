---
id: R2-SEC-02
title: "Criar a AccessPolicy (negar por padrão) em Controller::runActionWithFilters, começando em modo log"
release: R2
module: plataforma
type: seguranca
skill: tag-secure-controller
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R2-SEC-01]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3) e seção 6 (B2, Negar por padrão)"
write_scope:
  - app/src/Shared/Infrastructure/Yii/AccessPolicy.php
  - app/src/Shared/Infrastructure/Yii/PublicRoutes.php
  - app/config/public-routes/**
  - app/components/Controller.php
  - app/config/main.php
  - tests/Unit/Shared/**
  - tests/Integration/Shared/**
  - tests/_data/access-baseline.json
read_only:
  - app/modules/**/controllers/**
  - app/controllers/**
  - scripts/inventory-actions.php
forbidden:
  - composer.json
  - themes/default/views/layouts/**
  - app/models/**
  - app/modules/**/controllers/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter AccessPolicy"
  - "composer test:integration -- --filter AccessPolicyCoverage"
  - "Com ACCESS_POLICY_MODE=log nenhuma resposta muda; o acesso de visitante a rota fora da lista aparece no log com a categoria security.access"
---

# R2-SEC-02: Criar a AccessPolicy (negar por padrão) em Controller::runActionWithFilters, começando em modo log

## Contexto
Este é um **card do coordenador**: edita `app/components/Controller.php` e `app/config/main.php`, que têm dono.

Fatos verificados no código:
- **Controle de acesso incompleto:** há 69 classes de controller (grep em 2026-11-27) e 43 definem `filters()`. O README do backlog lista 24 controllers sem `accessControl` efetivo (6 definem `accessRules()` sem `filters()`, como o `FoodrequestController` e o `SiteController`, e 18 não definem nada).
- **Gancho único:** `CController::run()` chama `runActionWithFilters($action, $this->filters())` (`vendor/yiisoft/yii/framework/web/CController.php` ≈L265 e L283). Nenhum controller de `app/` sobrescreve esse método (grep). Cinco controllers de módulo sobrescrevem `beforeAction` (dois do enrollmentonline, `macete/TemplatesController` e dois do schoolreport), e esse método roda depois.
- **Ordem:** `Controller::init()` (≈L33–54) roda antes e já devolve 401 JSON para visitante em AJAX (≈L48–53). Os dois controllers do enrollmentonline sobrescrevem `init()` sem `parent::init()` e tratam o 401 em `beforeAction` (`getschools` e `getcities` ficam públicos, ≈L74–81). **Não mude esse 401.**
- **Regras públicas hoje (`'users' => ['*']`):**
  - `EnrollmentonlinestudentidentificationController`: `index`, `view`, `create`, `getCities`, `getSchools`.
  - `EnrollmentonlinepreenrollmenteventonlineController`: `index`, `view`, `getPreEnrollmentStages`.
  - `farmer/DefaultController`: 12 actions, entre elas `activateFarmers` e `toggleFarmerStatus`, que parecem administrativas.
  - `foods/FoodnoticeController`: `index`, `view`, `getTacoFoods`, `getNotice`, `activateNotice`, `toggleNoticeStatus`, `getNoticePdfUrl`, `getShoppingList`.
  - `foods/FoodrequestController` (sem `filters()`): `updateAcceptedFoods` ≈L168 e `updateReceivedFoods` ≈L218 são chamadas servidor a servidor do br-nham, autenticadas por Bearer.
- **Courseplan:** `CourseplanController` libera `'*'` (≈L31–51) para `create`, `update`, `save`, `deletePlan` e outras. Isso **não** é legítimo.
- **Site:** `SiteController` tem `accessRules()` sem `filters()` e expõe `login`, `logout`, `error`, `index` (redireciona o visitante, ≈L45), `captcha`, `page` e `contact`.
- **Log:** a rota de arquivo de `main.php` (≈L15) só captura `application,system.*`; a rota do Sentry (≈L27–31) não filtra categoria e só vale com `!YII_DEBUG`.

## Objetivo
Toda rota passa pela `AccessPolicy`: é pública (está na lista) ou exige login. Em modo `log` ela só registra o que bloquearia.

## Passos
1. Crie `AccessPolicy` com `decide(string $route, bool $isGuest): Decision` (pura, testável) e um carregador `PublicRoutes` que lê `app/config/public-routes/*.php` por glob. Cada arquivo devolve uma lista de rotas `modulo/controller/action`, em minúsculas, aceitando `*` no último segmento.
2. Em `Controller::runActionWithFilters()`: monte a rota com `$this->getUniqueId() . '/' . $action->getId()` em minúsculas (o urlManager não diferencia caixa, ≈L148). Rota pública libera. Visitante em rota não pública: AJAX → 401 JSON, como em `init()`; senão redireciona para `user->loginUrl`. Depois chame `parent::runActionWithFilters()`. Logado passa, e a autorização continua nos filtros dos controllers.
3. `ACCESS_POLICY_MODE=log|enforce` (env, padrão `log`; valor inválido vale `log`). Em `log`, registre `Yii::log(..., CLogger::LEVEL_WARNING, 'security.access')` com rota, método e se é AJAX, uma vez por request, sem dados do usuário. Em `main.php` acrescente `security.*` às categorias da rota de arquivo.
4. Crie a lista inicial, um arquivo por módulo, só com o que o código confirma:
   - `site.php`: `site/login`, `site/logout`, `site/error`, `site/index`, `site/captcha`. Confirme `site/contact` e `site/page` antes de listar.
   - `enrollmentonline.php`: as 8 actions acima. Confirme `enrollmentonline/default/index`.
   - `farmer.php` e `foods.php`: liste só o que as telas públicas do agricultor e do edital realmente chamam (leia as views e o JS). Comente com `// REVISAR:` o que parece administrativo. Inclua as duas actions do br-nham, com a justificativa de que saem da lista quando o R3 migrar a API.
   - **Não** liste as do courseplan: elas aparecerão no log como visitante em rota não pública.
5. Teste `AccessPolicyCoverageTest`: enumere controllers e actions (a saída do `scripts/inventory-actions.php`, criado no R1-AGT-03; se não existir, reflexão sobre `action*` e `actions()`). Cada action precisa estar na lista pública ou protegida (o controller tem `accessControl` em `filters()` e nenhuma regra libera visitante para ela). Os casos atuais ficam em `tests/_data/access-baseline.json`, que **só pode encolher**.
6. Documente no Handoff como ligar o `enforce` (a decisão fica para o R3).

## Fora de escopo
- Ligar o `enforce` e adicionar `filters()` aos 6 controllers (um card cada, depois do período em log).
- Mudar o 401 do `Controller::init()`.
- Corrigir as regras do courseplan e do farmer.
- Autorização por papel.

## Critérios de aceite
- [ ] Em modo `log` o comportamento HTTP não muda para nenhuma rota.
- [ ] Em modo `enforce` (teste), visitante em rota não pública recebe redirecionamento ou 401 JSON, e rota pública passa.
- [ ] Cada acesso de visitante a rota não pública gera um log `security.access` e chega ao Sentry com `!YII_DEBUG`.
- [ ] O teste de cobertura passa, com a baseline versionada.
- [ ] Cada rota da lista pública tem comentário com a justificativa.
- [ ] `Tag\Shared` continua sem `Yii::app()` fora de `Infrastructure/Yii`.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter AccessPolicy
composer test:integration -- --filter AccessPolicyCoverage

# visitante em rota que não é pública (modo log: responde como antes)
curl -i "http://localhost/?r=courseplan/courseplan/index"
# esperado: mesma resposta de antes e uma linha security.access em app/runtime/<INSTANCE>/<data>/
ACCESS_POLICY_MODE=enforce   # só em teste: a mesma rota passa a redirecionar para site/login
```
- Confirmar: se as actions públicas em AJAX do farmer funcionam hoje para visitante. O `Controller::init()` devolve 401 para visitante com `X-Requested-With`, então chamadas do jQuery a elas já falham.
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

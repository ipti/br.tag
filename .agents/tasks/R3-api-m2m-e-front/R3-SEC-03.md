---
id: R3-SEC-03
title: "Ativar filters()/accessControl nos 6 controllers cujas accessRules() nunca rodam"
release: R3
module: plataforma
type: seguranca
skill: tag-secure-controller
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-SEC-01]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3) e seção 6 (B2, filters() nos 6 controllers)"
write_scope:
  - app/controllers/CensoController.php
  - app/controllers/AdministratorController.php
  - app/controllers/SiteController.php
  - app/modules/foods/controllers/FoodrequestController.php
  - app/modules/schoolreport/controllers/FormsController.php
  - app/modules/schoolreport/controllers/ReportsController.php
  - app/config/public-routes/foods.php
  - tests/**
read_only:
  - app/components/Controller.php
  - app/components/utils/TagUtils.php
  - themes/default/views/layouts/menus/**
  - instance.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:integration -- --filter AccessRules"
  - "Smoke com curl da seção Como verificar: visitante → 302; usuário sem papel → 403; ações legítimas → 200"
---

# R3-SEC-03: Ativar filters()/accessControl nos 6 controllers cujas accessRules() nunca rodam

## Contexto
Módulos tocados: raiz (Censo, Administrator, Site), foods e schoolreport. Os 6 controllers definem `accessRules()` mas não `filters()`, e `Controller` (`app/components/Controller.php`) também não define `filters()`. Resultado: as regras nunca executam (seção 2, achado 3). A `AccessPolicy` do R2-SEC-02 e do R3-SEC-01 só separa visitante de logado; as regras por papel só rodam com `filters()`. Ligar `'accessControl'` **muda o comportamento**, por três razões verificadas:
- Sem regra final de `deny`, o `CAccessControlFilter` **libera** a action que nenhuma regra citou.
- Com `deny` final (já existe em Forms e Reports), a action esquecida passa a ser **negada**.
- Várias regras citam nomes que não existem como action.

| Controller | Regras hoje | Problema verificado |
|---|---|---|
| `CensoController` (≈L88–L117) | cópia das regras do Administrator; sem `deny` | cita `CreateUser`, `conflicts`, `acl`, `backup`, `syncExport`… que não existem aqui. As 11 actions reais (`ImportDegreeCodes`, `Validate`, `Index`, `ExportWithoutInepid`, `Export`, `ExportIdentification`, `DownloadExportFile*`, `InitialImport`, `InepImport`, `Import`) só têm regra em parte; `Validate`, `InitialImport`, `InepImport`, `ImportDegreeCodes` não têm |
| `AdministratorController` (≈L35–L50) | `CreateUser`, `index`, `conflicts` para `'*'`; resto para `'@'`; sem `deny` | `conflicts` não existe; `clearMaster` na regra, mas a action é `CleanMaster` (≈L1565); sem regra para `MultiStageClassroomVerify`, `SaveMultiStage`, `UpdateDB`, `LoadToMaster`, `DownloadExportFile`; `actionBackup` é `static` (≈L544) |
| `SiteController` (≈L13–L20) | só ações autenticadas; sem `deny` | `loadMoreWarns` na regra, mas a action é `LoadWarnsHtml` (≈L214); `ViewFileLogs` sem regra; `login`, `logout`, `error`, `captcha`, `page` (via `actions()`), `contact` e `index` (ela mesma redireciona visitante) têm de seguir públicas, como na lista do R2-SEC-02 |
| `FoodrequestController` (≈L5–L33) | `index`, `view`, `getFoodRequest`… para `'*'`; sem `deny` | `updateRequestStatus` e `getFarmerFoods` não existem; `admin` (≈L603) sem regra; `updateAcceptedFoods` e `updateReceivedFoods` (≈L168 e ≈L218) são públicas por Bearer |
| `FormsController` (schoolreport, ≈L10–L44) | `'@'` + `deny` | 6 actions reais sem regra: `StudentIMCReport`, `ConclusionCertification`, `StudentsFileForm`, `StatementAttended` (a regra diz `StudentStatementAttended`), `WarningTerm`, `SuspensionTerm`. `instance.php` (≈L2–L12, `$_FORMS`) aponta menus para `StatementAttended`, `WarningTerm`, `ConclusionCertification` e `SuspensionTerm` |
| `ReportsController` (schoolreport, ≈L10–L82) | `'@'` + `deny` | cerca de 19 actions sem regra, entre elas `TotalNumberOfStudentsEnrolled`, `StudentCpfRgNisAllSchools`, `ClassContentsReport` e `StudentsBetween5And14YearsOldReport` (as duas últimas aparecem em `resolveLayout()` ≈L100), `GetDisciplines`, `GetStagesMulti`, `GetEnrollments`, `GenerateElectronicDiaryReport`. Confirmar a lista exata com o script do passo 1 |

Os `beforeAction()` de Forms (≈L46) e Reports (≈L86) sobrescrevem o da base **sem chamar `parent::`**. Com isso pulam o `startTransaction` do Sentry, a checagem de `authTimeout` e a atualização de `last_activity` (`Controller.php` ≈L56–L88); o `afterAction` da base ainda tenta fechar um span que nunca abriu.

**Pontos de coordenação:** `app/controllers/AdministratorController.php` já foi alterado pelo R0-ADMIN-01 (papéis do `CreateUser`, `import` só superuser): releia as regras atuais antes de editar. `FoodrequestController.php` também entra no R3-FOODS-02: não rodem em paralelo, e o segundo rebasa no primeiro. O plano prevê um card por controller; se preferir, execute em 3 PRs (raiz, foods, schoolreport).

## Objetivo
Os 6 controllers passam a aplicar suas regras (`accessControl` com `deny` final e todas as actions reais classificadas), e o `beforeAction()` do schoolreport volta a chamar o da base.

## Passos
1. Gere a lista de actions de cada controller (`scripts/inventory-actions.php` do R1-AGT-03, se existir; senão `git grep -n "public function action"`) e monte, no Handoff, a tabela action → classificação (`pública`, `'@'` ou papel exigido) com a evidência (menu em `themes/default/views/layouts/menus/**`, `$_FORMS`, JS que chama a rota).
2. Em cada controller, acrescente `public function filters(): array { return ['accessControl']; }` (não declare tipo de retorno se o parent legado não tiver) e reescreva `accessRules()`: uma regra para cada grupo, terminando com `['deny', 'users' => ['*']]`. Remova nomes mortos.
3. Para ações restritas a papel use `'users' => ['@']` mais `'expression' => 'TagUtils::isAdmin() || TagUtils::isSuperuser()'`, como no R0-SYSADMIN-01. **Não** use `Yii::app()->user->checkAccess()` (achado 6). O papel de cada ação do Censo e do Administrator é decisão humana: liste a evidência, não decida sozinho.
4. foods: `updateAcceptedFoods` e `updateReceivedFoods` ficam com `'users' => ['*']`, e as duas já devem constar em `app/config/public-routes/foods.php` (R2-SEC-02), com a justificativa "Bearer do br-nham; remover no R3-API-05" (se faltar, acrescente). Confirme com o time se algum cliente externo usa as ações de leitura (`getFoodRequest`, `getFoodNotice`, `getFarmerRegister`…); sem evidência, passam a `'@'`.
5. schoolreport: em Forms e Reports troque o corpo do `beforeAction()` por: manter o redirecionamento do visitante, chamar `if (!parent::beforeAction($action)) { return false; }` e só depois definir `$this->year` (e o layout, em Reports). Registre no Handoff que a sessão agora expira pelo `authTimeout` nessas telas e renova `last_activity`.
6. `tag-qa`: teste de integração `AccessRulesCoverageTest` que percorre por reflexão cada controller e falha se uma action pública da classe não casar com nenhuma regra (ou se uma regra citar action inexistente); mais o caso de regressão do `instance.php` (`$_FORMS`).

## Fora de escopo
- Corrigir SQL ou regra de negócio desses controllers (R7-CENSO, R7-ADMIN-SITE, R5-FOODS e R6-SCHOOLREPORT).
- Os outros 18 controllers sem `accessControl` (cobertos pela política do R3-SEC-01 e pelos épicos dos módulos).
- Refatorar para services e mover controllers raiz para módulos.

## Critérios de aceite
- [ ] Os 6 controllers têm `filters()` com `accessControl` e `accessRules()` com `deny` final.
- [ ] Todas as actions reais estão classificadas na tabela do Handoff; nenhuma regra cita action inexistente.
- [ ] Visitante recebe 302 (ou 401 em AJAX) em todas as actions, exceto as públicas listadas (login, error, captcha e as duas do foods).
- [ ] Os formulários do `instance.php` e as ações `ClassContentsReport` e `StudentsBetween5And14YearsOldReport` continuam abrindo para usuário logado.
- [ ] `parent::beforeAction()` é chamado nos dois controllers do schoolreport.
- [ ] O teste de cobertura das regras existe e passa.

## Como verificar
```bash
curl -i "http://localhost/?r=censo/export"                              # visitante: 302 para site/login
curl -i "http://localhost/?r=schoolreport/forms/warningTerm"            # visitante: 302
curl -i -b "PHPSESSID=<professor>" "http://localhost/?r=administrator/import"   # sem papel: 403
curl -i -b "PHPSESSID=<admin>" "http://localhost/?r=schoolreport/forms/statementAttended&enrollmentId=1"   # 200
curl -i -X POST "http://localhost/?r=foods/foodrequest/updateAcceptedFoods" -H "Authorization: Bearer invalido"  # 200 com erro do controller
composer lint && composer analyse && composer test:integration -- --filter AccessRules
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

---
id: R5-SCHOOL
title: "school: levar o cadastro de escola ao critério de módulo pronto"
release: R5
module: school
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 2) e seção 7 (F4, F5)"
write_scope:
  - app/modules/school/**
  - app/src/School/**
  - tests/**/School/**
  - frontend/pages/school/**
  - sass/scss/pages/_school.scss
  - app/config/public-routes/school.php
  - metrics/baseline/school.json
  - phpstan/baseline/school.neon
read_only:
  - app/models/**
  - docs/arquitetura/plano-modernizacao.md
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/School no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php school mostra zero actions críticas com SQL cru ou superglobal"
  - "Golden master de criar, atualizar e excluir escola verde, com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo school: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-SCHOOL: school: levar o cadastro de escola ao critério de módulo pronto

## Contexto
O `school` cadastra a **escola** (identificação, estrutura física e dados do Educacenso, gestor), as etapas que ela oferece, as salas (`SchoolRoom`), o brasão (logo) e os relatórios da escola (resumo, movimento mensal e ficha). Não há `docs/modules/school.md`; a descrição vem do código. A escola é a base do tenant: quase todo módulo filtra por `Yii::app()->user->school`.

**Inventário do README (2026-11-27):** 1 controller, 691 LOC, 19 actions, 0 sem `accessControl`, 55 superglobais, 0 `createCommand`, 5 JSON, 15 views, 3 `<script>`, 8 `style=`, 3 Bootstrap 2, 4 JS (910 LOC), 0 CSS, 0 models, 0 services/usecases.

**Controller:** `SchoolController` (691 LOC, 19 actions), com os formulários em três models de `app/models/` (`SchoolIdentification`, `SchoolStructure`, `ManagerIdentification`).
- **`actionCreate` (≈L180–246) e `actionUpdate` (≈L253–349)** salvam três models e depois um laço de `SchoolStages`, **sem transação** (≈L221–227). O update também trata o upload do logo por `$_FILES` (≈L283).
- **`actionDelete` (≈L354–362)** apaga estrutura, identificação e gestor em sequência, sem transação e sem `postOnly`; não apaga `SchoolStages` nem `SchoolRoom`.
- **`accessRules` (≈L30–69):** a segunda regra tem o comentário "allow admin user" mas usa `'users' => ['@']` para `admin`, `manager`, `delete`, `reports`, `ReportsMonthlyTransaction` e `Record` (≈L57–62). Qualquer usuário logado pode **excluir escola**. A primeira regra lista nomes sem action (`edcenso_import`, `configacl`, `getorgans`).
- **Relatórios:** `actionReports` (≈L516), `actionReportsMonthlyTransaction` (≈L528) e `actionRecord` (≈L559); salas em `actionCreateRoom`, `actionUpdateRoom` e `actionDeleteRoom` (≈L590–685).

**JS** (`app/modules/school/resources/js/form/`, registrado em `views/school/_form.php` ≈L11–14): `validations.js` (448), `functions.js` (253), `pagination.js` (165) e `_initialization.js` (44). Um `?r=` literal. Não há JS no `js/` raiz.
**CSS:** nenhum arquivo. `<style>` em 5 views (`head.php`, `MonthlySummary.php`, `MonthlyTransaction.php`, `Record.php`, `_form.php`); são telas de impressão. `style=`: `_form.php` e `MonthlyTransaction.php` (3 cada).
**Sem `accessControl`:** nenhum.
**Models locais, services e usecases:** nenhum.

## Objetivo
Cadastro, exclusão e relatórios de escola passam a ter regra em `app/src/School/`, com perfil de acesso correto e gravação atômica, e o formulário em `frontend/pages/school/`.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Restringir `delete`, `admin`, `manager`, `reports`, `ReportsMonthlyTransaction` e `Record` aos perfis corretos (hoje `'@'`); decidir com uma pessoa **quem** pode excluir escola e quem vê os relatórios, antes de mudar. Usar `TagUtils::isAdmin()` e `isSuperuser()`, nunca `Yii::app()->user->checkAccess()`.
   - [ ] `postOnly + delete`, e `displayLogo`/`removeLogo` conferindo que a escola pertence ao usuário.
   - [ ] Limpar do `accessRules` os nomes sem action.
   - [ ] Conferir o `$_FILES` do logo (tipo, tamanho, caminho) e o bind das consultas dos relatórios.
   - [ ] CSRF no formulário da escola e nas salas.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Criar escola com estrutura, gestor, etapas e locais de funcionamento; atualizar com troca de etapas (`addNotInCondition`, ≈L311–315) e logo.
   - [ ] Excluir escola e criar, atualizar e excluir sala.
   - [ ] Saída dos três relatórios (snapshot do HTML ou dos dados).
4. **SVC (contexto `app/src/School`)**
   - [ ] `CreateSchoolService` e `UpdateSchoolService` com `TransactionManager`, incluindo `SchoolStages`.
   - [ ] `DeleteSchoolService` (o que deve cair junto: estrutura, gestor, etapas, salas) e `SchoolRoomService`.
   - [ ] Consultas dos relatórios em repositórios de `Infrastructure/Yii`.
5. **API:** não há endpoint para o app neste épico (a lista de escolas permitidas sai do `GET /me`).
6. **JS (`frontend/pages/school/`)**
   - [ ] Formulário da escola (4 arquivos) e o `?r=` literal restante; reaproveitar os validators do R3 (CEP, CPF, INEP).
7. **CSS (`sass/scss/pages/_school.scss`)**
   - [ ] Mover os `<style>` das telas de impressão (podem ir para `styles/print.scss`, conforme a F5) e os 8 `style=`; trocar as 3 ocorrências de Bootstrap 2.

## Fora de escopo
- Mudar regra de cadastro do Educacenso ou a estrutura da escola.
- Importação de escolas do Educacenso (`edcenso_import`, que não existe como action).
- Editar `app/models/SchoolIdentification.php` e demais models (arquivos com dono).
- Relatórios do `schoolreport` (épico R6-SCHOOLREPORT).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/school.php`; excluir escola exige o perfil definido; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** criar, atualizar, excluir e relatórios têm golden master ou teste de caracterização.
- [ ] **SVC:** `actionCreate`, `actionUpdate` e `actionDelete` são magras e atômicas; regras com 80% de cobertura.
- [ ] **JS:** formulário em `frontend/pages/school/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_school.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
php scripts/inventory-actions.php school
composer analyse && composer lint
composer test:unit -- --filter School            # confirmar os nomes dos scripts no R1-TEST-01
composer test:integration -- --filter School
npx playwright test tests/e2e/school
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

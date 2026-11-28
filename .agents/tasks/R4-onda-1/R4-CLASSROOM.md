---
id: R4-CLASSROOM
title: "classroom: levar o cadastro de turma ao critério de módulo pronto"
release: R4
module: classroom
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R4-REFINE-01]
size: G
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, próximas ondas) e seção 8.6 (onda 1)"
write_scope:
  - app/modules/classroom/**
  - app/src/Classroom/**
  - tests/**/Classroom/**
  - frontend/pages/classroom/**
  - sass/scss/pages/_classroom.scss
  - app/config/public-routes/classroom.php
  - metrics/baseline/classroom.json
  - phpstan/baseline/classroom.neon
  - js/classroom/**
read_only:
  - app/models/**
  - app/modules/student/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/Classroom no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php classroom mostra zero actions críticas com SQL cru ou superglobal"
  - "Golden master de criar, atualizar e excluir turma verde, com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo classroom: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R4-CLASSROOM: classroom: levar o cadastro de turma ao critério de módulo pronto

## Contexto
O `classroom` cadastra a **turma**: etapa e modalidade, dias da semana e turno, calendário, regras de nota, disciplinas, professores vinculados (matriz de ensino) e atividades complementares/AEE. Também faz as ações em lote sobre as matrículas (alteração de etapa, status, transporte e ordem diária) e a sincronização com o SEDSP. Não há `docs/modules/classroom.md`; a descrição vem do código.

**Inventário do README (2026-11-27):** 1 controller, 1032 LOC, 20 actions, 0 sem `accessControl`, 55 superglobais, 3 `createCommand`, 11 JSON, 8 views, 2 `<script>`, 33 `style=`, 27 Bootstrap 2, JS em `js/classroom/` (fora do módulo), 0 CSS, 0 models, 0 services/usecases.

**Controller:** `DefaultController` (1032 LOC, 20 actions). As maiores: `actionUpdate` (≈L501–728), `actionCreate` (≈L390–500), `actionDelete` (≈L729–791), `actionAdmin` (≈L826–888) e `actionSyncUnsyncedStudents` (≈L1003–1031).
- **Lotes sem checagem de dono:** `actionBatchUpdateTotal` (≈L303–315) trata as chaves de `$_POST` como id de `StudentEnrollment`, sem testar `null` nem se a matrícula é da escola. `actionUpdateDailyOrder` (≈L929) e `actionChangeEnrollments` (≈L963) fazem `findAllByPk` com ids postados e `save()` em laço sem transação.
- **SEDSP no meio da transação:** `actionDelete` abre transação, **exclui a turma no SEDSP** (≈L735–757) e só depois apaga no banco; falha do banco depois do SEDSP deixa os dois lados divergentes.
- **`accessRules` com nomes sem action:** `getcomplementaryactivitytype`, `move`, `updateDisciplines` e `getCalendars` (≈L35–55) não existem no controller. O `delete` não tem `postOnly`.

**JS** (`js/classroom/`, registrado por `views/default/_form.php` ≈L13–17 e `index.php` ≈L11): `form/functions.js` (549), `form/validations.js` (182), `form/_initialization.js` (130), `form/pagination.js` (85), `form/dialogs.js` (47) e `index/functions.js` (8). São 5 `?r=` literais. O módulo não tem `resources/`.
- **JSON na view:** `_form.php` (1296 linhas) emite `var teachingData = <?= json_encode($teachingDataArray) ?>` (≈L1219) e `var disciplines = …` (≈L1220) num `<script>` (≈L1215), e outro `<script>` (≈L1273–1296) com `multiStages` e `alternanciaRestrictedStages`. Os dados vêm de código que monta `<li>` à mão na view (≈L791–816).
**CSS:** nenhum arquivo. `style=`: `_form.php` (**23**), mais 2 em cada uma de `index.php`, `batchupdate*.php`. Bloco `<style media="print">` em 5 views (`_form.php` ≈L1004 e os 4 `batchupdate*`).
**Sem `accessControl`:** nenhum (a regra de `admin` usa `'users' => ['admin']`).
**Models:** nenhum local; usa `Classroom`, `StudentEnrollment`, `InstructorTeachingData` e correlatos de `app/models/`.

## Objetivo
Criar, atualizar, excluir e operar em lote sobre turmas passa a ter regra em `app/src/Classroom/`, com limite de escola nos lotes e o SEDSP fora da transação, e o formulário em `frontend/pages/classroom/`.

## Passos
1. O card de refinamento ([R4-REFINE-01](R4-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Nos lotes (`batchUpdateTotal`, `batchUpdateTransport`, `batchupdatEnrollment`, `updateDailyOrder` e `changeEnrollments`): validar que cada matrícula pertence a uma turma da escola do usuário e tratar `null`.
   - [ ] Remover do `accessRules` os nomes sem action, colocar `postOnly + delete` e conferir se `admin` deve continuar restrito ao usuário `admin`.
   - [ ] Conferir os três `createCommand` (≈L318, ≈L914 e ≈L920): o primeiro tem SQL fixo, os outros precisam de bind.
   - [ ] CSRF no formulário da turma e nos POST AJAX de lote.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Criar turma com disciplinas, professores, calendário e regras de nota (`saveClassroomVsGradeRules`), para etapa regular, multi, AEE e atividade complementar.
   - [ ] Atualizar turma (a maior action) e excluir com e sem matrículas, professores vinculados e AEE.
   - [ ] Reordenar matrículas (`updateDailyOrder` e `changeEnrollments`) e os lotes; sincronização SEDSP com dublê do gateway.
4. **SVC (contexto `app/src/Classroom`)**
   - [ ] `CreateClassroomService`, `UpdateClassroomService` e `DeleteClassroomService` com `TransactionManager`; o SEDSP passa a ser chamado por um gateway **depois** do commit, com compensação registrada.
   - [ ] `ReorderEnrollmentsService` e os serviços de lote, com a regra de escola.
   - [ ] `ListMyClassroomsService` já nasce no [R4-API-02](R4-API-02.md); não duplicar.
5. **API:** o app só precisa de `GET /me/classrooms` (R4-API-02). Nada neste épico.
6. **JS (`frontend/pages/classroom/`)**
   - [ ] Formulário da turma (`form/*`, 5 arquivos) e `index`.
   - [ ] Trocar `var teachingData` e `var disciplines` por `TFrontend::jsonScript` e os 5 `?r=` pelo `url()` gerado; tirar do `_form.php` a montagem de HTML dos professores e os dois `<script>` inline.
7. **CSS (`sass/scss/pages/_classroom.scss`)**
   - [ ] Mover os 33 `style=` e os 5 blocos `<style media="print">`; trocar as 27 ocorrências de Bootstrap 2.

## Fora de escopo
- `TimesheetController` e o quadro de horário (R5-TIMESHEET), embora o `Classroom` seja compartilhado.
- Reescrever a integração SEDSP (R7-SEDSP).
- Mudar regras de turma, de calendário ou de nota.
- Editar `app/models/Classroom.php` (arquivo com dono).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/classroom.php`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** criar, atualizar, excluir e lotes têm golden master ou teste de caracterização.
- [ ] **SVC:** `actionCreate`, `actionUpdate` e `actionDelete` são magras; nenhuma chamada ao SEDSP dentro de transação; regras com 80% de cobertura.
- [ ] **JS:** `js/classroom/` migrado para `frontend/pages/classroom/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_classroom.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
php scripts/inventory-actions.php classroom
composer analyse && composer lint
composer test:unit -- --filter Classroom          # confirmar os nomes dos scripts no R1-TEST-01
composer test:integration -- --filter Classroom
npx playwright test tests/e2e/classroom
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

---
id: R5-TIMESHEET
title: "timesheet: levar o quadro de horário ao critério de módulo pronto"
release: R5
module: timesheet
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, próximas ondas: timesheet) e seção 8.6 (onda 2)"
write_scope:
  - app/modules/timesheet/**
  - app/src/Timesheet/**
  - tests/**/Timesheet/**
  - frontend/pages/timesheet/**
  - sass/scss/pages/_timesheet.scss
  - app/config/public-routes/timesheet.php
  - metrics/baseline/timesheet.json
  - phpstan/baseline/timesheet.neon
read_only:
  - app/models/**
  - app/modules/classdiary/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/Timesheet no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php timesheet mostra zero actions críticas com SQL cru ou superglobal"
  - "Golden master de gerar, trocar e remover aulas verde (com o sorteio de disciplinas controlado), com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo timesheet: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-TIMESHEET: timesheet: levar o quadro de horário ao critério de módulo pronto

## Contexto
O `timesheet` monta o **quadro de horário** de cada turma: gera as aulas (`Schedule`) do ano a partir da matriz curricular, do calendário e dos dias da semana, deixa trocar, adicionar e remover aulas, marca indisponibilidade e cuida do **professor substituto** por dia. O quadro alimenta a frequência e o conteúdo do `classdiary`. Há só um `README.md` de uma linha no módulo; a descrição vem do código.

**Inventário do README (2026-11-27):** 1 controller, 1143 LOC, 16 actions, 0 sem `accessControl`, 66 superglobais, 10 `createCommand`, 14 JSON, 2 views, 0 `<script>`, 20 `style=`, 41 Bootstrap 2, 4 JS (1041 LOC), 1 CSS, 6 models, 0 services.

**Controller:** `TimesheetController` (1143 LOC, 16 actions).
- **`actionGenerateTimesheet` (≈L260–403):** recusa se já há frequência ou conteúdo (≈L284), depois `Schedule::model()->deleteAll(...)` (≈L290) e um insert em lote (≈L396), **sem transação** (falha no meio apaga o quadro). O sorteio usa `shuffle($disciplines)` (≈L342), então o resultado é aleatório; a consulta de eventos do calendário (≈L351) roda dentro do laço, uma vez por aula. Com os créditos zerados, `$disciplines[0]` fica indefinido.
- **`actionChangeSchedules` (≈L418–588):** troca duas aulas, com datas montadas de `$_POST` e consultas da última falta e do último conteúdo (≈L432–433).
- **Outras:** `actionRemoveSchedule` (≈L590), `actionAddSchedule` (≈L662), os três do substituto (≈L740–931), `actionGetFrequency` (≈L933), `actionGetDisciplines` (≈L991), `actionGetInstructors` (≈L1060) e `actionFixBuggedUnavailableDaysFor2024` (≈L1105, correção pontual de 2024 liberada para qualquer logado).
- **`accessRules` (≈L21–63):** `removeSchedule`, `addSchedule`, `substituteInstructor` e `getInstructors` **não estão na lista**, embora a tela use as quatro (`views/timesheet/index.php` ≈L17–18 e ≈L73; `substituteInstructors.js` ≈L229). Pelo `deny` final seriam recusadas. **Confirmar em ambiente** antes de qualquer mudança. Em sentido contrário, `GetInstructorDisciplines`, `addInstructors`, `loadUnavailability`, `addinstructorsdisciplines` e `ChangeInstructor` estão na lista sem action (e o `index.php` ≈L148 aponta o formulário para `timesheet/addInstructorsDisciplines`).
- **`Schedule` duplicado:** `app/models/Schedule.php` (154 linhas) e `app/modules/timesheet/models/Schedule.php` (171 linhas) declaram `class Schedule`. **Divergiram:** o do `app/models` valida `substitute_instructor_fk`; o do módulo tem `weekDays()` e `getInitialHour()`. Qual vale depende de quem carrega primeiro (o módulo só importa `timesheet.models.*` ao entrar nele). O `classdiary` e o `calendar` usam `Schedule::model()` de fora do módulo. **Renomear o do módulo** (por exemplo `TimesheetSchedule`) e unificar as regras; como `app/models/**` tem dono, a parte do model compartilhado vira card de contrato.

**JS** (`app/modules/timesheet/resources/common/js/`, publicado por `assetManager`): `timesheet.js` (583), `substituteInstructors.js` (315), `instructors.js` (143) e `index.js` (vazio). Cinco `?r=` literais, mais URLs injetadas por `var xURL = '<?= createUrl ?>'` no `index.php` (≈L14–21).
**CSS:** `resources/common/css/layout.css`, registrado em `index.php` (≈L12). `style=`: `index.php` (17) e `substituteInstructor.php` (3). Sem `<style>` e sem controller sem `accessControl`.
**Models locais:** `InstructorDisciplines`, `InstructorSchool`, `Schedule`, `TimesheetCurricularMatrix`, `TimesheetInstructor`, `Unavailability`.

## Objetivo
Gerar, trocar e remover aulas do quadro passa a ser regra testada em `app/src/Timesheet/`, atômica e com sorteio injetável, sem classe `Schedule` duplicada, e a tela em `frontend/pages/timesheet/`.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Confirmar em ambiente o que acontece com `removeSchedule`, `addSchedule`, `substituteInstructor` e `getInstructors`; ajustar o `accessRules` para refletir a realidade e remover os nomes sem action.
   - [ ] Restringir `fixBuggedUnavailableDaysFor2024` (ou remover, com aprovação) e decidir os perfis que alteram o quadro; `postOnly` nas escritas.
   - [ ] Conferir que `classroomId` e `schedule` postados pertencem à escola do usuário.
   - [ ] Conferir o bind dos 10 `createCommand` (os de calendário já usam `bindParam`).
   - [ ] CSRF nos POST AJAX do quadro e no formulário de professores e disciplinas.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Gerar quadro com semente fixa (o sorteio vira dependência injetável) para turmas de vários dias e créditos; recusa quando há frequência ou conteúdo.
   - [ ] Trocar duas aulas (inclui a última falta e o último conteúdo), adicionar e remover aula, marcar indisponibilidade.
   - [ ] Substituto: adicionar e remover por dia (essas duas actions já usam transação).
4. **SVC (contexto `app/src/Timesheet`)**
   - [ ] `GenerateTimesheetService` com `TransactionManager`, `Domain` de distribuição de créditos e o calendário lido uma vez, fora do laço.
   - [ ] `ChangeSchedulesService`, `AddScheduleService`, `RemoveScheduleService` e o serviço do substituto.
   - [ ] **Renomear o `Schedule` do módulo** e acertar as chamadas dentro de `app/modules/timesheet/**`; o card de contrato cuida de `app/models/Schedule.php`.
5. **API:** não há endpoint para o app neste épico (o app lê o quadro só pela frequência).
6. **JS (`frontend/pages/timesheet/`)**
   - [ ] `timesheet`, `substituteInstructors` e `instructors`; trocar os 5 `?r=` e as `var xURL` por `url()` e `jsonScript`.
7. **CSS (`sass/scss/pages/_timesheet.scss`)**
   - [ ] Mover `resources/common/css/layout.css` e os 20 `style=`; trocar as 41 ocorrências de Bootstrap 2.

## Fora de escopo
- O calendário escolar (`calendar`, R6-CALENDAR) e a frequência (`classdiary`).
- Mudar a regra de distribuição de aulas ou o conteúdo do quadro.
- Editar `app/models/Schedule.php` neste épico (arquivo com dono; vai para o card de contrato).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/timesheet.php`; o `accessRules` corresponde às actions reais; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** gerar, trocar, adicionar, remover e substituto têm golden master ou teste de caracterização.
- [ ] **SVC:** `actionGenerateTimesheet` e `actionChangeSchedules` são magras e atômicas; o sorteio é injetável; regras com 80% de cobertura.
- [ ] Existe uma única classe `Schedule` carregável por nome.
- [ ] **JS:** páginas em `frontend/pages/timesheet/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_timesheet.scss`, sem `layout.css` solto e Bootstrap 2 em queda.

## Como verificar
```bash
php scripts/inventory-actions.php timesheet
composer analyse && composer lint
composer test:unit -- --filter Timesheet         # confirmar os nomes dos scripts no R1-TEST-01
composer test:integration -- --filter Timesheet
npx playwright test tests/e2e/timesheet
git grep -n "^class Schedule" -- app             # esperado: uma ocorrência
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

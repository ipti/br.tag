---
id: R4-CLASSDIARY
title: "classdiary: levar o diário de classe (frequência, conteúdo e justificativas) ao critério de módulo pronto"
release: R4
module: classdiary
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R4-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, próximas ondas), seção 7 (F4, item 4) e seção 8.6 (onda 1)"
write_scope:
  - app/modules/classdiary/**
  - app/src/ClassDiary/**
  - tests/**/ClassDiary/**
  - frontend/pages/classdiary/**
  - sass/scss/pages/_classdiary.scss
  - app/config/public-routes/classdiary.php
  - metrics/baseline/classdiary.json
  - phpstan/baseline/classdiary.neon
read_only:
  - app/models/**
  - app/modules/instructor/controllers/DefaultController.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/ClassDiary no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php classdiary mostra zero actions críticas com SQL cru ou superglobal"
  - "Golden master e testes unitários de ClassDiary verdes, com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo classdiary: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R4-CLASSDIARY: classdiary: levar o diário de classe (frequência, conteúdo e justificativas) ao critério de módulo pronto

## Contexto
O `classdiary` é o diário de classe: o professor lança a **frequência** por aluno e aula, registra o **conteúdo** ministrado ligado ao plano de curso e dá **justificativa** de faltas. Não há `docs/modules/classdiary.md`; a descrição vem do código. É o módulo com mais PRs de correção em 24 meses (seção 6, B3) e a base do app mobile (frequência e turmas).

**Inventário do README (2026-11-27):** 3 controllers, 1561 LOC, 30 actions, 0 sem `accessControl`, 102 superglobais, 24 `createCommand`, 11 JSON, 18 views, 15 `style=`, 10 Bootstrap 2, 9 JS (1790 LOC), 0 CSS, 0 models, 21 services/usecases.
- **Divergência para o refinamento:** a árvore atual tem também `EntryLockController` (113 LOC, 1 action, `TagUtils::isAdmin()`), do bloqueio de lançamentos. Com ele são 4 controllers e 1713 LOC. Confirmar as contagens com `scripts/inventory-actions.php`.

**Controllers (LOC; actions):** `ClassesController` (1035; 13), `DefaultController` (377; 13), `ClassFaultsController` (188; 6, com cara de CRUD gerado pelo Gii), `EntryLockController` (113; 1). As maiores actions: `actionGetClassContents` (≈L173–476), `actionSaveClassContents` (≈L477–650) e `actionGetFrequency` (≈L683–785).
- **Duplicidade:** o salvar frequência existe duas vezes. `ClassesController::actionSaveFrequency` e `actionSaveFrequencies` (≈L786–882, lógica inline, `classroomId`) e `DefaultController::actionSaveFresquency` (≈L300, com typo, via usecase `SaveFrequency`, `classroom_id`). O salvar conteúdo também (`ClassesController` ≈L477 e `DefaultController` ≈L194).
- **Regra de matrícula:** `verifyStatusEnrollment` (≈L884–917) decide por status (1, 2, 13, 11) se o aluno recebe falta na data.

**JS** (`app/modules/classdiary/resources/`, publicado por `assetManager`): `legacy/classes/frequency/_initialization.js` (453) e `_initialization_instructor.js` (430), `legacy/classes/class-contents/_initialization.js` (302), `classDiary/functions.js` (285), `class-contents/functions.js` (111) e `validateFunctions.js` (99), `classDays/_initialization.js` (90), `entry-lock-notice.js` (59) e `index/*` (46). São 23 `?r=` literais. As views não registram arquivos do `js/` raiz.
**CSS:** nenhum arquivo. Há `<style>` em `classes/classContents.php`, `classes/validateClassContents.php` e `default/_frequencyElementMobile.php`. Mais `style=`: `classes/frequency.php` (4), `frequencyInstructor.php`, `classContents.php` e `validateClassContents.php` (3 cada).
**Sem `accessControl`:** nenhum. **Regras abertas:** `ClassFaultsController` libera `index` e `view` para `'*'` (≈L31–32).
**Models:** nenhum local; usa `Classroom`, `ClassFaults`, `ClassContents`, `Schedule` e `StudentEnrollment` de `app/models/`.
**Services e usecases existentes:** 6 services (`ClassesService`, `StudentService`, `CourseClassService`, `InstructorService`, `DisciplineService`, `DiaryEntryLock`) e 16 usecases de ~22 linhas, que só delegam aos services.

## Objetivo
Frequência, conteúdo e justificativa passam a ter regra em `app/src/ClassDiary/`, com testes, sem duplicidade de implementação, e as páginas do módulo vão para `frontend/pages/classdiary/`.

## Passos
1. O card de refinamento ([R4-REFINE-01](R4-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Fechar `index` e `view` do `ClassFaultsController` (`'*'`) ou listá-los em `app/config/public-routes/classdiary.php`, se houver uso público; descobrir se a tela é usada.
   - [ ] Remover do `ClassesController` as regras de `admin` e `delete` sem action correspondente, ou confirmar que existem.
   - [ ] Conferir o bind nos `createCommand` de `ClassesController` (≈L197–395 e ≈L980–1027) e de `EntryLockController`; `ClassesService` já usa `bindValue`.
   - [ ] CSRF nos formulários e nos POST AJAX de frequência e conteúdo (a ponte do R3-FRONT-04 acrescenta o token).
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Lançar e remover falta de um aluno e de toda a turma, para etapa menor e maior (`checkIsStageMinorEducation`), e o efeito de `DiaryEntryLock::assertUnlocked`.
   - [ ] Regra de matrícula por status (1, 2, 11, 13), caracterizando `case '13'` (≈L905–907) sem corrigir.
   - [ ] Salvar conteúdo com e sem `hasNewClassContent`; justificativa de falta.
4. **SVC (contexto `app/src/ClassDiary`)**
   - [ ] `SaveFrequencyService`, unificando `ClassesController::actionSaveFrequency(ies)` e `DefaultController::actionSaveFresquency`, com `TransactionManager` (hoje o `deleteAll` e os inserts rodam sem transação) e o `Domain` da regra de matrícula.
   - [ ] `GetFrequencyService`, `SaveClassContentsService`, `GetClassContentsService` e `SaveJustificationService`.
   - [ ] Levar o `DiaryEntryLock` para `Domain` e `Application`, mantendo o 403.
   - [ ] Os usecases viram fachadas até serem removidos.
5. **API (só se o app precisar):** os endpoints de frequência ficam no [R5-API-01](../R5-onda-2/R5-API-01.md). Este épico garante apenas que os services não leem `$_POST` nem sessão.
6. **JS (`frontend/pages/classdiary/`)**
   - [ ] `frequency` e `frequency-instructor` (453 e 430 linhas, provavelmente com lógica repetida).
   - [ ] `class-contents` (com `validateFunctions.js`), `class-days`, `class-diary` e `entry-lock-notice`.
   - [ ] Trocar os 23 `?r=` pelo `url()` gerado e `var x = <?= ?>` por `jsonScript`.
7. **CSS (`sass/scss/pages/_classdiary.scss`)**
   - [ ] Mover os 3 blocos `<style>` e os `style=` das 6 views com mais ocorrências; trocar as 10 ocorrências de Bootstrap 2 por classes TAG verificadas.

## Fora de escopo
- Os endpoints de frequência da API (R5-API-01) e a tela de professor `instructor/frequency` (épico R5-INSTRUCTOR).
- Mudar regra de frequência, de bloqueio de lançamentos ou de conteúdo.
- Editar models de `app/models/**` (arquivos com dono).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/classdiary.php`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** frequência, conteúdo e justificativa têm golden master ou teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; uma única implementação de salvar frequência e de salvar conteúdo; regras em `app/src/ClassDiary/` com 80% de cobertura.
- [ ] **JS:** páginas em `frontend/pages/classdiary/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_classdiary.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
php scripts/inventory-actions.php classdiary       # actions críticas sem SQL cru nem superglobal
composer analyse && composer lint
composer test:unit -- --filter ClassDiary          # confirmar os nomes dos scripts no R1-TEST-01
composer test:integration -- --filter ClassDiary
npx playwright test tests/e2e/classdiary
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

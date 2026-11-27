---
id: R5-GRADES
title: "grades: levar o restante do módulo de notas ao critério de módulo pronto"
release: R5
module: grades
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, piloto grades) e seção 7 (F4, itens 1 e 4: grades-structure)"
write_scope:
  - app/modules/grades/**
  - app/modules/gradeconcept/**
  - app/modules/gradestructure/**
  - app/src/Grades/**
  - tests/**/Grades/**
  - frontend/pages/grades/**
  - sass/scss/pages/_grades.scss
  - app/config/public-routes/grades.php
  - metrics/baseline/grades.json
  - phpstan/baseline/grades.neon
  - js/admin/grades-structure.js
  - js/enrollment/grades/**
  - css/grades.css
read_only:
  - app/models/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/Grades no nível máximo, sem baseline)"
  - "O golden master de notas do R2 continua verde após cada card do épico"
  - "scripts/inventory-actions.php grades mostra zero actions críticas com SQL cru ou superglobal"
  - "Catraca do módulo grades: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-GRADES: grades: levar o restante do módulo de notas ao critério de módulo pronto

## Contexto
O `grades` é o módulo de **notas**: lançamento por avaliação e por unidade, boletim, fechamento de turma, estrutura de notas (unidades e modalidades), conceitos e cálculo de média final e situação do aluno. O R2 fez o **piloto**: o motor de cálculo em `app/src/Grades` com golden master (R2-GRADES-01 a 08, com os services de R2-GRADES-05 a 07). O [R4-GRADES-01](../R4-onda-1/R4-GRADES-01.md) migrou o JS de `gradesRelease` e `reportCard`. Este épico cobre **o que sobrou** e não repete esses dois. Não há `docs/modules/grades.md`; a descrição vem do código.

**Inventário do README (2026-11-27):** 4 controllers, 1807 LOC, 35 actions, 0 sem `accessControl`, 28 superglobais, 13 `createCommand`, 16 JSON, 11 views, 2 `<script>`, 18 `style=`, 6 Bootstrap 2, 7 JS (1353 LOC), 0 CSS, 0 models, 16 usecases. Na árvore atual os controllers somam 1828 LOC (`DefaultController` 848, `StructureController` 481, `EntryController` 398, `ConceptController` 101); confirmar com o script de inventário.

**O que fica para este épico**
- **Controllers:** `DefaultController` (17 actions) fora do que o R2 extraiu: `actionGetModalities` (≈L55–65), `actionGetClassroomStages` (≈L90), `actionGetDisciplines` (≈L121), `actionGetUnities` (≈L156), `ClassClosure`, `ClassClosureList` e `BatchClassClose` (≈L681–764). `StructureController` (6 actions: `create`, `delete`, `getunities` ≈L185, **`saveunities` ≈L319–464**, `copy` ≈L465). `ConceptController` (4 actions; só `delete` com `postOnly`).
- **`EntryController` duplica o `DefaultController`:** as 8 actions (`grades`, `getGrades`, `saveGrades`, `getDisciplines`, `calculateFinalMedia`, `reportCard`, `saveGradesReportCard`, `gradesRelease`) repetem as do `DefaultController`, e as duas rotas estão vivas (as views de `entry/` carregam `js/enrollment/*`; as de `default/`, `resources/legacy/*`). Descobrir o porquê (menu, perfil?) e decidir o destino com o produto.
- **SQL:** `actionGetModalities` monta `"stage = $stage"` com `$_POST['Stage']` (≈L57–58); confirmar se o R0-SQL-03 já corrigiu.
- **Usecases (16 arquivos, em `app/modules/grades/usecases/`):** os que o R2 não levou para `app/src/Grades`, por exemplo `GetStudentGradesByDisciplineUsecase` (o maior, ≈26 KB), `CalculateNumericGradeUsecase` (≈15 KB), `CalculateConceptGradeUsecase`, `CopyGradeStructUsecase` e `UpdateGradeStructUsecase`, `UpdateGradeJustOneStructUsecase`, `UpdateGradePartialRecoveryUsecase` e `UpdateGradeRulesUsecase`. Conferir os nomes finais nos cards do R2.
- **Módulos satélites:** `gradeconcept` tem só `GradeconceptModule.php` e `resources/_initialization.js` (37 linhas); `gradestructure` é um **diretório vazio** (`controllers/` e `views/` sem arquivos). Os dois estão em `app/config/main.php` (≈L110 e ≈L121, arquivo com dono: a remoção da lista é card de contrato).

**JS**
- **No módulo** (`app/modules/grades/resources/`): `legacy/grades/functions.js` (451), `legacy/grades/_initialization.js` (227) e `_initialization.js` (34). Os de `gradesRelease` e `reportCard` saem no R4-GRADES-01. O `concept/_form.php` registra `$baseScriptUrl . '/functions.js'`, **arquivo que não existe** em `resources/` (a view pede um JS que não está lá).
- **No `js/` raiz:** `js/enrollment/grades/` (`functions.js` 23 e `_initialization.js` 263), **cópia divergente** de `legacy/grades/` (451 e 227 linhas), usada pelas rotas `entry`; `js/admin/grades-structure.js` (**1152 linhas**), registrado por `structure/gradesStructure.php` (≈L6).
- **`?r=` literais** em `grades/resources`: 19 (inclui os dos dois que o R4 migra).
**CSS:** nenhum arquivo do módulo; as seis views de lançamento registram `css/grades.css` (raiz). `style=`: `structure/gradesStructure.php` (9), `default/grades.php` (2); `<style>` em `default/grades.php`. **Sem `accessControl`:** nenhum.

## Objetivo
Estrutura, conceitos, fechamento e lançamento por avaliação passam a ter regra em `app/src/Grades/`, sem rota duplicada e sem JS duplicado, mantendo o resultado do cálculo igual ao do golden master do R2.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos. **Nenhum card muda regra de nota**; divergência do golden master vai para uma pessoa (seção 8.5).
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Bind em `actionGetModalities` e conferência dos 13 `createCommand`.
   - [ ] `postOnly` em `structure/delete`, `structure/copy` e `batchClassClose`; perfis das actions de estrutura e fechamento (hoje todas `'@'`), definidos com uma pessoa; confirmar que `saveunities` e `copy` conferem a escola.
   - [ ] CSRF nos formulários de estrutura e conceito e nos POST AJAX de lançamento.
3. **GM (fluxos a caracterizar; o do cálculo já existe no R2)**
   - [ ] Salvar estrutura de notas (unidades e modalidades), copiar estrutura para outro ano e excluir.
   - [ ] Fechamento de turma (individual, em lote e lista); conceito; lançamento por avaliação (`saveGrades`) nas duas rotas, comparando `default` e `entry`.
4. **SVC (contexto `app/src/Grades`, junto do piloto)**
   - [ ] `SaveGradeStructureService` (de `saveunities`, ≈145 linhas), `CopyGradeStructureService`, `DeleteGradeStructureService`, `ClassClosureService` e `GradeConceptService`.
   - [ ] Mover os usecases restantes para `Domain` e `Application`, um por card, com fachada até a remoção.
   - [ ] Unificar `EntryController` e `DefaultController` (uma action por comportamento), conforme a decisão do produto.
5. **API:** a consulta e o lançamento de notas para o app ficam no [R5-API-02](R5-API-02.md); aqui só se garante que os services não leem `$_POST` nem sessão.
6. **JS (`frontend/pages/grades/`)**
   - [ ] Página `grades` (lançamento por avaliação): uma entry só, no lugar de `legacy/grades/` e `js/enrollment/grades/`, que divergiram (caracterizar antes de escolher).
   - [ ] `js/admin/grades-structure.js` (1152 linhas) por responsabilidade; trocar os `?r=` literais pelo `url()` gerado; `concept` (corrigir o `functions.js` inexistente) e `batchclose`.
7. **CSS (`sass/scss/pages/_grades.scss`):** mover `css/grades.css` e os `style=`; trocar as 6 ocorrências de Bootstrap 2.

## Fora de escopo
- Mudar fórmula de média, recuperação, conceito ou situação do aluno.
- O piloto do R2 e o JS de `gradesRelease` e `reportCard` (R4-GRADES-01).
- Remover `gradeconcept` e `gradestructure` de `app/config/main.php` e editar `app/models/**` (arquivos com dono; card de contrato).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/grades.php`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** estrutura, conceito, fechamento e lançamento por avaliação têm golden master ou teste de caracterização; o golden master do R2 segue verde.
- [ ] **SVC:** as actions críticas são magras; não há action duplicada entre `entry` e `default`; regras com 80% de cobertura.
- [ ] **JS:** `grades-structure.js` e a página `grades` em `frontend/pages/grades/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_grades.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
php scripts/inventory-actions.php grades
composer analyse && composer lint
composer test:integration -- --filter GradesGoldenMaster && composer test:unit -- --filter Grades   # nomes: confirmar no R2 e no R1-TEST-01
npx playwright test tests/e2e/grades
```
Notas exigem revisão humana. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

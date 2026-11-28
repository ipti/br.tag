---
id: R6-CURRICULARMATRIX
title: "curricularmatrix (matriz curricular): módulo pronto"
release: R6
module: curricularmatrix
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 3)"
write_scope:
  - app/modules/curricularmatrix/**
  - app/src/CurricularMatrix/**
  - tests/**/CurricularMatrix/**
  - frontend/pages/curricularmatrix/**
  - sass/scss/pages/_curricularmatrix.scss
  - app/config/public-routes/curricularmatrix.php
  - metrics/baseline/curricularmatrix.json
  - phpstan/baseline/curricularmatrix.neon
read_only:
  - app/models/TeachingMatrixes.php
  - app/models/EdcensoDiscipline.php
  - app/models/EdcensoStageVsModality.php
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter CurricularMatrix (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do curricularmatrix sem proteção ou fora de public-routes/curricularmatrix.php"
  - "Catraca do módulo (metrics/baseline/curricularmatrix.json) sem aumento e com zero ?r= literais"
---

# R6-CURRICULARMATRIX: curricularmatrix (matriz curricular): módulo pronto

## Contexto
**Para que serve:** cadastra a matriz curricular, isto é, a combinação de etapa de ensino, disciplina, carga horária e créditos que define o que cada série oferece. Não há documento funcional em `docs/modules/`. A matriz é consumida por outros módulos: `grep` mostra o uso do `CurricularMatrix` em `classroom/controllers/DefaultController.php`, `classdiary/controllers/ClassesController.php`, `timesheet/controllers/TimesheetController.php`, `schoolreport/repositories/FormsRepository.php` e `app/controllers/SiteController.php`. Mudanças aqui exigem cuidado com esses consumidores.

**Linha do inventário:** 1 controller, 185 LOC, 4 actions, 0 sem `accessControl`, 4 superglobais, 0 `createCommand`, 3 JSON, 1 view, 0 `<script>`, 5 `style=`, 9 Bootstrap 2, JS 2 arquivos/67 linhas, 1 CSS, 1 model, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/CurricularmatrixController.php` (185 LOC): `index`, `addMatrix` (≈L53), `matrixReuse` (≈L103) e `delete` (≈L149).
- `accessRules()` (≈L19–40) libera `index`, `addMatrix` e `matrixReuse` para qualquer logado (`'@'`). A regra de `delete` e a de `'*'` têm `'actions' => []`, então **`delete` cai no `deny` final**. **confirmar:** se algum JS ou view chama `delete` e hoje recebe 403, e se a exclusão deveria ser só do admin.
- `actionAddMatrix` lê `$_POST['stages']`, `['disciplines']`, `['workload']` e `['credits']` sem validar tipo; o `foreach ($stages …)` quebra se vier texto. Qualquer perfil logado cria matriz (**confirmar:** com o produto).
- View: `views/curricularmatrix/index.php` (5 `style=`, 9 usos de Bootstrap 2, 1 handler `on*=`).
- JS: `resources/common/js/curricularmatrix.js` (67 linhas, 1 `?r=` literal) e um `index.js` vazio. CSS: `resources/common/css/layout.css` (72 linhas).
- Model do módulo: `CurricularMatrix`. Os models `TeachingMatrixes`, `EdcensoDiscipline` e `EdcensoStageVsModality` ficam em `app/models/` (arquivos com dono; só leitura aqui).

## Objetivo
Matriz curricular com permissão por perfil, validação de entrada e regra de criação e reuso em service testado.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-CURRICULARMATRIX-<nn>`.
2. **SEC**
   - [ ] Decidir quem cria, reutiliza e exclui matrizes (admin, secretaria) e aplicar `TagUtils::isAdmin()` ou o papel correspondente.
   - [ ] Resolver o `delete` inalcançável: corrigir a regra ou remover a action (decisão de produto), exigindo POST e CSRF.
   - [ ] Validar `stages`, `disciplines`, `workload` e `credits` (tipo, faixa, existência) antes do `foreach`.
3. **GM**
   - [ ] Caracterizar `addMatrix` (combinações geradas por etapa x disciplina) e `matrixReuse`, comparando as linhas gravadas em `curricular_matrix`.
4. **SVC**
   - [ ] `app/src/CurricularMatrix/Application/`: `AddCurricularMatrixService` e `ReuseCurricularMatrixService`; regra de duplicidade no `Domain`; repositório em `Infrastructure/Yii`.
5. **API:** nenhuma prevista.
6. **JS:** `curricularmatrix.js` para `frontend/pages/curricularmatrix/`, com `routes/` no lugar do `?r=` e delegação de eventos no lugar do `on*=`.
7. **CSS:** `sass/scss/pages/_curricularmatrix.scss`, `style=` da view para classes e Bootstrap 2 para as classes `t-*` verificadas.

## Fora de escopo
- Alterar o cálculo de carga horária usado em classdiary, timesheet e schoolreport.
- Mexer em `app/models/**` (inclusive `TeachingMatrixes`).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/curricularmatrix.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** criação e reuso de matriz têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/CurricularMatrix/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS:** páginas em `frontend/pages/curricularmatrix/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_curricularmatrix.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter CurricularMatrix   # confirmar scripts em R1
php scripts/inventory-actions.php                 # curricularmatrix sem action desprotegida
scripts/frontend-metrics                          # catraca; confirmar o comando em R1
```
Testes de navegador (cadastrar e reutilizar matriz) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

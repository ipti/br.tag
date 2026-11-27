---
id: R5-COURSEPLAN
title: "courseplan: levar o plano de curso ao critério de módulo pronto"
release: R5
module: courseplan
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 2) e seção 7 (F4, item 4: courseplan)"
write_scope:
  - app/modules/courseplan/**
  - app/src/CoursePlan/**
  - tests/**/CoursePlan/**
  - frontend/pages/courseplan/**
  - sass/scss/pages/_courseplan.scss
  - app/config/public-routes/courseplan.php
  - metrics/baseline/courseplan.json
  - phpstan/baseline/courseplan.neon
  - js/courseplan/**
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
  - "composer analyse (app/src/CoursePlan no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php courseplan mostra zero actions críticas com SQL cru ou superglobal"
  - "Visitante recebe redirecionamento ao login em todas as actions do módulo (curl da seção Como verificar)"
  - "Catraca do módulo courseplan: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-COURSEPLAN: courseplan: levar o plano de curso ao critério de módulo pronto

## Contexto
O `courseplan` cadastra o **plano de curso** por etapa e disciplina: aulas previstas, conteúdo, metodologia, habilidades (BNCC) e recursos. O professor escreve, e a coordenação **valida** (aprova ou devolve com observação); o `classdiary` usa as aulas do plano para o conteúdo ministrado. Não há `docs/modules/courseplan.md`; a descrição vem do código.

**Inventário do README (2026-11-27):** 2 controllers, 1237 LOC, 29 actions, 0 sem `accessControl`, 20 superglobais, 9 `createCommand`, 16 JSON, 8 views, 1 `<script>`, 30 `style=`, 12 Bootstrap 2, 4 JS (1479 LOC), 0 CSS, 0 models, 0 services.

**Controllers:** `CourseplanController` (805 LOC, 17 actions) e `DefaultController` (432 LOC, 12 actions).
- **Todas as actions estão abertas a visitantes.** `CourseplanController::accessRules` (≈L27–58) e `DefaultController::accessRules` (≈L27–40) liberam **todas** as actions para `'users' => ['*']`, não só create, update, save e delete. Entre elas: `save`, `deletePlan`, `addResources`, **`validatePlan`** (aprova o plano, ≈L726–747) e **`enableCoursePlanEdition`** (volta o plano para pendente, ≈L749–763). Nenhuma action das listas chega ao `deny` final. O `accessControl` existe, mas não protege.
- **`deletePlan` sem `postOnly`:** o filtro é `postOnly + delete` (≈L18), mas a action se chama `deletePlan`, então o filtro não a cobre. No `DefaultController` a action é `delete`, e o filtro vale.
- **Sem checagem de dono:** `actionSave` (≈L303–432) atualiza qualquer plano por `id` (`findByPk`), sem conferir a escola nem o autor. `loadModel` (≈L784) também não.
- **`DefaultController` parece código morto:** as views só existem em `views/courseplan/` (nenhuma em `views/default/`) e não há referência a `courseplan/default` fora de `CourseplanRoutes.php`, arquivo gerado. Confirmar nos logs de acesso antes de remover.
- **Maior action:** `actionSave` grava o plano e depois as aulas, habilidades e recursos em laço, com três `deleteAll` (≈L368, ≈L407 e ≈L419, com bind), sem transação.

**JS** (`app/modules/courseplan/resources/`, registrado por `_form.php`, `formValidate.php`, `index.php` e `pendingPlans.php`): `functions.js` (**675**), `_initialization.js` (**645**), `validations.js` (92) e `pagination.js` (67), com 14 `?r=` literais. O `_initialization.js` chama `initDatatable()` (≈L628 e ≈L643).
- **Pasta solta:** `js/courseplan/form/` (689 linhas em 4 arquivos, com `initDatatable()` em ≈L4 do `_initialization.js`) não é referenciada por nenhuma view nem script. **Confirmar com `git grep`** e remover se for cópia antiga.
**CSS:** nenhum arquivo. `<style>` em `_form.php` e `formValidate.php`. `style=`: `_form.php` (14), `formValidate.php` (9), `pendingPlans.php` (4) e `index.php` (3).
**Sem `accessControl`:** nenhum (mas as regras liberam tudo). **Models, services e usecases locais:** nenhum (usa `CoursePlan`, `CourseClass` e relacionados de `app/models/`).

## Objetivo
Só quem deve acessa, valida e exclui plano de curso, e o salvar é atômico e testado em `app/src/CoursePlan/`, com a tela em `frontend/pages/courseplan/`.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos. O pacote SEC vai **primeiro e sozinho**, como um card pequeno de segurança.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Trocar `'*'` por `'@'` nos dois controllers e definir com uma pessoa **quais perfis** criam, validam (`validatePlan`), liberam edição e excluem. Usar `TagUtils::isAdmin()`, `isManager()` etc., nunca `Yii::app()->user->checkAccess()`.
   - [ ] Restringir `save`, `update`, `deletePlan`, `validatePlan` e `enableCoursePlanEdition` ao dono do plano ou à coordenação da mesma escola, com `postOnly` em `deletePlan` e nas transições de situação.
   - [ ] Decidir sobre o `DefaultController` (remover, se confirmado como morto) e pôr CSRF nos formulários do plano e de validação.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Criar plano com aulas, habilidades e recursos; editar removendo aulas (os `deleteAll`).
   - [ ] Ciclo de situação: `PENDENTE`, aprovado, devolvido e liberado para edição.
   - [ ] Excluir plano usado e não usado em conteúdo ministrado (≈L521–543).
4. **SVC (contexto `app/src/CoursePlan`)**
   - [ ] `SaveCoursePlanService` com `TransactionManager` (plano, aulas, habilidades e recursos).
   - [ ] `ValidateCoursePlanService` e `EnableCoursePlanEditionService` (máquina de estados em `Domain`), `DeleteCoursePlanService` e as consultas de listagem e de pendentes.
5. **API:** não há endpoint para o app neste épico.
6. **JS (`frontend/pages/courseplan/`)**
   - [ ] Quebrar `functions.js` (675) e `_initialization.js` (645) por responsabilidade (formulário, aulas, habilidades, recursos); trocar os 14 `?r=` pelo `url()` gerado.
   - [ ] Remover `js/courseplan/` se confirmado órfão.
7. **CSS (`sass/scss/pages/_courseplan.scss`)**
   - [ ] Mover os 2 blocos `<style>` e os 30 `style=`; trocar as 12 de Bootstrap 2.

## Fora de escopo
- Mudar a regra de aprovação do plano ou o modelo de habilidades da BNCC.
- A integração com o conteúdo ministrado do `classdiary` além de manter o contrato atual.
- Editar models de `app/models/**` (arquivos com dono).

## Critérios de aceite
- [ ] **SEC:** nenhuma action liberada para `'*'`; os perfis aprovados estão no `accessRules`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** salvar, validar, liberar e excluir plano têm golden master ou teste de caracterização.
- [ ] **SVC:** `actionSave` é magra e atômica; a máquina de estados está em `Domain`; regras com 80% de cobertura.
- [ ] **JS:** páginas em `frontend/pages/courseplan/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_courseplan.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
# visitante, sem cookie: nenhuma action pode responder 200
curl -i "http://localhost/?r=courseplan/courseplan/validatePlan&id=1"
# esperado: 302 para site/login (pela regra atual, a action executa para o visitante; confirmar antes de corrigir)

php scripts/inventory-actions.php courseplan
composer analyse && composer lint && composer test:unit -- --filter CoursePlan   # nome do script: confirmar no R1-TEST-01
npx playwright test tests/e2e/courseplan
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

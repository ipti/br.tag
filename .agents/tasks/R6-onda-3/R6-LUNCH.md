---
id: R6-LUNCH
title: "lunch (merenda escolar): módulo pronto, com controle de acesso e limpeza do CSS e dos assets"
release: R6
module: lunch
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: G
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3), seção 7 (F0 e F5) e seção 8.6 (onda 3)"
write_scope:
  - app/modules/lunch/**
  - app/src/Lunch/**
  - tests/**/Lunch/**
  - frontend/pages/lunch/**
  - sass/scss/pages/_lunch.scss
  - css/lunch.css
  - app/config/public-routes/lunch.php
  - metrics/baseline/lunch.json
  - phpstan/baseline/lunch.neon
read_only:
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Lunch (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do lunch sem proteção ou fora de public-routes/lunch.php"
  - "Catraca do módulo (metrics/baseline/lunch.json) sem aumento e com zero ?r= literais"
---

# R6-LUNCH: lunch (merenda escolar): módulo pronto, com controle de acesso e limpeza do CSS e dos assets

## Contexto
**Para que serve:** a merenda escolar por escola. O módulo mantém o cardápio do dia (refeições e porções), o estoque de itens da merenda (entradas e saídas, com motivo) e o histórico de transações. Convive com o módulo foods, que cobre o mesmo domínio em escala de rede; o `LunchController` importa `foods.models.FoodMeasurement`. **confirmar:** com o produto se o lunch segue em uso por tenant e qual a relação com foods antes de investir em services.

**Linha do inventário:** 3 controllers, 426 LOC, 17 actions, 3 sem `accessControl`, 0 superglobais, 1 `createCommand`, 1 JSON, 7 views, 0 `<script>`, 59 `style=`, 83 Bootstrap 2, JS 3 arquivos/127 linhas, 2 CSS, 11 models, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- Controllers: `DefaultController` 21 LOC/2 actions, `LunchController` 272/11 (`index`, `create`, `update`, `lunchDelete`, `removePortion`, `getFoodAlias`, `getFoodMeasurement`, `getUnityMeasure`, `addPortion`, `addMeal`, `changeMeal`) e `StockController` 133/4 (`index`, `addItem`, `removeItem`, `deleteItem`). **Nenhum** define `filters()` ou `accessRules()`.
- `LunchController::init()` (≈L13) e `StockController::init()` (≈L12) sobrescrevem o `init()` **sem chamar `parent::init()`**. Com isso, o 401 em JSON para sessão expirada e o timeout de `app/components/Controller.php` (≈L33–52) não rodam, e a escola é lida de `Yii::app()->user->school` mesmo para visitante.
- **CSS `css/lunch.css`** (244 linhas, na raiz do repositório) é registrado por 4 views com `$cs->registerCssFile($baseUrl . '/css/lunch.css')`: `default/index.php` (≈L6), `lunch/index.php` (≈L14), `lunch/_form.php` (≈L16) e `stock/index.php` (≈L10). **Só `default/index.php` define `$baseUrl`** (≈L4, `Yii::app()->baseUrl`); nas outras 3 a variável é indefinida e o caminho vira `/css/lunch.css` por acidente.
- Há também `resources/common/css/layout.css` (51) e `error.css` (29), `resources/common/js/` com `lunch.js` (94), `stock.js` (30) e `layout.js` (3) e 5 `?r=` literais. Em `resources/lib/` existem uma cópia do jQuery 1.11.3 e um diretório `semantic/` (**confirmar:** sem uso; o plano F0 prevê remover).
- `models/School.php` (≈L66–88): `transactions()` monta o SQL com `$this->inep_id`, `$initialDate` e `$finalDate` interpolados e executa com `createCommand` (o único do módulo). **confirmar:** origem das datas (possível SQL injection).
- Views com mais `style=`: `lunch/_form.php` (25) e `stock/index.php` (23). Bootstrap 2: 83 usos.
- Models do módulo (11): `Inventory`, `Item`, `Meal`, `MealPortion`, `Menu`, `MenuMeal`, `Portion`, `Received`, `School`, `Spent`, `Unity`. **confirmar:** os nomes genéricos (`School`, `Item`, `Menu`) não colidem com classes globais; o plano cita 9 nomes duplicados.

## Objetivo
Merenda atrás do login, com escopo de escola garantido, SQL com bind, assets servidos pelo próprio módulo e CSS no design system.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-LUNCH-<nn>`.
2. **SEC**
   - [ ] Criar `filters()` com `accessControl` e `accessRules()` nos 3 controllers (papéis de merenda e admin, a definir com o produto). Listar em `public-routes/lunch.php` só o que for público (`default/error`).
   - [ ] Chamar `parent::init()` nos dois `init()` sobrescritos e tratar escola nula.
   - [ ] Aplicar bind em `School::transactions()` e exigir POST com CSRF em `lunchDelete`, `removePortion`, `deleteItem` e demais gravações.
3. **GM**
   - [ ] Caracterizar criação do cardápio (`create`/`update`), `addMeal`/`addPortion` e as entradas e saídas de estoque.
4. **SVC**
   - [ ] `app/src/Lunch/Application/`: `SaveMenuService`, `AddStockItemService`, `RemoveStockItemService` e a consulta de transações; regras de quantidade e unidade no `Domain`.
5. **API:** nenhuma prevista.
6. **JS:** `lunch.js` e `stock.js` para `frontend/pages/lunch/`, com `routes/` no lugar dos 5 `?r=`.
7. **CSS**
   - [ ] Corrigir o `$baseUrl` das 3 views (ou, melhor, mover `css/lunch.css` e `layout.css` para `sass/scss/pages/_lunch.scss`).
   - [ ] Apagar `resources/lib/semantic` e a cópia do jQuery se confirmados sem uso.
   - [ ] Mover os 59 `style=` e trocar os 83 usos de Bootstrap 2 por classes `t-*` verificadas.

## Fora de escopo
- Unificar lunch e foods.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/lunch.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** os fluxos de cardápio e estoque têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/Lunch/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS:** páginas em `frontend/pages/lunch/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_lunch.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda, sem variável `$baseUrl` indefinida.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Lunch      # confirmar scripts em R1
php scripts/inventory-actions.php         # lunch sem action desprotegida
scripts/frontend-metrics                  # catraca; confirmar o comando em R1
curl -i "http://localhost/?r=lunch/stock/index"   # visitante: 302 para o login
```
Testes de navegador (cardápio e estoque) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

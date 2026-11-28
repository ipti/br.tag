---
id: R5-FOODS
title: "foods: levar o restante da alimentação escolar ao critério de módulo pronto"
release: R5
module: foods
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R5-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B4, piloto M2M foods) e seção 8.6 (onda 2)"
write_scope:
  - app/modules/foods/**
  - app/src/Foods/**
  - tests/**/Foods/**
  - frontend/pages/foods/**
  - sass/scss/pages/_foods.scss
  - app/config/public-routes/foods.php
  - metrics/baseline/foods.json
  - phpstan/baseline/foods.neon
read_only:
  - app/models/**
  - app/modules/farmer/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer analyse (app/src/Foods no nível máximo, sem baseline)"
  - "scripts/inventory-actions.php foods mostra zero controllers sem accessControl e zero actions críticas com SQL cru ou superglobal"
  - "Golden master de cardápio, aviso e estoque verde, com cobertura de pelo menos 80% no código novo"
  - "Catraca do módulo foods: zero ?r= literais, zero globais novos e Bootstrap 2 em queda"
---

# R5-FOODS: foods: levar o restante da alimentação escolar ao critério de módulo pronto

## Contexto
O `foods` cuida da **alimentação escolar**: alimentos e cardápios (com refeições, públicos e etapas), estoque, **avisos de compra da agricultura familiar** e pedidos aos agricultores, alertas de validade e relatórios. Parte dos dados vai para o Firestore do app de agricultores (br-nham). Não há `docs/modules/foods.md`; a descrição vem do código.

**Este épico exclui o que já saiu no R3:** os services de itens do pedido (R3-FOODS-01 e 02) e a API M2M para o br-nham (R3-API-05), mais a troca do Bearer fixo do `FoodrequestController` (R0-INFRA-01 e R3-SEC-03). Confirmar nesses cards o que ficou pendente antes de quebrar este.

**Inventário do README (2026-11-27):** 7 controllers, 2451 LOC, 62 actions, **4 sem `accessControl`**, 42 superglobais, 16 `createCommand`, 32 JSON, 31 views, 5 `<script>`, 16 `style=`, 5 Bootstrap 2, 13 JS (3300 LOC), 0 CSS, 25 models, 15 services/usecases.

**Controllers (LOC; actions):** `FoodrequestController` (632; 13), `FoodmenuController` (554; 13), `FoodnoticeController` (470; 12), `FoodinventoryController` (409; 15), `FoodalertController` (240; 5), `ReportsController` (78; 2) e `DefaultController` (68; 2).
- **Sem `accessControl`:** `DefaultController`, `FoodmenuController`, `FoodrequestController` e `ReportsController`. O `FoodrequestController` declara `accessRules()` (≈L5–36) mas **não tem `filters()`**, então as regras não são aplicadas; todas as 13 actions (incluindo `create`, `update` e `delete`) ficam abertas a visitantes. O `FoodmenuController` (incluindo `delete`, `viewLunch`, `studentAllergies`) e os dois relatórios também.
- **Regras com `'*'` em controllers que têm filtro:** `FoodnoticeController` libera para visitantes `activateNotice`, `toggleNoticeStatus`, `getNoticePdfUrl` e `getShoppingList` (≈L38–48), duas delas mudam estado. `FoodinventoryController` libera `index` e `view` (≈L48–49).
- **Models duplicados:** `FoodMenu` e `FoodIngredient` existem em `app/models/` (3,7 KB e 4,5 KB) e em `app/modules/foods/models/` (3,5 KB e 4,2 KB), com conteúdo diferente; o que vale depende da ordem de import. Renomear ou unificar (a parte de `app/models/**` vira card de contrato).
- **Firestore:** `services/FireBaseService.php` (≈9 KB) usa o projeto `br-nham-agrigultor` fixo no código (≈L21) e lê `PWD_FIREBASE` e `TOKEN_FIREBASE` do ambiente. É chamado por 7 usecases (`CreateFarmerRegister`, `CreateFoodRequest`, `CreateNotice`, `DeleteFarmerRegister`, `GetFarmerRegister`, `UpdateFarmerRegister` e `UpdateFoodNotice`).
- **Service grande:** `services/FoodMenuService.php` (≈20 KB).

**JS** (`app/modules/foods/resources/`, 13 arquivos, 34 `?r=` literais): `menuComponents.js` (**739**), `inventory/_initialization.js` (431), `_initialization.js` (357), `inventory/functions.js` (351), `request/_initialization.js` (303), `components.js` (286), `notice/functions.js` (244), `functions.js` (214), `mealsOfWeek/_initialization.js` (163), `request/functions.js` (102), `notice/_initialization.js` (82), `notice/activateNotice.js` (17) e `notice/validations.js` (11).
- **Caminhos com barra invertida:** `foodinventory/_form.php` (≈L10–11), `foodrequest/_form.php` (≈L9–10) e `foodrequest/index.php` (≈L13–14) registram `'app\modules\foods\resources\...'`, e `foodnotice/_form.php` (≈L8–10) usa `'\notice\functions.js'`. Em URL funcionam por conversão do navegador, mas dependem de o diretório estar exposto e são frágeis. **Confirmar em produção.**
**CSS:** nenhum arquivo. `<style>` em `default/_form.php`, `foodmenu/_form.php`, `foodnotice/_form.php` e nos dois relatórios (que registram `sass/css/main.css`). `style=`: `foodnotice/activateNotice.php` e `foodmenu/viewlunch.php` (4 cada), `foodmenu/_form.php` e `foodinventory/_form.php` (3 cada).

## Objetivo
Todo o `foods` exige login e perfil, o cardápio, o aviso e o estoque têm regra testada em `app/src/Foods/`, sem models duplicados, e as páginas passam para `frontend/pages/foods/`.

## Passos
1. O card de refinamento ([R5-REFINE-01](R5-REFINE-01.md)) quebra este épico em cards executáveis. Os pacotes abaixo são a ordem e os alvos. O SEC vai **primeiro**, um card por controller.
2. **SEC (acesso, SQL e CSRF)**
   - [ ] Dar `filters()` com `accessControl` aos quatro controllers sem ele (começar pelo `FoodrequestController`) e trocar o `'*'` das actions de escrita por `'@'` mais perfil (`nutritionist`, `admin`, `foodServiceWorker`, a definir com uma pessoa). Não deixar de fora as rotas que o br-nham chama (R3-API-05 define o que fica público ou por Bearer).
   - [ ] `activateNotice` e `toggleNoticeStatus` com perfil e `postOnly`; `delete` do cardápio com `postOnly`.
   - [ ] Conferir o bind nos 16 `createCommand` e pôr CSRF nos formulários de cardápio, aviso, estoque e pedido.
3. **GM (fluxos a caracterizar antes de mexer)**
   - [ ] Criar e editar cardápio com refeições, componentes, públicos e etapas (`FoodMenuService`).
   - [ ] Criar aviso, ativar e desativar; gerar lista de compras e os dois relatórios.
   - [ ] Entradas e saídas de estoque; alertas de validade. Firestore com dublê (sem chamar o serviço real).
4. **SVC (contexto `app/src/Foods`)**
   - [ ] Quebrar o `FoodMenuService` em services por caso de uso (criar, atualizar, listar, refeições da semana).
   - [ ] Services de aviso e de estoque; `FireBaseService` como porta em `Application` e adaptador em `Infrastructure`.
   - [ ] Resolver `FoodMenu` e `FoodIngredient` duplicados.
5. **API:** o M2M já saiu no R3. Se o app de agricultores precisar de mais endpoints, abrir card próprio.
6. **JS (`frontend/pages/foods/`)**
   - [ ] `menuComponents.js` (739), cardápio (`_initialization.js` e `functions.js`), estoque, aviso, pedido e `mealsOfWeek`; trocar os 34 `?r=` pelo `url()` gerado e os caminhos com barra invertida por `registerEntry`.
7. **CSS (`sass/scss/pages/_foods.scss`)**
   - [ ] Mover os 5 blocos `<style>` e os 16 `style=`; trocar as 5 de Bootstrap 2.

## Fora de escopo
- Os services de itens do pedido e a API M2M do R3, e o Bearer fixo do `FoodrequestController`.
- Mudar o cadastro de agricultores do módulo `farmer` (R6-FARMER) e a estrutura do Firestore.
- Mudar regras de cardápio, per capita ou lista de compras.
- Editar models de `app/models/**` (arquivos com dono).

## Critérios de aceite
- [ ] **SEC:** nenhum controller sem `accessControl`; toda action está protegida ou listada em `app/config/public-routes/foods.php`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** cardápio, aviso, estoque e relatórios têm golden master ou teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; o Firestore fica atrás de uma porta; `FoodMenu` e `FoodIngredient` existem uma única vez; regras com 80% de cobertura.
- [ ] **JS:** páginas em `frontend/pages/foods/`; zero `?r=` literais e zero globais novos na catraca.
- [ ] **CSS:** `_foods.scss`, zero blocos `<style>` nas views e Bootstrap 2 em queda.

## Como verificar
```bash
# visitante, sem cookie: nenhuma action de escrita pode executar
curl -i -X POST "http://localhost/?r=foods/foodnotice/toggleNoticeStatus" -d "id=1"       # esperado: 302 para site/login

php scripts/inventory-actions.php foods
composer analyse && composer lint && composer test:unit -- --filter Foods   # nome do script: confirmar no R1-TEST-01
git grep -n "^class FoodMenu\b\|^class FoodIngredient\b" -- app    # esperado: uma ocorrência de cada
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

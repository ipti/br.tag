---
id: R6-INVENTORY
title: "inventory (almoxarifado): módulo pronto, com a correção do checkAccess nas regras de gestor"
release: R6
module: inventory
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 6), seção 8.6 (onda 3) e seção 13 (risco do checkAccess)"
write_scope:
  - app/modules/inventory/**
  - app/src/Inventory/**
  - tests/**/Inventory/**
  - frontend/pages/inventory/**
  - sass/scss/pages/_inventory.scss
  - app/config/public-routes/inventory.php
  - metrics/baseline/inventory.json
  - phpstan/baseline/inventory.neon
read_only:
  - app/models/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Inventory (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do inventory sem proteção ou fora de public-routes/inventory.php"
  - "Catraca do módulo (metrics/baseline/inventory.json) sem aumento e com zero ?r= literais"
---

# R6-INVENTORY: inventory (almoxarifado): módulo pronto, com a correção do checkAccess nas regras de gestor

## Contexto
**Para que serve** (`docs/modules/inventory/index.md`): controla o estoque de materiais das escolas e do Almoxarifado Central (Secretaria). Cobre itens com estoque mínimo, movimentações (entrada, saída, transferência do central para a escola), requisições de material (a escola pede, o admin aprova e o sistema gera a entrada) e, no código, fornecedores e cotações. Gestor escolar só movimenta a própria escola; admin vê todas.

**Linha do inventário:** 5 controllers, 890 LOC, 28 actions, 0 sem `accessControl`, 41 superglobais, 0 `createCommand`, 0 JSON, 26 views, 2 `<script>`, 4 `style=`, 0 Bootstrap 2, JS 1 arquivo/23 linhas, 0 CSS, 7 models, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- Controllers: `ItemController` 99 LOC/5 actions, `MovementController` 250/5, `QuotationController` 211/6, `RequestController` 238/8, `SupplierController` 92/4. Todos têm `filters()` com `accessControl`.
- `MovementController::accessRules()` (≈L12–25) libera `index`, `view`, `createEntry`, `createExit`, `history` e `transfer` para `'@'`, ou seja, qualquer papel logado. **confirmar:** as regras dos outros 4 controllers.
- **Uso do `checkAccess('manager')`:** `MovementController` ≈L32, ≈L44 e ≈L104; `models/InventoryMovement.php` ≈L108; `models/InventoryStock.php` ≈L93; e a view `views/movement/createExit.php` ≈L12 (`$isManager`). Hoje a chamada sempre retorna falso (achado 6 do plano), então **o filtro por escola do gestor nunca roda** e todo usuário vê o estoque de todas as escolas. Depois do R2-SEC-01 o gestor passa a ver só a própria escola: é mudança de comportamento intencional, com nota de release, e precisa de decisão de produto sobre os demais papéis.
- JS: `resources/quotation/functions.js` (23 linhas). Views com `style=`: `movement/index.php` (3). Há 2 blocos `<style>` e 1 handler `on*=` nas views.
- Models do módulo: `InventoryItem`, `InventoryMovement`, `InventoryQuotation`, `InventoryQuotationItem`, `InventoryRequest`, `InventoryStock`, `InventorySupplier`. Services: nenhum.
- **confirmar:** se `Transfer` (≈L142) e `RequestController::actionApprove` (≈L155) gravam as duas pontas do estoque numa transação.

## Objetivo
O almoxarifado respeita escola e papel de verdade, com as regras de estoque em services testados e o front fora das views.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-INVENTORY-<nn>`.
2. **SEC**
   - [ ] Definir, por action, quem pode: admin, gestor da escola ou outros papéis (decisão de produto, registrada no card).
   - [ ] Trocar os 5 usos de `checkAccess('manager')` (e o da view) pelo helper corrigido em R2-SEC-01.
   - [ ] Garantir o filtro por `Yii::app()->user->school` em toda consulta do gestor, inclusive `history`, `view` e `request`.
   - [ ] Confirmar CSRF nos formulários de entrada, saída, transferência, aprovar e rejeitar.
3. **GM**
   - [ ] Caracterizar entrada, saída, transferência (debita o central e credita a escola) e aprovação de requisição (gera a entrada), nos perfis admin e gestor.
   - [ ] Incluir o caso "gestor vê só a própria escola" como teste novo, pois o comportamento muda.
4. **SVC**
   - [ ] `app/src/Inventory/Application/`: `RegisterEntryService`, `RegisterExitService`, `TransferStockService` e `ApproveRequestService`, com `TransactionManager`.
   - [ ] Repositório em `Infrastructure/Yii` sobre `InventoryStock` e `InventoryMovement`; regra de estoque mínimo no `Domain`.
5. **API:** nenhuma prevista (o app não usa o almoxarifado). Confirmar com o produto no refinamento.
6. **JS:** migrar `quotation/functions.js` e os handlers inline para `frontend/pages/inventory/`, com `?r=` via `routes/` gerado.
7. **CSS:** criar `sass/scss/pages/_inventory.scss`, remover os 2 blocos `<style>` e os `style=` das views.

## Fora de escopo
- Mexer em `app/models/**` (arquivos com dono; as classes em `app/modules/inventory/models/` estão no escopo).
- Alertas automáticos de estoque baixo via notificações (ver docs/modules/notifications.md).
- Mudar o fluxo de aprovação de requisições.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/inventory.php`; não há SQL concatenado; os formulários enviam CSRF.
- [ ] **GM:** os fluxos de movimentação e requisição têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras e as regras estão em `app/src/Inventory/` com pelo menos 80% de cobertura.
- [ ] **JS:** as páginas estão em `frontend/pages/inventory/`; a catraca marca zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_inventory.scss`, sem blocos `<style>` nas views, Bootstrap 2 em zero ou em queda.
- [ ] A nota de release descreve o efeito da correção do `checkAccess` sobre o gestor.

## Como verificar
```bash
composer lint && composer analyse        # verdes; nenhum erro novo no baseline do módulo
composer test:unit -- --filter Inventory # confirmar o script em R1
php scripts/inventory-actions.php        # inventory sem action desprotegida
scripts/frontend-metrics                 # catraca; confirmar o comando em R1
```
Testes de navegador (login de admin e de gestor, transferência e aprovação) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

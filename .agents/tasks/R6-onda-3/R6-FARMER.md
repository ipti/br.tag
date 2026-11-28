---
id: R6-FARMER
title: "farmer (agricultor familiar): módulo pronto, com revisão das ações hoje públicas"
release: R6
module: farmer
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3), seção 6 (B2) e seção 8.6 (onda 3)"
write_scope:
  - app/modules/farmer/**
  - app/src/Farmer/**
  - tests/**/Farmer/**
  - frontend/pages/farmer/**
  - sass/scss/pages/_farmer.scss
  - app/config/public-routes/farmer.php
  - metrics/baseline/farmer.json
  - phpstan/baseline/farmer.neon
read_only:
  - app/modules/foods/**
  - docs/migracao_modulos/farmer.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Farmer (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do farmer sem proteção ou fora de public-routes/farmer.php"
  - "Catraca do módulo (metrics/baseline/farmer.json) sem aumento e com zero ?r= literais"
---

# R6-FARMER: farmer (agricultor familiar): módulo pronto, com revisão das ações hoje públicas

## Contexto
**Para que serve:** cadastro de agricultores familiares que fornecem alimentos à merenda. O módulo cria e atualiza o cadastro (nome, CPF, telefone, tipo de grupo), ativa e inativa agricultores, e consulta os alimentos de cada um, o edital (`FoodNotice`) e as entregas. Ele se apoia no módulo foods: `FarmerModule::init` importa `application.modules.foods.models.*`, `services.*` e `usecases.*`, e o model `FarmerRegister` vem de lá. A migração do controller legado está em `docs/migracao_modulos/farmer.md` (marcada em andamento).

**Linha do inventário:** 1 controller, 447 LOC, 16 actions, 0 sem `accessControl`, 8 superglobais, 0 `createCommand`, 8 JSON, 7 views, 0 `<script>`, 4 `style=`, 0 Bootstrap 2, JS 3 arquivos/450 linhas, 0 CSS, 0 models no módulo, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php` tem `filters()` com `accessControl` e `postOnly + delete`, mas a primeira regra de `accessRules()` (≈L16–31) libera **12 actions para `'*'`, inclusive visitante**: `index`, `view`, `activateFarmers`, `toggleFarmerStatus`, `createFarmerRegister`, `updateFarmerRegister`, `getFarmerRegister`, `getFoodAlias`, `getFarmerFoods`, `getFoodNotice`, `getFoodNoticeItems` e `getFarmerDeliveries`. Só `create` e `update` exigem login, e `admin` e `delete` usam `'users' => ['admin']` (nome de usuário literal, não papel).
- Efeitos visíveis no código: `actionCreateFarmerRegister` (≈L58) e `actionUpdateFarmerRegister` (≈L114) gravam sem login; `actionToggleFarmerStatus` (≈L388) inverte o status sem checar `findByPk` nulo; `actionActivateFarmers` (≈L380) lista todos os agricultores (nome, CPF, telefone). Em `actionCreateFarmerRegister`, quando o CPF já existe, a ação **reativa** o cadastro em vez de recusar. **confirmar:** com o produto e o time de merenda se algum fluxo deve mesmo ser público.
- O módulo força o layout `fullmenu` em `beforeControllerAction`, que é o layout de usuário logado.
- JS: `resources/farmer/_initialization.js` (255), `functions.js` (178) e `activateFarmers.js` (17), com 12 `?r=` literais; mais 1 `?r=` em view. Não há CSS do módulo. Views com `style=`: `activateFarmers.php` (4).
- **confirmar:** onde ficam `FarmerRegister` e `FarmerFoods` (foods/models) e se o R5-FOODS os altera.

## Objetivo
Nenhuma ação do farmer fica pública sem decisão explícita; as regras do cadastro e das entregas vivem em services testados.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-FARMER-<nn>`.
2. **SEC**
   - [ ] Decidir, action por action, o que é público. Padrão: tudo exige login, e `createFarmerRegister`, `updateFarmerRegister` e `toggleFarmerStatus` exigem papel de merenda ou admin. O que continuar público entra em `app/config/public-routes/farmer.php`, com justificativa.
   - [ ] Trocar `'users' => ['admin']` por `TagUtils::isAdmin()` e exigir POST com CSRF em `toggleFarmerStatus`, `create*` e `update*`.
   - [ ] Tratar `findByPk` nulo (404) e a reativação por CPF repetido (mensagem de conflito, ou regra de produto).
   - [ ] Não expor CPF e telefone em listagem pública.
3. **GM**
   - [ ] Caracterizar criar, atualizar, ativar e inativar agricultor e os JSON `getFarmerFoods`, `getFoodNotice`, `getFoodNoticeItems` e `getFarmerDeliveries`.
4. **SVC**
   - [ ] `app/src/Farmer/Application/`: `RegisterFarmerService`, `UpdateFarmerService`, `ToggleFarmerStatusService` e as consultas de alimentos e entregas; validação de CPF no `Domain`.
5. **API:** nenhuma prevista. **confirmar:** se o br-nham (API M2M de foods) consome o cadastro de agricultores.
6. **JS:** migrar os 3 arquivos para `frontend/pages/farmer/`, com `routes/` no lugar dos 12 `?r=`.
7. **CSS:** `sass/scss/pages/_farmer.scss` e os `style=` de `activateFarmers.php`.

## Fora de escopo
- Mudar o módulo foods e seus models (read-only; o R5-FOODS cuida).
- Mudar regras do edital ou de entrega de alimentos.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/farmer.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** os fluxos de cadastro e consulta têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/Farmer/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica, ou os endpoints têm teste de contrato.
- [ ] **JS:** páginas em `frontend/pages/farmer/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_farmer.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.
- [ ] Visitante sem sessão recebe 302 para o login nas actions que deixaram de ser públicas.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Farmer     # confirmar scripts em R1
php scripts/inventory-actions.php         # farmer sem action desprotegida
scripts/frontend-metrics                  # catraca; confirmar o comando em R1
curl -i -X POST "http://localhost/?r=farmer/default/toggleFarmerStatus" -d "id=1&status=Ativo"   # visitante: 302
```
Testes de navegador (cadastro, ativação, consultas) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

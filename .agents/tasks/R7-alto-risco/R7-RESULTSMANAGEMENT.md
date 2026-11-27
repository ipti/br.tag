---
id: R7-RESULTSMANAGEMENT
title: "resultsmanagement: medir o uso por tenant e decidir entre aposentar, manter em quarentena ou reescrever"
release: R7
module: resultsmanagement
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F5, ilhas em quarentena), seção 15 e seção 16 (item 3)"
write_scope:
  - app/modules/resultsmanagement/**
  - app/src/ResultsManagement/**
  - tests/**/ResultsManagement/**
  - frontend/pages/resultsmanagement/**
  - sass/scss/pages/_resultsmanagement.scss
  - app/config/public-routes/resultsmanagement.php
  - metrics/baseline/resultsmanagement.json
  - phpstan/baseline/resultsmanagement.neon
read_only:
  - themes/default/views/layouts/resultsmanagement.php
  - themes/default/views/layouts/menus/_admin_menu.php
  - app/components/auth/RbacSeeder.php
  - docs/migracao_modulos/resultsmanagement.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "Decisão (aposentar, quarentena ou reescrever) registrada em ADR, com a medição de uso por tenant anexada"
  - "composer lint && composer analyse"
  - "php scripts/inventory-actions.php: nenhuma action do resultsmanagement sem proteção ou fora de public-routes/resultsmanagement.php"
  - "Nenhum asset de CDN restante no módulo (git grep -n 'https://' -- app/modules/resultsmanagement, exceto o que o ADR autorizar)"
---

# R7-RESULTSMANAGEMENT: resultsmanagement: medir o uso por tenant e decidir entre aposentar, manter em quarentena ou reescrever

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. A decisão é de produto: o agente só mede, propõe e executa o que a pessoa aprovar.

**Para que serve:** painel de **gestão por resultados** das escolas (módulo "interno"), com frequência, desempenho, proficiência e evolução por turma, além de um mapa. Convive com o painel Power BI do módulo `dashboard`. O `docs/migracao_modulos/resultsmanagement.md` trata de outra coisa (a migração de um `GradesController`, marcada como concluída).

**Linha do inventário:** 2 controllers (321 LOC, 12 actions, **2 sem `accessControl`**), 0 superglobais, 12 `createCommand`, 8 JSON, 8 views, 0 `<script>`, 3 `style=`, 0 Bootstrap 2, JS 8 arquivos/778 linhas, 2 CSS, 0 models, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `DefaultController` (47 LOC): `index` e `getGMapInfo`. `ManagementschoolController` (274 LOC): `index`, `performance`, `frequency`, `loadClassroomInfos`, `loadChartData`, `loadPerformanceClassroomInfos`, `loadPerformanceChartData`, `loadDisciplineInfo`, `loadDataForProficiency` e `loadDataForEvolution`. **Nenhum define `filters()` ou `accessRules()`**; os JSON de dados saem sem login. O SQL principal está em ≈L107, com bind (`queryAll(true, $params)`). **confirmar:** os outros `createCommand` e o escopo por escola.
- **A "flag" é só de menu:** `TTask::TASK_MANAGEMENT_PERFORMANCE` é uma task do RBAC (`app/components/auth/TTask.php` ≈L20, `RbacSeeder.php` ≈L142 e ≈L238) usada pelo item de menu em `themes/default/views/layouts/menus/_admin_menu.php` (≈L399–405). Ela esconde o link, mas **não protege as rotas**: quem digitar `?r=resultsmanagement` chega ao módulo. A irmã `TASK_MANAGEMENT_PERFORMANCE_BI` abre o `dashboard` (Power BI).
- **Ilha de front:** o layout `themes/default/views/layouts/resultsmanagement.php` (arquivo com dono, ≈L27–80) carrega por CDN o Bootstrap 3.3.5 (CSS e JS), o Select2 4.0.0 (cdnjs), o jQuery 1.11.3 e o jQuery UI 1.11.4 (ajax.googleapis.com). O CSS `resources/common/css/style.css` (165 KB) é o **AdminKIT v2.0** (cabeçalho do arquivo). `views/default/index.php` (≈L13) registra o Google Maps com `key=` vazia.
- O plano (F2 e F5) já prevê para as ilhas só trocar o jQuery do CDN pelo local e servir os assets localmente, **sem reescrever** antes de medir o uso. A pergunta 3 da seção 16 (quais tenants usam resultsmanagement) segue em aberto.
- JS em `resources/common/js/` (`resultsLayout.js`, `frequency/filter.js`, `index/*`, `performance/*`) e `legacy/`. Usa `$.parseJSON` em 4 arquivos de `performance/` e `frequency/` (alvo do codemod do R8-FRONT-01).

## Objetivo
Uma decisão registrada sobre o futuro do módulo, e a execução dela, com as rotas protegidas e sem dependência de CDN nem de chave de API vazia.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-RESULTSMANAGEMENT-<nn>`. O primeiro é sempre a medição.
2. **Medição (primeiro card)**
   - [ ] Levantar por tenant quem tem a task `TASK_MANAGEMENT_PERFORMANCE` (`auth_assignment` e RBAC) e quantos acessos ao módulo houve nos últimos 90 dias (log de acesso do nginx ou auditoria; **confirmar:** a fonte disponível).
   - [ ] Entregar a tabela à pessoa responsável e registrar a decisão em ADR: **A) aposentar**, **B) manter em quarentena** ou **C) reescrever** no design system.
3. **SEC (vale para B e C)**
   - [ ] `filters()` com `accessControl` e papéis (`TRole::ADMIN`, `TRole::READER`, como no menu) nos 2 controllers; checar a task no servidor.
   - [ ] Escopo por escola nas consultas dos gráficos e bind em todos os `createCommand`.
4. **Caminho A (aposentar):** retirar rotas, menu e task do RBAC (migration SQL e card de contrato para o menu e o `main.php`), remover o módulo e os assets.
5. **Caminho B (quarentena):** servir Bootstrap 3, Select2 e jQuery localmente (o layout é arquivo com dono: card de contrato), trocar o jQuery 1.11.3 pelo pacote local do R2-FRONT, tirar o Google Maps com chave vazia ou configurar a chave por ambiente. GM e SVC só se a pessoa responsável pedir.
6. **Caminho C (reescrever):** GM dos JSON de dados, services em `app/src/ResultsManagement/`, páginas em `frontend/pages/resultsmanagement/` e `_resultsmanagement.scss`, sem AdminKIT. Só com orçamento aprovado.

## Fora de escopo
- Reescrever antes da medição e da decisão.
- Mexer no módulo `dashboard` (Power BI).
- Alterar as regras de cálculo de frequência e desempenho.

## Critérios de aceite
- [ ] A medição por tenant e o ADR de decisão estão registrados.
- [ ] **SEC:** as 12 actions estão protegidas (ou o módulo foi removido); o SQL usa bind; escopo por escola garantido.
- [ ] O módulo não carrega mais JS, CSS ou fontes de CDN (exceto o que o ADR autorizar).
- [ ] No caminho C: **GM** dos JSON, **SVC** com pelo menos 80% de cobertura, **JS** e **CSS** nos padrões do plano.
- [ ] A pessoa responsável aprovou cada PR.

## Como verificar
```bash
composer lint && composer analyse
php scripts/inventory-actions.php                  # resultsmanagement sem action desprotegida
git grep -n "https://" -- app/modules/resultsmanagement
curl -i "http://localhost/?r=resultsmanagement/managementschool/loadChartData"   # visitante: 302 para o login
```
Testes de navegador (painéis de frequência, desempenho e proficiência) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

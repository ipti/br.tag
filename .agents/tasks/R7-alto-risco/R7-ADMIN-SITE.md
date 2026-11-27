---
id: R7-ADMIN-SITE
title: "AdministratorController e SiteController: migrar para módulos, com decisão sobre o que fica na raiz"
release: R7
module: raiz
type: epico
skill: tag-module-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achados 3 e 4), seção 8.6 (alto risco) e seção 4 (golden master antes de refatorar)"
write_scope:
  - app/controllers/AdministratorController.php
  - app/controllers/SiteController.php
  - themes/default/views/site/**
  - app/modules/censo/**
  - app/modules/dashboard/**
  - app/src/Administrator/**
  - app/src/Site/**
  - tests/**/Administrator/**
  - tests/**/Site/**
  - frontend/pages/site/**
  - sass/scss/pages/_site.scss
  - app/config/public-routes/site.php
  - metrics/baseline/site.json
  - phpstan/baseline/site.neon
read_only:
  - app/modules/systemadmin/**
  - app/models/**
  - js/site/**
  - docs/migracao_modulos/PROGRESSO.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "Golden master do login, de changeSchool e changeYear e dos gráficos do painel (composer test:integration; confirmar o script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action de Administrator e Site sem proteção ou fora de public-routes/site.php"
  - "git grep -n 'AdministratorController' -- app themes js (sem resultados ao final)"
---

# R7-ADMIN-SITE: AdministratorController e SiteController: migrar para módulos, com decisão sobre o que fica na raiz

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. Login, troca de escola e troca de ano são o coração da sessão e do tenant; erro aqui derruba o sistema inteiro.

**Linha do inventário:** `AdministratorController` 1609 linhas, 18 actions, 12 superglobais, 19 `createCommand`, 3 JSON; `SiteController` 555 linhas, 15 actions, 18 superglobais, 0 `createCommand`, 5 JSON. Nenhum dos dois tem `accessControl`.

**Detalhamento (conferido em 2026-11-27)**
- **Administrator, acesso:** `accessRules()` (≈L35) libera `CreateUser`, `index` e `conflicts` para `'*'` e não há `filters()`. O R0-ADMIN-01 e o R3-SEC-03 tratam o acesso; este épico trata a estrutura.
- **Administrator, ações:** `index` (≈L54), `multiStageClassroomVerify` (≈L59), `saveMultiStage` (≈L85), `syncExport` (≈L93), `syncImport` (≈L323), `updateDB` (≈L490), `backup` (≈L544, estática), `data` (≈L554), `exportStudentIdentify` (≈L610), `export` (≈L652), `downloadExportFile` (≈L814), `createUser` (≈L832), `import` (≈L867), `acl` (≈L946), `loadToMaster` (≈L1203), `exportToMaster` (≈L1318), `cleanMaster` (≈L1565) e `importFromMaster` (≈L1605, esqueleto que só redireciona).
- **`actionImport` (≈L867–945):** grava o arquivo enviado em `app/import/<nome>` sem validar, lê linhas do Educacenso, monta INSERTs em texto, concatenando as linhas do arquivo (`getInsertSQL`, ≈L1141; **confirmar:** o escape em `getInsertValues`), liga `SET foreign_key_checks = 0` (≈L926), executa 9 SQLs com `utf8_encode` (função depreciada no PHP 8.2) e religa (≈L938). Falha no meio deixa o banco sem as chaves checadas. É também a duplicata do import do `CensoController`.
- **Banco master:** `Yii::app()->db2` em `loadToMaster` (≈L1253), `exportToMaster` (≈L1322) e ≈L1509–1585; `cleanMaster` detecta duplicatas por distância de Levenshtein.
- **Duplicatas com o systemadmin:** `createUser` e `acl` repetem `createUser` e `restoreRBAC` do `systemadmin`; o `docs/migracao_modulos/censo.md` manda manter só no systemadmin. As views de `Administrator` (`index`, `data`, `createUser`) **não existem** em `themes/default/views/administrator/` (só há `censo/` e `site/`); `MultiStageClassroomVerify` está em `systemadmin/views/default/`. **confirmar:** o que é código morto.
- **Site, ações:** `actions` (≈L24), `index` (≈L43), `error` (≈L55), `contact` (≈L69), `login` (≈L90), `logout` (≈L134), `changeSchool` (≈L140), `changeYear` (≈L151), `loadMoreLogs` (≈L209), `loadWarnsHtml` (≈L214), `loadLineChartData` (≈L348), `loadCylinderChartData` (≈L429), `loadPieChartData` (≈L441), `viewFileLogs` (≈L451) e `loadSchoolSummary` (≈L513). `changeSchool` e `changeYear` gravam o POST na sessão sem validar (achado 4 do plano, em tratamento no R0-SQL-01).
- Views do Site: `themes/default/views/site/` (`index`, `login`, `contact`, `error`, `viewLogs`, `_warns`, `_maintenance_mode`). JS: `js/site/index.js` (367 linhas) e `login.js` (12).
- **Decisão pendente:** o `PROGRESSO.md` diz que o `SiteController` **fica na raiz** neste ciclo (login, logout, `changeSchool`, `changeYear`), e sugere que os gráficos poderiam ir para o módulo `dashboard`. A pessoa responsável decide o recorte.

## Objetivo
`AdministratorController` deixa de existir e suas ações vão para módulos com acesso real e regras testadas; do `SiteController` sai o que a decisão determinar, e o que ficar tem login e troca de tenant sob golden master.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-ADMIN-SITE-<nn>`, com a decisão sobre o `Site` registrada primeiro.
2. **SEC**
   - [ ] Remover as duplicatas `createUser` e `acl` e as actions mortas; `filters()` em tudo o que sobrar.
   - [ ] Validar `changeSchool` e `changeYear` contra as escolas e anos permitidos do usuário.
   - [ ] Reescrever `import`: upload validado e fora do web root, parsing em DTOs, inserção com bind e transação, sem `foreign_key_checks`. A decisão de manter a ação é da pessoa responsável.
3. **GM**
   - [ ] Caracterizar login (sucesso, falha, escolha do ano e redirecionamento do papel `guardian` para o `enrollmentonline`, ≈L90–131), `changeSchool`, `changeYear` e os 3 JSON dos gráficos.
   - [ ] Caracterizar `syncExport`/`syncImport` (ZIP) e `exportToMaster` com dataset anonimizado.
4. **SVC**
   - [ ] Mover sync, master e import do Educacenso para `app/modules/censo/` (conforme `PROGRESSO.md`, controllers `Sync` e `Inep`) e `app/src/Administrator/` (sem `Yii::app()` em `Application`).
   - [ ] Se a decisão mover os gráficos: `app/modules/dashboard/` e `app/src/Site/`.
5. **API:** nenhuma prevista aqui. O login do app é de R4-API.
6. **JS:** `js/site/index.js` e `login.js` para `frontend/pages/site/`.
7. **CSS e limpeza:** `sass/scss/pages/_site.scss`; buscar `?r=administrator/` e `?r=site/` em PHP, JS e menus, conferir o estado ativo dos menus e remover o controller e as views órfãs.

## Fora de escopo
- Reescrever a autenticação (sessão por tenant é do R2/R3).
- Mudar o formato do arquivo do Educacenso (R7-CENSO).
- Alterar o banco master ou o desenho de `db2`.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/site.php`; o SQL usa bind; os formulários enviam CSRF; uploads validados.
- [ ] **GM:** login, troca de escola, troca de ano e sync têm golden master ou teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/` com pelo menos 80% de cobertura.
- [ ] **JS e CSS:** páginas em `frontend/pages/site/` e `_site.scss`; catraca sem `?r=` literais.
- [ ] `AdministratorController.php` foi removido, sem rota ou view órfã.
- [ ] A decisão sobre o `SiteController` está registrada e a pessoa responsável aprovou cada PR.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter 'Site|Administrator'   # confirmar o script em R1
php scripts/inventory-actions.php                            # sem action desprotegida
git grep -n "AdministratorController\|?r=administrator/" -- app themes js
```
Testes de navegador (login, troca de escola e de ano, sync, import) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

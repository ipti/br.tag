---
id: R7-SYSTEMADMIN
title: "systemadmin: isolar os exports que cruzam tenants e extrair as regras para app/src"
release: R7
module: systemadmin
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achados 1 e 2), seção 8.6 (alto risco) e seção 13 (mesma credencial MySQL em todos os tenants)"
write_scope:
  - app/modules/systemadmin/**
  - app/src/SystemAdmin/**
  - tests/**/SystemAdmin/**
  - frontend/pages/systemadmin/**
  - js/admin/auditory.js
  - js/admin/instance-config.js
  - js/admin/manage-modules.js
  - sass/scss/pages/_systemadmin.scss
  - app/config/public-routes/systemadmin.php
  - metrics/baseline/systemadmin.json
  - phpstan/baseline/systemadmin.neon
read_only:
  - app/modules/tag/**
  - app/models/**
  - js/admin/grades-structure.js
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "Golden master dos exports (grades, faults, students, master) e do import master (composer test:integration; confirmar o script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do systemadmin sem proteção; exports entre tenants só para superuser"
  - "git grep -n 'connectionString' -- app/modules/systemadmin (sem resultados ao final)"
---

# R7-SYSTEMADMIN: systemadmin: isolar os exports que cruzam tenants e extrair as regras para app/src

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. Este é o painel de quem opera todos os tenants, então qualquer erro de escopo vaza dado de outra rede.

**Para que serve** (`docs/migracao_modulos/systemadmin.md`): administração do sistema. Usuários e papéis (RBAC), exports e imports de dados, auditoria, changelog, módulos habilitados e configuração da instância.

**Linha do inventário:** 1 controller, 1310 LOC, 26 actions, 37 superglobais, 17 `createCommand`, 3 JSON, 15 views, 5 `<script>`, 33 `style=`, 38 Bootstrap 2, 0 models, 0 services. O R0-SYSADMIN-01 já tratou acesso de visitante, `CreateUser` e o `PHPConfig`; este épico trata a estrutura.

**Detalhamento (conferido em 2026-11-27, antes do R0-SYSADMIN-01)**
- `controllers/DefaultController.php`: `index` (≈L83), `restoreRBAC` (≈L88), `exports` (≈L103), `exportCountUsers` (≈L108), `exportMaster` (≈L174), `exportStudents` (≈L210), `exportGrades` (≈L275, cerca de 420 linhas), `exportFaults` (≈L699), `importMaster` (≈L831), gestão de usuários (≈L877–1077), `importBNCC` (≈L1155), `changelog`, `instanceConfig`, `auditory`/`getAuditoryLogs` (≈L1204–1287) e `manageModules`.
- **`exportCountUsers` (≈L108–172):** lê `HOST_DB_TAG`, **reaponta o `Yii::app()->db` global** (`setActive(false)`, nova `connectionString`), roda `SHOW DATABASES`, percorre todos os bancos de tenants contando professores, alunos, secretários e coordenadores, imprime HTML sem escapar o nome do banco e grava `resultados.csv` no diretório de trabalho do PHP, com link de download. **confirmar:** se esse diretório é servido pelo nginx (achado 8 do plano).
- **`exportMaster` (≈L174–208)** repete o reapontamento (≈L195–198) depois de montar os dados com `ExportModel` e `Adapter` do módulo `tag`. **`importMaster` (≈L831–876)** lê `./app/export/InfoTagJSON/<banco>.json`, liga `SET FOREIGN_KEY_CHECKS=0` dentro de uma transação e chama `ImportModel` (≈L845).
- Consequência: depois do reapontamento, o resto do request (log de auditoria, Sentry, sessão) roda contra outro banco. Hoje a action termina logo depois, o que esconde o problema. **confirmar:** o que o `Controller` base e o log de ações gravam depois.
- O R0-SYSADMIN-01 libera as ações para `admin` **ou** `superuser`. Um admin de uma rede passaria a poder rodar os exports que cobrem todos os tenants. **confirmar:** a regra final no card R0.
- JS em `js/admin/`: `auditory.js` (145), `instance-config.js` (30) e `manage-modules.js` (35); o `grades-structure.js` (1152) é do módulo de notas (**confirmar**). CSS: não há arquivo do módulo; as views têm 33 `style=` e 38 usos de Bootstrap 2.
- O módulo `tag` (R6-PEQUENOS) fornece `ExportModel`, `ImportModel` e `Adapter`; coordenar a ordem.

## Objetivo
Nenhuma action troca a conexão global; os exports entre tenants são exclusivos do superuser e vivem em services testados.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-SYSTEMADMIN-<nn>`.
2. **SEC**
   - [ ] Restringir `exportCountUsers`, `exportMaster`, `importMaster` e demais ações multi-tenant a `TagUtils::isSuperuser()`.
   - [ ] Escapar a saída HTML e gravar CSVs fora do web root (ou entregar por stream, sem arquivo).
   - [ ] POST e CSRF em `deleteUser`, `disableUser`, `activeUser`, `editPassword`, `restoreRBAC`, `importBNCC` e `importMaster`.
3. **GM**
   - [ ] Caracterizar `exportGrades`, `exportFaults`, `exportStudents`, `exportMaster` e `importMaster` com um tenant demo anonimizado, incluindo o JSON do `Adapter`.
4. **SVC**
   - [ ] `app/src/SystemAdmin/`: um `TenantCatalog` que abre **uma conexão PDO própria por tenant**, no lugar do reapontamento de `Yii::app()->db`; `ExportTenantStatsService`, `ExportGradesService`, `ExportFaultsService`, `ExportMasterService` e `ImportMasterService` (com `TransactionManager`).
   - [ ] Separar a gestão de usuários em `UserAdministrationService`, com a regra "só superuser cria superuser" do R0-SYSADMIN-01.
5. **API:** nenhuma prevista.
6. **JS:** os 3 arquivos de `js/admin/` e os `<script>` das views para `frontend/pages/systemadmin/`.
7. **CSS:** `sass/scss/pages/_systemadmin.scss`; mover os 33 `style=` e trocar os 38 usos de Bootstrap 2.

## Fora de escopo
- Um usuário MySQL por tenant (risco da seção 13; trabalho de infraestrutura).
- Reescrever o `ExportModel`/`ImportModel` do módulo `tag` (R6-PEQUENOS).
- O que o R0-SYSADMIN-01 já fechou (acesso de visitante, `CreateUser`, `PHPConfig`).

## Critérios de aceite
- [ ] **SEC:** toda action está protegida; os exports entre tenants são só de superuser; o SQL usa bind; os formulários enviam CSRF; nenhum arquivo com dado de aluno fica em diretório público.
- [ ] **GM:** os exports e o import master batem com o golden master.
- [ ] **SVC:** as actions são magras; as regras estão em `app/src/SystemAdmin/` com pelo menos 80% de cobertura, e nenhuma troca `Yii::app()->db->connectionString`.
- [ ] **API:** não se aplica.
- [ ] **JS e CSS:** páginas em `frontend/pages/systemadmin/` e `_systemadmin.scss`; catraca sem `?r=` literais.
- [ ] A pessoa responsável aprovou cada PR.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter SystemAdmin    # confirmar o script em R1
php scripts/inventory-actions.php
git grep -n "connectionString" -- app/modules/systemadmin
curl -i -b "PHPSESSID=<sessão de admin comum>" "http://localhost/?r=systemadmin/default/exportCountUsers"   # esperado: 403
```
Testes de navegador (exports, import, usuários) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

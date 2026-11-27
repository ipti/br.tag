---
id: R6-PEQUENOS
title: "Módulos pequenos: abilities, aeerecord, curricularcomponents, dashboard, gradeconcept, gradestructure, stages, tag, tools e wizard"
release: R6
module: plataforma
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3), seção 8.4 (um registro por módulo) e seção 8.6 (onda 3)"
# As chaves {a,b} abaixo expandem para um caminho por módulo. O R6-REFINE-01 gera cards com os caminhos exatos de cada módulo.
write_scope:
  - "app/modules/{abilities,aeerecord,curricularcomponents,dashboard,gradeconcept,gradestructure,stages,tag,tools,wizard}/**"
  - "app/src/{Abilities,AeeRecord,CurricularComponents,Dashboard,Stages,Tag,Tools,Wizard}/**"
  - "tests/**/{Abilities,AeeRecord,CurricularComponents,Dashboard,Stages,Tag,Tools,Wizard}/**"
  - "frontend/pages/{aeerecord,curricularcomponents,dashboard,gradeconcept,stages,tools,wizard}/**"
  - "sass/scss/pages/_{abilities,aeerecord,curricularcomponents,dashboard,stages,tools,wizard}.scss"
  - "app/config/public-routes/{abilities,aeerecord,curricularcomponents,dashboard,stages,tools,wizard}.php"
  - "metrics/baseline/{abilities,aeerecord,curricularcomponents,dashboard,gradeconcept,gradestructure,stages,tag,tools,wizard}.json"
  - "phpstan/baseline/{abilities,aeerecord,curricularcomponents,dashboard,stages,tag,tools,wizard}.neon"
read_only:
  - app/models/**
  - app/modules/systemadmin/controllers/DefaultController.php
  - app/modules/sedsp/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action dos 10 módulos sem proteção ou fora de app/config/public-routes/<módulo>.php"
  - "Catraca de cada módulo (metrics/baseline/<módulo>.json) sem aumento e com zero ?r= literais"
---

# R6-PEQUENOS: Módulos pequenos: abilities, aeerecord, curricularcomponents, dashboard, gradeconcept, gradestructure, stages, tag, tools e wizard

## Contexto
Dez módulos pequenos num épico só. Cada um ganha seus cards no R6-REFINE-01, e dois cards de módulos diferentes podem rodar em paralelo. **Linha do inventário** no formato controllers/LOC/actions/sem `accessControl`; as contagens são de 2026-11-27.

- **abilities** (1/178/4/0): CRUD de habilidades BNCC (`CourseclassabilitiesController`), base da lista usada pelo MACETE. 7 superglobais, 4 views, 4 usos de Bootstrap 2, sem JS próprio. Tem `accessControl`; **confirmar:** quem edita as habilidades.
- **aeerecord** (2/359/11/1): registro do Atendimento Educacional Especializado (model `StudentAeeRecord`). `ReportsController` (30 LOC, `actionAeeRecordReport($id)`) **não tem `filters()`**; o SQL usa bind mas filtra só por `sar.id` (**confirmar:** sem escopo de escola, o relatório abre o registro de qualquer escola). No `DefaultController`, `index` e `view` estão liberados para `'*'` (≈L43). 5 `createCommand`, 4 JSON, JS 2 arquivos/104 linhas, 1 `<script>`.
- **curricularcomponents** (1/179/5/0): CRUD de componentes curriculares (disciplinas; **confirmar:** model `EdcensoDiscipline`). 10 superglobais, 4 views, 4 Bootstrap 2, JS `_initialization.js` com 9 linhas.
- **dashboard** (1/16/1/1): embute o relatório do Power BI. `DefaultController::actionIndex` **não tem `accessControl`** e gera o token na hora (`GetToken` → `DashboardService`, 86 linhas, fluxo OAuth por senha com `USER_NAME_POWER_BI`, `PSS_POWER_BI` e `CLIENT_ID_POWER_BI` lidos do ambiente). Os ids do grupo e do relatório estão **fixos no controller** (≈L9 e ≈L11). O menu usa `TTask::TASK_MANAGEMENT_PERFORMANCE_BI` (`themes/default/views/layouts/menus/_admin_menu.php` ≈L396).
- **gradeconcept** (0 controllers): só `GradeconceptModule.php` e `resources/_initialization.js` (37 linhas). Registrado em `app/config/main.php` ≈L110, sem nenhuma referência no código. **confirmar:** código morto; o conceito de nota vive em `grades/ConceptController`.
- **gradestructure** (0): a pasta tem só um `resources/` vazio e **não existe classe `GradestructureModule`**, embora o módulo esteja em `main.php` ≈L121. A única referência é um `includes("gradestructure")` em `js/datatables/init.js` ≈L145. **confirmar:** a estrutura de notas hoje está em `grades/StructureController`.
- **stages** (1/178/6/0): CRUD de `EdcensoStageVsModality` (etapa x modalidade). 4 views, 2 Bootstrap 2, JS de 3 linhas.
- **tag** (0 controllers): sem rota própria. `models/ExportModel.php` (233 linhas, 11 `createCommand`), `models/ImportModel.php` (161) e `components/` (`Adapter` e 2 interfaces) servem à exportação e importação de dados de escola. **O consumidor é `systemadmin` (`DefaultController` ≈L179–180 e ≈L834–845)**, ou seja, o R7-SYSTEMADMIN. Coordenar a ordem com esse card.
- **tools** (1/192/4/0): comandos e diagnósticos (logs, opcache), só superuser (`'expression' => 'TagUtils::isSuperuser()'`, ≈L50–60). Migração concluída (ver `PROGRESSO.md`). 23 `style=` e 1 `<script>` nas 2 views.
- **wizard** (2/210/6/2): assistente de configuração da escola (`ConfigurationController` com `index`, `school`, `classroom`, `student` e `getStudents`, mais `DefaultController`). **Nenhum dos dois tem `accessControl`.** 11 superglobais (`$_POST['Classrooms']`, `['StudentEnrollment']`), 42 usos de Bootstrap 2 e 6 `style=`. Importa classes do sedsp (`sedsp.usecases`, `sedsp.datasources`). Entradas em `classroom/views/default/index.php` e `student/views/student/index.php`.

## Objetivo
Cada módulo pequeno fecha o critério de "módulo pronto" ou é removido com justificativa (gradeconcept e gradestructure), e nenhuma action fica sem controle.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards `R6-PEQUENOS-<nn>`, um por módulo ou por grupo de pacotes.
2. **SEC**
   - [ ] `dashboard`: `filters()` com `accessControl` e papel admin ou leitor; mover os ids do relatório e as credenciais do Power BI para configuração por ambiente.
   - [ ] `aeerecord`: `filters()` no `ReportsController`; escopo de escola em `actionAeeRecordReport`; tirar `index`/`view` de `'*'` (ou listar em `public-routes/aeerecord.php`).
   - [ ] `wizard`: `filters()` com `accessControl` nos 2 controllers e CSRF nos formulários.
   - [ ] `abilities`, `curricularcomponents`, `stages`: trocar `'users' => ['admin']` por `TagUtils::isAdmin()`; POST nas exclusões.
3. **GM:** caracterizar `aeerecord` (criar registro, relatório), `wizard` (`school`, `classroom`, `student`) e as exportações do `tag` (`ExportModel`) antes de mover consultas.
4. **SVC:** `app/src/Wizard/` (configuração da escola e matrícula em lote, com `TransactionManager`), `app/src/AeeRecord/`, `app/src/Dashboard/` (cliente do Power BI atrás de interface) e `app/src/Tag/` (export e import com bind). As regras do `tag` só mudam em acordo com o R7-SYSTEMADMIN.
5. **API:** nenhuma prevista nestes módulos.
6. **JS:** `aeerecord` (104 linhas), `curricularcomponents`, `stages`, `gradeconcept` (se não for removido) e os `<script>` inline de `dashboard`, `tools` e `wizard` para `frontend/pages/<módulo>/`.
7. **CSS:** `sass/scss/pages/_<módulo>.scss`; começar por `wizard` (42 Bootstrap 2) e `tools` (23 `style=`).
8. **Remoção:** com grep de rotas, menus e JS, propor a retirada de `gradeconcept` e `gradestructure` de `app/config/main.php` (card de contrato, arquivo com dono).

## Fora de escopo
- Reescrever o dashboard do Power BI ou trocar o provedor.
- Mudar as regras de exportação e importação do `tag` (R7-SYSTEMADMIN).
- Alterar a integração do wizard com o sedsp (R7-SEDSP).

## Critérios de aceite
- [ ] **SEC:** toda action dos módulos está protegida ou listada em `app/config/public-routes/<módulo>.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo nem id de relatório no código.
- [ ] **GM:** aeerecord, wizard e as exportações do tag têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/<Contexto>/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica.
- [ ] **JS:** páginas em `frontend/pages/<módulo>/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_<módulo>.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.
- [ ] `gradeconcept` e `gradestructure` foram removidos ou têm justificativa de permanência.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter 'Wizard|AeeRecord|Dashboard|Tag'   # confirmar scripts em R1
php scripts/inventory-actions.php         # nenhum dos módulos com action desprotegida
scripts/frontend-metrics                  # catraca por módulo; confirmar o comando em R1
curl -i "http://localhost/?r=dashboard"   # visitante: 302 para o login
curl -i "http://localhost/?r=wizard/configuration/index"   # visitante: 302
```
Testes de navegador (wizard, relatório AEE, dashboard) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

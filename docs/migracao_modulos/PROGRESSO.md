# Progresso de Migração: app/controllers/ → Módulos

> Atualizado manualmente conforme cada etapa for concluída.
> Detalhes de cada migração: ver o `.md` do respectivo módulo nesta pasta.

---

## Legenda de status

| Símbolo | Significado |
|---|---|
| ⬜ | Não iniciado |
| 🔄 | Em andamento |
| ✅ | Concluído |
| ⚠️ | Bloqueado / requer decisão |

---

## Tier 1 — Alta prioridade (mapeamento direto para módulos existentes)

### ✅ tools — ToolsController → `app/modules/tools/`

- [x] Confirmar que módulo está registrado em `app/config/main.php`
- [x] Adicionar `actionOpcache()` e `actionViewLogs()` ao `DefaultController` do módulo
- [x] Atualizar `actionIndex()` do módulo para incluir novos links (seção Diagnósticos)
- [x] Criar view `viewLogs.php` em `modules/tools/views/default/` (root não tinha views)
- [x] Buscar referências `?r=tools/` — encontrada view legada em `themes/default/views/tools/index.php` (removida)
- [x] Verificar active-state nos menus — `_admin_menu.php` já usava `['tools/default/index']` (rota do módulo)
- [x] Constante `INSTANCE` usada em `actionViewLogs()` — mesma constante global do root
- [ ] Testar: logs do dia carregam, opcache exibe sem erro
- [x] Remover `app/controllers/ToolsController.php`
- [x] Remover `themes/default/views/tools/index.php` (view órfã do root, menu já apontava para módulo)

> **Nota:** As views estavam em `themes/default/views/tools/`, não em `app/views/tools/` (sistema de temas Yii).
> O menu em `_admin_menu.php` já apontava para `tools/default/index` antes da migração.
> A migração adicionou as ações ao `DefaultController` e criou `viewLogs.php` no módulo.
> O `accessRules()` foi atualizado para incluir `opcache` e `viewLogs` (superuser only).

---

### ✅ classdiary — ClassesController + ClassFaultsController → `app/modules/classdiary/`

- [x] Mapear todas as views em `app/views/classfaults/` e `app/views/classescontents/`
- [x] Verificar quais views do módulo já existem (evitar sobrescrita)
- [x] Criar `ClassFaultsController.php` em `modules/classdiary/controllers/`
- [x] Adicionar ações de `ClassesController` ao módulo (novo controller ou `DefaultController`)
- [x] Verificar constantes globais do `ClassesController` (não remover se usadas externamente)
- [x] Garantir imports do módulo `timesheet` no novo contexto
- [x] Mover views para `modules/classdiary/views/`
- [x] Atualizar asset registration (seguir padrão `$this->module->baseScriptUrl`)
- [x] Buscar e atualizar `?r=classes/` e `?r=classFaults/` em PHP, JS e layouts
- [x] Verificar active-state nos menus
- [ ] Testar: lançamento de conteúdo, frequência, justificativa, CRUD de faltas
- [x] Remover `app/controllers/ClassesController.php`
- [x] Remover `app/controllers/ClassFaultsController.php`

---

### ✅ resultsmanagement — GradesController → `app/modules/resultsmanagement/`

- [x] Buscar referências externas a `GradesController::saveGradeResults` no repositório
- [x] Verificar localização dos usecases (`CalculateFinalMediaUsecase`, `ChageStudentStatusByGradeUsecase`, etc.)
- [x] Decidir sobre ações duplicadas com `EnrollmentController` (ver `student.md`)
- [x] Criar `GradesController.php` em `modules/resultsmanagement/controllers/`
- [x] Garantir imports de usecases no módulo (importados globalmente em `app/config/main.php` linha 56–57)
- [x] Mover views de `app/views/grades/` para `modules/resultsmanagement/views/grades/`
- [x] Buscar e atualizar `?r=grades/` e `createUrl('grades/` em PHP, JS e layouts
- [x] Verificar active-state nos menus
- [ ] Testar: lançamento de nota, cálculo de média, encerramento de turma, boletim
- [ ] Confirmar status de aluno atualizado corretamente após encerramento
- [x] Remover `app/controllers/GradesController.php`
- [x] Remover `themes/default/views/grades/` (diretório órfão vazio)

> **Nota:** As views estavam em `themes/default/views/grades/` (não em `app/views/grades/`), já movidas para `modules/resultsmanagement/views/grades/` antes desta sessão.
> Usecases em `app/domain/grades/usecases/` são importados globalmente — nenhum import adicional necessário no módulo.
> `GradesController::getDisciplines()` chama `ClassroomController::classroomDisciplineLabelArray()` — funciona via stub em `app/controllers/ClassroomController.php` mantido após migração do módulo `classroom`.
> As duplicações com `EnrollmentController` foram documentadas em `student.md`, mas não consolidadas nesta etapa.

---

### ✅ instructor — InstructorController → novo `app/modules/instructor/`

> **Decisão:** Módulo isolado `instructor` criado em vez de adicionar ao `professional` (que gerencia funcionários, domínio distinto).

- [x] Criar `app/modules/instructor/` com `InstructorModule.php`
- [x] Registrar módulo em `app/config/main.php`
- [x] Criar `DefaultController.php` com todas as ações do root controller
- [x] Mover views de `themes/default/views/instructor/` para `modules/instructor/views/default/`
- [x] Garantir layouts `fullmenu` (módulo) e `reports` (printHistory/printYearHistory override)
- [x] Atualizar rotas `instructor/printHistory`, `instructor/printYearHistory`, `instructor/update` → `instructor/default/...`
- [x] Verificar active-state nos menus — `?r=instructor` continua funcionando via module default controller
- [x] `PasswordHasher` em `app/components/` — acessível globalmente, sem import especial
- [ ] Testar: CRUD de instrutor, frequência de professor, lookup CEP, impressão de histórico
- [x] Remover `app/controllers/InstructorController.php`
- [x] Remover `themes/default/views/instructor/` (diretório de views movido para módulo)

> **Nota:** Views estavam em `themes/default/views/instructor/` (não em `app/views/`).
> `redirect(['index'])` atualizado para `redirect(['instructor/default/index'])` no controller.
> `['instructor']` no menu resolve para `instructor/default/index` automaticamente via `CWebModule::$defaultController`.
> `classroom/update` em `actionDelete` atualizado para `classroom/default/update` após migração do módulo `classroom`.

---

### 🔄 schoolreport — ReportsController + FormsController + ReportCardController → `app/modules/schoolreport/`

- [x] Verificar conteúdo real de `ReportCardController.php` (suspeita de conflito de header)
- [x] Buscar todas as referências a `ReportsRepository` e `FormsRepository` no repositório
- [x] Decidir se repositórios migram para dentro do módulo ou permanecem compartilhados
- [x] Criar `ReportsController.php` em `modules/schoolreport/controllers/`
- [x] Criar `FormsController.php` em `modules/schoolreport/controllers/`
- [x] Mover views de `app/views/reports/` para `modules/schoolreport/views/reports/`
- [x] Mover views de `app/views/forms/` para `modules/schoolreport/views/forms/`
- [x] Verificar layouts: `reportsclean`, `reports`, `fullmenu` nas ações corretas
- [x] Buscar e atualizar `?r=reports/` e `?r=forms/` em PHP, JS e layouts
- [x] Verificar active-state nos menus
- [ ] Testar: 5+ relatórios mais usados, formulário de transferência, declarações
- [x] Confirmar que `DefaultController` existente do módulo não foi afetado
- [x] Remover `app/controllers/ReportsController.php`
- [x] Remover `app/controllers/FormsController.php`
- [x] Remover `app/controllers/ReportCardController.php` (após decisão de destino)

> **Nota:** As views reais estavam em `themes/default/views/reports/` e `themes/default/views/forms/`, não em `app/views/`.
> `ReportsRepository` e `FormsRepository` foram movidos para `app/modules/schoolreport/repositories/`.
> Os módulos consumidores (`student`, `resultsmanagement` e `grades`) agora importam `FormsRepository` diretamente de `schoolreport`.
> `ReportCardController.php` não pertencia ao domínio de `schoolreport`: o arquivo continha uma cópia antiga de `GradesController`, então o destino funcional correto permaneceu em `resultsmanagement`.

---

## Tier 2 — Médio prazo (requerem análise de rotas e dependências)

### ✅ gradestructure — GradesStructureController → novo `app/modules/gradestructure/`

> **Decisão:** Módulo isolado `gradestructure` criado em vez de adicionar ao `gradeconcept` (domínio distinto: estrutura de avaliação vs. conceitos de notas).

- [x] Criar `app/modules/gradestructure/` com `GradestructureModule.php`
- [x] Registrar módulo em `app/config/main.php` e remover `controllerMap` entry
- [x] Criar `DefaultController.php` com todas as ações
- [x] Usecases (`UpdateGradeStructUsecase`, `CopyGradeStructUsecase`) em `app/domain/admin/usecases/` — importados globalmente
- [x] Mover views de `themes/default/views/admin/` para `modules/gradestructure/views/default/`
- [x] Atualizar render paths de `//admin/indexGradesStructure` → `indexGradesStructure` (relativo ao módulo)
- [x] Atualizar `?r=gradesStructure/saveunities` e `getunities` em `js/admin/grades-structure.js`
- [x] Atualizar `createUrl('gradesStructure/index')` em `themes/default/views/admin/index.php`
- [x] Atualizar rotas `gradesStructure/create|delete|copy` em `indexGradesStructure.php`
- [x] Atualizar `action.includes("indexGradesStructure")` em `js/datatables/init.js` → `action.includes("gradestructure")`
- [x] Remover `app/controllers/GradesStructureController.php`
- [x] Remover views órfãs de `themes/default/views/admin/` (`indexGradesStructure.php`, `gradesStructure.php`)
- [ ] Testar: criação de estrutura, adição de unidades, cópia para novo ano, exclusão com validações

> **Nota:** Views estavam em `themes/default/views/admin/` (não em `themes/default/views/gradesStructure/`).
> `controllerMap` entry `'gradesstructure' => 'GradesStructureController'` removido — módulo assume o roteamento.
> Usecases importados globalmente via `app/config/main.php:54–55` (`application.domain.admin.usecases.*`).
> `$this->render('//admin/...')` substituído por render relativo dentro do módulo.

---

### 🔄 student — EnrollmentController → `app/modules/student/`

- [ ] Resolver duplicação de ações com `GradesController` (migrar Tier 1 primeiro)
- [x] Verificar acesso a `FormsRepository` no módulo (cálculo de frequência)
- [x] Verificar acesso a `AuthenticateSEDTokenInterface` no módulo
- [x] Criar `EnrollmentController.php` em `modules/student/controllers/`
- [x] Adicionar imports SEDSP ao `StudentModule.php` ou ao controller
- [x] Mover views de `app/views/enrollment/` para `modules/student/views/enrollment/`
- [x] Buscar e atualizar `?r=enrollment/` e `createUrl('enrollment/` em PHP, JS e layouts
- [ ] Verificar active-state nos menus
- [ ] Testar: CRUD de matrícula, mudança de status
- [ ] Testar: fluxo de transferência SEDSP (com e sem feature flag)
- [x] Confirmar exclusão em cascata (FrequencyAndMeanByDiscipline, FrequencyByExam)
- [x] Remover `app/controllers/EnrollmentController.php`

> **Nota:** `FrequencyAndMeanByDiscipline` e `FrequencyByExam` não usam `ON DELETE CASCADE` no banco local (`DELETE_RULE = NO ACTION`).
> A limpeza foi centralizada em `StudentEnrollment::beforeDelete()`, então a exclusão segura da matrícula agora depende do model, não do controller.

---

### ✅ classroom (novo) — ClassroomController → `app/modules/classroom/`

> **Decisão:** Módulo isolado `classroom` criado em vez de adicionar ao `school` (domínio distinto: turmas vs. escola).

- [x] Criar `app/modules/classroom/` com `ClassroomModule.php`
- [x] Registrar módulo em `app/config/main.php`
- [x] Criar `DefaultController.php` com todas as ações do root controller
- [x] Importar SEDSP e `aeerecord` no `ClassroomModule::init()`
- [x] Mover views de `themes/default/views/classroom/` para `modules/classroom/views/default/`
- [x] Atualizar rotas internas nas views movidas (`classroom/create`, `classroom/update`, etc.)
- [x] Atualizar rotas em `js/classroom/form/_initialization.js` e `functions.js`
- [x] Atualizar `instructor/default/DefaultController.php` (link para `classroom/default/update`)
- [x] Atualizar `sedsp/controllers/DefaultController.php` (2 links)
- [x] Atualizar `sagres/views/default/inconsistencys.php` (2 rotas)
- [x] Atualizar `student/views/student/_form.php` (1 rota)
- [x] Atualizar `themes/default/views/censo/_validatemessages.php` (3 rotas)
- [x] Atualizar `enrollmentonline/resources/functions.js` (1 rota)
- [x] Atualizar `js/instructor/form/functions.js` (1 rota)
- [x] Atualizar testes `ClassroomCest.php`, `ClassroomRemoveCest.php`, `ClassroomRobots.php`
- [x] Manter stub `app/controllers/ClassroomController.php` com métodos estáticos (10+ callers externos — migração gradual)
- [x] Remover `themes/default/views/classroom/` (diretório de views movido para módulo)
- [ ] Testar: CRUD de turma, operações batch, drag-and-drop de alunos
- [ ] Testar: sync SEDSP (com e sem feature flag)

> **Nota:** Views estavam em `themes/default/views/classroom/` (não em `app/views/`).
> `ClassroomController::classroomDisciplineLabelArray()` e outros 3 métodos estáticos têm 10+ callers externos (models, modules). Stub mantido em `app/controllers/ClassroomController.php`; callers não precisam ser atualizados ainda.
> Active-state `strpos($_SERVER['REQUEST_URI'], "?r=classroom")` continua funcionando pois `?r=classroom/default/...` contém `?r=classroom`.
> `ClassroomModule::init()` importa SEDSP e aeerecord (que antes eram `Yii::import()` no topo do controller raiz).

---

## Tier 3 — Complexos ou especializados

### 🔄 farmer — FarmerRegisterController → novo `app/modules/farmer/`

- [x] Verificar se modelo `FarmerRegister` é específico ou compartilhado
- [x] Verificar se há link de menu apontando para `?r=farmerRegister/`
- [x] Criar estrutura de diretórios `app/modules/farmer/`
- [x] Criar `FarmerModule.php`
- [x] Criar `DefaultController.php` com as ações do fluxo real
- [x] Mover views e resources do fluxo ativo para `modules/farmer/views/default/`
- [x] Registrar módulo em `app/config/main.php`
- [x] Buscar e atualizar referências do fluxo em PHP, JS e layouts
- [ ] Testar: CRUD completo de agricultor
- [x] Remover `app/controllers/FarmerRegisterController.php`

Observações:
- O fluxo real de agricultor não estava ativo no controller raiz. Ele já vivia em `app/modules/foods/`.
- `FarmerRegister` é compartilhado e permanece em `app/modules/foods/models/FarmerRegister.php`.
- O novo módulo `farmer` reutiliza modelos, services e usecases de `foods`.

---

### ✅ systemadmin — AdminController → `app/modules/systemadmin/`

- [x] Ler `DefaultController.php` do módulo `systemadmin` (mapear conteúdo atual — 2 ações: manageModules, editManageModules)
- [x] Fundir todas as 24 ações do `AdminController` no `DefaultController` existente do módulo
- [x] Mover `Yii::import('application.domain.admin.usecases.*')` para `SystemadminModule::init()`
- [x] Mover views de `themes/default/views/admin/` para `modules/systemadmin/views/default/` (14 arquivos)
- [x] Atualizar rotas `admin/...` → `systemadmin/default/...` nas views com referências
- [x] Buscar e atualizar `?r=admin/` e `createUrl('admin/` em PHP, JS e layouts
- [x] Atualizar `js/admin/auditory.js` (AJAX DataTable) e `js/admin/instance-config.js`
- [x] Atualizar `themes/default/views/layouts/menus/_superuser_menu.php` (editPassword + item Administração)
- [x] Atualizar `themes/default/views/layouts/menus/_admin_menu.php` (editPassword)
- [x] Atualizar `themes/default/views/layouts/fullmenu.php` (changeYear)
- [x] Atualizar `config.php` (BOARD_MSG com link para changelog)
- [x] Atualizar testes `RegisterUserCest.php` e `ChangePasswordCest.php`
- [x] Remover `app/controllers/AdminController.php`
- [x] Remover `themes/default/views/admin/` (diretório de views movido para módulo)
- [ ] Testar: CRUD de usuário, exportação de dados, logs de auditoria (DataTables), configurações de instância

---

## Tier 4 — Alta complexidade (migração incremental)

### ⬜ censo — AdministratorController + CensoController → novo `app/modules/censo/`

**Fase 1 — Preparação**
- [ ] Localizar classes de validação (`*Validation.php`) no repositório
- [ ] Remover ações duplicadas do `AdministratorController` (`actionCreateUser`, `actionACL`)
- [ ] Criar estrutura do módulo `app/modules/censo/`
- [ ] Mover classes de validação para `modules/censo/validators/`
- [ ] Registrar módulo em `app/config/main.php`

**Fase 2 — Exportação / Importação**
- [ ] Criar `ExportController.php` e `ImportController.php`
- [ ] Verificar path da biblioteca `./app/libraries/Educacenso/Educacenso.php` no módulo
- [ ] Encapsular `Educacenso::exportar()` em `EducacensoExportService`
- [ ] Testar: geração de TXT, download, importação de registros pipe-delimitados

**Fase 3 — Validação do censo**
- [ ] Criar `DefaultController.php` com `actionIndex()` e `actionValidate()`
- [ ] Testar os 9 validadores com dados reais de censo
- [ ] Testar: cálculo de dígito verificador de certidão civil
- [ ] Testar: correspondência probabilística de INEP

**Fase 4 — Sincronização e INEP**
- [ ] Criar `SyncController.php` (sync ZIP, operações master DB)
- [ ] Criar `InepController.php` (gerenciamento de IDs INEP)
- [ ] Verificar conexão `db2` (banco master) no contexto do módulo
- [ ] Testar: exportação ZIP, importação ZIP, match de INEP

**Fase 5 — Limpeza**
- [ ] Buscar e atualizar `?r=censo/` e `?r=administrator/` em PHP, JS e layouts
- [ ] Verificar active-state nos menus
- [ ] Remover `app/controllers/CensoController.php`
- [ ] Remover `app/controllers/AdministratorController.php`

---

## Fora de escopo

### SiteController — manter em root

`SiteController` contém autenticação crítica (`login`, `logout`, `changeSchool`, `changeYear`) e **não será migrado** neste ciclo. Dashboard charts podem ser revisados futuramente para o módulo `dashboard`.

---

## Resumo geral

| Módulo | Tier | Controllers | Status |
|---|---|---|---|
| tools | 1 | ToolsController | ✅ |
| classdiary | 1 | ClassesController, ClassFaultsController | ✅ |
| resultsmanagement | 1 | GradesController | ✅ |
| instructor (novo) | 1 | InstructorController | ✅ |
| schoolreport | 1 | ReportsController, FormsController, ReportCardController | 🔄 |
| gradestructure (novo) | 2 | GradesStructureController | ✅ |
| classroom (novo) | 2 | ClassroomController | ✅ |
| student | 2 | EnrollmentController | 🔄 |
| farmer | 3 | FarmerRegisterController | 🔄 |
| systemadmin | 3 | AdminController | ✅ |
| censo | 4 | AdministratorController, CensoController | ⬜ |
| SiteController | — | (manter root) | — |

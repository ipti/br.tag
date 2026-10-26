# Migração: ReportsController + FormsController + ReportCardController → `app/modules/schoolreport/`

## Objetivo

Consolidar os três controllers de relatórios legados (`ReportsController`, `FormsController`, `ReportCardController`) no módulo `schoolreport`, centralizando toda a geração de relatórios educacionais.

---

## Origem

| Controller | Arquivo | Tamanho | Nº de Ações |
|---|---|---|---|
| ReportsController | `app/controllers/ReportsController.php` | ~27 KB | 40+ |
| FormsController | `app/controllers/FormsController.php` | ~8 KB | 24 |
| ReportCardController | `app/controllers/ReportCardController.php` | ~29 KB | 10 |

---

## Destino

**Módulo:** `app/modules/schoolreport/`
**Já existente:** Sim
**Layout do módulo:** `schoolreport` (layout customizado isolado)
**Controller atual:** `app/modules/schoolreport/controllers/DefaultController.php`

---

## Análise: ReportsController

### Dependência principal
```php
require_once 'app/modules/schoolreport/repositories/ReportsRepository.php';
$repository = new ReportsRepository();
```
`ReportsRepository` encapsula todas as queries — o controller é basicamente um dispatcher.

### Ações (40+ reports)

| Categoria | Exemplos de ações |
|---|---|
| Matrícula | `actionNumberStudentsPerClassroomReport`, `actionEnrollmentStatisticsByYearReport`, `actionEnrollmentPerClassroomReport($id)` |
| Alunos | `actionStudentsFileReport`, `actionStudentPendingDocument`, `actionStudentsWithDisabilitiesPerClassroom` |
| Transferência | `actionClassContentsReport($classroomId, $month, $year, $disciplineId)` |
| Professores | `actionTeachersByStage`, `actionTeachersBySchool`, `actionInstructorsPerClassroomReport` |
| Desempenho | `actionResultBoardReport`, `actionQuarterlyReport`, `actionClassCouncilReport` |
| Diário | `actionElectronicDiary`, `actionGenerateElectronicDiaryReport` (POST) |
| AJAX support | `actionGetStudentClassrooms($id)`, `actionGetDisciplines()`, `actionGetStagesMulti()`, `actionGetEnrollments()` |
| Bolsa Família | `actionBFReport()` |

### Layouts usados
- `fullmenu` — tela de seleção/index
- `reportsclean` — saída de impressão
- `reports` — relatórios formatados

### Modelos diretos usados
- `Classroom::model()`, `EdcensoDiscipline::model()`, `StudentEnrollment::model()`
- `InstructorIdentification::model()`, `ClassContents::model()`, `ClassFaults::model()`, `Schedule::model()`

---

## Análise: FormsController

### Dependência principal
```php
require_once 'app/modules/schoolreport/repositories/FormsRepository.php';
$repository = new FormsRepository();
```
Padrão idêntico ao `ReportsController` — toda query está no repositório.

### Ações (24 ações)

| Tipo | Exemplos |
|---|---|
| Boletim/certificado | `actionEnrollmentGradesReport($enrollmentId)`, `actionConclusionCertification($enrollmentId)`, `actionIndividualRecord($enrollmentId)` |
| Declarações | `actionEnrollmentDeclarationReport($enrollmentId)`, `actionStudentsDeclarationReport($enrollmentId)`, `actionStatementAttended($enrollmentId)` |
| Transferência | `actionTransferRequirement($enrollmentId)`, `actionTransferForm($enrollmentId)` |
| Nutrição/IMC | `actionStudentIMCReport($classroomId)`, `actionStudentIMCHistoryReport($studentId)` |
| Notificações | `actionEnrollmentNotification($enrollmentId)` |
| Documentos disciplinares | `actionWarningTerm($enrollmentId)`, `actionSuspensionTerm($enrollmentId)` |
| AJAX (JSON) | `actionGet*Information(...)` — retornam JSON para impressão via JS |
| Performance | `actionAtaSchoolPerformance($id)` |

### Layouts usados
- `fullmenu` — index
- `reports` — documentos impressos

---

## Análise: ReportCardController

### Atenção: Conflito de conteúdo
O arquivo `ReportCardController.php` na realidade contém código de `GradesController` (conflito de header). Verificar o conteúdo real antes da migração.

### Ações (10 ações)
- Lançamento e consulta de notas por disciplina
- Boletim de conceitos
- Cálculo de média final

> **Verificar:** Se o conteúdo real deste arquivo é de boletim ou lançamento de notas, pode precisar migrar junto com `GradesController` para `resultsmanagement`.

---

## Situação do Módulo `schoolreport` Atual

O `DefaultController` do módulo é **voltado ao aluno/responsável** (leitura de notas e frequência):
- `actionSelect()` — seletor de aluno/matrícula
- `actionGrades($eid)` — notas do aluno (requer login do módulo)
- `actionFrequency($eid)` — frequência do aluno
- Autenticação **isolada** (state prefix `_schoolreport`, login próprio)

**Os domínios são complementares:**
| Característica | Controllers legados | Módulo atual |
|---|---|---|
| Ator | Gestor/secretaria | Aluno/responsável |
| Acesso | Login do sistema TAG | Login independente do módulo |
| Propósito | Geração de relatórios | Consulta de notas próprias |

**Estratégia:** Criar controllers separados (`ReportsController`, `FormsController`) dentro do módulo, mantendo o `DefaultController` existente intacto.

---

## Repositórios a Mover

| Repositório | Localização atual | Destino recomendado |
|---|---|---|
| `ReportsRepository` | `app/modules/schoolreport/repositories/ReportsRepository.php` | `app/modules/schoolreport/repositories/ReportsRepository.php` |
| `FormsRepository` | `app/modules/schoolreport/repositories/FormsRepository.php` | `app/modules/schoolreport/repositories/FormsRepository.php` |

Verificar se esses repositórios são referenciados por outros controllers antes de mover.

---

## Views a Mover

### ReportsController
| View atual | Novo caminho |
|---|---|
| `app/views/reports/index.php` | `app/modules/schoolreport/views/reports/index.php` |
| `app/views/reports/BFReport.php` | `app/modules/schoolreport/views/reports/BFReport.php` |
| `app/views/reports/*.php` (40+) | `app/modules/schoolreport/views/reports/` |
| Views de buzios: `app/views/reports/buzios/` | `app/modules/schoolreport/views/reports/buzios/` |

### FormsController
| View atual | Novo caminho |
|---|---|
| `app/views/forms/index.php` | `app/modules/schoolreport/views/forms/index.php` |
| `app/views/forms/EnrollmentGradesReport.php` | `app/modules/schoolreport/views/forms/` |
| `app/views/forms/*.php` (24+) | `app/modules/schoolreport/views/forms/` |

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=reports/
?r=forms/
createUrl('reports/
createUrl('forms/
```

Verificar especialmente:
- `themes/default/views/layouts/menus/` — links do menu principal
- Views de boletim/declaração que usam AJAX para `?r=reports/getDisciplines` etc.
- Links de "imprimir" que apontam para `?r=forms/transferForm` etc.

---

## Riscos e Observações

- **Risco alto** — 70+ ações de relatório; qualquer rota quebrada afeta a secretaria escolar
- Os repositórios (`ReportsRepository`, `FormsRepository`) podem ser usados por outros controllers — buscar antes de mover
- O módulo usa layout `schoolreport` (personalizado) — verificar se os relatórios de impressão precisam desse layout ou do `reports` root
- `ReportCardController` precisa de inspeção manual do conteúdo real antes de decidir o destino
- Relatórios de nutrição/IMC podem ter sobreposição com o módulo `foods` — avaliar

---

## Passos de Migração

1. Verificar conteúdo real de `ReportCardController.php` (conflito de header suspeito)
2. Buscar todas as referências a `ReportsRepository` e `FormsRepository` no repositório
3. Criar `ReportsController.php` e `FormsController.php` em `app/modules/schoolreport/controllers/`
4. Mover repositórios para `modules/schoolreport/repositories/` (ou manter como serviço compartilhado)
5. Mover views de `app/views/reports/` e `app/views/forms/` para dentro do módulo
6. Inspecionar o módulo (`SchoolreportModule.php`) para padrão de asset publication
7. Buscar e atualizar todas as referências `?r=reports/` e `?r=forms/` em PHP, JS e layouts
8. Testar: pelo menos 5 relatórios mais usados + fluxo de formulário de transferência
9. Remover controllers legados após validação

---

## Checklist de Encerramento

- [ ] Módulo registrado em `app/config/main.php`
- [ ] `ReportsController` e `FormsController` criados dentro do módulo
- [ ] `ReportCardController` analisado e direcionado para o módulo correto
- [ ] Repositórios movidos ou acessíveis no contexto do módulo
- [ ] Views movidas para `modules/schoolreport/views/`
- [ ] Layouts `reportsclean` e `reports` funcionando corretamente
- [ ] Rotas `?r=reports/` e `?r=forms/` atualizadas
- [ ] AJAX flows testados (getDisciplines, getEnrollments, etc.)
- [ ] Relatórios de impressão (PDF/HTML) funcionais
- [ ] `DefaultController` existente do módulo não afetado
- [ ] Controllers legados removidos após validação

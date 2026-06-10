# Migração: EnrollmentController → `app/modules/student/`

## Objetivo

Mover o gerenciamento de matrículas escolares (CRUD, notas, frequência, integração SEDSP) do controller legado `EnrollmentController` para o módulo `student`.

---

## Origem

**Arquivo:** `app/controllers/EnrollmentController.php`
**Tamanho:** ~31 KB
**Layout:** `fullmenu`
**Interface implementada:** `AuthenticateSEDTokenInterface`

---

## Destino

**Módulo:** `app/modules/student/`
**Já existente:** Sim
**Controller atual:** `app/modules/student/controllers/StudentController.php`

---

## Análise das Ações

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionIndex()` | GET | — | Lista matrículas da escola atual |
| `actionView($id)` | GET | `$id` | Exibe matrícula individual |
| `actionCreate()` | GET/POST | form data | Cria nova matrícula |
| `actionUpdate($id)` | GET/POST | `$id` + form data | Atualiza matrícula, sincroniza SEDSP |
| `actionDelete($id)` | GET | `$id` | Exclui matrícula com limpeza em cascata |
| `actionUpdateDependencies()` | POST (AJAX) | — | Retorna dropdown de turmas |
| `actionGetModalities()` | POST (AJAX) | `Stage` | Retorna modalidades por estágio (HTML `<option>`) |
| `actionCheckEnrollmentDelete($enrollmentId)` | GET (AJAX) | `$enrollmentId` | Verifica notas/frequência antes de excluir |
| `actionGetDisciplines()` | POST (AJAX) | `classroom` | Retorna disciplinas da turma |
| `actionReportCard()` | GET | — | Exibe form de boletim |
| `actionGradesRelease()` | GET | — | Exibe form de lançamento de notas |
| `actionGrades()` | GET | — | Exibe form de notas |
| `actionGetGrades()` | POST (AJAX) | `classroom`, `discipline` | Busca notas dos alunos |
| `actionSaveGradesReportCard()` | POST | `discipline`, `classroom`, `students` | Salva notas do boletim |
| `actionCalculateFinalMedia()` | POST | `disciplineId`, `classroomId` | Calcula e atualiza média final |

---

## Integração SEDSP (Crítica)

### Imports necessários
```php
Yii::import('application.modules.sedsp.models.Student.*');
Yii::import('application.modules.sedsp.models.Enrollment.*');
Yii::import('application.modules.sedsp.usecases.Enrollment.*');
```

### Classes SEDSP usadas
- `LoginUseCase` — autenticação token SED
- `InAluno` — objeto aluno
- `InMatriculaTrocar` — dados de transferência
- `InTrocarAlunoEntreClasses` — mudança de turma
- `InExcluirMatricula` — exclusão de matrícula
- `InBaixarMatricula` — baixa/evasão
- `TrocarAlunoEntreClassesUseCase` — execução de transferência
- `DeleteEnrollmentUseCase` — execução de exclusão
- `TerminateEnrollmentUseCase` — execução de encerramento
- `ClassroomMapper` — conversão de tipo de ensino

### Feature flag
```php
TFeature::FEAT_INTEGRATIONS_SEDSP
```
A sincronização só ocorre se a feature estiver habilitada.

### Mapeamento de status → operação SEDSP
| Status | Operação SEDSP |
|---|---|
| 2 (Transferência) | `TrocarAlunoEntreClassesUseCase` |
| 3 (Cancelamento) | `DeleteEnrollmentUseCase` |
| 4 (Baixa/Evasão) | `TerminateEnrollmentUseCase` |
| 11 (Exclusão) | `DeleteEnrollmentUseCase` |

---

## Usecases de Nota (Migrar junto)

```php
ChageStudentStatusByGradeUsecase($enrollmentId, $disciplineId, $numUnities, $frequency)
GetStudentGradesResultUsecase($enrollmentId, $discipline)
CalculateFinalMediaUsecase($gradeResult, $gradeRules, $gradesCount)
```

---

## Modelos Usados

- `StudentEnrollment::model()` (entidade principal)
- `StudentIdentification::model()`
- `Classroom::model()`
- `EdcensoStageVsModality::model()`
- `ClassFaults::model()`
- `Grade::model()`
- `GradeResults::model()`
- `FrequencyAndMeanByDiscipline::model()`
- `FrequencyByExam::model()`
- `StudentEnrollmentHistory::model()`
- `GradeUnity::model()`
- `GradeConcept::model()`
- `GradeUnityModality::model()`
- `Log::model()` (audit)

Todos compartilhados — **não mover**.

---

## Lógica de Negócio Crítica

### `actionUpdate()` — conversão de datas
```php
// Converte de d/m/Y → Y-m-d para salvar no banco
// Reverte de Y-m-d → d/m/Y para exibir no form
```

### `actionDelete()` — limpeza em cascata
Exclui antes de deletar a matrícula:
- `FrequencyAndMeanByDiscipline`
- `FrequencyByExam`
- Log de auditoria via `Log::saveAction()`
- Redireciona para `student/student/index`

### `actionCalculateFinalMedia()` — frequência
```php
$totalClasses = FormsRepository::contentsPerDisciplineCalculate(...);
$frequency = ($totalClasses - $faults) / $totalClasses * 100;
```
Depende de `FormsRepository` — verificar acesso no módulo.

---

## Situação do Módulo `student` Atual

O `StudentModule.php` **não importa modelos locais** (`setImport([])` vazio), indicando que depende exclusivamente dos modelos compartilhados de `app/models/`. O `StudentController.php` (46 KB) gerencia perfis e identificação de alunos.

**Domínios relacionados mas distintos:**
| Característica | EnrollmentController | StudentController (módulo) |
|---|---|---|
| Entidade | Matrícula (vínculo aluno-turma) | Identificação do aluno |
| Foco | Status, notas, transferência | Dados cadastrais |

**Estratégia:** Criar `EnrollmentController.php` dentro de `app/modules/student/controllers/` como controller separado.

---

## Ações Duplicadas com GradesController

As seguintes ações são **idênticas** em `EnrollmentController` e `GradesController`:
- `actionGetModalities()`
- `actionGetDisciplines()`
- `actionReportCard()`
- `actionGrades()`
- `actionGetGrades()`
- `actionSaveGradesReportCard()`

**Recomendação:** Resolver a duplicação antes de migrar. Opções:
1. Remover essas ações do `EnrollmentController` (se `GradesController` já tiver sido migrado)
2. Criar controller base compartilhado

---

## Views a Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/enrollment/index.php` | `app/modules/student/views/enrollment/index.php` |
| `app/views/enrollment/view.php` | `app/modules/student/views/enrollment/view.php` |
| `app/views/enrollment/create.php` | `app/modules/student/views/enrollment/create.php` |
| `app/views/enrollment/update.php` | `app/modules/student/views/enrollment/update.php` |
| `app/views/enrollment/reportCard.php` | `app/modules/student/views/enrollment/reportCard.php` |
| `app/views/enrollment/gradesRelease.php` | `app/modules/student/views/enrollment/gradesRelease.php` |
| `app/views/enrollment/grades.php` | `app/modules/student/views/enrollment/grades.php` |

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=enrollment/
createUrl('enrollment/
student/student/index
```

Verificar especialmente:
- `actionDelete()` redireciona hardcoded para `student/student/index` — já está correto para o módulo
- Views de turma (`ClassroomController`) que linkam para matrícula
- JS de lançamento de notas que chama `?r=enrollment/getGrades`

---

## Riscos e Observações

- **Risco alto** — integração SEDSP é crítica e deve ser testada em ambiente com feature flag habilitada
- Implementa `AuthenticateSEDTokenInterface` — garantir que essa interface esteja acessível no módulo
- `FormsRepository` é usado para cálculo de frequência — verificar acesso no contexto do módulo
- Ações duplicadas com `GradesController` devem ser resolvidas antes ou durante esta migração
- O redirecionamento para `student/student/index` já está no formato do módulo — verificar se está correto

---

## Passos de Migração

1. Resolver duplicação de ações com `GradesController` (preferir migrar `GradesController` primeiro)
2. Verificar acesso a `FormsRepository` e `AuthenticateSEDTokenInterface` no módulo
3. Criar `EnrollmentController.php` em `app/modules/student/controllers/`
4. Adicionar imports SEDSP ao `StudentModule.php` ou ao controller
5. Mover views de `app/views/enrollment/` para `modules/student/views/enrollment/`
6. Buscar e atualizar referências `?r=enrollment/` em PHP, JS e layouts
7. Testar: CRUD de matrícula, mudança de status, integração SEDSP (com e sem feature flag)
8. Remover `app/controllers/EnrollmentController.php` após validação

---

## Checklist de Encerramento

- [ ] Ações duplicadas com `GradesController` resolvidas
- [ ] `EnrollmentController` criado em `modules/student/controllers/`
- [ ] `AuthenticateSEDTokenInterface` acessível no módulo
- [ ] Imports SEDSP funcionando no contexto do módulo
- [ ] `FormsRepository` acessível para cálculo de frequência
- [ ] Views movidas para `modules/student/views/enrollment/`
- [ ] Rotas `?r=enrollment/` atualizadas
- [ ] Fluxo de transferência de aluno (SEDSP) testado
- [ ] Exclusão em cascata (FrequencyAndMeanByDiscipline, FrequencyByExam) funcional
- [ ] Controller legado removido

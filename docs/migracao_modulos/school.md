# Migração: ClassroomController → `app/modules/school/`

## Objetivo

Mover o gerenciamento de turmas (CRUD, disciplinas, batch operations, sincronização SEDSP) do controller legado `ClassroomController` para o módulo `school`.

---

## Origem

**Arquivo:** `app/controllers/ClassroomController.php`
**Tamanho:** ~58 KB
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/school/`
**Já existente:** Sim
**Controller atual:** `app/modules/school/controllers/SchoolController.php` (~28 KB)

---

## Análise das Ações

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionIndex()` | GET | — | Lista turmas da escola atual |
| `actionView($id)` | GET | `$id` | Exibe dados da turma |
| `actionCreate()` | GET/POST | form data | Cria turma com professores e disciplinas |
| `actionUpdate($id)` | GET/POST | `$id` + form data | Atualiza turma, sincroniza SEDSP |
| `actionDelete($id)` | GET | `$id` | Exclui turma com transação (JSON response) |
| `actionBatchupdate($id)` | POST | `$id` + enrollment data | Atualiza em lote: tipo de admissão, situação |
| `actionBatchUpdateTotal($id)` | POST | `$id` + enrollment data | Atualiza em lote: etapa/modalidade, status |
| `actionBatchUpdateTransport($id)` | POST | `$id` + transport flags | Atualiza em lote: transporte público, tipo de veículo |
| `actionBatchupdatEnrollment($id)` | POST | `$id` + reenrollment flags | Atualiza em lote: flag de rematrícula |
| `actionGetAssistanceType()` | POST (AJAX) | `Classroom` | Retorna tipos de atendimento |
| `actionUpdateAssistanceTypeDependencies()` | POST (AJAX) | `Classroom` | Retorna modalidade/etapa baseado no tipo de atendimento |
| `actionUpdateComplementaryActivity()` | POST (AJAX) | `Classroom` | Retorna tipos de atividade complementar disponíveis |
| `actionGetGradesRulesClassroom()` | POST (AJAX) | `classroom_id` | Retorna regras de nota selecionadas/disponíveis |
| `actionUpdateTime()` | POST (AJAX) | `Classroom['turn']` | Retorna horários de aula por turno (M/T/N/I) |
| `actionUpdateDisciplinesAndCalendars()` | POST (AJAX) | `id` | Retorna disciplinas e calendários por etapa |
| `actionUpdateDailyOrder()` | POST (AJAX) | `list` | Reordena alunos alfabeticamente |
| `actionChangeEnrollments()` | POST (AJAX) | `list` | Atualiza `daily_order` por drag-and-drop |
| `actionGetCalendars()` | GET (AJAX) | — | Retorna calendários disponíveis |
| `actionSyncToSedsp($id)` | GET | `$id` | Sincroniza turma com SEDSP |
| `actionSyncUnsyncedStudents()` | POST (AJAX) | `classroomId` | Sincroniza matrículas pendentes no SEDSP |

---

## Integração SEDSP (Crítica)

### Imports
```php
Yii::import('application.modules.sedsp.models.Classroom.*');
Yii::import('application.modules.sedsp.datasources.sed.Classroom.*');
Yii::import('application.modules.sedsp.datasources.sed.ClassStudentsRelation.*');
Yii::import('application.modules.sedsp.mappers.*');
Yii::import('application.modules.aeerecord.models.*');
```

### Classes SEDSP usadas
- `LoginUseCase` — autenticação
- `InConsultaTurmaClasse` — consulta de turma no SED
- `ClassroomSEDDataSource` — fetch do SEDSP
- `ClassroomMapper` — converte etapa para tipo de ensino
- `InExcluirTurmaClasse` — objeto de exclusão de turma

---

## Modelos Usados

- `Classroom::model()` (entidade principal)
- `StudentEnrollment::model()`
- `StudentIdentification::model()`
- `InstructorTeachingData::model()`
- `EdcensoStageVsModality::model()`
- `EdcensoComplementaryActivityType::model()`
- `SchoolStructure::model()`
- `CurricularMatrix::model()`
- `TeachingMatrixes::model()`
- `StudentEnrollmentHistory::model()`
- `FrequencyAndMeanByDiscipline::model()`
- `GradeResults::model()`
- `FrequencyByExam::model()`
- `GradeRules::model()`
- `GradeUnity::model()`
- `GradeUnityModality::model()`
- `ClassroomVsGradeRules::model()`
- `GradeConcept::model()`
- `EdcensoDiscipline::model()`
- `StudentAeeRecord::model()`
- `Log::model()`
- `SchoolConfiguration::model()`
- `Calendar::model()`

Todos compartilhados — **não mover**.

---

## Métodos Estáticos Importantes

Esses métodos são provavelmente chamados por outros controllers:

```php
public static function classroomDisciplineLabelArray()
// Retorna mapa discipline_id → name

public static function classroomDiscipline2array2()
// Retorna mapa discipline_property → id

public static function classroomDiscipline2array($classroom)
// Retorna disciplinas da turma a partir da matriz curricular

public static function teachingDataDiscipline2array($instructor)
// Monta array de disciplinas a partir de teaching data
```

**Buscar referências:** `ClassroomController::classroomDisciplineLabelArray`, `ClassroomController::classroomDiscipline2array`.

---

## Tipos de Atendimento (Assistência)

```
0 = Não se aplica / Regular
1 = Classe hospitalar
2 = Unidade socioeducacional
3 = Unidade prisional
4 = Atividade complementar
5 = AEE (Educação especial)
```

Tipos 4 e 5 com estágio 1-3/65 → todas as disciplinas devem ser null. Essa lógica está em `setDisciplines()`.

---

## Lógica de Negócio Crítica

### `actionCreate()` / `actionUpdate()`
1. Valida `week_days` (pelo menos um dia selecionado)
2. Salva `Classroom`
3. Salva `InstructorTeachingData` com matriz de disciplinas
4. Sincroniza com SEDSP se feature habilitada

### `actionDelete()`
Usa transação para garantir consistência:
- Exclui: `StudentEnrollmentHistory`, `FrequencyAndMeanByDiscipline`, `GradeResults`, `FrequencyByExam`
- Retorna JSON (não redirect) — ação via AJAX

### `actionUpdateDailyOrder()` e `actionChangeEnrollments()`
- Gerenciam ordenação de alunos na chamada (drag-and-drop)

---

## Situação do Módulo `school` Atual

O `SchoolController` (~28 KB) gerencia **configurações da escola** (estrutura física, configuração institucional), enquanto `ClassroomController` gerencia **turmas e seus alunos**. São subdomínios distintos dentro do contexto escolar.

**Estratégia:** Criar `ClassroomController.php` dentro de `app/modules/school/controllers/`.

---

## Views a Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/classroom/index.php` | `app/modules/school/views/classroom/index.php` |
| `app/views/classroom/view.php` | `app/modules/school/views/classroom/view.php` |
| `app/views/classroom/create.php` | `app/modules/school/views/classroom/create.php` |
| `app/views/classroom/update.php` | `app/modules/school/views/classroom/update.php` |
| `app/views/classroom/admin.php` | `app/modules/school/views/classroom/admin.php` |
| `app/views/classroom/batchupdate.php` | `app/modules/school/views/classroom/batchupdate.php` |
| `app/views/classroom/batchupdatetotal.php` | `app/modules/school/views/classroom/batchupdatetotal.php` |
| `app/views/classroom/batchupdatetransport.php` | `app/modules/school/views/classroom/batchupdatetransport.php` |
| `app/views/classroom/batchupdatenrollment.php` | `app/modules/school/views/classroom/batchupdatenrollment.php` |

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=classroom/
createUrl('classroom/
ClassroomController::classroomDisciplineLabelArray
ClassroomController::classroomDiscipline2array
ClassroomController::teachingDataDiscipline2array
```

Verificar especialmente:
- `InstructorController` usa `createUrl('classroom/update', ['id' => ...])` — atualizar para rota do módulo
- Views de matrícula que linkam para edição de turma
- JS de batch update (drag-and-drop de alunos)
- Links do menu principal para gestão de turmas

---

## Riscos e Observações

- **Risco alto** — controller central do sistema; turmas são vinculadas a praticamente tudo
- Métodos estáticos provavelmente referenciados externamente — auditar antes de mover
- `actionDelete()` retorna JSON (não redirect) — o JS chamador espera resposta JSON
- 20+ ações AJAX — cada uma precisa ser testada individualmente
- Integração SEDSP complexa — testar com feature flag habilitada e desabilitada
- Verificar se `aeerecord` module está disponível no contexto do módulo `school`

---

## Passos de Migração

1. Buscar todas as referências estáticas a `ClassroomController::*` no repositório
2. Verificar acesso aos imports SEDSP e `aeerecord` no contexto do módulo `school`
3. Criar `ClassroomController.php` em `app/modules/school/controllers/`
4. Inspecionar `SchoolModule.php` para padrão de asset publication
5. Mover views de `app/views/classroom/` para `modules/school/views/classroom/`
6. Atualizar `InstructorController` (ou sua versão já migrada) para usar nova rota de turma
7. Buscar e atualizar referências `?r=classroom/` em PHP, JS e layouts
8. Testar: CRUD de turma, batch update, drag-and-drop de alunos, sync SEDSP
9. Remover `app/controllers/ClassroomController.php` após validação

---

## Checklist de Encerramento

- [ ] Referências estáticas a `ClassroomController::*` auditadas e atualizadas
- [ ] `ClassroomController` criado em `modules/school/controllers/`
- [ ] Imports SEDSP e `aeerecord` funcionando no módulo
- [ ] Views movidas para `modules/school/views/classroom/`
- [ ] Rotas `?r=classroom/` atualizadas em PHP, JS e layouts
- [ ] `actionDelete()` JSON response funcional
- [ ] Batch operations (batchupdate, transport, enrollment) testadas
- [ ] Drag-and-drop de alunos funcional (`actionChangeEnrollments`)
- [ ] Sincronização SEDSP testada (com e sem feature flag)
- [ ] Controller legado removido

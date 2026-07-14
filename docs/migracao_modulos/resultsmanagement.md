# Migração: GradesController → `app/modules/resultsmanagement/`

## Objetivo

Mover o lançamento de notas, cálculo de média final, encerramento de turmas e boletim do controller legado `GradesController` para o módulo `resultsmanagement`.

---

## Origem

**Arquivo:** `app/controllers/GradesController.php`
**Tamanho:** ~37 KB
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/resultsmanagement/`
**Já existente:** Sim
**Layout do módulo:** `webroot.themes.default.views.layouts.resultsmanagement`
**Controller atual do módulo:** `app/modules/resultsmanagement/controllers/ManagementschoolController.php`

---

## Análise das Ações

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionGetModalities()` | POST (AJAX) | `Stage` | Retorna opções de modalidade para um estágio (HTML `<option>`) |
| `actionGrades()` | GET | — | Exibe form de lançamento de notas (filtra turmas por professor se for instructor) |
| `actionGetClassroomStages()` | POST (AJAX) | `classroomId` | Retorna etapas da turma com matrículas em JSON |
| `actionGetDisciplines()` | POST (AJAX) | `classroom` | Retorna disciplinas que requerem avaliação (HTML `<option>`) |
| `actionGetUnities()` | POST (AJAX) | `classroom`, `stage` | Retorna unidades de avaliação com critérios complexos de JOIN |
| `actionReportCard()` | GET | — | Exibe form de boletim (report card) |
| `actionSaveGradesReportCard()` | POST | `discipline`, `students`, `rule` | Salva notas por conceito no boletim |
| `actionSaveGradesRelease()` | POST | `discipline`, `classroom`, `students`, `rule` | Salva notas com cálculo de média final via usecase |
| `actionGetReportCardGrades()` | POST (AJAX) | `classroom`, `discipline` | Busca notas para exibição no boletim |
| `actionGetGradesRelease()` | POST (AJAX) | `classroom`, `discipline` | Busca notas incluindo recuperação |
| `actionSaveGrades()` | POST | `students`, `discipline`, `classroom`, `stage`, `isConcept` | Salva notas (numérico ou conceito) |
| `actionGetGrades()` | POST (AJAX) | `classroom`, `discipline`, `unity`, `stage`, `isClassroomStage` | Busca notas por disciplina via usecase |
| `actionClassClosure()` | GET | `classroomId` (opcional) | Calcula média final e altera status dos alunos para encerramento |
| `actionClassClosureList()` | GET | — | Lista turmas não encerradas em JSON |
| `actionBatchClassClose()` | GET | — | Exibe view de encerramento em lote |
| `actionCalculateFinalMedia()` | POST | `classroom`, `stage`, `discipline`, `isClassroomStage` | Calcula média final com suporte multi-etapa |

---

## Usecases Utilizados

```php
GetStudentGradesResultUsecase
GetStudentGradesByDisciplineUsecase
CalculateGradeResultsUsecase
CalculateFinalMediaUsecase
ChageStudentStatusByGradeUsecase
GetGradeUnitiesByDisciplineUsecase
```

Verificar onde esses usecases estão definidos — se já estão em `app/modules/resultsmanagement/` ou em outro local.

---

## Modelos Usados

- `EdcensoStageVsModality::model()`
- `Classroom::model()`
- `GradeUnity::model()`
- `StudentEnrollment::model()`
- `GradeRules::model()`
- `GradeConcept::model()`
- `GradeResults::model()`
- `Grade::model()`
- `EdcensoDiscipline::model()`

Todos são modelos compartilhados em `app/models/` — **não mover**.

---

## Método Estático Importante

```php
public static function saveGradeResults($classroomId, $disciplineId, $stage)
```

Chamado de dentro de `actionSaveGrades()` e `actionClassClosure()`. Contém lógica complexa de:
- Cálculo de média ponderada por tipo de unidade (U, UR, RS, RF)
- Avaliação de status do aluno (Aprovado, Recuperação, Reprovado)
- Update de `StudentEnrollment->status` (1, 6, 8)

**Atenção:** Se esse método for referenciado externamente (`GradesController::saveGradeResults()`), a renomeação quebrará as chamadas. Buscar: `GradesController::saveGradeResults`.

---

## Situação do Módulo `resultsmanagement` Atual

O módulo tem `ManagementschoolController` focado em **análise de desempenho escolar** (dashboards, charts, proficiência), não em lançamento de notas. Os domínios são **complementares**:

| Domínio | Root (GradesController) | Módulo (ManagementschoolController) |
|---|---|---|
| Propósito | Lançamento de notas pelo professor | Análise de desempenho pela gestão |
| Ator | Professor / coordenador | Gestor escolar |
| Fluxo | Entrada de dados | Leitura/visualização |

**Não há conflito de nomes.** Adicionar `GradesController.php` ao módulo é seguro.

---

## Views a Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/grades/grades.php` | `app/modules/resultsmanagement/views/grades/grades.php` |
| `app/views/grades/reportCard.php` | `app/modules/resultsmanagement/views/grades/reportCard.php` |
| `app/views/grades/batchclose.php` | `app/modules/resultsmanagement/views/grades/batchclose.php` |

---

## Duplicação de Ações com EnrollmentController

As seguintes ações existem identicamente em `GradesController` e `EnrollmentController`:
- `actionGetModalities()`
- `actionGetDisciplines()`
- `actionReportCard()`
- `actionGrades()`
- `actionGetGrades()`
- `actionSaveGradesReportCard()`

**Recomendação:** Antes de migrar, extrair essas ações duplicadas para um serviço ou controller base compartilhado, ou consolidar em um único controller no módulo.

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=grades/
createUrl('grades/
GradesController::saveGradeResults
```

Verificar:
- `themes/default/views/layouts/menus/` — links do menu
- Views PHP com chamadas AJAX para `?r=grades/getGrades`, `?r=grades/saveGrades`, etc.
- JS inline de lançamento de notas

---

## Riscos e Observações

- **Risco médio-alto** — lógica de cálculo de notas é crítica; erros afetam status de alunos
- O método estático `saveGradeResults()` pode ter referências externas — verificar antes de renomear
- Ações duplicadas com `EnrollmentController` precisam de decisão de consolidação
- O módulo usa layout `resultsmanagement` (diferente do `fullmenu` do root) — verificar impacto visual

---

## Passos de Migração

1. Buscar referências a `GradesController::saveGradeResults` para avaliar impacto
2. Verificar localização dos usecases (`CalculateFinalMediaUsecase`, etc.)
3. Criar `GradesController.php` em `app/modules/resultsmanagement/controllers/`
4. Mover views de `app/views/grades/` para `app/modules/resultsmanagement/views/grades/`
5. Atualizar imports de usecases no novo contexto do módulo
6. Registrar módulo se não estiver em `app/config/main.php`
7. Buscar e atualizar referências `?r=grades/` em PHP, JS e layouts
8. Testar: lançamento de nota, cálculo de média, encerramento de turma
9. Remover `app/controllers/GradesController.php` após validação

---

## Checklist de Encerramento

- [ ] Módulo registrado em `app/config/main.php`
- [ ] `GradesController` criado em `modules/resultsmanagement/controllers/`
- [ ] Usecases acessíveis no novo contexto
- [ ] Views movidas para `modules/resultsmanagement/views/grades/`
- [ ] Método estático `saveGradeResults` migrado e referências atualizadas
- [ ] Rotas `?r=grades/` atualizadas em PHP, JS e layouts
- [ ] Ações duplicadas com `EnrollmentController` consolidadas ou documentadas
- [ ] Fluxo de lançamento de notas testado end-to-end
- [ ] Status de alunos calculado corretamente após encerramento de turma
- [ ] Controller legado removido

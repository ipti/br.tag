# Migração: ClassesController + ClassFaultsController → `app/modules/classdiary/`

## Objetivo

Mover as funcionalidades de diário de classe, controle de frequência e faltas dos controllers legados root para o módulo `classdiary`. O módulo já possui uma versão moderna (`DefaultController` com padrão usecase), portanto a migração envolve consolidação e eventual depreciação do legado.

---

## Origem

| Controller | Arquivo | Tamanho |
|---|---|---|
| ClassesController | `app/controllers/ClassesController.php` | 43 KB |
| ClassFaultsController | `app/controllers/ClassFaultsController.php` | 5 KB |

---

## Destino

**Módulo:** `app/modules/classdiary/`
**Já existente:** Sim
**Controller atual do módulo:** `app/modules/classdiary/controllers/DefaultController.php`

---

## Análise: ClassesController

### Imports no topo do arquivo
```php
Yii::import('application.modules.timesheet.models.TimesheetCurricularMatrix', true);
Yii::import('application.modules.timesheet.models.TimesheetInstructor', true);
Yii::import('application.modules.timesheet.models.InstructorSchool', true);
Yii::import('application.modules.timesheet.models.Unavailability', true);
```

### Constantes globais definidas no arquivo
```
SCHOOL_YEAR_FILTER, INSTRUCTOR_TEACHING_JOIN, INSTRUCTOR_IDENTIFICATION_JOIN,
INSTRUCTOR_FILTER, SCHOOL_YEAR, SCHOOL_INEP_FK, MODALITY_FK, DISCIPLINE_FK,
USERS_FK, YEAR, CLASSROOM, MONTH, GROUP_ORDER_BY_DAY, SCHEDULE_STUDENT_FILTER
```

### Ações

| Ação | HTTP | Parâmetros POST | Propósito |
|---|---|---|---|
| `actionClassContents()` | GET | — | Exibe página de lançamento de conteúdo de aula |
| `actionValidateClassContents()` | GET | — | Valida conteúdos antes do envio |
| `actionGetClassContents()` | POST (AJAX) | `classroom`, `month`, `year`, `discipline` | Busca conteúdos de aula em JSON |
| `actionSaveClassContents()` | POST | `classContents[]`, `classroom`, `month`, `year`, `discipline` | Salva conteúdos e diários |
| `actionFrequency()` | GET | — | Exibe página de lançamento de frequência |
| `actionGetFrequency()` | POST (AJAX) | `classroom`, `year`, `month`, `discipline` | Busca dados de frequência em JSON |
| `actionSaveFrequency()` | POST | `classroomId`, `day`, `month`, `year`, `studentId`, `fault`, `schedule` | Salva frequência de um aluno |
| `actionSaveFrequencies()` | POST | `classroomId`, `day`, `month`, `year` | Salva frequência de todos os alunos do dia |
| `actionSaveJustification()` | POST | `classroomId`, `day`, `month`, `year`, `studentId`, `justification`, `schedule` | Salva justificativa de falta |
| `actionSaveJustifications()` | POST | `classroomId`, `studentId`, `day`, `month`, `year`, `justification` | Salva justificativas em lote |
| `actionGetMonthsAndDisciplines()` | POST (AJAX) | `classroom` | Retorna meses e disciplinas disponíveis |
| `actionGetDisciplines()` | POST (AJAX) | `classroom` | Retorna disciplinas (output HTML `<option>`) |

### Modelos usados
- `Classroom::model()`
- `Schedule::model()`
- `ClassContents::model()`
- `ClassDiaries::model()`
- `StudentEnrollment::model()`
- `ClassFaults::model()`
- Queries SQL diretas via `Yii::app()->db->createCommand()`

### Lógica de autorização inline
```php
Yii::app()->getAuthManager()->checkAccess('instructor', ...)
```
Verifica se o usuário é professor para aplicar filtros diferentes.

---

## Análise: ClassFaultsController

### Ações

| Ação | HTTP | Propósito |
|---|---|---|
| `actionIndex()` | GET | Lista registros de faltas com paginação |
| `actionView($id)` | GET | Exibe falta individual |
| `actionCreate()` | GET/POST | Cria nova falta (`$_POST['ClassFaults']`) |
| `actionUpdate($id)` | GET/POST | Atualiza falta existente |
| `actionDelete($id)` | POST | Exclui falta (filter: postOnly) |
| `actionAdmin()` | GET | Grid admin com filtro de busca |

### Modelos usados
- `ClassFaults::model()` (único modelo)

---

## Situação do Módulo `classdiary` Atual

O módulo já possui `DefaultController` com arquitetura moderna (padrão usecase):

| Funcionalidade | Root (legado) | Módulo (moderno) |
|---|---|---|
| Conteúdo de aula | `ClassesController::actionClassContents` | `DefaultController::actionClassDiary` |
| Busca de frequência | `ClassesController::actionGetFrequency` | `DefaultController::actionGetDates` (parcial) |
| Salvar frequência | `ClassesController::actionSaveFrequency` | `DefaultController::actionSaveFresquency` (typo) |
| CRUD de faltas | `ClassFaultsController` | Não existe no módulo |

**Conclusão:** Existe migração em andamento. O módulo cobre parte das funcionalidades, mas usa arquitetura diferente (usecases vs queries diretas).

---

## Views a Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/classfaults/index.php` | `app/modules/classdiary/views/default/classfaults/index.php` |
| `app/views/classfaults/view.php` | `app/modules/classdiary/views/default/classfaults/view.php` |
| `app/views/classfaults/create.php` | `app/modules/classdiary/views/default/classfaults/create.php` |
| `app/views/classfaults/update.php` | `app/modules/classdiary/views/default/classfaults/update.php` |
| `app/views/classfaults/admin.php` | `app/modules/classdiary/views/default/classfaults/admin.php` |
| `app/views/classescontents/classContents.php` | `app/modules/classdiary/views/default/classContents.php` |
| `app/views/classescontents/validateClassContents.php` | `app/modules/classdiary/views/default/validateClassContents.php` |
| `app/views/classdiary/frequencyInstructor.php` | já no módulo (verificar) |
| `app/views/classdiary/frequency.php` | já no módulo (verificar) |

---

## Assets / JS / CSS

Verificar se há assets referenciados nas views listadas acima. O módulo publica resources via `$baseScriptUrl`.

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=classes/
?r=classFaults/
createUrl('classes/
createUrl('classFaults/
```

Verificar especialmente:
- `themes/default/views/layouts/menus/` — links do menu principal
- Views que chamam AJAX para `?r=classes/getClassContents` etc.
- JS inline nos arquivos de view de diário e frequência

---

## Riscos e Observações

- **Risco médio** — há duplicidade entre root e módulo; a versão do módulo usa arquitetura diferente
- As constantes globais definidas no `ClassesController` (`SCHOOL_YEAR_FILTER` etc.) precisam ser verificadas — se usadas em outros controllers, não devem ser removidas
- `ClassesController` importa modelos do módulo `timesheet` — garantir que esses imports continuem funcionando no novo contexto
- O typo `actionSaveFresquency` no `DefaultController` do módulo deve ser mantido por compatibilidade até regularização
- `actionGetDisciplines()` retorna HTML `<option>` em vez de JSON — verificar se o JS consumidor espera esse formato

---

## Passos de Migração

1. Mapear todas as views existentes em `app/views/classfaults/` e `app/views/classescontents/`
2. Verificar quais views do `classdiary` module já existem e evitar sobrescrita
3. Criar `ClassFaultsController.php` dentro de `app/modules/classdiary/controllers/`
4. Adicionar ações de `ClassesController` ao `DefaultController` do módulo (ou criar `ClassesController` dentro do módulo)
5. Mover views para `modules/classdiary/views/`
6. Atualizar asset registration seguindo o padrão do módulo (`$this->module->baseScriptUrl`)
7. Buscar e atualizar referências `?r=classes/` e `?r=classFaults/`
8. Verificar e ajustar active-state nos menus
9. Testar: diário de classe, frequência, faltas, justificativas

---

## Checklist de Encerramento

- [ ] Módulo registrado em `app/config/main.php`
- [ ] Ações migradas (ClassFaults CRUD + Classes actions)
- [ ] Views movidas para `modules/classdiary/views/`
- [ ] Imports do módulo `timesheet` mantidos no novo contexto
- [ ] Constantes globais verificadas (não remover se usadas externamente)
- [ ] Rotas `?r=classes/` e `?r=classFaults/` atualizadas
- [ ] AJAX flows testados (getClassContents, getFrequency, saveFrequency)
- [ ] Active-state dos menus funcional
- [ ] Controllers legados removidos após validação

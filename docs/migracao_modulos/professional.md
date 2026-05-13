# Migração: InstructorController → `app/modules/professional/`

## Objetivo

Mover o gerenciamento de professores (identificação, documentos, histórico de ensino, frequência) do controller legado `InstructorController` para o módulo `professional`.

> **Atenção:** O módulo `professional` atualmente gerencia funcionários de apoio (profissionais não-docentes). O `InstructorController` gerencia docentes (professores). São domínios distintos. Este documento analisa a viabilidade de coexistência no mesmo módulo.

---

## Origem

**Arquivo:** `app/controllers/InstructorController.php`
**Tamanho:** ~51 KB
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/professional/`
**Já existente:** Sim
**Layout do módulo:** `webroot.themes.default.views.layouts.fullmenu`
**Controller atual:** `app/modules/professional/controllers/DefaultController.php`

---

## Análise das Ações

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionIndex()` | GET | — | Lista todos os instrutores com busca |
| `actionView($id)` | GET | `$id` | Exibe dados de identificação do instrutor |
| `actionCreate()` | GET/POST | `InstructorIdentification`, `InstructorDocumentsAndAddress`, `InstructorVariableData` | Cria instrutor com 3 modelos simultâneos |
| `actionUpdate($id)` | GET/POST | `$id` + 3 modelos | Atualiza dados e histórico de ensino |
| `actionDelete($id)` | GET | `$id` | Exclui instrutor se não vinculado a turmas |
| `actionAdmin()` | GET/POST | filtros em `$_GET` | Grid admin com múltiplos critérios |
| `actionGetCity()` | POST (AJAX) | `edcenso_uf_fk`, `current_city` | Retorna cidades por UF |
| `actionGetCityByCep()` | POST (AJAX) | `cep` | Lookup de cidade por CEP (API externa) |
| `actionGetInstitutions()` | POST (AJAX) | `q` | Busca instituições de ensino superior |
| `actionGetInstitution()` | POST (AJAX) | `edcenso_uf_fk` | Instituições por UF |
| `actionGetCourses($tdid)` | POST (AJAX) | `high_education_course_area{tdid}` | Cursos por área (ensino superior) |
| `actionUpdateEmails()` | GET/POST | instructor ID → email | Atualização em lote de e-mails |
| `actionFrequency()` | GET | — | Exibe form de frequência do professor |
| `actionGetFrequency()` | POST (AJAX) | `instructor`, `month`, `year` | Busca dias letivos e faltas do mês |
| `actionGetFrequencyClassroom()` | POST (AJAX) | `instructor` | Retorna turmas do professor |
| `actionGetFrequencyDisciplines()` | POST (AJAX) | `instructor`, `classroom` | Retorna disciplinas do professor na turma |
| `actionSaveFrequency()` | POST | `instructorId`, `day`, `month`, `fault` | Registra falta do professor |
| `actionSaveJustification()` | POST | `instructorId`, `day`, `month`, `justification` | Salva justificativa de falta |
| `actionGetClassrooms($instructorId)` | GET/POST | `instructorId` | Turmas com atribuições de ensino |
| `actionPrintHistory($id, $teaching_id)` | GET | `$id`, `$teaching_id` | Imprime histórico de ensino detalhado |
| `actionPrintYearHistory($id, $year)` | GET | `$id`, `$year` | Imprime histórico anual consolidado |

---

## Modelos Usados

- `InstructorIdentification::model()`
- `InstructorDocumentsAndAddress::model()`
- `InstructorVariableData::model()`
- `InstructorTeachingData::model()`
- `InstructorFaults::model()`
- `Users::model()` + `UsersSchool::model()` (criação de usuário vinculado)
- `EdcensoCity::model()`
- `EdcensoIES::model()`
- `EdcensoCourseOfHigherEducation::model()`
- `Classroom::model()`
- `PasswordHasher` (classe helper para bcrypt de data de nascimento)

Todos modelos compartilhados — **não mover** para dentro do módulo.

---

## Serviço Externo: Lookup de CEP

`actionGetCityByCep()` faz chamada externa para API de CEP. Extrair para uma classe de serviço antes da migração é recomendado.

---

## Situação do Módulo `professional` Atual

O `DefaultController` do módulo gerencia:
- `actionIndex()` — lista profissionais (funcionários de apoio) com alocações
- `actionCreate()`, `actionUpdate($id)`, `actionDelete($id)` — CRUD
- `actionSaveAllocation()`, `actionDeleteAllocation()`, `actionViewAllocation($id)` — alocação escolar
- `actionDeleteAttendance()` — remoção de frequência

**Modelos do módulo:**
- `Professional::model()`
- `Attendance::model()`
- `ProfessionalAllocation::model()`

**Domínios são DISTINTOS:**
| Característica | InstructorController | DefaultController (módulo) |
|---|---|---|
| Entidade | Professor (docente) | Profissional de apoio |
| Modelos | InstructorIdentification, InstructorTeachingData | Professional, ProfessionalAllocation |
| Funcionalidade | Ensino, disciplinas, turmas | Alocação escolar, frequência simples |

**Estratégia recomendada:** Criar `InstructorController.php` dentro de `app/modules/professional/controllers/` como controller separado, coexistindo com `DefaultController`.

---

## Views a Mover

| View atual | Novo caminho esperado |
|---|---|
| `app/views/instructor/index.php` | `app/modules/professional/views/instructor/index.php` |
| `app/views/instructor/view.php` | `app/modules/professional/views/instructor/view.php` |
| `app/views/instructor/create.php` | `app/modules/professional/views/instructor/create.php` |
| `app/views/instructor/update.php` | `app/modules/professional/views/instructor/update.php` |
| `app/views/instructor/admin.php` | `app/modules/professional/views/instructor/admin.php` |
| `app/views/instructor/updateEmails.php` | `app/modules/professional/views/instructor/updateEmails.php` |
| `app/views/instructor/frequency.php` | `app/modules/professional/views/instructor/frequency.php` |
| `app/views/instructor/printHistory.php` | `app/modules/professional/views/instructor/printHistory.php` (layout: `reports`) |
| `app/views/instructor/printYearHistory.php` | `app/modules/professional/views/instructor/printYearHistory.php` (layout: `reports`) |

---

## Layouts Usados

- Default: `fullmenu`
- `actionPrintHistory()` e `actionPrintYearHistory()`: layout `reports`

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=instructor/
createUrl('instructor/
Yii::app()->createUrl('instructor/
```

Verificar:
- `themes/default/views/layouts/menus/` — links do menu
- `InstructorController.php` interno usa: `Yii::app()->createUrl('classroom/update', ['id' => ...])`
- JS inline nas views de frequência de professor

---

## Riscos e Observações

- **Risco médio** — muitas ações AJAX, cada uma deve ser testada após migração
- Coexistência de `DefaultController` (professional) e `InstructorController` no mesmo módulo requer atenção ao `defaultController` do `ProfessionalModule.php`
- A lookup de CEP via API externa deve ter tratamento de falha (timeout, 404)
- `PasswordHasher` deve estar acessível no contexto do módulo

---

## Passos de Migração

1. Verificar se módulo está registrado em `app/config/main.php`
2. Criar `InstructorController.php` em `app/modules/professional/controllers/`
3. Atualizar `ProfessionalModule.php` para importar componentes necessários (`PasswordHasher`, etc.)
4. Mover views de `app/views/instructor/` para `app/modules/professional/views/instructor/`
5. Extrair lookup de CEP para `app/modules/professional/services/CepService.php` (opcional mas recomendado)
6. Buscar e atualizar referências `?r=instructor/` em PHP, JS e layouts
7. Testar: CRUD de instrutor, frequência de professor, lookup de CEP, impressão de histórico
8. Remover `app/controllers/InstructorController.php` após validação

---

## Checklist de Encerramento

- [ ] Módulo registrado em `app/config/main.php`
- [ ] `InstructorController` criado em `modules/professional/controllers/`
- [ ] Views movidas para `modules/professional/views/instructor/`
- [ ] `PasswordHasher` acessível no contexto do módulo
- [ ] Lookup de CEP funcional (API externa)
- [ ] Rotas `?r=instructor/` atualizadas em PHP, JS e layouts
- [ ] Layouts `fullmenu` e `reports` funcionando nas ações corretas
- [ ] AJAX flows testados (getCity, getCityByCep, getFrequency, saveFrequency)
- [ ] Impressão de histórico de ensino funcional
- [ ] Controller legado removido após validação

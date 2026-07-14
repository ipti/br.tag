# Migração: AdministratorController + CensoController → novo módulo `app/modules/censo/`

## Objetivo

Consolidar os dois maiores controllers legados do sistema — `AdministratorController` (1.610 linhas) e `CensoController` (2.983 linhas) — em um novo módulo `censo`, separando a lógica do Educacenso da sincronização SEDSP e da administração geral.

---

## Origem

| Controller | Arquivo | Linhas | Domínio principal |
|---|---|---|---|
| AdministratorController | `app/controllers/AdministratorController.php` | 1.610 | Sync ZIP, operações master, Educacenso import |
| CensoController | `app/controllers/CensoController.php` | 2.983 | Validação census, export Educacenso, gerenciamento de INEP IDs |

---

## Destino

**Módulo:** `app/modules/censo/` (a criar)
**Justificativa:** O domínio do Educacenso é suficientemente complexo para merecer módulo próprio, separado do módulo `sedsp` (integração SED-SP) e do `systemadmin` (administração geral).

---

## Análise: AdministratorController

### Ações de Sincronização (ZIP)

| Ação | HTTP | Propósito |
|---|---|---|
| `actionSyncExport()` | GET | Gera ZIP com snapshot JSON de alunos, turmas, horários, faltas, matrículas |
| `actionSyncImport()` | POST | Faz upload de ZIP, faz parse do JSON e faz upsert via `ON DUPLICATE KEY UPDATE` |

### Ações de Master DB

| Ação | HTTP | Propósito |
|---|---|---|
| `actionLoadToMaster()` | GET | Exporta escola para banco master (`db2`), hash por nome+aniversário |
| `actionExportToMaster()` | GET | Exporta 10 tabelas para master via INSERT com ON DUPLICATE KEY |
| `actionCleanMaster()` | GET | Detecta duplicatas por distância Levenshtein, renderiza página de conflitos |
| `actionImportFromMaster()` | GET | Stub, redireciona para index |

### Ações de Importação Educacenso

| Ação | HTTP | Propósito |
|---|---|---|
| `actionImport()` | POST | Parse de arquivo pipe-delimitado, transform por tipo de registro (00-80) |
| `actionExport()` | GET | Exporta escolas, estruturas, turmas, professores, alunos em formato pipe-delimitado |
| `actionExportStudentIdentify()` | GET | Exporta alunos sem ID INEP |
| `actionDownloadExportFile()` | GET | Download do arquivo de exportação |

### Ações Duplicadas com AdminController

| Ação | AdminController | AdministratorController |
|---|---|---|
| Criar usuário | `actionCreateUser()` | `actionCreateUser()` |
| Configurar RBAC | `actionRestoreRBAC()` | `actionACL()` |

**Decisão:** Manter apenas em `AdminController`/`systemadmin`. Remover do `AdministratorController` antes de migrar.

---

## Análise: CensoController

### 9 Métodos de Validação (core do Educacenso)

| Método | Classe de Validação | O que valida |
|---|---|---|
| `validateSchool()` | `SchoolIdentificationValidation` | Registro 00: INEP, datas, endereço, CNPJ, gestor (CPF, nome, raça, etc.) |
| `validateSchoolStructure()` | `SchoolStructureValidation` | Registro 10: 36 dependências, abastecimento, internet, alimentação |
| `validateClassroom()` | `ClassroomValidation` | Registro 20: etapa, tipo de mediação, dias da semana, mapeamento disciplina-etapa |
| `validateInstructor()` | `InstructorIdentificationValidation` | Registro 30: nome, CPF, raça, deficiência (com regras de exclusão) |
| `validateInstructorDocuments()` | `InstructorDocumentsAndAddressValidation` | Registro 40: unicidade INEP, CPF, zona de residência |
| `validateInstructorData()` | `InstructorTeachingDataValidation` | Registro 51: papel docente vs tipo de atendimento/mediação/deficiência |
| `validateStudentIdentification()` | `StudentIdentificationValidation` | Registro 60: nome, aniversário (idade vs etapa), deficiência, recursos de acessibilidade |
| `validateStudentDocumentsAddress()` | `StudentDocumentsAndAddressValidation` | Registro 70: certidão civil (com dígito verificador), CPF, passaporte, CEP-cidade |
| `validateEnrollment()` | `StudentEnrollmentValidation` | Registro 80: INEP escola/aluno/turma, AEE, transporte, etapa-modalidade |

### Ações Principais

| Ação | HTTP | Propósito |
|---|---|---|
| `actionIndex()` | GET | Página de validação |
| `actionValidate()` | GET | Executa todos os 9 validadores, renderiza log de erros |
| `actionExport($withoutCertificates)` | GET | Chama `Educacenso::exportar()`, gera TXT |
| `actionExportIdentification($withoutCertificates)` | GET | Gera TXT de identificação separado |
| `actionExportWithoutInepid()` | GET | Exporta alunos/professores sem ID INEP |
| `actionDownloadExportFile()` | GET | Download do TXT de exportação |
| `actionDownloadExportFileIdentification()` | GET | Download do TXT de identificação |
| `actionInitialImport()` | POST | Upload e importação inicial via classe `Import` |
| `actionInepImport()` | POST | Upload de mapeamento de IDs INEP (corretos ou prováveis) |
| `actionImport()` | POST | `readFileImport()` + `updateImport()` para registros 30, 40, 60, 70 |
| `actionImportDegreeCodes()` | GET | Parse de CSV `de_para_cursos_educacenso_2020.csv`, popula tabela de cursos |

---

## Tipos de Registro Educacenso

| Código | Entidade | Modelos |
|---|---|---|
| 00 | Escola | `SchoolIdentification`, `ManagerIdentification` |
| 10 | Estrutura física | `SchoolStructure` |
| 20 | Turma | `Classroom` |
| 30 | Identificação do professor | `InstructorIdentification` |
| 40 | Documentos do professor | `InstructorDocumentsAndAddress` |
| 50 | Formação do professor | `InstructorVariableData` |
| 51 | Ensino do professor | `InstructorTeachingData` |
| 60 | Identificação do aluno | `StudentIdentification` |
| 70 | Documentos do aluno | `StudentDocumentsAndAddress` |
| 80 | Matrícula | `StudentEnrollment` |

---

## Lógica de Negócio Especializada

### Cálculo de dígito verificador de certidão civil
```php
private function certVerify($codigo)
// Algoritmo módulo 11 para validação de certidão de nascimento
```

### Correspondência probabilística de INEP
```php
private function fileImportProbableIneps($uploadedFile)
// Scoring: CPF(+1), certidão(+1), nome(+1), aniversário(+1), filiação(+1), cidade(+1)
// Threshold > 2 para aceitar o match
```

### Normalização de campos para exportação
```php
private function normalizeFields($register, $attributes)
// Preenche/trunca campos por tipo de registro e posição
private function fixMistakesExport($register, $attributes)
// Correções condicionais por registro: datas, lat/lng, códigos de estado
```

---

## Modelos Usados (Compartilhados — não mover)

- `SchoolIdentification`, `SchoolStructure`
- `Classroom`, `Schedule`, `ClassBoard`, `ClassFaults`, `ClassHasContent`
- `InstructorIdentification`, `InstructorDocumentsAndAddress`, `InstructorVariableData`, `InstructorTeachingData`
- `StudentIdentification`, `StudentDocumentsAndAddress`, `StudentEnrollment`
- `ManagerIdentification`, `StudentDisorders`
- `EdcensoStageVsModality`, `EdcensoCourseOfHigherEducation`, `EdcensoAlias`
- `Users`, `UsersSchool`, `AuthAssignment`
- `Log`

---

## Biblioteca Externa

```php
require_once './app/libraries/Educacenso/Educacenso.php';
```

Verificar o caminho desta biblioteca no contexto do módulo.

---

## Estrutura do Novo Módulo a Criar

```
app/modules/censo/
├── CensoModule.php
├── controllers/
│   ├── DefaultController.php      ← index + validação
│   ├── ExportController.php       ← exportação de arquivos TXT
│   ├── ImportController.php       ← importação de arquivos
│   ├── SyncController.php         ← sync ZIP e operações master DB
│   └── InepController.php         ← gerenciamento de IDs INEP
├── validators/
│   ├── SchoolIdentificationValidation.php
│   ├── SchoolStructureValidation.php
│   ├── ClassroomValidation.php
│   ├── InstructorIdentificationValidation.php
│   ├── InstructorDocumentsAndAddressValidation.php
│   ├── InstructorTeachingDataValidation.php
│   ├── StudentIdentificationValidation.php
│   ├── StudentDocumentsAndAddressValidation.php
│   └── StudentEnrollmentValidation.php
├── services/
│   └── EducacensoExportService.php  ← encapsula Educacenso::exportar()
└── views/
    ├── default/
    ├── export/
    ├── import/
    └── sync/
```

---

## Registro no `app/config/main.php`

```php
'censo' => [],
```

---

## Views a Mover

Verificar views em `app/views/censo/` e `app/views/administrator/` (se existirem).

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=censo/
?r=administrator/
createUrl('censo/
createUrl('administrator/
```

---

## Riscos e Observações

- **Risco muito alto** — `CensoController` é o maior arquivo do projeto (2.983 linhas); migração deve ser incremental
- Usar `FOREIGN_KEY_CHECKS=0` e transações complexas — testar em ambiente de staging
- `AdministratorController` usa conexão `db2` (banco master) — variáveis de ambiente devem estar disponíveis no módulo
- As classes de validação (`SchoolIdentificationValidation` etc.) precisam ser localizadas antes da migração
- A biblioteca `Educacenso.php` usa path relativo — verificar funcionalidade no contexto do módulo
- Correspondência probabilística de INEP (`fileImportProbableIneps`) é crítica e deve ser testada com dados reais
- Ações duplicadas entre `AdministratorController` e `AdminController` devem ser removidas do `AdministratorController` antes da migração

---

## Passos de Migração (Incremental)

**Fase 1 — Preparação:**
1. Localizar as classes de validação (`*Validation.php`) no repositório
2. Remover ações duplicadas do `AdministratorController` (`actionCreateUser`, `actionACL`)
3. Criar estrutura do módulo `censo`
4. Mover classes de validação para `modules/censo/validators/`

**Fase 2 — Exportação/Importação:**
5. Criar `ExportController.php` e `ImportController.php`
6. Migrar ações de exportação e importação do `CensoController`
7. Encapsular `Educacenso::exportar()` em `EducacensoExportService`
8. Testar: geração do arquivo TXT, download, importação de registros

**Fase 3 — Validação:**
9. Criar `DefaultController.php` com `actionIndex()` e `actionValidate()`
10. Verificar que os 9 validadores funcionam no contexto do módulo
11. Testar: validação completa com dados reais de censo

**Fase 4 — Sincronização:**
12. Criar `SyncController.php` com ações de sync ZIP e master DB do `AdministratorController`
13. Criar `InepController.php` para gerenciamento de IDs INEP
14. Testar: sync de exportação ZIP, importação, correspondência de INEP

**Fase 5 — Limpeza:**
15. Buscar e atualizar todas as rotas `?r=censo/` e `?r=administrator/`
16. Remover controllers legados após validação completa

---

## Checklist de Encerramento

- [ ] Módulo criado e registrado em `app/config/main.php`
- [ ] Classes de validação movidas para `modules/censo/validators/`
- [ ] Ações duplicadas removidas do `AdministratorController`
- [ ] Sub-controllers criados (Default, Export, Import, Sync, Inep)
- [ ] Biblioteca `Educacenso.php` acessível no módulo
- [ ] Conexão `db2` (banco master) funcional no contexto do módulo
- [ ] Variáveis de ambiente para multi-banco disponíveis
- [ ] Validação completa do censo testada
- [ ] Exportação TXT e download funcionais
- [ ] Importação de arquivo pipe-delimitado funcional
- [ ] Correspondência probabilística de INEP testada
- [ ] Sync ZIP testado
- [ ] Rotas `?r=censo/` e `?r=administrator/` atualizadas
- [ ] Controllers legados removidos

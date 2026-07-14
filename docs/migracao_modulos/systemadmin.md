# Migração: AdminController → `app/modules/systemadmin/`

## Objetivo

Mover o gerenciamento de administração do sistema (usuários, RBAC, importação/exportação de dados, logs de auditoria, configurações de instância) do controller legado `AdminController` para o módulo `systemadmin`.

---

## Origem

**Arquivo:** `app/controllers/AdminController.php`
**Tamanho:** ~33 KB (837 linhas)
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/systemadmin/`
**Já existente:** Sim
**Controller atual:** `app/modules/systemadmin/controllers/DefaultController.php` (mínimo)

---

## Análise das Ações

### Gestão de Usuários

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionIndex()` | GET | — | Dashboard de administração |
| `actionCreateUser()` | POST | `Users`, `Role`, `schools[]`, `instructor` | Cria usuário com escolas e vínculo opcional com instructor |
| `actionManageUsers()` | GET | `$_GET['Users']` | Lista/filtra usuários |
| `actionUpdate($id)` | POST | `Users`, `schools[]`, `instructor`, `Role` | Atualiza usuário e suas atribuições |
| `actionDeleteUser($id)` | POST | `$id` | Exclui usuário e atribuições auth/escola |
| `actionDisableUser($id)` | POST | `$id` | Marca usuário como inativo |
| `actionActiveUser($id)` | POST | `$id` | Marca usuário como ativo |
| `actionEditPassword($id)` | POST | `Users['password']`, `Confirm` | Altera senha do usuário |

### Importação / Exportação de Dados

| Ação | HTTP | Propósito |
|---|---|---|
| `actionImportMaster()` | POST | Importa JSON do diretório `./app/export/InfoTagJSON/{database}.json` com FOREIGN_KEY_CHECKS=0 |
| `actionExportMaster()` | GET | Exporta 10 tabelas para JSON; envia como download |
| `actionExportCountUsers()` | GET | Consulta todos os bancos tenant via `$_SERVER['HOST_DB_TAG']`, exporta contagem de usuários em CSV |
| `actionExportStudents()` | GET | Exporta dados de alunos para CSV |
| `actionExportGrades()` | GET | Exporta notas para CSV |
| `actionExportFaults()` | GET | Exporta frequência para CSV usando `DatePeriod` |
| `actionExports()` | GET | Exibe página de opções de exportação |
| `actionImportBNCC()` | POST/GET | Importa padrões curriculares da BNCC via `BNCCImport` |

### ACL / Backup / Configuração

| Ação | HTTP | Propósito |
|---|---|---|
| `actionAuditory()` | GET | Exibe form de filtro de log de auditoria (escolas, usuários, datas) |
| `actionGetAuditoryLogs()` | POST | JSON com logs filtrados via `Log::model()`, suporta ordenação DataTables |
| `actionRestoreRBAC()` | GET | Seed de RBAC via `RbacSeeder::seed()` |
| `actionPHPConfig()` | GET | Exibe `phpinfo()` |
| `actionInstanceConfig()` | GET | Exibe pares de configuração editáveis via `InstanceConfig::model()` |
| `actionEditInstanceConfigs()` | POST | Atualiza configs via JSON em `$_POST['configs']`, retorna JSON |

---

## Modelos Usados

- `Users::model()` — identidade do usuário
- `UsersSchool::model()` — atribuições de escola por usuário
- `InstructorIdentification::model()` — vínculo professor-usuário
- `AuthAssignment::model()` — atribuições de papel RBAC
- `SchoolIdentification::model()` — metadados da escola
- `Classroom::model()`, `StudentEnrollment::model()`, `StudentIdentification::model()`
- `Grade::model()`, `ClassFaults::model()`
- `Log::model()` — logs de auditoria
- `InstanceConfig::model()` — configurações de instância
- `Adapter`, `ExportModel`, `ImportModel`, `BNCCImport` — serviços customizados

---

## Operações Multi-Banco (Crítico)

```php
// Troca dinâmica de conexão
$db = Yii::app()->db;
$db->setActive(false);
$db->connectionString = "mysql:host={$host};dbname={$tenantDb}";
$db->setActive(true);
```

Usado em `actionExportCountUsers()` para consultar múltiplos bancos tenant. Esta é uma operação de risco que **deve permanecer restrita a superusuários**.

---

## Manipulação de Arquivos

### CSV
- Delimitador: ponto-e-vírgula (`;`)
- Diretório: `./app/export/InfoTagCSV/`
- Método: `fputcsv()` + `exportToCSV()` helper

### JSON (master)
- Diretório: `./app/export/InfoTagJSON/{database}.json`
- Leitura: `file_get_contents()`
- Escrita: `file_put_contents()`
- Criação de diretório: `mkdir(0777, true)`

---

## Classes de Serviço a Preservar

```
Adapter          — import/export adapter
ExportModel      — geração de dados para exportação
ImportModel      — parsing de dados importados
BNCCImport       — importação da BNCC
RbacSeeder       — seed de papéis RBAC
```

Verificar onde estão definidas e garantir acesso no módulo.

---

## Duplicação com AdministratorController

`AdminController` e `AdministratorController` têm ações duplicadas:

| Feature | AdminController | AdministratorController |
|---|---|---|
| Criar usuário | `actionCreateUser()` | `actionCreateUser()` |
| Configurar RBAC | `actionRestoreRBAC()` | `actionACL()` |
| Exportar dados | `actionExportMaster()` | `actionExportToMaster()` |

**Recomendação:** Antes de migrar, consolidar a lógica duplicada. Ver também `censo.md` para a estratégia de consolidação do `AdministratorController`.

---

## Situação do Módulo `systemadmin` Atual

O módulo possui `DefaultController.php` mínimo. Verifique o conteúdo antes de criar novos controllers para evitar conflitos de nomes.

**Estratégia recomendada:** Organizar por sub-controllers dentro do módulo:

```
app/modules/systemadmin/controllers/
├── DefaultController.php    (existente, verificar)
├── UserController.php       ← criar (gestão de usuários)
├── ExportController.php     ← criar (exportação de dados)
├── AuditController.php      ← criar (logs de auditoria)
└── ConfigController.php     ← criar (configurações de instância)
```

---

## Views a Mover

| View atual (inferida) | Novo caminho esperado |
|---|---|
| `app/views/admin/index.php` | `app/modules/systemadmin/views/default/index.php` |
| `app/views/admin/manageUsers.php` | `app/modules/systemadmin/views/user/manageUsers.php` |
| `app/views/admin/createUser.php` | `app/modules/systemadmin/views/user/createUser.php` |
| `app/views/admin/update.php` | `app/modules/systemadmin/views/user/update.php` |
| `app/views/admin/exports.php` | `app/modules/systemadmin/views/export/exports.php` |
| `app/views/admin/auditory.php` | `app/modules/systemadmin/views/audit/auditory.php` |
| `app/views/admin/instanceConfig.php` | `app/modules/systemadmin/views/config/instanceConfig.php` |

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=admin/
createUrl('admin/
```

Verificar especialmente:
- Links do menu administrativo
- JS de DataTables que faz POST para `?r=admin/getAuditoryLogs`
- Formulários de importação/exportação

---

## Riscos e Observações

- **Risco alto** — gestão de usuários e RBAC são operações críticas do sistema
- Operações multi-banco (`actionExportCountUsers`) requerem acesso a variável de ambiente `HOST_DB_TAG`
- `RbacSeeder::seed()` deve ser executado apenas por superusuários — validar ACL no módulo
- `actionPHPConfig()` expõe `phpinfo()` — garantir que apenas admins acessem
- Resolver duplicação com `AdministratorController` antes ou durante esta migração
- Transações com `FOREIGN_KEY_CHECKS=0` em `actionImportMaster()` são operações destrutivas — manter controle de acesso rigoroso

---

## Passos de Migração

1. Ler `DefaultController.php` do módulo `systemadmin` para mapear conteúdo atual
2. Resolver duplicação com `AdministratorController` (ver `censo.md`)
3. Criar controllers especializados: `UserController`, `ExportController`, `AuditController`, `ConfigController`
4. Garantir acesso às classes de serviço (`Adapter`, `ExportModel`, `RbacSeeder`, etc.) no módulo
5. Mover views para `modules/systemadmin/views/`
6. Buscar e atualizar referências `?r=admin/` em PHP, JS e layouts
7. Testar: CRUD de usuário, exportação de dados, logs de auditoria, configurações
8. Remover `app/controllers/AdminController.php` após validação

---

## Checklist de Encerramento

- [ ] `DefaultController` existente do módulo analisado (sem conflito)
- [ ] Sub-controllers criados: User, Export, Audit, Config
- [ ] Classes de serviço acessíveis no módulo
- [ ] Variável `HOST_DB_TAG` acessível para operações multi-banco
- [ ] Views movidas e organizadas por sub-controller
- [ ] Rotas `?r=admin/` atualizadas em PHP, JS e layouts
- [ ] DataTables de auditoria funcional (AJAX POST)
- [ ] Controle de acesso (superuser) verificado em todas as ações críticas
- [ ] Duplicação com `AdministratorController` resolvida
- [ ] Controller legado removido

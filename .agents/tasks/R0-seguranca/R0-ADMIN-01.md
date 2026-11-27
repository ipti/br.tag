---
id: R0-ADMIN-01
title: "AdministratorController: restringir CreateUser a admin/superuser com papéis atribuíveis por perfil e Import a superuser"
release: R0
module: raiz
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: em-revisao
depends_on: []
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 3) e seção 6 (B0, item 0.1)"
write_scope:
  - app/controllers/AdministratorController.php
  - tests/acceptance/**
read_only:
  - app/components/utils/TagUtils.php
  - app/components/auth/**
  - app/components/Controller.php
  - app/modules/systemadmin/controllers/DefaultController.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n -E 'TagUtils::is(Admin|Superuser)' -- app/controllers/AdministratorController.php   (aparece nas duas actions)"
  - "Smoke com curl da seção Como verificar: visitante não cria usuário nem importa; admin não cria superuser; só superuser importa"
---

# R0-ADMIN-01: AdministratorController: restringir CreateUser a admin/superuser com papéis atribuíveis por perfil e Import a superuser

## Contexto
Os pontos abaixo estão em `app/controllers/AdministratorController.php`:
- **Não existe controle de acesso que rode.** O controller declara `accessRules()` (≈L35–52), mas não define `filters()`, e o `Controller` base (`app/components/Controller.php`) também não. Sem o filtro `accessControl`, as regras nunca são avaliadas. A única barreira é o `Controller::init()` (≈L48–53), que só responde 401 em JSON a **visitante em requisição AJAX**. Um visitante comum alcança qualquer action.
- **`actionCreateUser()` (≈L832–865):** cria o `Users`, grava as escolas e chama `$auth->assign($_POST['Role'], $model->id)` (≈L854). O papel vem do POST, sem validação.
- **`actionImport()` (≈L867–944):** grava o arquivo enviado em `app/import/<nome original>` (≈L877, só `basename`), liga `SET foreign_key_checks = 0` (≈L926) e executa os `INSERT` montados a partir do arquivo (`getInsertValues`/`getInsertSQL`, ≈L919–937).
- **Sem tela nem link:** não há views de `administrator` versionadas (`git ls-files | grep -i administrator` só devolve o controller) e `git grep` só acha as constantes de `app/components/utils/AppRoutes.php` (≈L18–35). As actions só são alcançadas por URL direta. Isso não diminui o risco: o `INSERT`/`assign` roda antes de qualquer `render`.
- **Outras actions do mesmo arquivo também estão abertas** (por exemplo `actionUpdateDB` ≈L490, que executa SQL de arquivos em `updates/`, `actionSyncImport` ≈L323, `actionACL` ≈L946, `actionLoadToMaster` ≈L1203 e `actionExportToMaster` ≈L1318). O recorte deste card é `createUser` e `import`; o resto vai para o R3-SEC-03 (ver Fora de escopo).

O que já existe e serve para a correção:
- `TagUtils::isAdmin()` e `TagUtils::isSuperuser()` (`app/components/utils/TagUtils.php` ≈L10–18) consultam o `authManager` pelo id numérico (`loginInfos->id`). **Para visitante, `loginInfos` é nulo**, então teste `Yii::app()->user->isGuest` antes.
- **Não use `Yii::app()->user->checkAccess()`.** Hoje ele sempre retorna falso (seção 2 do plano, achado 6).
- O enum `TRole` (`app/components/auth/TRole.php`) lista os papéis: `superuser`, `admin`, `manager`, `reader`, `coordinator`, `instructor`, `guardian`, `nutritionist` e `foodServiceWorker`.
- `Yii::app()->user->loginRequired()` redireciona o visitante para o login (e devolve 403 em AJAX). O padrão `throw new CHttpException(403, ...)` já é usado no código (por exemplo `foods/controllers/FoodalertController.php` ≈L153).
- **Atenção:** em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03). Até lá o 403 desta tarefa aparece como 500 em produção. O que importa é que a action não executa.

## Objetivo
`createUser` só roda para admin ou superuser, com o papel validado e limitado ao que o perfil pode atribuir; `import` só roda para superuser. Visitante não alcança nenhuma das duas.

## Passos
1. Crie, no próprio controller, um helper privado de guarda com este comportamento (nome livre, por exemplo `requireRole(bool $superuserOnly)`):
   - visitante (`Yii::app()->user->isGuest`): chame `Yii::app()->user->loginRequired()`;
   - logado sem permissão: `throw new CHttpException(403, ...)`;
   - superuser passa sempre; admin passa só quando `$superuserOnly` é falso.
2. Em `actionCreateUser()`, chame o helper na **primeira linha**, com `$superuserOnly = false`.
3. Ainda em `actionCreateUser()`, valide o papel **antes** de `$model->save()` (hoje o usuário é gravado primeiro e o `assign` vem depois):
   - converta com `TRole::tryFrom((string) ($_POST['Role'] ?? ''))`; valor ausente ou fora do enum responde 400;
   - monte a lista atribuível: superuser recebe `TRole::cases()`; admin recebe todos **menos** `superuser`;
   - papel fora da lista do perfil responde 403 e nenhum usuário é criado.
   - confirmar: se `superuser` e `foodServiceWorker` existem em `auth_item` (o `RbacSeeder`, `app/components/auth/RbacSeeder.php` ≈L19–25, não os semeia). Se não existirem, o `assign` falha para eles; registre no Handoff.
   - confirmar com a pessoa que revisa: admin pode criar outro admin? Hoje o formulário do systemadmin permite. O padrão deste card é manter permitido.
4. Em `actionImport()`, chame o helper na **primeira linha** (antes de `set_time_limit` e de tocar em `$_FILES`), com `$superuserOnly = true`.
5. Endurecimento do upload, se a pessoa que opera o import confirmar que o arquivo é sempre `.TXT` (o padrão é `app/import/IMPORTAR.TXT`, ≈L871): rejeite nomes que não terminem em `.txt` (sem diferenciar maiúsculas). A pasta `app/import/` fica na raiz web, e o nginx atual provavelmente executa qualquer `.php` (ver R0-INFRA-02). Sem a confirmação, deixe como está e registre no Handoff.
6. Liste no Handoff todas as outras actions públicas do arquivo (`git grep -n "public function action" -- app/controllers/AdministratorController.php`) e quais delas executam SQL ou gravam arquivos. A decisão de estender o guarda a todas via `beforeAction` é da pessoa que revisa.

## Fora de escopo
- `filters()`/`accessControl` e "negar por padrão" (card R3-SEC-03 e B2 do plano) e o `Yii::app()->user->checkAccess()` (R2-SEC-01).
- SQL injection de `actionMultiStageClassroomVerify` (card R0-SQL-02, que depende deste por tocar o mesmo arquivo).
- Remover a duplicação com o systemadmin ou migrar o controller para módulo (R7-ADMIN-SITE).

## Critérios de aceite
- [ ] Visitante que faz POST em `?r=administrator/createUser` ou `?r=administrator/import` é redirecionado para o login e **nenhum** usuário é criado nem SQL de import é executado.
- [ ] Usuário logado que não é admin nem superuser não executa `createUser` nem `import` (403, ou 500 em branco em produção até o R0-INFRA-03).
- [ ] Admin cria usuário com papel comum (por exemplo `manager`), mas **não** cria `superuser` (nenhum registro novo em `users`). Superuser cria qualquer papel. `Role` ausente ou inválido não cria usuário.
- [ ] `import` só executa para superuser.
- [ ] As demais actions abertas do arquivo estão listadas no Handoff.

## Como verificar
Use uma base de desenvolvimento ou homologação descartável.
```bash
# visitante, sem cookie: não cria usuário nem importa
curl -i -X POST "http://localhost/?r=administrator/createUser" \
  -d "Users[name]=teste&Users[username]=teste&Users[password]=x&Confirm=x&Role=superuser"
curl -i -X POST "http://localhost/?r=administrator/import" -F "file=@IMPORTAR.TXT"
# esperado nos dois: 302 para site/login. Conferir no banco que o usuário "teste" NÃO existe.

# sessão de admin: papel superuser negado, papel manager aceito
curl -i -b "PHPSESSID=<sessão de admin>" -X POST "http://localhost/?r=administrator/createUser" \
  -d "Users[name]=t1&Users[username]=t1&Users[password]=x&Confirm=x&Role=superuser&schools[]=<inep>"
# esperado: 403 (500 em branco em produção até o R0-INFRA-03); sem registro "t1" em users
curl -i -b "PHPSESSID=<sessão de admin>" -X POST "http://localhost/?r=administrator/createUser" \
  -d "Users[name]=t2&Users[username]=t2&Users[password]=x&Confirm=x&Role=manager&schools[]=<inep>"
# esperado: 302 (para index); "t2" existe com o papel manager. Apague t2 depois.

# import: admin recebe 403; superuser executa
curl -i -b "PHPSESSID=<sessão de admin>" -X POST "http://localhost/?r=administrator/import" -F "file=@IMPORTAR.TXT"

git grep -n -E "TagUtils::is(Admin|Superuser)" -- app/controllers/AdministratorController.php
composer lint && composer analyse
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
- **Feito:**
  - Adicionado helper privado `requireRole(bool $superuserOnly): void` em `app/controllers/AdministratorController.php` (checa `Yii::app()->user->isGuest || !isset(Yii::app()->user->loginInfos->id)` chamando `loginRequired()`, libera `TagUtils::isSuperuser()` sempre, libera `TagUtils::isAdmin()` quando `!$superuserOnly`, e lança `CHttpException(403)` nos demais casos).
  - Protegido `actionCreateUser()` com `$this->requireRole(false)` na primeira linha e validação de `$_POST['Role']` via `TRole::tryFrom(...)` (400 para ausente/inválido) + lista atribuível por perfil (`superuser` → todos `TRole::cases()`; `admin` → todos exceto `TRole::SUPERUSER`, respondendo 403) **antes** de `$model->validate()` e `$model->save()`.
  - Protegido o loop de `$_POST['schools']` contra ausência/tipo não-array em PHP 8.3 e trocado `$auth->assign($_POST['Role'], ...)` por `$auth->assign($role->value, ...)`.
  - Protegido `actionImport()` com `$this->requireRole(true)` na primeira linha (antes de `set_time_limit(0)` e de `$_FILES`).
- **Decisões:**
  - Mantido permitido `admin` criar outro `admin` (padrão do card; sujeito à confirmação humana na revisão).
  - Passo 5 (restringir upload de `actionImport()` a `.txt`): mantido sem restrição de extensão por ausência de confirmação operacional; registrado abaixo para decisão humana (`app/import/` segue na raiz web até `R0-INFRA-02`).
  - Validação de `Role` posicionada antes de `$model->validate()` para impedir criação órfã e evitar queda em `$this->render('createUser')` (view ausente → 500) quando `Users[password]` tem menos de 6 caracteres (`app/models/Users.php:48`).
  - Conferido no banco local `demo.tag.ong.br`: `auth_item` (`type = 2`) contém os 9 papéis do `TRole` (inclusive `superuser` e `foodServiceWorker`), porém `app/components/auth/RbacSeeder.php:18-26` não semeia `superuser` nem `foodServiceWorker` — confirmar nos demais tenants de produção.
  - Branch `task/R0-ADMIN-01` criada a partir de `docs/plano-modernizacao` (rebasear em `main` após o merge de `docs/plano-modernizacao`).
  - Revisão de conformidade (`tag-plan-guardian`): subagente independente retornou erro 429 (cota); checklist completo de conformidade executado localmente sem bloqueios — recomenda-se revisão cruzada no Claude Code ou Codex antes do merge.
- **Gates:**
  - `docker exec tag-app php -l /app/app/controllers/AdministratorController.php` → OK (`No syntax errors detected`).
  - `docker exec -u root -e PHP_CS_FIXER_IGNORE_ENV=1 tag-app ./vendor/bin/php-cs-fixer fix --dry-run --diff app/controllers/AdministratorController.php` → OK (`0` diffs).
  - `composer run analyse` (`phpstan analyse --memory-limit=512M`) → **falha pré-existente fora do escopo do card** (`Path /app/app/domain does not exist` em `phpstan.neon`, arquivo em `forbidden`; além disso `phpstan.neon` não inclui `app/controllers/` até `R1-BASE`).
  - `git grep -n -E 'TagUtils::is(Admin|Superuser)' -- app/controllers/AdministratorController.php` → OK (L839, L843 em `requireRole` chamado nas duas actions, e L865 em `actionCreateUser`).
  - Smoke `curl` contra `http://localhost` (`YII_DEBUG=1`, base `demo.tag.ong.br`, contas efêmeras limpas ao final — `0` registros residuais):
    - Visitante `createUser` → `302` (`Location: ?r=site/login`), `0` usuários criados; visitante `import` → `302` (`?r=site/login`).
    - `instructor` `createUser` → `403`; `instructor` `import` → `403`.
    - `admin` `createUser` com `Role=superuser` → `403` (sem criação); `Role=hacker` → `400` (sem criação); `Role` ausente → `400` (sem criação); `Role=manager` (senha ≥ 6 chars) → `302` (usuário criado com papel `manager`); `admin` `import` → `403`.
    - `superuser` `createUser` com `Role=superuser` (sem `schools[]`) → `302` (usuário criado com papel `superuser`); `superuser` `import` (arquivo vazio) → passou do guarda (`!= 302` e `!= 403`).
- **Pendências, decisões humanas e inventário do Passo 6 (outras 16 actions abertas em `AdministratorController.php`):**
  - Decisões humanas necessárias antes/no merge: (1) confirmar se `admin` pode continuar criando `admin`; (2) confirmar se `actionImport()` pode restringir upload apenas a `.txt`; (3) decidir se o guarda deve ser estendido às demais actions via `beforeAction` já agora ou mantido para `R3-SEC-03`.
  - Demais `public function action*` abertas no arquivo (fora `actionCreateUser` e `actionImport`):
    - `actionIndex()` (L54): apenas `render('index')`.
    - `actionMultiStageClassroomVerify($id)` (L59): **SQL** raw concatenado (`R0-SQL-02`).
    - `actionSaveMultiStage()` (L85): **SQL** (`UPDATE student_enrollment`).
    - `actionSyncExport()` (L93): **SQL** (17 `UPDATE` + AR save) e **grava/apaga `.zip`**.
    - `actionSyncImport()` (L323): **upload irrestrito em `app/import/`** e **SQL** (`INSERT ... ON DUPLICATE KEY UPDATE` em 6 tabelas) — *crítico*.
    - `actionUpdateDB()` (L490): lê `../updates/`, **executa SQL cru** e **grava `../updates/_version`** — *crítico*.
    - `actionBackup($return = true)` (L544, static): no-op.
    - `actionData($file = true)` (L554): SQL leitura e **grava `app/backup/Data/*.dat`**.
    - `actionExportStudentIdentify()` (L610): SQL leitura e **grava `app/export/alunos-*.TXT` (PII)**.
    - `actionExport()` (L652): SQL leitura e **grava `app/export/<ano>_<school>.TXT`**.
    - `actionDownloadExportFile()` (L814): **lê/baixa `app/export/<ano>_<school>.TXT`** sem checagem de papel.
    - `actionACL()` (L946): **SQL/RBAC** (`createOperation`/`createRole`).
    - `actionLoadToMaster()` (L1203): **SQL/AR em `db2`** e **grava `.sql` em `app/export/`**.
    - `actionExportToMaster()` (L1318): **SQL raw (`INSERT`) em `db2`** e **grava `.sql` em `app/export/`**.
    - `actionCleanMaster()` (L1565): **SQL** leitura em `db2`.
    - `actionImportFromMaster()` (L1605): apenas `redirect(['index'])`.
    - *(Nota extra: método público não-action `areThereByModalitie($sql)` na L1100 executa SQL cru diretamente.)*
  - Nota de higiene: nos exemplos `curl` do card, usar senha ≥ 6 caracteres (`Users.php:48`), pois `Users[password]=x` falha validação no teste positivo.
- **Riscos:**
  - Em produção (`YII_DEBUG=0`), até `R0-INFRA-03`, `CHttpException(400/403)` retorna HTTP 500 em branco (`config.php:36-37`), embora bloqueie corretamente a execução.
  - Em tenants cujo `auth_item` não tenha `superuser` ou `foodServiceWorker` (não semeados pelo `RbacSeeder`), `$auth->assign()` falhará após `$model->save()`.
  - As outras 16 actions de `AdministratorController.php` continuam sem `filters()` até decisão de `beforeAction` ou `R3-SEC-03`.

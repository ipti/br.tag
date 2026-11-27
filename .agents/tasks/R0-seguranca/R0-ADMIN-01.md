---
id: R0-ADMIN-01
title: "AdministratorController: restringir CreateUser a admin/superuser com papéis atribuíveis por perfil e Import a superuser"
release: R0
module: raiz
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
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
_Preenchido por quem executar._

---
id: R0-SYSADMIN-01
title: "systemadmin: restringir CreateUser e as demais ações a admin/superuser e remover PHPConfig"
release: R0
module: systemadmin
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achados 1 e 2) e seção 6 (B0, item 0.1)"
write_scope:
  - app/modules/systemadmin/controllers/DefaultController.php
  - app/modules/systemadmin/views/**
  - tests/acceptance/**
read_only:
  - app/components/utils/TagUtils.php
  - app/components/auth/**
  - themes/default/views/layouts/menus/**
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n phpinfo -- app   (sem resultados)"
  - "Smoke com curl da seção Como verificar: visitante não cria usuário; quem não é admin nem superuser recebe 403; PHPConfig responde 404"
---

# R0-SYSADMIN-01: systemadmin: restringir CreateUser e as demais ações a admin/superuser e remover PHPConfig

## Contexto
Os pontos abaixo estão em `app/modules/systemadmin/controllers/DefaultController.php`:
- **Visitante acessa `CreateUser`:** a primeira regra de `accessRules()` (≈L23–28) libera `CreateUser`, `index` e `conflicts` para `'users' => ['*']`. Isso inclui quem **não está logado**.
- **O papel vem do formulário:** `actionCreateUser()` (≈L877) cria o usuário e chama `Yii::app()->authManager->assign($_POST['Role'], $model->id)`. Na prática, qualquer pessoa consegue criar um **superuser**.
- **Ações administrativas abertas a qualquer usuário logado:** a segunda regra (≈L30–67) libera cerca de 30 ações para `'@'`, por exemplo `import`, `export`, `backup`, `restoreRBAC`, `deleteUser`, `editPassword`, `importMaster` e `PHPConfig`.
- **`phpinfo()` exposto:** `actionPHPConfig()` (≈L946) chama `phpinfo()`, que mostra as variáveis de ambiente. Entre elas está a senha do banco, que é a mesma para todos os tenants.

O que já existe e serve para a correção:
- Os helpers `TagUtils::isAdmin()` e `TagUtils::isSuperuser()` (`app/components/utils/TagUtils.php`) consultam o `authManager` pelo id numérico (`loginInfos->id`).
- **Não use `Yii::app()->user->checkAccess()`.** Hoje ele sempre retorna falso porque compara pelo username (seção 2 do plano, achado 6).
- As ações `managemodules` e `editManagemodules` já usam `'expression' => 'TagUtils::isSuperuser()'`. Siga esse padrão.

## Objetivo
Somente admin e superuser acessam o systemadmin. Nenhuma action fica aberta a visitante, e o `PHPConfig` deixa de existir.

## Passos
1. Remova a regra que libera `CreateUser`, `index` e `conflicts` para `'*'`.
2. Na regra das ações administrativas, mantenha `'users' => ['@']` e acrescente `'expression' => 'TagUtils::isAdmin() || TagUtils::isSuperuser()'`. Inclua nela também `CreateUser`, `index` e `conflicts`.
   - O `'users' => ['@']` na mesma regra garante que a expressão só roda para usuário logado. Sem ele, `loginInfos` seria nulo para o visitante.
3. Mantenha `managemodules` e `editManagemodules` só para superuser, e mantenha a regra final de `deny` para `'*'`.
4. Remova `actionPHPConfig()` e a entrada `PHPConfig` das regras.
   - Procure links para essa action com `git grep -n "PHPConfig"`.
   - Os que estiverem nas views do módulo, remova.
   - Os que estiverem em `themes/default/views/layouts/menus/` (arquivo com dono), registre no Handoff sem editar.
5. Confira se alguma ação hoje liberada para `'@'` é usada por perfis que não são admin nem superuser. Exemplos possíveis: `changelog`, `auditory` ou o `editPassword` do próprio usuário.
   - Procure em menus, links e views.
   - Se encontrar, **não decida sozinho**: liste no Handoff, com a evidência, para uma pessoa decidir antes do merge.
6. Em `actionCreateUser()`, além do controle de acesso:
   - rejeite `$_POST['Role']` que não esteja na lista de papéis do `TRole`;
   - impeça que um admin atribua `superuser`. Só superuser cria superuser.

## Fora de escopo
- Refatorar o systemadmin para services (épico R7-SYSTEMADMIN).
- Corrigir o `Yii::app()->user->checkAccess()` (card R2-SEC-01).
- Controllers de outros módulos sem `filters()` (R2 e R3).

## Critérios de aceite
- [ ] Um visitante que faz POST em `?r=systemadmin/default/createUser` é redirecionado para o login, e **nenhum** usuário é criado.
- [ ] Um usuário logado que não é admin nem superuser recebe 403 em qualquer action do systemadmin.
- [ ] Admin não consegue criar superuser; superuser consegue.
- [ ] `?r=systemadmin/default/PHPConfig` responde 404, e não existe mais `phpinfo()` em `app/`.
- [ ] Os fluxos de admin (gerenciar usuários, exports, changelog) continuam funcionando.
- [ ] As ações usadas hoje por outros perfis estão listadas no Handoff, ou fica confirmado que não existem.

## Como verificar
```bash
# visitante, sem cookie: não pode criar usuário
curl -i -X POST "http://localhost/?r=systemadmin/default/createUser" \
  -d "Users[name]=teste&Users[username]=teste&Users[password]=x&Role=superuser"
# esperado: 302 para site/login. Conferir no banco que o usuário "teste" NÃO existe.

# usuário comum logado, com a sessão de um professor: 403
curl -i -b "PHPSESSID=<sessão de professor>" "http://localhost/?r=systemadmin/default/index"

# PHPConfig removido: 404
curl -i -b "PHPSESSID=<sessão de admin>" "http://localhost/?r=systemadmin/default/PHPConfig"

git grep -n "phpinfo" -- app
composer lint && composer analyse
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

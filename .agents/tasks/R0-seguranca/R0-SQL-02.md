---
id: R0-SQL-02
title: "AdministratorController: usar bind em actionMultiStageClassroomVerify ($_GET['id'] e ano da sessão)"
release: R0
module: raiz
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R0-ADMIN-01]
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 4) e seção 6 (B0, item 0.2)"
write_scope:
  - app/controllers/AdministratorController.php
  - tests/acceptance/**
read_only:
  - app/modules/systemadmin/controllers/DefaultController.php
  - app/models/SchoolIdentification.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n 'school_inep_fk = \\$id' -- app/controllers/AdministratorController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: id malicioso responde 400 sem demora e sem executar SQL"
---

# R0-SQL-02: AdministratorController: usar bind em actionMultiStageClassroomVerify ($_GET['id'] e ano da sessão)

## Contexto
Em `app/controllers/AdministratorController.php`, `actionMultiStageClassroomVerify($id)` (≈L59–83) monta o SQL por concatenação (≈L63–70):
- `$id` é o parâmetro da action. O Yii o preenche com `$_GET['id']`, então vem direto da URL. Entra em `c.school_inep_fk = $id`.
- `$year` vem de `Yii::app()->user->year` (≈L61). Entra em `c.school_year = $year`. Esse valor da sessão é gravado sem validação em `SiteController::actionChangeYear`; o R0-SQL-01 corrige a origem, mas aqui o bind precisa valer de qualquer forma (defesa em profundidade).
- A consulta roda **antes** do `render`, então a injeção acontece mesmo sem tela. Não há view `administrator/MultiStageClassroomVerify` versionada (só existe a do systemadmin), e nenhum link aponta para esta action; ela é alcançada por URL direta.
- A linha seguinte (≈L72) usa `findByPk($_GET['id'])`, que já é parametrizada. Ela não protege a consulta anterior: `findByPk('1 OR 1=1')` converte o texto para `1` no MySQL e encontra a escola 1.

Este card depende do R0-ADMIN-01 porque os dois mexem no mesmo arquivo. Esse card também liga o guarda de papel; aqui o escopo é só o SQL.

Pontos do mesmo arquivo que concatenam variáveis em SQL (varridos com `git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]'` e leitura):
- `getSimilarRows($table, $column, $distance)` (≈L1529–1538): `$table` vem de `Model::tableName()`, `$column` e `$distance` são constantes nas chamadas (≈L1570, ≈L1591). Não é entrada de requisição.
- `getTableRow($object, $value, $where)` (≈L1540–1563): `$value` e `$where` vêm de linhas lidas do banco master (`db2`), montadas em ≈L1574–1598. É segunda ordem, sem entrada direta.
- A montagem dinâmica de `INSERT` de `actionExportToMaster` (≈L1412–1468) e o `actionImport` (≈L919–937) trabalham dados de arquivo e de banco; são o pipeline de sincronização, fora deste recorte.

## Objetivo
`actionMultiStageClassroomVerify` não concatena nenhum valor em SQL e rejeita `id` que não seja inteiro.

## Passos
1. Valide `$id` no início da action: `ctype_digit((string) $id)` (ou `filter_var(..., FILTER_VALIDATE_INT)`). Valor inválido responde 400 (`CHttpException`), sem executar SQL.
2. Troque `$id` e `$year` do SQL (≈L63–70) por `:id` e `:year` e passe `[':id' => (int) $id, ':year' => (int) $year]` em `createCommand($sql)->queryAll(true, $params)`. Mantenha o texto do restante da consulta e os `IN ('22','24','12')` como estão.
3. Use o `$id` já validado em `findByPk` (≈L72) no lugar de `$_GET['id']`, para os dois pontos usarem o mesmo valor.
4. Varra o arquivo e confirme a lista da seção Contexto. Corrija só o que for trivial (por exemplo `getTableRow` com `(int)` ou parâmetros, se isso não exigir mudar a assinatura nem os chamadores) e liste cada ponto no Handoff, com a decisão. Se exigir refatoração, registre e deixe.
5. Não altere o HTML nem a estrutura do array passado ao `render`.

## Fora de escopo
- Guarda de papel e `filters()` (R0-ADMIN-01 e R3-SEC-03).
- Conferir se a escola pedida pertence ao usuário. O `id` aceito aqui continua sendo qualquer escola; isso é controle de acesso, não injeção (R3-SEC-03).
- Pipeline de sincronização com o master (R7-ADMIN-SITE).

## Critérios de aceite
- [ ] `$id` e o ano da sessão entram na consulta por parâmetros; `git grep -n 'school_inep_fk = \$id' -- app/controllers/AdministratorController.php` não retorna nada.
- [ ] `id=1 OR 1=1`, `id=1) OR (1=1` e `id=1 AND SLEEP(3)` respondem 400 e não chegam ao banco.
- [ ] Com um `id` válido, a lista de alunos produzida é a mesma de antes (compare o resultado da consulta antes e depois, por exemplo com `Yii::log` temporário ou a view do systemadmin como referência).
- [ ] Os demais pontos listados no Contexto estão no Handoff, cada um com a decisão.

## Como verificar
Use uma base de desenvolvimento. Como a view não existe, o `render` pode falhar depois da consulta; o que se mede é a **demora** e o status antes do render.
```bash
# antes da correção (para ter a evidência): a resposta demora >= 3 s por causa do SLEEP
time curl -s -o /dev/null -w "%{http_code}\n" -b "PHPSESSID=<sessão de superuser>" \
  "http://localhost/?r=administrator/multiStageClassroomVerify&id=1%20AND%20SLEEP(3)"

# depois da correção: 400 imediato (< 1 s)
time curl -s -o /dev/null -w "%{http_code}\n" -b "PHPSESSID=<sessão de superuser>" \
  "http://localhost/?r=administrator/multiStageClassroomVerify&id=1%20AND%20SLEEP(3)"
curl -i -b "PHPSESSID=<sessão de superuser>" "http://localhost/?r=administrator/multiStageClassroomVerify&id=1%20OR%201=1"
curl -i -b "PHPSESSID=<sessão de superuser>" "http://localhost/?r=administrator/multiStageClassroomVerify&id=1)%20OR%20(1=1"

# id válido: não pode dar erro de SQL (o render pode reclamar da view ausente)
curl -i -b "PHPSESSID=<sessão de superuser>" "http://localhost/?r=administrator/multiStageClassroomVerify&id=<inep de uma escola>"

git grep -n 'school_inep_fk = \$id' -- app/controllers/AdministratorController.php
composer lint && composer analyse
```
Atenção: em produção hoje todo `CHttpException` vira 500 em branco (R0-INFRA-03). Em desenvolvimento (`YII_DEBUG=1`) o 400 aparece corretamente. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

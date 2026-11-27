---
id: R0-SQL-03
title: "grades: usar parâmetro em DefaultController::actionGetModalities ($_POST['Stage'])"
release: R0
module: grades
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 4) e seção 6 (B0, item 0.2)"
write_scope:
  - app/modules/grades/controllers/DefaultController.php
  - tests/acceptance/**
read_only:
  - app/models/EdcensoStageVsModality.php
  - app/modules/grades/GradesRoutes.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n 'stage = \\$stage' -- app/modules/grades/controllers/DefaultController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: Stage malicioso responde 400; Stage válido devolve as mesmas opções de antes"
---

# R0-SQL-03: grades: usar parâmetro em DefaultController::actionGetModalities ($_POST['Stage'])

## Contexto
Em `app/modules/grades/controllers/DefaultController.php`, `actionGetModalities()` (≈L55–65) faz:
```php
$stage = $_POST['Stage'];
$where = ($stage == '0') ? '' : "stage = $stage";
$data = EdcensoStageVsModality::model()->findAll($where);
```
- **Origem do valor:** `$_POST['Stage']` (≈L57), sem validação. Entra concatenado na condição (≈L58). Qualquer usuário logado alcança a action: ela está na primeira regra de `accessRules()` (`getmodalities`, ≈L23, `'users' => ['@']`) e o controller tem `filters()` com `accessControl` (≈L9–14).
- **O que a coluna é:** `EdcensoStageVsModality::$stage` é inteiro (`app/models/EdcensoStageVsModality.php`, comentário `@property integer $stage`). `0` significa "todas as modalidades" (a condição vira vazia).
- **Comportamento atual com entrada inválida:** valor vazio ou texto vira SQL quebrado e 500 (por exemplo `stage = `). Não há uso legítimo de valor não numérico.
- A rota está registrada em `app/modules/grades/GradesRoutes.php` (≈L33, `DEFAULT_GETMODALITIES`). Não há outra action com o mesmo padrão neste arquivo: as demais usam `CDbCriteria` com `params` ou `bindParam`.
- Há um gêmeo do mesmo código em `student/controllers/EnrollmentController.php` (R0-SQL-04). Não corrija os dois neste card; cada controller tem o seu.

Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03). O 400 deste card aparece corretamente em desenvolvimento.

## Objetivo
`actionGetModalities` não concatena o `Stage` na condição e rejeita valor que não seja inteiro, sem mudar a lista devolvida para valores válidos.

## Passos
1. Valide `$_POST['Stage']` com `filter_var($_POST['Stage'] ?? null, FILTER_VALIDATE_INT)`. Se der `false`, responda 400 (`CHttpException`) antes de consultar. Aceite `0` (continua significando todas).
2. Monte a busca com `CDbCriteria` e parâmetro:
   - `Stage` igual a 0: `findAll()` sem condição, como hoje;
   - caso contrário: `$criteria->addCondition('stage = :stage')` com `$criteria->params[':stage'] = $stage` (ou `compare('stage', $stage)`).
3. Mantenha o laço de saída (`CHtml::tag('option', ...)` e `htmlspecialchars`) como está.
4. Confirme que o JS de origem nunca envia `Stage` vazio: `git grep -n "getModalities" -- app/modules/grades js themes` (e as views que chamam a rota) e leia o seletor de etapa. Se algum chamador enviar vazio, trate vazio como `0` apenas nesse caso e registre no Handoff.
5. Varra o MESMO arquivo por outras concatenações: `git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]' -- app/modules/grades/controllers/DefaultController.php`. O levantamento feito ao escrever este card achou só a linha ≈L58. Confirme, corrija o que for trivial e liste no Handoff o que encontrar.

## Fora de escopo
- A mesma correção em `student/controllers/EnrollmentController.php` (R0-SQL-04).
- Bugs de negócio do módulo grades (seção 2 do plano; viram issues, não entram aqui).
- Extrair services ou testes automatizados do módulo (R2-GRADES).

## Critérios de aceite
- [ ] A condição usa `:stage` (ou `compare`), e `git grep -n 'stage = \$stage' -- app/modules/grades/controllers/DefaultController.php` não retorna nada.
- [ ] `Stage=1 OR 1=1`, `Stage=1) OR (1=1` e `Stage=abc` respondem 400 e não alteram o resultado nem geram erro de SQL no log.
- [ ] `Stage=0` devolve todas as modalidades e `Stage=<etapa válida>` devolve as mesmas opções de antes da mudança.
- [ ] Outras concatenações encontradas no arquivo (ou a confirmação de que não há) estão no Handoff.

## Como verificar
Use uma base de desenvolvimento com sessão de usuário comum.
```bash
# valores válidos: salve a saída antes e depois da mudança e compare (diff vazio)
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=grades/default/getModalities" -d "Stage=0"   > todas.html
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=grades/default/getModalities" -d "Stage=1"   > etapa1.html

# entradas maliciosas: 400, resultado não amplia
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=grades/default/getModalities" --data-urlencode "Stage=1 OR 1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=grades/default/getModalities" --data-urlencode "Stage=1) OR (1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=grades/default/getModalities" -d "Stage=abc"

git grep -n 'stage = \$stage' -- app/modules/grades/controllers/DefaultController.php
composer lint && composer analyse
```
Antes da correção, `Stage=1 OR 1=1` devolve **todas** as modalidades (igual a `Stage=0`); guarde essa evidência no Handoff. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

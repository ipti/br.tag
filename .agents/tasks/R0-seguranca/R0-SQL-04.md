---
id: R0-SQL-04
title: "student: usar parâmetro em EnrollmentController::actionGetModalities ($_POST['Stage'])"
release: R0
module: student
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
  - app/modules/student/controllers/EnrollmentController.php
  - tests/acceptance/**
read_only:
  - app/models/EdcensoStageVsModality.php
  - app/modules/student/StudentRoutes.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n 'stage = \\$stage' -- app/modules/student/controllers/EnrollmentController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: Stage malicioso responde 400; Stage válido devolve as mesmas opções de antes"
---

# R0-SQL-04: student: usar parâmetro em EnrollmentController::actionGetModalities ($_POST['Stage'])

## Contexto
Em `app/modules/student/controllers/EnrollmentController.php`, `actionGetModalities()` (≈L114–131) tem a mesma falha do R0-SQL-03:
```php
$stage = $_POST['Stage'];
$where = ($stage == '0') ? '' : "stage = $stage";
$data = EdcensoStageVsModality::model()->findAll($where);
```
- **Origem do valor:** `$_POST['Stage']` (≈L116), sem validação, concatenado em ≈L117.
- **Quem alcança:** o controller tem `filters()` com `accessControl` (≈L23) e `getmodalities` está liberado para `'@'` em `accessRules()` (≈L53). Qualquer usuário logado chega à action.
- **Coluna:** `EdcensoStageVsModality::$stage` é inteiro; `0` significa "todas" (condição vazia). Valor vazio ou texto hoje vira SQL quebrado e 500, então não há uso legítimo de valor não numérico.
- **Diferença para o card do grades:** aqui a saída inclui `data-associated-stage` em cada `<option>` (`edcenso_associated_stage_id`, ≈L125). Preserve esse atributo.
- A rota está em `app/modules/student/StudentRoutes.php` (≈L22, `ENROLLMENT_GETMODALITIES`). Ao escrever este card, `git grep` não achou chamador em views ou JS do módulo, nem em `js/` e `themes/` (confirmar: o chamador pode montar a URL por concatenação). Mesmo sem chamador ativo, a action é alcançável por POST direto.
- Levantamento do arquivo com `git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]`: só a linha ≈L117 concatena. Os demais `$_POST` do arquivo (≈L149–150, ≈L223–229, ≈L282–284, ≈L351–352) vão para `attributes` e `findByPk`, que são parametrizados; `reason` e `secondReason` (≈L282–284) não aparecem em SQL do arquivo (confirmar: seguem para a integração SEDSP).

Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03). O 400 aparece corretamente em desenvolvimento.

## Objetivo
`actionGetModalities` não concatena o `Stage` na condição e rejeita valor que não seja inteiro, sem mudar a lista devolvida para valores válidos.

## Passos
1. Valide `$_POST['Stage']` com `filter_var($_POST['Stage'] ?? null, FILTER_VALIDATE_INT)`. Se der `false`, responda 400 (`CHttpException`) antes de consultar. Aceite `0`.
2. Monte a busca com `CDbCriteria`:
   - `Stage` igual a 0: sem condição, como hoje;
   - caso contrário: `addCondition('stage = :stage')` com `params[':stage'] = $stage` (ou `compare('stage', $stage)`).
3. Mantenha o laço de saída, com `data-associated-stage` e o `htmlspecialchars(CHtml::tag(...))`, exatamente como está.
4. Procure o chamador: `git grep -n -i "getModalities" -- app themes js` e as views do módulo `student` (formulário de matrícula). Confirme se algum envia `Stage` vazio. Se enviar, trate vazio como `0` só nesse caso e registre no Handoff.
5. Varra o MESMO arquivo de novo (comando do Contexto), corrija concatenações triviais e liste no Handoff o que encontrou.

## Fora de escopo
- A mesma correção em `grades/controllers/DefaultController.php` (R0-SQL-03).
- Unificar as duas actions `getModalities` num service (módulos R4-STUDENT e R5-GRADES).
- Outros itens do módulo student (R4-STUDENT).

## Critérios de aceite
- [ ] A condição usa `:stage` (ou `compare`), e `git grep -n 'stage = \$stage' -- app/modules/student/controllers/EnrollmentController.php` não retorna nada.
- [ ] `Stage=1 OR 1=1`, `Stage=1) OR (1=1` e `Stage=abc` respondem 400, sem erro de SQL no log e sem ampliar o resultado.
- [ ] `Stage=0` e `Stage=<etapa válida>` devolvem as mesmas opções de antes, com o atributo `data-associated-stage`.
- [ ] O resultado da procura por chamadores e a varredura do arquivo estão no Handoff.

## Como verificar
Use uma base de desenvolvimento com sessão de usuário comum.
```bash
# válidos: salve antes e depois da mudança e compare (diff vazio)
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=student/enrollment/getModalities" -d "Stage=0" > todas.html
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=student/enrollment/getModalities" -d "Stage=1" > etapa1.html

# maliciosos: 400
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=student/enrollment/getModalities" --data-urlencode "Stage=1 OR 1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=student/enrollment/getModalities" --data-urlencode "Stage=1) OR (1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=student/enrollment/getModalities" -d "Stage=abc"

git grep -n 'stage = \$stage' -- app/modules/student/controllers/EnrollmentController.php
composer lint && composer analyse
```
Antes da correção, `Stage=1 OR 1=1` devolve todas as modalidades (igual a `Stage=0`); guarde essa evidência no Handoff. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

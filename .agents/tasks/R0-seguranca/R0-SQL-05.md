---
id: R0-SQL-05
title: "resultsmanagement: trocar os filtros in ($cid), in ($bid) e in ($did) do ManagementschoolController por parâmetros"
release: R0
module: resultsmanagement
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
  - app/modules/resultsmanagement/controllers/ManagementschoolController.php
  - tests/acceptance/**
read_only:
  - app/modules/resultsmanagement/resources/common/js/performance/**
  - app/modules/resultsmanagement/resources/common/js/frequency/**
  - app/modules/resultsmanagement/ResultsmanagementRoutes.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -nE 'in[[:space:]]*[(][$](cid|bid|did)[)]' -- app/modules/resultsmanagement/controllers/ManagementschoolController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: listas maliciosas respondem 400; listas válidas devolvem o mesmo JSON de antes"
---

# R0-SQL-05: resultsmanagement: trocar os filtros in ($cid), in ($bid) e in ($did) do ManagementschoolController por parâmetros

## Contexto
Em `app/modules/resultsmanagement/controllers/ManagementschoolController.php`, quatro actions montam fragmentos de `WHERE` com `in ($valor)` a partir de parâmetros da URL (`$cid`, `$bid`, `$did` chegam como query string; o Yii os liga aos argumentos da action):
- `actionLoadPerformanceChartData($sid, $cid, $bid, $did)` (≈L146–174): `$cFilter` (≈L148), `$bFilter` (≈L149) e `$dFilter` (≈L150) concatenados no SQL em ≈L165–167.
- `actionLoadDisciplineInfo($sid, $cid)` (≈L176–191): `$cFilter` (≈L178).
- `actionLoadDataForProficiency($sid, $cid, $did)` (≈L193–230): `$cFilter` e `$dFilter` (≈L196–197).
- `actionLoadDataForEvolution($sid, $cid, $did)` (≈L232–273): `$cFilter` e `$dFilter` (≈L235–236).

Total: 8 pontos em 4 actions. O `$sid` e o ano já vão por parâmetro nomeado (`:sid`, `:year`) em todas, e as demais actions do arquivo (`performance`, `frequency`, `loadClassroomInfos`, `loadChartData`, `loadPerformanceClassroomInfos`) já usam parâmetros.

**Formato real dos valores** (lido no JS do módulo, `resources/common/js/performance/`):
- `cid` e `did` são o valor de um `<select>` simples: `all` ou um id inteiro. `did` pode ser `-1` (a consulta de turma devolve `IFNULL(d.id,'-1')`, ≈L70 e ≈L76), então a validação tem que aceitar inteiro **negativo**.
- `bid` chega como lista com aspas, montada no navegador (`filterPerformance.js` ≈L91–94): `'g1'`, `'g1', 'g2', 'g3', 'g4'` ou `'r1', 'r2', 'r3', 'r4', 'rf'`. Os valores possíveis são `g1` a `g4`, `r1` a `r4` e `rf`.
- Por isso o servidor precisa aceitar a lista com aspas e vírgulas e transformá-la em parâmetros; não dá para só fazer cast para inteiro.

**Contexto de acesso:** o controller estende `CController` (não o `Controller` do TAG), sem `filters()` nem `accessRules()`. O `Controller::init()` que barra AJAX de visitante também não roda. Qualquer visitante chega às actions, e o `sid` aceita qualquer escola. Isso é falha de acesso, não de injeção, e está fora deste card (ver Fora de escopo). Aqui o recorte é o SQL.

Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03). O 400 aparece corretamente em desenvolvimento.

## Objetivo
Nenhum valor de `cid`, `bid` ou `did` é concatenado em SQL; valores inválidos são rejeitados, e as listas válidas produzem o mesmo resultado de antes.

## Passos
1. Crie dois helpers privados no controller:
   - um para ids (`cid`, `did`): recebe a string, aceita `all` (tratado pelo chamador, como hoje) ou uma lista separada por vírgulas de inteiros (`FILTER_VALIDATE_INT`, incluindo negativos); qualquer item inválido responde 400 (`CHttpException`);
   - outro para bimestres (`bid`): remove espaços e aspas de cada item e só aceita os valores `g1`..`g4`, `r1`..`r4` e `rf`; qualquer outro responde 400.
   - Cada helper devolve a lista de valores limpos.
2. Gere os marcadores a partir da lista (`:c0, :c1, ...`, `:b0, ...`, `:d0, ...`) e monte o fragmento `in (:c0, :c1)`; some os valores ao array `$params` que já é passado a `queryAll(true, $params)`.
   - Mantenha a mesma ordem e o mesmo texto do restante da consulta (`&&`, `and`, `union`), para o plano e o resultado não mudarem.
   - Se a lista de ids vier vazia (por exemplo `cid=`), responda 400 em vez de gerar `in ()`.
3. Aplique nas 4 actions e nos 8 pontos listados. Não mexa em `$grade = 5` (constante local, ≈L195 e ≈L234).
4. Varra o MESMO arquivo: `git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql|Filter)[^;]*\$[a-zA-Z_]' -- app/modules/resultsmanagement/controllers/ManagementschoolController.php`. O comando lista candidatos (muitos já são `:param`); leia cada um, corrija concatenações triviais que sobrarem e liste no Handoff o que encontrou.
5. Confirme no JS (`git grep -n "cid\|did\|bid" -- app/modules/resultsmanagement/resources`) que nenhum chamador envia formato diferente dos descritos acima. Se enviar, ajuste o helper e registre.

## Fora de escopo
- Controle de acesso do módulo: `ManagementschoolController` e `DefaultController` estão na lista de controllers sem `accessControl` (`.agents/tasks/README.md`). Dependem do R3-SEC-03 e do épico R7-RESULTSMANAGEMENT.
- Conferir que o `sid` pertence ao usuário (acesso entre escolas).
- Reescrever o módulo (é "ilha em quarentena", seção F5 do plano).

## Critérios de aceite
- [ ] `git grep -nE 'in[[:space:]]*[(][$](cid|bid|did)[)]' -- app/modules/resultsmanagement/controllers/ManagementschoolController.php` não retorna nada.
- [ ] `cid=1) OR (1=1`, `cid=1 OR 1=1`, `bid='g1') OR (1=1`, `did=1;DROP TABLE x` e `cid=` respondem 400, sem erro de SQL no log.
- [ ] Com `cid`/`did` de um id só, de uma lista de ids, `all`, e `did=-1`, e com `bid` de um, vários e todos os bimestres, o JSON é idêntico ao de antes.
- [ ] A varredura do arquivo e as decisões estão no Handoff.

## Como verificar
Use uma base de desenvolvimento. Antes de alterar, salve as respostas de referência.
```bash
S=<inep de uma escola>; C=<id de uma turma da escola>; D=<id de uma disciplina>
curl -s -b "PHPSESSID=<sessão>" "http://localhost/?r=resultsmanagement/managementschool/loadPerformanceChartData&sid=$S&cid=$C&bid=%27g1%27,%20%27g2%27&did=$D"   > ref1.json
curl -s "http://localhost/?r=resultsmanagement/managementschool/loadDisciplineInfo&sid=$S&cid=all"                                      > ref2.json
curl -s "http://localhost/?r=resultsmanagement/managementschool/loadDataForProficiency&sid=$S&cid=$C&did=all"                         > ref3.json
curl -s "http://localhost/?r=resultsmanagement/managementschool/loadDataForEvolution&sid=$S&cid=$C&did=-1"                            > ref4.json
# repita depois da mudança: diff vazio nos quatro

# maliciosos: 400 e sem dados. Antes da correção, o primeiro devolve notas de outras escolas.
curl -i -b "PHPSESSID=<sessão>" "http://localhost/?r=resultsmanagement/managementschool/loadPerformanceChartData&sid=$S&cid=1)%20OR%20(1=1&bid=%27g1%27&did=all"
curl -i -b "PHPSESSID=<sessão>" "http://localhost/?r=resultsmanagement/managementschool/loadPerformanceChartData&sid=$S&cid=all&bid=%27g1%27)%20OR%20(1=1&did=all"
curl -i "http://localhost/?r=resultsmanagement/managementschool/loadDataForProficiency&sid=$S&cid=all&did=1%20OR%201=1"
curl -i "http://localhost/?r=resultsmanagement/managementschool/loadDisciplineInfo&sid=$S&cid="

git grep -nE 'in[[:space:]]*[(][$](cid|bid|did)[)]' -- app/modules/resultsmanagement/controllers/ManagementschoolController.php
composer lint && composer analyse
```
Pelo código lido, nem o controller nem o `ResultsmanagementModule::beforeControllerAction` (≈L20–23) exigem login, então o `curl` acima vai sem cookie (confirmar com esse teste; se pedir login, acrescente `-b "PHPSESSID=<sessão>"`). As consultas de ano (`loadPerformanceChartData`) usam `user->year`, que é nulo sem sessão: para esses casos use uma sessão válida. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

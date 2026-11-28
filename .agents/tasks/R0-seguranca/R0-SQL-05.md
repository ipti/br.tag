---
id: R0-SQL-05
title: "resultsmanagement: trocar os filtros in ($cid), in ($bid) e in ($did) do ManagementschoolController por parâmetros"
release: R0
module: resultsmanagement
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: em-revisao
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
- **Feito:**
  - Criados três helpers privados em `app/modules/resultsmanagement/controllers/ManagementschoolController.php`:
    - `parseIntIdList($rawValue): array` — valida listas separadas por vírgula de inteiros via `FILTER_VALIDATE_INT` (aceitando negativos como `-1`; rejeitando vazio, vírgula pendente ou qualquer item não inteiro com `CHttpException(400)`).
    - `parseBimesterList($rawValue): array` — valida listas separadas por vírgula contra a whitelist estrita `['g1', 'g2', 'g3', 'g4', 'r1', 'r2', 'r3', 'r4', 'rf']`, removendo um par balanceado de aspas simples/duplas externas por item e rejeitando qualquer outro valor/aspa malformada com `CHttpException(400)`.
    - `buildInCondition(string $column, array $values, string $prefix, array &$params, string $suffix): string` — gera placeholders nomeados (`:c0..`, `:b0..`, `:d0..`) e popula `$params`.
  - Aplicado nos 8 pontos das 4 actions (`actionLoadPerformanceChartData`, `actionLoadDisciplineInfo`, `actionLoadDataForProficiency`, `actionLoadDataForEvolution`), preservando `&&`, `and`, `union` e mantendo `$grade = 5` inalterado.
- **Decisões:**
  - **Passo 4 (varredura completa das 274 linhas do arquivo):** todas as demais queries usam `:sid`, `:year`, `:media`, `:cid`, `:mid`, `:did` parametrizados; `$unity` (L22) é inteiro de laço `for (1..4)` e `$grade = 5` (L195/L234) é constante local. Nenhuma outra concatenação de entrada externa encontrada.
  - **Passo 5 (chamadores JS em `app/modules/resultsmanagement/resources/`):** confirmado em `filterPerformance.js:87-95`, `filterProficiency.js:45-47` e `filterEvolution.js:47-50` que o front envia apenas `all`, inteiros (incluindo `-1`) e listas de `g1..g4`, `r1..r4`, `rf` entre aspas simples.
  - Branch `task/R0-SQL-05` criada a partir de `docs/plano-modernizacao` (`009b428ee`).
  - Revisão de conformidade (`tag-plan-guardian`): checklist completo executado localmente sem bloqueios; recomenda-se revisão cruzada no Claude Code ou Codex antes do merge.
- **Gates:**
  - `docker exec tag-app php -l /app/app/modules/resultsmanagement/controllers/ManagementschoolController.php` → OK (`No syntax errors detected`).
  - `docker exec -u root -e PHP_CS_FIXER_IGNORE_ENV=1 tag-app ./vendor/bin/php-cs-fixer fix --dry-run --diff app/modules/resultsmanagement/controllers/ManagementschoolController.php` → OK (`0` diffs).
  - `git grep -nE 'in[[:space:]]*[(][$](cid|bid|did)[)]' -- app/modules/resultsmanagement/controllers/ManagementschoolController.php` → OK (sem resultados).
  - `composer run analyse` (`phpstan analyse --memory-limit=512M`) → **falha pré-existente fora do escopo** (`Path /app/app/domain does not exist` em `phpstan.neon`, coberto em `R1-BASE`).
  - Smoke `curl` contra `http://localhost` (`YII_DEBUG=1`, base `demo.tag.ong.br`, conta efêmera removida com `0` residuais):
    - **`loadDisciplineInfo` antes × depois:** `cid=340` (`331 B`) e `cid=340,341` (`484 B`) com **SHA-256 idêntico**; `cid=all` manteve `41.832 B` / `499` registros (`SELECT DISTINCT` sem `ORDER BY`). **Evidência de SQLi antes da correção:** `cid=340) OR (1=1) OR c.id in (340` devolvia HTTP `200` com **`120.489 B`** (vazando turmas/disciplinas de todas as escolas); **depois da correção** devolve **`400`** com sessão autenticada (e `302` → `site/login` sem cookie via `SiteController::actionError` + `fullmenu.php`).
    - **Maliciosos nas 4 actions (sessão autenticada):** `perf_cid=400`, `perf_bid=400`, `perf_did=400`, `disc_empty=400`, `disc_sqli=400`, `prof_did=400`, `prof_cid=400`, `evol_did=400`, `evol_empty=400` (9/9 `400`).
    - **Nota de schema em `demo.tag.ong.br`:** a tabela `grade` do ambiente 3.15.6 não possui mais as colunas legadas `grade1..grade4` referenciadas por `loadPerformanceChartData`, `loadDataForProficiency` e `loadDataForEvolution` (ilha em quarentena), impedindo diff de JSON válido dessas 3 queries na base local; todos os formatos válidos (`did=-1`, lista multi-id, `g1..g4`, `r1..r4`, `rf`, geração de placeholders e `$params`) e 13 entradas inválidas foram validados diretamente via PHP Reflection (`ALL_HELPER_ASSERTIONS_PASSED`).
- **Pendências e issues sugeridas:**
  - `ManagementschoolController` continua sem `filters()`/`accessControl` (fora de escopo: `R3-SEC-03` e `R7-RESULTSMANAGEMENT`).
  - As 3 queries legadas com `grade1..grade4` não são compatíveis com o schema atual de `grade` na 3.15.6 (reforça o levantamento de uso por tenant previsto no épico `R7-RESULTSMANAGEMENT`).
  - Testes de navegador não executados (`.agents/rules/e2e-testing.md`).
- **Riscos:**
  - Em produção (`YII_DEBUG=0`), até `R0-INFRA-03`, `CHttpException(400)` responde HTTP 500 em branco (`config.php:36-37`), embora rejeite a entrada antes de qualquer query.
  - Para visitante sem sessão em `YII_DEBUG=1`, `SiteController::actionError()` renderiza `fullmenu.php`, que redireciona `302` para `site/login` após a `CHttpException(400)` bloquear a query.

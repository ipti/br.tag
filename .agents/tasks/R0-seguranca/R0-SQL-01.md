---
id: R0-SQL-01
title: "SiteController: usar bind nos gráficos e validar ChangeSchool e ChangeYear"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 4) e seção 6 (B0, item 0.2)"
write_scope:
  - app/controllers/SiteController.php
  - tests/acceptance/**
read_only:
  - app/components/UserIdentity.php
  - app/models/LoginForm.php
  - themes/default/views/layouts/fullmenu.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -nE '(school_fk|school_inep_fk|school_inep_id_fk|year[(]date[)]|school_year) *= *[$]' -- app/controllers/SiteController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: entradas maliciosas rejeitadas e resultado válido inalterado"
---

# R0-SQL-01: SiteController: usar bind nos gráficos e validar ChangeSchool e ChangeYear

## Contexto
Todos os pontos estão em `app/controllers/SiteController.php`. O controller não define `filters()`; o `accessRules()` (≈L13–22) nunca roda (R3-SEC-03 trata disso).
**SQL concatenado:**
- `actionLoadLineChartData()` (≈L348–359): `$year = $_POST['year']` (≈L350) e `Yii::app()->user->school` (≈L351) entram direto nas quatro subconsultas (≈L353–356).
- `actionLoadCylinderChartData()` (≈L429–439): `$year` do POST (≈L432) e `$school` da sessão (≈L431) concatenados em ≈L434–436.
- `actionLoadPieChartData()` (≈L441–449): o mesmo padrão em ≈L444–446.
- Os chamadores de `js/site/index.js` (≈L76, ≈L129, ≈L222) estão comentados. Os endpoints seguem alcançáveis por POST direto, então a correção vale mesmo assim.

**Valores que vão para a sessão sem validação (injeção de segunda ordem):**
- `actionChangeSchool()` (≈L140–149) grava `$_POST['UsersSchool']['school_fk']` ou `$_POST['SchoolIdentification']['inep_id']` em `Yii::app()->user->school`. Além da injeção, permite trocar para a escola de outro município.
- `actionChangeYear()` (≈L151–158) grava `$_POST['years']` em `Yii::app()->user->year`.
- Esses dois valores são lidos como confiáveis em todo o sistema, inclusive em SQL concatenado (por exemplo o R0-SQL-02).

**Quais escolas o usuário pode escolher** (`app/components/UserIdentity.php` ≈L28–43):
- papéis `admin`, `nutritionist`, `reader`, `guardian` e `superuser` recebem em `usersSchools` **todas as escolas ativas** (`SchoolIdentification`, campo `inep_id`);
- os demais recebem os registros `UsersSchool` do usuário (campo `school_fk`).

Os dois tipos de objeto convivem em `Yii::app()->user->usersSchools`. Ler `inep_id` num `UsersSchool` lança `CException`, então teste o tipo (`instanceof SchoolIdentification`).

**Faixa de anos válida:** o formulário do menu oferece `range(date('Y'), 2014)` (`themes/default/views/layouts/fullmenu.php` ≈L278), e o login (`actionLogin`, ≈L117–127) oferece de 2014 até o ano seguinte a partir de novembro. Use 2014 a `date('Y') + 1`.

Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03).

## Objetivo
Nenhum valor de `$_POST` nem de sessão é concatenado em SQL neste controller, e a sessão só aceita escola do próprio usuário e ano inteiro em faixa válida.

## Passos
1. Nos três gráficos, valide `$_POST['year']` com `filter_var($_POST['year'] ?? null, FILTER_VALIDATE_INT)` e a faixa 2014 a `date('Y') + 1`. Valor inválido responde 400 (`CHttpException`), sem executar SQL.
2. Troque a concatenação por parâmetros nomeados e passe-os em `createSqlCommand($sql, $params)` (`Yii::app()->db->schema->commandBuilder`), por exemplo `[':year' => $year, ':school' => $school]`.
   - O mesmo `:school`/`:year` aparece várias vezes na consulta. Isso funciona porque o `DBCONFIG` usa `emulatePrepare => true` (`instance.php` ≈L34); o teste da seção Como verificar prova.
   - Não mude o formato do JSON de resposta.
3. Em `actionChangeSchool()`:
   - leia o valor candidato dos dois campos já aceitos hoje;
   - monte a lista de escolas permitidas a partir de `Yii::app()->user->usersSchools` (`inep_id` para `SchoolIdentification`, `school_fk` para `UsersSchool`);
   - se o valor não estiver na lista, registre `Yii::log(..., CLogger::LEVEL_WARNING, 'application')` com o id do usuário e **não** altere a sessão; responda 403;
   - visitante tem `usersSchools` vazio, então cai nesse caso.
4. Em `actionChangeYear()`: aceite só inteiro (`filter_var` com `FILTER_VALIDATE_INT`) em 2014 a `date('Y') + 1`; grave como string (o login também grava a string do formulário). Fora da faixa, 403 sem alterar a sessão.
5. Varra o arquivo por outras concatenações: `git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]' -- app/controllers/SiteController.php`. O comando só lista candidatos; leia cada um. O levantamento ao escrever este card achou só os três gráficos (`loadLogsHtml` e `loadSchoolSummary` já usam parâmetros). Confirme, corrija o que for trivial e liste no Handoff o que encontrou e o que deixou.

## Fora de escopo
- `filters()`/`accessControl` do controller (R3-SEC-03).
- `actionViewFileLogs` e `actionDownloadFileLog` (≈L451–495) entregam `application.log` sem nenhuma checagem de papel. Abra o card próprio e avise o coordenador.
- XSS: `loadLogsHtml` e `loadWarnsHtml` (≈L192–321) montam HTML com `$classroom->name` e `$elements['text']` sem escape.
- O ano do login (`app/models/LoginForm.php` ≈L66) também grava o ano sem validar. É arquivo com dono; só registre no Handoff.

## Critérios de aceite
- [ ] Os três gráficos usam parâmetros; `year` inválido (`2025 OR 1=1`, `2025) OR (1=1`, texto) responde 400 e não executa SQL.
- [ ] Com um ano e uma escola válidos, o JSON dos gráficos é idêntico ao de antes da mudança.
- [ ] `ChangeSchool` com escola fora de `usersSchools` não altera a sessão; com escola própria continua funcionando para os dois tipos de usuário (que veem todas as escolas e que veem só as suas).
- [ ] `ChangeYear` só aceita inteiro de 2014 a `date('Y') + 1`.
- [ ] Concatenações adicionais encontradas e a decisão sobre cada uma estão no Handoff.

## Como verificar
Use uma base de desenvolvimento e duas sessões: uma de professor (uma ou poucas escolas) e uma de admin.
```bash
# ano malicioso: 400, e nada de erro de SQL no log
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=site/loadLineChartData" --data-urlencode "year=2025 OR 1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=site/loadPieChartData" --data-urlencode "year=2025) OR (1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=site/loadCylinderChartData" --data-urlencode "year=0 UNION SELECT 1,2,3,4"

# ano válido: salve a resposta antes e depois da mudança e compare (diff vazio)
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=site/loadLineChartData" -d "year=2025"

# trocar para escola de outro usuário: 403 e a escola da sessão não muda
curl -i -b "PHPSESSID=<sessão de professor>" -X POST "http://localhost/?r=site/changeSchool" -d "UsersSchool[school_fk]=<inep de outra escola>"
curl -i -b "PHPSESSID=<sessão de professor>" -X POST "http://localhost/?r=site/changeSchool" --data-urlencode "UsersSchool[school_fk]=1 OR 1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=site/changeYear" --data-urlencode "years=2025 OR 1=1"

composer lint && composer analyse
```
Confira com `?r=site/loadSchoolSummary` (resposta JSON com os números da escola atual) que a sessão ficou na escola original. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
- **Feito:**
  - Adicionados helpers privados `validateYearParam($rawYear, int $errorCode = 400): int` (faixa `2014 .. date('Y') + 1` via `FILTER_VALIDATE_INT`, rejeitando arrays/texto/fora de faixa) e `requireSessionSchool(): string` (usando `Yii::app()->user->getState('school')`) em `app/controllers/SiteController.php`.
  - Parametrizadas todas as 9 interpolações de `$year` e `$school` nos 3 gráficos (`actionLoadLineChartData`, `actionLoadCylinderChartData` e `actionLoadPieChartData`) usando `:year` e `:school` passados em `createSqlCommand($sql, [':year' => $year, ':school' => $school])`, preservando o formato JSON original.
  - Endurecido `actionChangeSchool()`: lê `Yii::app()->user->getState('usersSchools', [])` (sem lançar `CException` para visitante), monta a lista de escolas permitidas cobrindo ambos os tipos (`SchoolIdentification::$inep_id` e `UsersSchool::$school_fk`), compara estritamente (`in_array(..., true)`), registra `Yii::log(..., CLogger::LEVEL_WARNING, 'application')` com `user_id` e responde `CHttpException(403)` sem alterar a sessão quando a escola não for permitida ou vier vazia.
  - Endurecido `actionChangeYear()`: bloqueia visitante (`403`), valida inteiro na faixa `2014 .. date('Y') + 1` (`403` sem alterar sessão) e grava como string `(string) $year` (compatível com `actionLogin`).
- **Decisões:**
  - **Passo 5 (varredura completa das 555 linhas de `SiteController.php`):** apenas os 3 gráficos concatenavam variáveis em SQL; `loadLogsHtml` (L160–207), `actionLoadWarnsHtml` (L214–346) e `actionLoadSchoolSummary` (L513–554) já usam `CDbCriteria::compare`, `addNotInCondition`, `:date` e `findAllByAttributes`/`countByAttributes`.
  - **Envio vazio (`""`) em `changeSchool`:** tratado como não pertencente a `usersSchools` → `403` sem alterar a sessão (evita estado inconsistente e cobre visitante com `usersSchools` vazio).
  - **`loadLineChartData` com `26.047` linhas em `log`:** a query original executa 4 subconsultas correlacionadas sobre toda a tabela `log` (> 150s na base local `demo.tag.ong.br`); validamos o SQL parametrizado com placeholders repetidos (`:year`/`:school`, `PDO::ATTR_EMULATE_PREPARES => true`) via `EXPLAIN` (`LINE_CHART_EXPLAIN_OK`, 5 linhas de plano) + rejeição instantânea `400` para entradas maliciosas/fora da faixa.
  - Branch `task/R0-SQL-01` criada a partir de `docs/plano-modernizacao` (`009b428ee`).
  - Revisão de conformidade (`tag-plan-guardian`): checklist completo executado localmente sem bloqueios; recomenda-se revisão cruzada no Claude Code ou Codex antes do merge.
- **Gates:**
  - `docker exec tag-app php -l /app/app/controllers/SiteController.php` → OK (`No syntax errors detected`).
  - `docker exec -u root -e PHP_CS_FIXER_IGNORE_ENV=1 tag-app ./vendor/bin/php-cs-fixer fix --dry-run --diff app/controllers/SiteController.php` → OK (`0` diffs).
  - `git grep -nE '(school_fk|school_inep_fk|school_inep_id_fk|year[(]date[)]|school_year) *= *[$]' -- app/controllers/SiteController.php` → OK (sem resultados).
  - `composer run analyse` (`phpstan analyse --memory-limit=512M`) → **falha pré-existente fora do escopo** (`Path /app/app/domain does not exist` em `phpstan.neon`, coberto em `R1-BASE`).
  - Smoke `curl` contra `http://localhost` (`YII_DEBUG=1`, base `demo.tag.ong.br`, contas efêmeras `instructor` e `admin` removidas com `0` residuais):
    - Gráficos válidos (`year=2024`): `loadCylinderChartData` (`{"schools":1,"classrooms":66,"instructors":99,"students":1471}`) e `loadPieChartData` (`{"students":1471,"enrollments":1949}`) com **SHA-256 idêntico** antes × depois; `loadLineChartData` validado via PDO `EXPLAIN` (`LINE_CHART_EXPLAIN_OK`).
    - Gráficos maliciosos/fora da faixa: `line` (`2025 OR 1=1`, `2013`, `2099`), `pie` (`2025) OR (1=1`) e `cyl` (`0 UNION SELECT 1,2,3,4`) → todos `400`.
    - `changeSchool` (`instructor` com escolas `28016912` e `28016939`): escola alheia `28016947` → `403`; `1 OR 1=1` → `403`; vazio `""` → `403`; `loadSchoolSummary` confirmou sessão inalterada (`unchanged=True`); troca para 2ª escola própria `28016939` → `200` (`changed=True`).
    - `changeSchool` (`admin` / `SchoolIdentification`): escola ativa `28016947` → `200`; INEP inexistente `99999999` → `403`. Visitante sem cookie → `302` via `fullmenu.php` (sem 500/`CException`).
    - `changeYear`: `2025 OR 1=1` → `403`; `2013` → `403`; `2099` → `403`; `2025` → `200`; `2027` (`date('Y')+1`) → `200`; visitante → `302`.
- **Pendências e issues sugeridas (Fora de escopo):**
  - **`actionViewFileLogs` e `actionDownloadFileLog`** (`SiteController.php` L451–508) expõem `application.log` sem checagem de papel — abrir card próprio e avisar o coordenador.
  - **`app/models/LoginForm.php:66`** (arquivo com dono em `forbidden`): grava o ano do login na sessão sem validar faixa/inteiro — abrir card de contrato.
  - **`app/components/UserIdentity.php:30-35`** (`read_only`): inclui `guardian` no grupo que carrega todas as escolas ativas em `usersSchools` (embora o select no menu esteja `disabled` para `guardian`) — avaliar restrição em card futuro.
  - **Coordenação com `R0-INFRA-03`:** ambos editam `SiteController.php` em trechos distintos (`actionError` vs `actionChangeSchool`/`actionChangeYear`/gráficos); quem entrar depois faz rebase.
  - Testes de navegador não executados (`.agents/rules/e2e-testing.md`).
- **Riscos:**
  - Em produção (`YII_DEBUG=0`), até `R0-INFRA-03`, `CHttpException(400/403)` responde HTTP 500 em branco (`config.php:36-37`), embora bloqueie antes da query/mutação de sessão.
  - `SiteController` segue sem `filters()`/`accessControl` até `R3-SEC-03`.

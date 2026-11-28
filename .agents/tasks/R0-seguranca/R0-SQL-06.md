---
id: R0-SQL-06
title: "school: usar parâmetro em SchoolController::actionUpdateUfDependencies (edcenso_uf_fk)"
release: R0
module: school
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
  - app/modules/school/controllers/SchoolController.php
  - tests/acceptance/**
read_only:
  - app/models/SchoolIdentification.php
  - app/models/EdcensoRegionalEducationOrgan.php
  - app/modules/school/views/school/_form.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n 'edcenso_uf_fk = \\$uf' -- app/modules/school/controllers/SchoolController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: UF maliciosa não amplia o resultado; UF válida devolve o mesmo JSON de antes"
---

# R0-SQL-06: school: usar parâmetro em SchoolController::actionUpdateUfDependencies (edcenso_uf_fk)

## Contexto
Em `app/modules/school/controllers/SchoolController.php`, `actionUpdateUfDependencies()` (≈L85–107) monta a condição por concatenação:
```php
$school->attributes = $_POST[$this->schoolIdentification];   // ≈L88
$uf = $school->edcenso_uf_fk;                                // ≈L90
...
$criteria->condition = "city.edcenso_uf_fk = $uf";           // ≈L98
```
- **Origem do valor:** `$_POST['SchoolIdentification']['edcenso_uf_fk']`. Esse atributo é seguro para atribuição em massa (`app/models/SchoolIdentification.php` ≈L106 e ≈L108 listam `edcenso_uf_fk` nas regras, mas as regras só rodam em `validate()`, que aqui **não** é chamado). Então o texto do POST chega intacto a ≈L98.
- **Quem alcança:** o controller tem `filters()` com `accessControl` (≈L18–23), e `updateufdependencies` está na primeira regra de `accessRules()` para `'@'` (≈L47). Qualquer usuário logado chega à action.
- **Chamador legítimo:** o select de UF do formulário da escola (`app/modules/school/views/school/_form.php` ≈L599, URL `school/school/updateUfDependencies`). O valor pode vir vazio quando o usuário escolhe o texto "Selecione".
- **Precedente no mesmo arquivo:** `actionGetCities()` (≈L109–124), chamada em ≈L93 com o mesmo valor, já faz `(int)$school->edcenso_uf_fk` e usa parâmetro (`:uf_id`, ≈L115). `actionGetDistricts()` (≈L126–140) também usa parâmetro (`:city_id`). Só a consulta de órgãos regionais ficou de fora.
- **Comportamento atual com valor vazio:** `city.edcenso_uf_fk = ` é SQL inválido e vira 500. Com o cast para inteiro, o vazio vira `0` e a lista de órgãos fica vazia, que é o comportamento coerente com `actionGetCities`.
- Varredura do arquivo (`git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]'`): só ≈L98 concatena variável em SQL. O outro `condition` (≈L306, `school_fk = :school_fk`) é parametrizado.

## Objetivo
A condição de órgãos regionais usa parâmetro e valor inteiro; entrada maliciosa não altera o resultado.

## Passos
1. Em `actionUpdateUfDependencies()`, converta o valor: `$uf = (int) $school->edcenso_uf_fk;`. O mesmo cast já existe em `actionGetCities` (≈L115); mantenha o estilo.
   - Não rejeite com 400: o select de UF pode enviar vazio, e hoje isso só quebra com 500. Com o cast, o vazio vira 0 e a resposta fica sem órgãos regionais.
2. Troque a condição por parâmetro:
   - `$criteria->condition = 'city.edcenso_uf_fk = :uf';`
   - `$criteria->params = [':uf' => $uf];`
3. Não mude o `join`, o `order`, a lista de `option` nem o formato do JSON (`City` e `Regional`).
4. Varra o MESMO arquivo com o comando do Contexto, corrija o que for trivial e liste no Handoff o que encontrou.
5. Se `edcenso_uf_fk` chegar como array (POST `SchoolIdentification[edcenso_uf_fk][]=1`), o `(int)` de um array vira 1 sem erro. Confirme no teste que não há exceção, e registre o resultado.

## Fora de escopo
- Tornar os outros endpoints do controller (`actionGetCities`, `actionGetDistricts`) mais estritos: já usam parâmetro.
- Permissão por papel dentro do módulo (`manager`, `reports` etc.): é do épico R5-SCHOOL.
- Qualquer mudança no formulário da escola.

## Critérios de aceite
- [ ] `git grep -n 'edcenso_uf_fk = \$uf' -- app/modules/school/controllers/SchoolController.php` não retorna nada.
- [ ] POST com `edcenso_uf_fk` igual a `1 OR 1=1`, `1) OR (1=1` ou `1 UNION SELECT 1` devolve o mesmo resultado que `edcenso_uf_fk=1` e não gera erro de SQL no log.
- [ ] Com uma UF válida, o JSON (`City` e `Regional`) é idêntico ao de antes da mudança.
- [ ] Com valor vazio, a resposta é um JSON válido com `Regional` apenas com a opção "Selecione o órgão" (hoje é 500).
- [ ] A varredura do arquivo está no Handoff.

## Como verificar
Use uma base de desenvolvimento e uma sessão de usuário comum.
```bash
# válido: salve antes e depois da mudança e compare (diff vazio)
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=school/school/updateUfDependencies" \
  -d "SchoolIdentification[edcenso_uf_fk]=28" > uf28.json

# malicioso: o resultado tem que ser igual ao da UF 1, nunca a união de todas as UFs
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=school/school/updateUfDependencies" \
  --data-urlencode "SchoolIdentification[edcenso_uf_fk]=1 OR 1=1" > mal1.json
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=school/school/updateUfDependencies" \
  -d "SchoolIdentification[edcenso_uf_fk]=1" > uf1.json
diff mal1.json uf1.json   # esperado: vazio
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=school/school/updateUfDependencies" \
  --data-urlencode "SchoolIdentification[edcenso_uf_fk]=1) OR (1=1"

# vazio: JSON válido, sem 500
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=school/school/updateUfDependencies" -d "SchoolIdentification[edcenso_uf_fk]="

git grep -n 'edcenso_uf_fk = \$uf' -- app/modules/school/controllers/SchoolController.php
composer lint && composer analyse
```
Antes da correção, o `1 OR 1=1` devolve os órgãos regionais de **todas** as UFs; guarde essa evidência no Handoff. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação. A tela relevante é o formulário de escola, trocando a UF.

## Handoff
- **Feito:**
  - `SchoolController::actionUpdateUfDependencies()` (≈L90 e ≈L98–99): `$uf = (int) $school->edcenso_uf_fk;` e condição `'city.edcenso_uf_fk = :uf'` com `params = [':uf' => $uf]`. `join`, `order`, `option` e formato do JSON ficaram iguais.
  - Testes em `tests/acceptance/`: `R0Sql06Bootstrap.php` (carrega a action real contra MySQL descartável), `R0Sql06Characterization.php` + `.json` (golden master de 4 UFs válidas) e `R0Sql06Security.php`.
- **Decisões:**
  - Sem rejeitar com 400: o select de UF pode enviar vazio, e o cast transforma isso em 0 (antes dava 500), como o card orienta.
  - O golden master foi gravado com o código **anterior** à correção (`--record`) e não foi regravado depois.
  - Testes rodam em container com MySQL descartável e esquema real (DDL copiado, sem dados) das tabelas `school_identification`, `edcenso_city` e `edcenso_regional_education_organ`; nunca contra a base de desenvolvimento (o bootstrap lança exceção se host ou banco forem outros).
- **Gates:**

| Comando | Resultado |
|---|---|
| `php -l` no controller | Aprovado |
| `php-cs-fixer fix --dry-run --diff` só no controller (container isolado) | Aprovado, sem alterações |
| `phpstan analyse --memory-limit=512M` (container isolado, base do PR #1223 já na base) | Aprovado, `No errors` (o escopo atual cobre só `app/components`) |
| `git grep -n 'edcenso_uf_fk = \$uf'` no controller | Sem resultados |
| `R0Sql06Characterization.php` (código novo) | Aprovado, 4 casos idênticos ao golden master |
| `R0Sql06Security.php` (código novo) | Aprovado |
| `R0Sql06Security.php` (código antigo) | **Falha em `1 OR 1=1`**, como esperado |
| Smoke `curl` e E2E no formulário da escola | **Não verificado**: sem sessão autenticada nem URL e aprovação (`e2e-testing.md`) |
| `composer lint` global | **Não rodado**: o lint global já falha no repositório (202 arquivos, registrado no R0-SQL-04); rodei só no arquivo do card |

- **Evidência antes e depois** (cenário sintético, UF 1 tem os órgãos 102 e 101; há 4 órgãos em 3 UFs):

| Entrada | Antes | Depois |
|---|---|---|
| `edcenso_uf_fk=1` | 102, 101 | 102, 101 |
| `edcenso_uf_fk=1 OR 1=1` | 102, 101, 201, 281 (órgãos de todas as UFs) | 102, 101 |
| `edcenso_uf_fk=` (vazio) | erro de SQL (500) | JSON válido, só "Selecione o órgão" |

  Também passam sem alterar o resultado: `1) OR (1=1`, `1 UNION SELECT 1`, `1'; DROP TABLE edcenso_city; --` e `1 AND SLEEP(5)` (sem atraso; a tabela continua com 4 registros).
- **Varredura do arquivo** (comando do Contexto): nenhuma concatenação de variável em SQL. As únicas `condition` são ≈L98 (`:uf`) e ≈L307 (`:school_fk`). `actionGetManagerCities` (≈L164) usa `$_POST['edcenso_uf_fk']` sem cast, mas com bind `:uf_id`; não é injeção e não foi alterada.
- **Array (passo 5):** `SchoolIdentification[edcenso_uf_fk][]=1` devolve o mesmo resultado da UF 1, sem exceção (cast de array não vazio dá 1).
- **Como reproduzir os testes:**
  1. `docker network create tag-r0-sql06-net` e `docker run -d --name tag-r0-sql06-mysql --network tag-r0-sql06-net --tmpfs /var/lib/mysql -e MYSQL_ALLOW_EMPTY_PASSWORD=yes -e MYSQL_DATABASE=tag_r0_test mysql:8.0`.
  2. Importar só o DDL (sem dados) das 3 tabelas acima, com `SET FOREIGN_KEY_CHECKS=0`.
  3. Rodar com a imagem do projeto (`--entrypoint php`, `-e TAG_TEST_DB_HOST=tag-r0-sql06-mysql -e TAG_TEST_DB_NAME=tag_r0_test`, o worktree montado em `/app` e o `vendor/` em `/app/vendor`): `php tests/acceptance/R0Sql06Characterization.php` e `php tests/acceptance/R0Sql06Security.php`. `--record` só com aprovação humana.
- **Pendências e issues sugeridas** (nada disso foi alterado):
  - As opções de órgão regional não passam por `CHtml::encode` (as de cidade passam): um nome com `<` sai cru no HTML. Os dados vêm de tabela de referência do Educacenso, então o risco é baixo, mas vale um card.
  - `$_POST['SchoolIdentification']` ausente por completo não é tratado (≈L88). Com a chave presente e vazia, o teste passa.
  - Os scripts de teste são avulsos (fora do `composer test`). Decidir se viram gate do `tag-qa` e onde fica o setup do MySQL descartável.
- **Riscos:**
  - O teste chama a action real, mas com um stub de `CController`: o filtro `accessControl` (≈L47, só `'@'`) e o `Controller` real não foram exercitados.
  - A tela de escola não foi aberta no navegador.
  - Um valor de UF inválido agora devolve listas vazias, em vez de erro 500.
- **Revisão do Guardião:** `tag-plan-guardian` (subagente independente): **aprovado com ressalvas**; a única ressalva era este Handoff. Reexecutou o golden master com o controller antigo e confirmou que ele foi gravado antes da correção.
- **Revisão humana obrigatória antes do merge** (card de segurança): decidir se o teste em container basta ou se o smoke `curl` e o E2E são exigidos.

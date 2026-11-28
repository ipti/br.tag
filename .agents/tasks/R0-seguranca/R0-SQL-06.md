---
id: R0-SQL-06
title: "school: usar parâmetro em SchoolController::actionUpdateUfDependencies (edcenso_uf_fk)"
release: R0
module: school
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
_Preenchido por quem executar._

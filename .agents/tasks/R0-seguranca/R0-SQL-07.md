---
id: R0-SQL-07
title: "schoolreport: parametrizar o filtro de disciplina em DefaultController::actionFrequency"
release: R0
module: schoolreport
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 4) e seção 6 (B0, item 0.2)"
write_scope:
  - app/modules/schoolreport/controllers/DefaultController.php
  - tests/acceptance/**
read_only:
  - app/models/Schedule.php
  - app/modules/schoolreport/views/default/frequency.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n '\"= \\$did\"' -- app/modules/schoolreport/controllers/DefaultController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: saída de actionFrequency idêntica antes e depois, para disciplina e para 'todos os componentes'"
---

# R0-SQL-07: schoolreport: parametrizar o filtro de disciplina em DefaultController::actionFrequency

## Contexto
Em `app/modules/schoolreport/controllers/DefaultController.php`, `actionFrequency($eid)` (≈L113–166) concatena a disciplina no SQL:
```php
$did = $c['discipline_fk'] == null ? -1 : $c['discipline_fk'];   // ≈L138
$didQuery = $did == -1 ? 'IS NULL' : "= $did";                   // ≈L150
... "and discipline_fk $didQuery" ...                            // ≈L156
```
**Esta é uma injeção de segunda ordem, de risco menor que as demais.** O valor de `$did` **não** vem da requisição:
- vem de `$classesList` (≈L130–135), uma consulta a `schedule` filtrada por `classroom_fk = :cid` (parametrizada), lida do campo `schedule.discipline_fk`;
- esse campo é chave estrangeira para `edcenso_discipline.id` (inteiro; ver `app/migrations/old_migrations/m160311_140704_timeseet_tables.php` ≈L58), então um valor malicioso só chegaria ali se alguém gravasse texto numa coluna inteira (confirmar o tipo no banco: `SHOW COLUMNS FROM schedule`).
O risco é baixo, mas a regra do plano é bind em todos os pontos, e o padrão `"= $did"` pode ser copiado para outro lugar. Por isso o card existe com `risk: medio`.

Os demais parâmetros da action já são seguros: `$eid` entra por `findByPk` (≈L126) e por `:eid` (≈L159). Um `eid` como `1 OR 1=1` vira `1` na comparação do MySQL com a coluna inteira, então não amplia o resultado; `:month` e `:cid` também são parametrizados. O guarda de visitante (`isGuest` → redirect, ≈L115) existe, mas **não há checagem de papel nem de posse da matrícula**: qualquer usuário logado informa qualquer `eid`. Isso é falha de acesso (IDOR), fora deste card (ver Fora de escopo).

Varredura do arquivo (`git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]'` e leitura): só ≈L150–156 concatena variável em SQL. As consultas de ≈L65 e ≈L130 são parametrizadas. Os outros controllers do módulo (`FormsController`, `ReportsController`) **não** foram varridos para este card.

## Objetivo
O filtro de disciplina de `actionFrequency` usa parâmetro (ou condição fixa), sem concatenar `$did`, e a saída não muda.

## Passos
1. Troque a construção de `$didQuery` por duas condições fixas, sem interpolação:
   - quando `$did == -1`: `discipline_fk IS NULL`;
   - caso contrário: `discipline_fk = :did`, com `':did' => (int) $did` somado ao array passado em `queryRow(true, [...])` (≈L159).
   - Mantenha `:eid` e `:month` como estão e não mude o `GROUP BY` nem o texto do restante do SQL.
2. Garanta que o array de parâmetros só contém `:did` no ramo em que o marcador existe. PDO com `emulatePrepare` aceita parâmetro sobrando, mas não deixe sobrar por clareza.
3. Varra o MESMO arquivo com o comando do Contexto, corrija o que for trivial e liste no Handoff o que encontrou.
4. Registre no Handoff que `FormsController` e `ReportsController` do módulo não foram cobertos e sugira um card de varredura (o README conta 94 `createCommand` no módulo).

## Fora de escopo
- Verificar se o `eid` pertence a um aluno do responsável logado, ou limitar por papel (IDOR). O módulo é o portal de consulta de notas e frequência; abra card de controle de acesso (R3-SEC-03 e R6-SCHOOLREPORT) e avise o coordenador.
- `actionGetGrades` e `actionGrades` (mesmo problema de acesso).
- `FormsController` e `ReportsController`.
- Mudanças de layout ou de view do módulo (ilha com Semantic UI, seção F5 do plano).

## Critérios de aceite
- [ ] `git grep -n '"= \$did"' -- app/modules/schoolreport/controllers/DefaultController.php` não retorna nada, e `$did` não aparece interpolado em string de SQL.
- [ ] Para uma matrícula com disciplinas específicas e para uma com "Todos os componentes curriculares/eixos" (`did = -1`), a saída de `actionFrequency` (HTML ou o array passado ao `render`) é idêntica à de antes.
- [ ] `eid` malicioso (`1 OR 1=1`, `1) OR (1=1`) produz os mesmos números de aulas e faltas de `eid=1`, nunca um resultado ampliado. A única diferença aceitável são os trechos em que a view repete o `eid` enviado.
- [ ] A varredura do arquivo e a recomendação sobre os outros controllers estão no Handoff.

## Como verificar
Use uma base de desenvolvimento e uma sessão logada qualquer (o módulo só exige login).
```bash
# referência: salve a saída ANTES da mudança para duas matrículas (uma com disciplina, outra sem)
curl -s -b "PHPSESSID=<sessão>" "http://localhost/?r=schoolreport/default/frequency&eid=<matrícula A>" > a_antes.html
curl -s -b "PHPSESSID=<sessão>" "http://localhost/?r=schoolreport/default/frequency&eid=<matrícula B sem disciplina>" > b_antes.html
# repita depois da mudança e compare: diff vazio nos dois (ignore tokens de sessão, se houver)

# eid malicioso: tem que dar os mesmos números do eid=1, nunca ampliado
curl -s -b "PHPSESSID=<sessão>" "http://localhost/?r=schoolreport/default/frequency&eid=1" > um.html
curl -s -b "PHPSESSID=<sessão>" -G "http://localhost/" --data-urlencode "r=schoolreport/default/frequency" --data-urlencode "eid=1 OR 1=1" > mal.html
diff um.html mal.html     # esperado: só as linhas que repetem o eid enviado; as tabelas de aulas e faltas iguais

git grep -n '"= \$did"' -- app/modules/schoolreport/controllers/DefaultController.php
composer lint && composer analyse
```
Como o valor de `$did` vem do banco, **não há entrada de requisição que o atinja**; por isso o teste de entrada maliciosa usa o `eid`, que é o único ponto de entrada da action. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

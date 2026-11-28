---
id: R0-SQL-09
title: "wizard: usar parâmetro em ConfigurationController::actionGetStudents ($_POST['Classrooms'])"
release: R0
module: wizard
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
  - app/modules/wizard/controllers/ConfigurationController.php
  - tests/acceptance/**
read_only:
  - app/modules/wizard/views/configuration/students.php
  - app/modules/wizard/WizardRoutes.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n 'classroom_fk = \\$id' -- app/modules/wizard/controllers/ConfigurationController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: Classrooms malicioso responde 400; id válido devolve as mesmas opções de antes"
---

# R0-SQL-09: wizard: usar parâmetro em ConfigurationController::actionGetStudents ($_POST['Classrooms'])

## Contexto
Em `app/modules/wizard/controllers/ConfigurationController.php`, `actionGetStudents()` (≈L184–200) concatena o POST no `JOIN`:
```php
$id = $_POST['Classrooms'];                                                                // ≈L187
$criteria->join = "JOIN student_enrollment AS se ON (se.student_fk = t.id AND se.classroom_fk = $id)";   // ≈L189
```
- **Origem do valor:** `$_POST['Classrooms']`, sem validação, concatenado em ≈L189. O teste `!empty(...)` (≈L186) só descarta valor vazio.
- **Sem controle de acesso:** o controller não tem `filters()` nem `accessRules()` e herda do `Controller` do TAG. O `Controller::init()` só barra visitante em requisição AJAX; um POST comum de visitante passa. Isso é do R3-SEC-03; aqui o recorte é o SQL. Aproveite e registre no Handoff se a action responde a visitante sem sessão.
- **Chamador:** `git grep -i getstudents` só acha a constante de rota (`app/modules/wizard/WizardRoutes.php` ≈L20) e a própria action. A view `students.php` (≈L47–60) tem um `dropDownList('Classrooms', ...)` com `multiple` e não faz essa chamada. Conclusão: a action está **disponível, sem chamador ativo no repositório** (confirmar: o front pode montar a URL por texto). Se um chamador existir, `Classrooms` pode chegar como array; hoje isso vira `Array` no SQL e quebra.
- **Varredura do arquivo** (`git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]` e leitura): só ≈L189 concatena variável em SQL. As consultas das demais actions usam `findByPk`, `findAllByAttributes` ou parâmetros (≈L146 e ≈L155–158 usam `'c' => ...`/`'s' => ...`).

Pontos vistos no caminho e **fora deste card**:
- `actionGetStudents` (≈L192) usa `'name' . 'id'` como campo de texto da lista (`CHtml::listData(..., 'id', 'name' . 'id')`). Parece erro de digitação (confirmar: `StudentIdentification` não tem campo `nameid`). Registre no Handoff, não corrija aqui.
- A turma pedida não é conferida contra a escola do usuário (acesso entre escolas).

Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03). O 400 aparece corretamente em desenvolvimento.

## Objetivo
`actionGetStudents` não concatena `Classrooms` no SQL, rejeita valor que não seja inteiro e devolve as mesmas opções para ids válidos.

## Passos
1. Valide `$_POST['Classrooms']` com `filter_var($_POST['Classrooms'] ?? null, FILTER_VALIDATE_INT)`. Se der `false` (inclui array e texto), responda 400 (`CHttpException`). Mantenha o ramo atual de valor vazio, que responde vazio (`echo ''`), como hoje.
2. Troque a concatenação por parâmetro: `se.classroom_fk = :classroom` no `join` e `$criteria->params[':classroom'] = $id`. O `CDbCriteria` aplica os parâmetros também ao `join`.
3. Não mude o `order`, o `listData`, o laço de `option` nem o tratamento do vazio.
4. Varra o MESMO arquivo com o comando do Contexto, corrija o que for trivial e liste no Handoff o que encontrou.
5. Registre no Handoff a existência (ou não) de chamador, o resultado do teste sem sessão e o achado do `'name' . 'id'`.

## Fora de escopo
- Adicionar `filters()` e `accessControl` ao controller (R3-SEC-03).
- Conferir se a turma pertence à escola da sessão.
- Corrigir o `'name' . 'id'` e demais bugs de negócio do wizard (R6-PEQUENOS).
- Remover a action caso se confirme que está sem uso (decisão do coordenador).

## Critérios de aceite
- [ ] `git grep -n 'classroom_fk = \$id' -- app/modules/wizard/controllers/ConfigurationController.php` não retorna nada; o `join` usa `:classroom`.
- [ ] `Classrooms=1 OR 1=1`, `Classrooms=1) OR (1=1`, `Classrooms[]=1` e `Classrooms=abc` respondem 400 e não ampliam o resultado nem geram erro de SQL no log.
- [ ] Com `Classrooms=<turma válida>`, as opções devolvidas são idênticas às de antes da mudança.
- [ ] Com `Classrooms` vazio ou ausente, a resposta continua vazia.
- [ ] O Handoff registra chamador, teste sem sessão e o achado do `'name' . 'id'`.

## Como verificar
Use uma base de desenvolvimento e uma sessão de usuário comum.
```bash
T=<id de uma turma com matrículas>

# válido: salve antes e depois da mudança e compare (diff vazio)
curl -s -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=wizard/configuration/getStudents" -d "Classrooms=$T" > antes.html

# antes da correção, o primeiro devolve alunos de TODAS as turmas; depois, 400
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=wizard/configuration/getStudents" --data-urlencode "Classrooms=$T OR 1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=wizard/configuration/getStudents" --data-urlencode "Classrooms=$T) OR (1=1"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=wizard/configuration/getStudents" -d "Classrooms[]=$T"
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=wizard/configuration/getStudents" -d "Classrooms=abc"

# vazio: resposta vazia, como hoje
curl -i -b "PHPSESSID=<sessão>" -X POST "http://localhost/?r=wizard/configuration/getStudents" -d "Classrooms="

# sem sessão (para o Handoff)
curl -i -X POST "http://localhost/?r=wizard/configuration/getStudents" -d "Classrooms=$T"

git grep -n 'classroom_fk = \$id' -- app/modules/wizard/controllers/ConfigurationController.php
composer lint && composer analyse
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

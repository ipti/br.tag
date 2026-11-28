---
id: R0-SQL-08
title: "classroom: usar parâmetro em DefaultController::actionBatchupdatEnrollment ($id da URL)"
release: R0
module: classroom
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
  - app/modules/classroom/controllers/DefaultController.php
  - tests/acceptance/**
read_only:
  - app/modules/classroom/ClassroomRoutes.php
  - app/modules/classroom/views/default/batchupdatenrollment.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/default/views/layouts/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n '\"classroom_fk = \\$classroom\"' -- app/modules/classroom/controllers/DefaultController.php   (sem resultados)"
  - "Smoke com curl da seção Como verificar: id malicioso responde 400 sem demora; id válido lista os mesmos alunos de antes"
---

# R0-SQL-08: classroom: usar parâmetro em DefaultController::actionBatchupdatEnrollment ($id da URL)

## Contexto
Em `app/modules/classroom/controllers/DefaultController.php`, `actionBatchupdatEnrollment($id)` (≈L357–388) concatena o id da turma na condição:
```php
$classroom = $id;                                            // ≈L375
$criteria->condition = "classroom_fk = $classroom";          // ≈L380
```
- **Origem do valor:** `$id` é o argumento da action, preenchido pelo Yii com `$_GET['id']` (`?r=classroom/default/batchupdatEnrollment&id=...`). Não há validação.
- **Por que o `loadModel` não protege:** a action chama `$this->loadModel($id, $this->modelClassroom)` antes (≈L359), que usa `Classroom::model()->findByPk($id)` (≈L844, parametrizado). Um `id` como `12 OR 1=1` é comparado com a coluna inteira como `12` e **encontra a turma 12**; o fluxo segue e a condição concatenada vira `classroom_fk = 12 OR 1=1`, que lista as matrículas e os alunos (`JOIN student_identification`) de **todas** as turmas do banco.
- **Quem alcança:** o controller tem `filters()` com `accessControl` (≈L12–16) e `batchupdatenrollment` está liberado para `'@'` (≈L32). Qualquer usuário logado chega à action.
- **Chamadores legítimos:** os links em `app/modules/classroom/views/default/_form.php` (≈L881 e ≈L957), com o id inteiro da turma.
- **Varredura do arquivo** (`git grep -nE '(\$sql|\$where|condition|->join|createCommand|BySql)[^;]*\$[a-zA-Z_]` e leitura): só ≈L380 concatena variável em SQL. As demais condições do arquivo (≈L89, ≈L479, ≈L671, ≈L723, ≈L994) e as consultas de ≈L317, ≈L914 e ≈L920 são parametrizadas ou fixas; as de ≈L914–924 usam `bindParam` com `$_POST['id']`.

Pontos vistos no caminho e **fora deste card**: o ramo de gravação (≈L360–373) percorre `$_POST` inteiro e faz `findByPk($eid)->update(...)` em qualquer matrícula informada, sem checar se pertence à turma ou à escola do usuário (acesso entre escolas); e `$enro` nulo derruba a action.

Atenção: em produção hoje todo `CHttpException` vira 500 em branco (achado 9, corrigido no R0-INFRA-03). O 400 aparece corretamente em desenvolvimento.

## Objetivo
O `$id` da turma é validado como inteiro e entra na consulta por parâmetro; entrada maliciosa não altera a lista de matrículas.

## Passos
1. No início de `actionBatchupdatEnrollment`, **antes** de `loadModel`, valide `$id` com `ctype_digit((string) $id)` (ou `filter_var(..., FILTER_VALIDATE_INT)`). Valor inválido responde 400 (`CHttpException`), sem consultar o banco.
2. Troque a condição por `'classroom_fk = :classroom_fk'` com `$criteria->params = [':classroom_fk' => (int) $id]`. Mantenha `alias`, `select`, `join` e `order` como estão.
   - Remova a variável `$classroom = $id` se ficar sem uso, ou mantenha-a recebendo o inteiro validado.
3. Não mude o ramo de gravação (≈L360–373) nem o `render`.
4. Varra o MESMO arquivo com o comando do Contexto, corrija concatenações triviais e liste no Handoff o que encontrou.
5. Confirme que `loadModel` não é chamado com o `id` bruto em nenhum outro ponto desta action.

## Fora de escopo
- Validar que a turma é da escola do usuário e que as matrículas do `$_POST` pertencem a ela (acesso entre escolas; R3-SEC-03 e R4-CLASSROOM).
- Proteger o ramo de gravação contra matrícula inexistente.
- Refatorar o módulo (R4-CLASSROOM).

## Critérios de aceite
- [ ] `git grep -n '"classroom_fk = \$classroom"' -- app/modules/classroom/controllers/DefaultController.php` não retorna nada; a condição usa `:classroom_fk`.
- [ ] `id=<turma> OR 1=1`, `id=<turma>) OR (1=1` e `id=<turma> AND SLEEP(3)` respondem 400 imediatamente (< 1 s) e não executam a consulta.
- [ ] Com `id` válido, a página lista as mesmas matrículas, na mesma ordem, que antes da mudança.
- [ ] A varredura do arquivo e as decisões estão no Handoff.

## Como verificar
Use uma base de desenvolvimento com mais de uma turma e uma sessão de usuário comum.
```bash
T=<id de uma turma existente>

# referência: salve ANTES da mudança e compare depois (diff vazio)
curl -s -b "PHPSESSID=<sessão>" "http://localhost/?r=classroom/default/batchupdatEnrollment&id=$T" > lista_antes.html

# antes da correção: devolve alunos de TODAS as turmas; guarde a evidência (contagem de linhas) no Handoff
curl -s -b "PHPSESSID=<sessão>" -G "http://localhost/" --data-urlencode "r=classroom/default/batchupdatEnrollment" --data-urlencode "id=$T OR 1=1" | grep -c "<tr"

# depois da correção: 400, e sem demora
time curl -s -o /dev/null -w "%{http_code}\n" -b "PHPSESSID=<sessão>" -G "http://localhost/" \
  --data-urlencode "r=classroom/default/batchupdatEnrollment" --data-urlencode "id=$T AND SLEEP(3)"
curl -i -b "PHPSESSID=<sessão>" -G "http://localhost/" \
  --data-urlencode "r=classroom/default/batchupdatEnrollment" --data-urlencode "id=$T) OR (1=1"
curl -s -b "PHPSESSID=<sessão>" "http://localhost/?r=classroom/default/batchupdatEnrollment&id=$T" > lista_depois.html
diff lista_antes.html lista_depois.html    # esperado: vazio

git grep -n '"classroom_fk = \$classroom"' -- app/modules/classroom/controllers/DefaultController.php
composer lint && composer analyse
```
Não use o ramo de gravação (POST) nesses testes. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação. A tela relevante é a edição da turma, no link de reutilizar/atualizar matrículas em lote.

## Handoff
- **Feito:**
  - `actionBatchupdatEnrollment($id)`: `ctype_digit((string) $id)` antes do `loadModel`, com `CHttpException(400)` para valor inválido; condição `'classroom_fk = :classroom_fk'` com `params` e `(int) $id`. `alias`, `select`, `join`, `order`, o ramo de gravação e o `render` ficaram iguais. Não há outro `loadModel` com id bruto nesta action.
  - **Extra, achado pela varredura do passo 4:** `actionUpdateComplementaryActivity` concatenava `complementary_activity_type_1..6` do POST em `id!="..."`. Passou a montar um `NOT IN` parametrizado (`addNotInCondition`), ignorando valores não numéricos.
  - `tests/acceptance/R0Sql08Smoke.py`: smoke HTTP A/B (login, GET em `batchupdatEnrollment`, POST de leitura em `updateComplementaryActivity`). Credenciais só por variável de ambiente.
- **Decisões:**
  - O limiar de "sem demora" do card (`< 1 s`) virou `< 3 s`, menos que o `SLEEP(3)` do payload: o app de desenvolvimento leva de 0,4 a 1,4 s só para subir (um id válido leva de 1,4 a 4 s). Medido no código corrigido: 400 em 0,4 a 1,4 s.
  - O 400 sai antes de qualquer consulta ao banco, inclusive do `loadModel`.
  - A mudança extra ficou no mesmo arquivo e não toca regra de negócio; o `tag-plan-guardian` a considerou concatenação trivial, dentro do passo 4.
- **Gates:**

| Comando | Resultado |
|---|---|
| `php -l` no controller | Aprovado |
| `php-cs-fixer fix --dry-run --diff` só no controller (container isolado) | Aprovado, sem alterações |
| `phpstan analyse --memory-limit=512M` (container isolado) | Aprovado, `No errors` (escopo atual: `app/components`) |
| `git grep -n '"classroom_fk = \$classroom"'` no controller | Sem resultados |
| Smoke HTTP A/B (tabelas abaixo), login `admin`, tenant `demo` | Aprovado |
| Controle sem sessão em `batchupdatEnrollment` | Aprovado: 302 para o login |
| `composer lint` global | **Não rodado**: já falha no repositório todo (registrado no R0-SQL-04); rodei só no arquivo do card |
| E2E no navegador (telas de turma) | **Não verificado** |

- **Smoke A/B** (base de desenvolvimento, turma 356 com 85 linhas na lista; A = código antigo na porta 80, B = corrigido na 8081, ambos logados como `admin`):

| `batchupdatEnrollment`, caso | A: antigo | B: corrigido |
|---|---|---|
| `id` válido | 200, 85 linhas | 200, **página idêntica** (mesmo hash) |
| `id) OR (1=1` | 500, erro de SQL | 400 |
| `abc` | 500 | 400 |
| `id AND SLEEP(3)` | **mais de 240 s** (o `SLEEP` roda por linha: 85 × 3 s) | 400 em cerca de 1 s |
| `id OR 1=1` | **mais de 240 s** (monta a página das matrículas de todas as turmas, cerca de 9,9 mil) | 400 em cerca de 1 s |

| `updateComplementaryActivity`, caso | A: antigo | B: corrigido |
|---|---|---|
| seis campos `null` (POST realista do formulário) | 200, 171 opções | 200, idêntico |
| dois ids válidos | 200, 169 opções | 200, idêntico |
| aspa solta `1"` | 500, erro de SQL | 200, igual ao sem seleção |
| `10101" OR "1"="1` | 200, 171 opções | 200, 171 opções |
| só o tipo 1 como `null` (demais ausentes) | 500 | 200 |

  - O último caso é uma **mudança de comportamento consciente**: o código antigo gerava `&& id!=""` sem a primeira condição (erro de SQL). Isso só acontecia com um POST parcial, que o formulário não envia.
  - O card pedia a contagem de `<tr` do `OR 1=1` antes da correção; o código antigo não terminou em 240 s, então a evidência é o tempo.
- **Varredura do arquivo** (comando do Contexto): as concatenações encontradas foram `actionBatchupdatEnrollment` (≈L380, corrigida) e `actionUpdateComplementaryActivity` (≈L172–177, corrigida). O `createCommand($sql1)` de ≈L318 usa texto fixo, sem entrada. As demais `condition` (≈L89, ≈L484, ≈L676, ≈L728, ≈L999) já eram parametrizadas.
- **Pendências e issues sugeridas** (nada disso foi alterado):
  - O ramo de gravação de `batchupdatEnrollment` percorre o `$_POST` inteiro e faz `findByPk($eid)->update(...)` sem checar escola ou turma, e com `$enro` nulo derruba a action (já citado no Contexto; R3-SEC-03 e R4-CLASSROOM).
  - `loadModel` faz `$return->complementary_activity_type_1` sem checar `$return` nulo (≈L845): um id numérico inexistente ainda derruba a action.
  - `actionUpdateComplementaryActivity` e `actionBatchUpdateTotal` têm o mesmo padrão de gravação sem checagem de escola.
  - O smoke em Python não é Codeception e precisa de duas instâncias (A e B). Decidir se fica em `tests/acceptance/` ou se vira gate do `tag-qa`.
- **Riscos:**
  - Os casos pesados do código antigo deixaram requisições rodando por minutos na base de desenvolvimento. Por isso o script tem `TAG_SKIP_HEAVY_A`.
  - O smoke usou o usuário `admin`; o card pede usuário comum. Outros papéis não foram testados.
  - Em produção, até o R0-INFRA-03, o 400 pode aparecer como 500 em branco.
  - Ids negativos ou com sinal passam a dar 400 (antes davam 404 do `loadModel`).
- **Revisão do Guardião:** `tag-plan-guardian` (subagente independente): **aprovado com ressalvas**; a pendência era este Handoff e o texto do docstring do smoke, ambos resolvidos aqui.
- **Revisão humana obrigatória antes do merge** (card de segurança).

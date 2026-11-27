---
id: R0-SQL-08
title: "classroom: usar parâmetro em DefaultController::actionBatchupdatEnrollment ($id da URL)"
release: R0
module: classroom
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
_Preenchido por quem executar._

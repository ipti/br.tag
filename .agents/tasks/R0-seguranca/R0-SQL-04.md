---
id: R0-SQL-04
title: "student: usar parâmetro em EnrollmentController::actionGetModalities ($_POST['Stage'])"
release: R0
module: student
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: bloqueado
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

- **Feito:** validado `Stage` como inteiro antes de consultar; estágio zero mantém a listagem completa; os demais usam `CDbCriteria` com `:stage`. O laço e o HTML de saída foram preservados.
- **Decisões:** somente os testes e o controller do escopo foram alterados. Dois chamadores ativos foram encontrados em `app/modules/student/views/enrollment/_form.php` e `app/modules/student/views/student/_form.php`: ambos oferecem valores de 0 a 7, sem opção vazia. A varredura do controller não encontrou outra concatenação SQL no padrão indicado no card.
- **Caracterização:** golden master gerado antes da correção e preservado após a correção. Na aplicação Yii de teste isolada, a versão original respondeu a `Stage=1 OR 1=1` com a mesma saída de `Stage=0`.
- **Gates:**

| Verificação | Resultado |
|---|---|
| `php tests/acceptance/R0Sql04Characterization.php` | Aprovado antes e depois, dados sintéticos. |
| `php tests/acceptance/R0Sql04Security.php` | Aprovado: ataques, array, vazio e ausente rejeitados antes de consultar; bind e zero verificados. |
| `php tests/acceptance/R0Sql04MysqlIntegration.php` em container com Yii e MySQL descartável | Aprovado; tabela temporária e dados sintéticos. |
| `python tests/acceptance/R0Sql04HttpIntegration.py` com dois servidores Yii e MySQL descartáveis | Aprovado com identidade e configuração sintéticas, `YII_DEBUG=1`: filtro de acesso, saídas válidas iguais ao baseline e HTTP 400 para entradas inválidas. Não cobre login/bootstrap reais do TAG nem produção. |
| Busca `stage = $stage` no controller | Nenhuma ocorrência. |
| Lint direcionado ao controller, container isolado somente leitura | Aprovado. |
| Lint global equivalente a `composer lint`, container isolado | Falhou: 202 arquivos do repositório fora do padrão; controller do card aprovado. |
| PHPStan equivalente a `composer analyse`, container isolado | Falhou antes da análise: `app/domain` não existe na main. A configuração está fora do escopo. |
| Navegador nos dois formulários | Não verificado: superfície de navegador indisponível. O localhost informado aponta para o checkout do Gemini, não para este worktree. |
| Semgrep SQL injection | Não verificado: ferramenta/gate não disponível nesta base. |

O teste HTTP acima foi uma execução pontual; o comando Python sozinho não prepara os serviços. Para reproduzi-lo, é necessário um MySQL descartável com banco `tag_r0_test`, tabela de modalidades e três linhas sintéticas; dois servidores Yii com o bootstrap de teste nas portas 18085 e 18086; e uma cópia temporária do controller de `origin/main` em `tests/acceptance/R0Sql04Baseline.tmp.php` para o servidor de baseline. Ambos os servidores devem montar código e dependências somente para leitura e usar a rede de teste isolada. Remova os servidores, o banco descartável e o arquivo temporário após executar.

- **Pendências e issues sugeridas:** corrigir os gates globais em card de infraestrutura separado; validar os formulários no navegador em ambiente de teste do worktree; comprovar o status HTTP em produção após R0-INFRA-03. O plano e o backlog ainda não estavam integrados à main na criação desta branch.
- **Riscos:** a autenticação do smoke é sintética; a aplicação TAG real não foi exercitada no HTTP. Com o handler de exceções desligado em produção, o 400 pode ser apresentado como 500 até R0-INFRA-03. Revisão humana obrigatória antes de merge.
- **Revisão do Guardião:** aprovado com ressalvas. O card permanece bloqueado enquanto os gates obrigatórios e a verificação pendente não estiverem resolvidos.

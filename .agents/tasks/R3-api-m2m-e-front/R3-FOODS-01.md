---
id: R3-FOODS-01
title: "Golden master dos pedidos de alimentos: itens aceitos e recebidos do FoodrequestController"
release: R3
module: foods
type: testes
skill: tag-golden-master
agents: [tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-TEST-01]
size: P
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, golden master; B4, piloto M2M) e seção 8.5"
write_scope:
  - tests/Integration/Foods/GoldenMaster/**
  - tests/_data/foods/**
  - tests/Support/Builders/Foods*.php
read_only:
  - app/modules/foods/**
  - app/models/**
  - tests/providers/CustomProvider.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/modules/foods/controllers/**
  - app/modules/foods/models/**
  - app/src/**
acceptance:
  - "composer test:integration -- --filter FoodsGoldenMaster   (passa no código atual, sem nenhuma alteração em produção)"
  - "composer analyse"
  - "git diff --stat main...HEAD -- app/   (vazio: este card só escreve testes e dados de teste)"
---

# R3-FOODS-01: Golden master dos pedidos de alimentos: itens aceitos e recebidos do FoodrequestController

## Contexto
O br-nham (agricultor) chama duas actions públicas do `app/modules/foods/controllers/FoodrequestController.php`: `actionUpdateAcceptedFoods()` (≈L168) e `actionUpdateReceivedFoods()` (≈L218). O R3-FOODS-02 vai extraí-las para services e o R3-API-05 expõe os mesmos services na API. Antes disso é preciso gravar o comportamento **atual**, defeitos incluídos.

Comportamento verificado no código (não corrigir, só registrar):
- Autenticação: compara `$_SERVER['HTTP_AUTHORIZATION']` com um `Bearer …` literal (hoje no código; o R0-INFRA-01 o move para env, **confirmar o nome da variável**). Sem o header a chave não existe no `$_SERVER` (aviso de índice indefinido). **Não copie o valor literal do token para testes ou fixtures.** Os testes usam o token de teste vindo do env.
- Respostas são `echo json_encode([...])` seguido de `Yii::app()->end()`, **sempre com HTTP 200**. Mensagens: `token nao informado`, `token nao autorizado`, `Nao foi possivel encontrar uma solicitacao com esse id`, `Status da solicitacao modificado com sucesso`, `Alimento registrado com sucesso`, `Não foi possível registrar…`.
- A solicitação é localizada por `FoodRequest.reference_id`; o agricultor, por `FarmerRegister.cpf` (`saveItemReceived` ≈L274, `saveItemAccepted` ≈L291). Com CPF inexistente o código lê `$farmer->id` de `null` (≈L281 e ≈L297).
- Aceitos: grava `FoodRequestItemAccepted` e muda o status para `Aceita por agricultor`.
- Recebidos: grava `FoodRequestItemReceived` e chama `checkItemsReceived()` (≈L307). Quando a conta fecha, anula o estado `loginInfos` da sessão (≈L251) e muda o status para `Finalizada`. A comparação usa `&&` entre quantidade e unidade (≈L340), então diferença só de quantidade com a mesma unidade conta como completa. Ignora o resultado de `saveItemReceived` ao finalizar.
- Os models usam `CTimestampBehavior` com `CONVERT_TZ(NOW(), "+00:00", "-03:00")`: datas devem ser mascaradas nos snapshots.

**Armadilha de execução:** `Yii::app()->end()` chama `exit`. Chamar a action direto dentro do processo do PHPUnit encerra a suíte. Use (a) HTTP contra o servidor embutido do PHP com router para `index.php` (host `localhost` cai no tenant `demo`, `instance.php` ≈L14–L21) ou (b) um processo separado por caso. Registre a escolha no Handoff.

## Objetivo
Uma suíte de integração que passa no código de hoje e prova que o `FoodrequestController` legado e, depois, os services do R3-FOODS-02 produzem a mesma resposta e os mesmos efeitos no banco.

## Passos
1. Leia `.agents/agents/tag-qa.md` (seção "Como montar um golden master") e a skill `tag-golden-master`, se já existir.
2. Monte o dataset anonimizado (builders em `tests/Support/Builders/`, Faker pt_BR, `CustomProvider` para CPF): 1 edital (`FoodNotice`), 1 solicitação com `reference_id` e 2 itens (`FoodRequestItem`), 2 alimentos, 2 agricultores com CPF válido fake.
3. Escreva os casos, cada um com resposta HTTP, corpo JSON e estado das tabelas `food_request`, `food_request_item_accepted` e `food_request_item_received` (ordenadas por `id`, `created_at`/`updated_at` mascarados):
   - aceitos: caminho feliz; header ausente; header vazio; token errado; `requestId` inexistente; CPF inexistente;
   - recebidos: item parcial (status não muda); todos os itens (status `Finalizada`); quantidade diferente com a mesma unidade; CPF inexistente; `requestId` inexistente.
4. Grave os snapshots JSON ordenados em `tests/_data/foods/golden/` e o teste que reexecuta e compara. O CPF inexistente e a diferença só de quantidade registram o comportamento **atual**, mesmo errado.
5. Para cada defeito (CPF inexistente lendo `null`, `&&` na comparação, status 200 em erro, aceitos sem transação), escreva um segundo teste com o comportamento esperado marcado `markTestIncomplete('Bug: <issue>')` e liste a issue sugerida no relatório. Não corrija.
6. Registre no Handoff como rodar a suíte, o banco usado (serviço `mysql-test` do R1-TEST-01) e a variável do token de teste.

## Fora de escopo
- Alterar código de produção (controller, models, `app/src`).
- Corrigir os defeitos encontrados; cada um vira issue com decisão de produto.
- Os endpoints da API (R3-API-05).

## Critérios de aceite
- [ ] A suíte `FoodsGoldenMaster` passa no código atual, sem alteração em `app/`.
- [ ] Os 11 casos do passo 3 existem, com snapshot de resposta e de banco.
- [ ] Nenhum valor de token, CPF ou dado real aparece nos arquivos.
- [ ] Os defeitos têm teste `markTestIncomplete` e issue sugerida no Handoff.
- [ ] Os testes são determinísticos (sem `sleep`, datas mascaradas, ordem independente) e rodam duas vezes seguidas com o mesmo resultado.

## Como verificar
```bash
composer test:integration -- --filter FoodsGoldenMaster
composer test:integration -- --filter FoodsGoldenMaster   # repetir: mesmo resultado
git diff --stat main...HEAD -- app/                        # vazio
```

## Handoff
_Preenchido por quem executar._

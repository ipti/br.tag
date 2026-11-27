---
id: R3-API-05
title: "API: endpoints M2M de itens aceitos e recebidos (foods), homologação com o br-nham e desligamento dos antigos"
release: R3
module: foods
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-API-04, R3-FOODS-02]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B4, piloto M2M) e seção 14 (B4/B5: br-nham homologado)"
write_scope:
  - app/modules/api/controllers/v1/FoodrequestsController.php
  - app/modules/api/openapi/v1.yaml
  - app/config/api.php
  - app/src/Foods/**
  - tests/Api/Foods/**
  - tests/Unit/Foods/**
read_only:
  - app/modules/foods/controllers/FoodrequestController.php
  - app/modules/foods/models/**
  - tests/Integration/Foods/GoldenMaster/**
  - app/src/Shared/Auth/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - app/models/**
  - .github/workflows/**
acceptance:
  - "npm run api:lint"
  - "composer test:api -- --filter Foods   (contrato + paridade com o endpoint legado)"
  - "composer test:unit -- --filter Foods"
  - "composer analyse"
  - "Homologação: br-nham registra um item aceito e um recebido na API nova e o banco fica igual ao do fluxo legado"
---

# R3-API-05: API: endpoints M2M de itens aceitos e recebidos (foods), homologação com o br-nham e desligamento dos antigos

## Contexto
É o piloto M2M e a implementação de referência da skill `tag-add-api-endpoint`. Hoje o br-nham chama duas actions públicas do `FoodrequestController` (`actionUpdateAcceptedFoods` ≈L168 e `actionUpdateReceivedFoods` ≈L218) com um Bearer fixo, e elas respondem sempre HTTP 200. O R3-FOODS-02 extraiu a regra para `RegisterAcceptedItemService` e `RegisterReceivedItemService`; o R3-API-04 entregou o token (`client_credentials`), o `ApiController` e a validação de contrato. Este card liga os dois pontos e retira o legado.

Fatos e armadilhas:
- A solicitação é localizada hoje por `FoodRequest.reference_id` (UUID de até 36 caracteres), enviado como `requestId`. **Confirmar com o time do br-nham** que o `{id}` do caminho é esse `reference_id`, e não o `id` numérico interno.
- O `CWebApplication::createController` só aceita ids `\w+` e, com `caseSensitive=false`, os põe em minúsculas (`CWebApplication.php` ≈L322–L324). Por isso a rota pública `food-requests` (com hífen) mapeia para o controller `foodrequests`, arquivo `app/modules/api/controllers/v1/FoodrequestsController.php`, classe `Tag\Api\Controllers\v1\FoodrequestsController`. O `{id}` chega à action como `$_GET['id']`.
- O legado devolve 200 para tudo (inclusive erro) e, com CPF inexistente, lê `$farmer->id` de `null` (≈L281 e ≈L297). A API nova **não herda** esses defeitos: define os status corretos. Os defeitos continuam registrados nas issues do R3-FOODS-01.
- O escopo exigido é `foods.requests:write`. `api_client.allowed_schools` (R3-API-03) restringe por escola; as escolas de uma solicitação vêm do model `FoodRequestVsSchoolIdentification` (`app/modules/foods/models/`).
- Depois que o br-nham migrar, saem as duas rotas públicas legadas. Elas hoje estão em `app/config/public-routes/foods.php` (R2-SEC-02 e R3-SEC-03), em `app/config/csrf-exempt.php` (R2-SEC-03) e dependem da variável de env do Bearer (R0-INFRA-01).

## Objetivo
`POST /api/v1/food-requests/{id}/accepted-items` e `/received-items` funcionam com token M2M, usam os services e têm contrato validado, e o br-nham passa a usá-los, com data combinada para desligar os endpoints antigos.

## Passos
1. OpenAPI primeiro (`v1.yaml`): os dois caminhos, o corpo `{foodId, farmerCpf, amount, measurementUnit}`, respostas `201` (`requestId`, `status`, `itemId`; no recebido, também `completed`), `401`, `403` (`insufficient_scope` ou escola não permitida), `404` (solicitação), `422` (validação por campo, inclusive CPF sem agricultor) e `429`.
2. Regras REST com `verb=POST` em `app/config/api.php`, por exemplo `api/v1/food-requests/<id:[0-9a-fA-F-]{36}>/accepted-items` → `api/v1/foodrequests/acceptedItems`.
3. `FoodrequestsController` magro: `requireScope('foods.requests:write')`, verificar `allowed_schools` do cliente contra as escolas da solicitação (regra sugerida: todas as escolas da solicitação devem estar permitidas; confirmar com o time), `Input::fromArray()` a partir de `readJson()`, chamar o service com um `UserContext` de máquina (`isMachine`, `scopes`, usuário de serviço do cliente) e `renderJson(..., 201)`. Falhas passam pelo `ExceptionMapper`; se o service lançar uma exceção que não vire 404/422 conforme acima, ajuste o service sem mudar a fachada legada (o golden master do R3-FOODS-01 continua verde).
4. `tag-qa`: testes de contrato em `tests/Api/Foods/` (sem token, escopo errado, escola não permitida, JSON inválido, `amount` ≤ 0, caminho feliz) e um teste de **paridade**: o mesmo dataset do golden master, uma vez pelo endpoint legado e outra pela API, com as tabelas `food_request`, `food_request_item_accepted` e `food_request_item_received` iguais (timestamps mascarados).
5. Homologação com o br-nham: crie o cliente no tenant de homologação (`yiic apiclient create --scopes=foods.requests:write --tenant=<homolog>`) e entregue `client_id` e segredo por canal seguro, **nunca** em repositório, chat ou ticket. O br-nham troca o Bearer fixo por `oauth/token` + os dois endpoints; rodem um pedido completo (aceito e recebido) e comparem o banco com o fluxo legado.
6. Combine com o produto e o br-nham a data de desligamento dos endpoints antigos e registre no Handoff. Antes dela, confirme no `access.log` do nginx que ninguém mais chama `updateAcceptedFoods`/`updateReceivedFoods` por pelo menos 14 dias (sem editar o controller).
7. Abra o card de remoção (a executar na data combinada): apagar as duas actions e os métodos privados do `FoodrequestController`, tirar as rotas de `public-routes/foods.php` e de `csrf-exempt.php`, remover a variável de env do Bearer e atualizar o OpenAPI.

## Fora de escopo
- Remover as actions legadas agora (só após a data combinada).
- Idempotência por `Idempotency-Key` (anote no Handoff se o br-nham reenvia em timeout).
- Os outros endpoints do foods e a API de leitura de pedidos (R5-FOODS).

## Critérios de aceite
- [ ] O OpenAPI descreve os dois endpoints e passa no lint.
- [ ] Os testes de contrato e o de paridade passam; o golden master do R3-FOODS-01 continua verde.
- [ ] Cliente sem o escopo `foods.requests:write` recebe 403; sem token, 401; escola fora de `allowed_schools`, 403.
- [ ] O br-nham homologado: item aceito e item recebido via API nova com o mesmo efeito no banco do fluxo legado.
- [ ] A data de desligamento está registrada no Handoff, junto com o card de remoção.

## Como verificar
```bash
npm run api:lint && composer test:api -- --filter Foods && composer test:unit -- --filter Foods && composer analyse
TOKEN=$(curl -s -u "<client_id>:<segredo>" -d "grant_type=client_credentials&scope=foods.requests:write" http://localhost/api/v1/oauth/token | jq -r .access_token)
curl -i -X POST "http://localhost/api/v1/food-requests/<reference_id>/accepted-items" -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -d '{"foodId":1,"farmerCpf":"<cpf de teste>","amount":10,"measurementUnit":"kg"}'   # 201
curl -i -X POST "http://localhost/api/v1/food-requests/<reference_id>/accepted-items" -d '{}'   # 401 problem+json
```

## Handoff
_Preenchido por quem executar._

---
id: R3-FOODS-02
title: "Extrair RegisterAcceptedItemService e RegisterReceivedItemService do FoodrequestController"
release: R3
module: foods
type: refactor
skill: tag-extract-service
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FOODS-01, R1-BASE-07]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 3, seção 6 (B3 e B4, piloto M2M) e seção 8.3"
write_scope:
  - app/src/Foods/**
  - tests/Unit/Foods/**
  - tests/Integration/Foods/Infrastructure/**
  - app/modules/foods/controllers/FoodrequestController.php
read_only:
  - tests/Integration/Foods/GoldenMaster/**
  - app/modules/foods/models/**
  - app/models/**
  - app/src/Shared/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - app/modules/foods/models/**
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter Foods"
  - "composer test:integration -- --filter FoodsGoldenMaster   (idêntico ao baseline do R3-FOODS-01, sem regravar snapshot)"
  - "cobertura do código novo em app/src/Foods ≥ 80%"
---

# R3-FOODS-02: Extrair RegisterAcceptedItemService e RegisterReceivedItemService do FoodrequestController

## Contexto
É a implementação de referência do piloto M2M (B4) para a skill `tag-add-api-endpoint`. As duas actions legadas de `app/modules/foods/controllers/FoodrequestController.php` (`actionUpdateAcceptedFoods` ≈L168, `actionUpdateReceivedFoods` ≈L218, mais `saveItemAccepted`/`saveItemReceived`/`checkItemsReceived` ≈L274–L346) misturam autenticação, leitura de `$_POST`, regra de negócio, gravação e resposta. O R3-FOODS-01 gravou o comportamento atual; este card o preserva dentro de services testáveis, que o R3-API-05 reaproveita.

Fatos de entrada:
- Contratos de `app/src/Shared` vêm do R1-BASE-07 (`UserContext`, `ExceptionMapper`, exceções `NotFound`/`ValidationFailed`/`BusinessRule`, `TransactionManager`; **confirmar** nomes no Handoff dele).
- Models legados (`FoodRequest`, `FoodRequestItem`, `FoodRequestItemAccepted`, `FoodRequestItemReceived`, `FarmerRegister`) continuam globais e entram com `use`. Eles vivem em `app/modules/foods/models/` e só são importados quando o `FoodsModule` inicia (`FoodsModule.php` ≈L20–L27). Para a API, **confirmar** que `app/config/parts/imports.php` (R1-BASE) os importa; se não, é pedido ao coordenador.
- `app/modules/foods/controllers/FoodrequestController.php` também é editado pelo R3-SEC-03 (`filters()`): não rodem em paralelo, e o segundo rebasa no primeiro.
- O Bearer sai do literal e passa a ser lido do env (R0-INFRA-01, **confirmar** o nome da variável). A conferência do token **fica no controller legado**; o service não conhece o header.
- Hoje o fluxo "aceito" grava o item e depois o status em duas gravações sem transação, e o fluxo "recebido" ignora a falha de `saveItemReceived` ao finalizar. Isso é comportamento a preservar.

## Objetivo
Dois services em `app/src/Foods/`, com testes, que reproduzem exatamente o golden master do R3-FOODS-01, e actions legadas magras que só os chamam.

## Passos
1. Leia a skill `tag-extract-service` (ou o plano B3), o piloto `app/src/Grades` (se já existir) e o golden master do R3-FOODS-01.
2. Crie (namespace `Tag\Foods\…`, `declare(strict_types=1)`, `final readonly` nos DTOs):
   - `Application/RegisterAcceptedItemService` e `Application/RegisterReceivedItemService`, cada um com um único `handle(Input, UserContext): Result`;
   - `Application/RegisterAcceptedItemInput` e `RegisterReceivedItemInput` (campos `requestReferenceId`, `foodId`, `farmerCpf`, `amount`, `measurementUnit`, com `fromArray()` que valida e lança `ValidationFailed`) e os `…Result`;
   - `Domain/ReceivedItemsCompletionPolicy`, extraída de `checkItemsReceived()` **com a mesma condição** (inclusive o `&&` que o golden master registra);
   - `Infrastructure/Yii/FoodRequestRepository`, `FarmerRegisterRepository` e `FoodRequestItemRepository` (ActiveRecord → DTOs; nenhum model sai dessa camada).
3. `Domain/` e `Application/` não usam `Yii::app()`, `$_POST`, `echo`/`exit` nem ActiveRecord. Dependências pelo construtor.
4. Reescreva as duas actions: conferir o Bearer (com `hash_equals`), montar o Input de `$_POST`, chamar o service, converter exceção em **exatamente o JSON e o HTTP 200 do legado** (o golden master manda). Não introduza 404/422 na rota legada.
5. Não acrescente transação nem mude a ordem das gravações. Se a equipe quiser atomicidade, registre no Handoff como decisão humana: muda o resultado numa falha parcial.
6. `tag-qa`: testes unitários da política e dos services com repositórios em memória (`tests/Unit/Foods/`), e de integração dos repositórios (`tests/Integration/Foods/Infrastructure/`). Rode o golden master sem regravar snapshot.
7. Remova do controller os métodos privados que ficaram sem uso.

## Fora de escopo
- Os endpoints da API e o escopo `foods.requests:write` (R3-API-05).
- Corrigir os defeitos registrados no R3-FOODS-01 (CPF inexistente, `&&`, status 200 em erro).
- Extrair as demais actions do foods (R5-FOODS) e mexer em `FoodnoticeController` (Bearer em ≈L243 e ≈L270 é do R0-INFRA-01).

## Critérios de aceite
- [ ] O golden master do R3-FOODS-01 passa sem nenhuma alteração nos snapshots.
- [ ] As duas actions legadas ficam magras e só chamam os services.
- [ ] `app/src/Foods` passa no PHPStan nível max sem baseline e sem `@phpstan-ignore`.
- [ ] Cobertura do código novo ≥ 80%.
- [ ] Nenhum literal de token no código; o Bearer vem do env.
- [ ] Nenhum arquivo fora do `write_scope` foi alterado.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Foods
composer test:integration -- --filter FoodsGoldenMaster
git grep -nE '\$_(POST|GET)|Yii::app\(\)' -- 'app/src/Foods/Domain/*' 'app/src/Foods/Application/*'   # sem resultados
git grep -n 'Bearer \$2b' -- app   # sem resultados
```

## Handoff
_Preenchido por quem executar._

---
id: R1-TEST-02
title: "Estabilizar a aceitação legada: credenciais por env, waitFor no lugar de sleep e Cests quebrados"
release: R1
module: plataforma
type: testes
skill: .agents/playbooks/e2e-testing.md
agents: [tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-TEST-01]
size: M
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco Infraestrutura de testes: testes de aceitação) e seção 7 (F0, Playwright)"
write_scope:
  - tests/acceptance/**
  - tests/robots/**
  - tests/builders/**
  - tests/_support/Helper/Acceptance.php
  - tests/acceptance.suite.yml
  - .github/workflows/acceptance-nightly.yml
read_only:
  - tests/providers/CustomProvider.php
  - .agents/rules/e2e-testing.md
  - package.json
forbidden:
  - app/**
  - themes/**
  - composer.json
  - docker-compose.yml
acceptance:
  - "git grep -c 'sleep(' -- tests/acceptance tests/robots tests/builders   (sem resultados)"
  - "git grep -nE '\\$(user|secret) = \"\"' -- tests/acceptance   (sem resultados)"
  - "docker exec tag-app ./vendor/bin/codecept dry-run acceptance   (todos os Cests carregam)"
  - "Execução real dos Cests contra a stack local, com URL, credenciais e aprovação pedidas ao usuário"
---

# R1-TEST-02: Estabilizar a aceitação legada: credenciais por env, waitFor no lugar de sleep e Cests quebrados

## Contexto
Os 14 Cests de Codeception em `tests/acceptance/` (WebDriver, Chrome, 1280x960, `http://localhost/`, em `tests/acceptance.suite.yml`) não rodam no CI e estão frágeis. O plano mantém esses testes até serem portados para o Playwright (R1-FRONT-01); aqui eles só ficam confiáveis.

Fatos conferidos:
- **Credenciais vazias no código:** 13 dos 14 Cests têm `$user = ""` e `$secret = ""` no `_before` (todos exceto `InstructorHistoryCest`, que já lê `getenv('TAG_TEST_USER')` e `getenv('TAG_TEST_PASSWORD')`, ≈L23–24; esse é o padrão a seguir). `LoginCest` tem casos negativos propositais (`"coentro"`), que devem ficar como estão.
- **200 chamadas a `sleep()`** em `tests/acceptance`, `tests/robots` e `tests/builders`: `StudentsCest` 94, `StudentsEditCest` 55, `ClassroomCest` 12, `InstructorCest` 10, `InstructorHistoryCest` 5, `StudentsRemoveCest` 5 e o restante de 2 a 3 por arquivo. Já existem cerca de 21 usos de `waitFor*`.
- **`ClassroomRemoveCest`** (≈L5) faz `require_once __DIR__.'/../builders/ClassroomBuilder.php.php'`, arquivo que não existe (o real é `ClassroomBuilder.php`). Ele ainda depende de `StudentsCest::addStudentsRapidFieldsRequired` (um teste chama outro): confirmar se o encadeamento funciona.
- **`tests/robots/ManageStagesRobots.php`** declara `class StagesRobots`, e o `ManageStagesCest` usa `new StagesRobots(...)` (≈L26, L45, L63, L94): funciona por causa do `require_once` por caminho, mas o nome da classe não bate com o arquivo.
- `tests/_support/Helper/Acceptance.php` existe e está vazio: lugar certo para helpers de espera.
- O `package.json` da raiz só tem `selenium-standalone ^9.5.0`; o `tests/acceptance.suite.yml` não define Selenium.
- A regra `.agents/rules/e2e-testing.md` vale: antes de rodar no navegador, peça URL, credenciais, dados necessários e aprovação do plano.

## Objetivo
Os Cests lêem usuário, senha e URL do ambiente, esperam por condição (nunca por tempo fixo), carregam sem erro e podem rodar à noite no CI sem bloquear PRs.

## Passos
1. **Credenciais.** Troque `$user`/`$secret` vazios por `getenv('TAG_TEST_USER')` e `getenv('TAG_TEST_PASSWORD')` (mesmos nomes do `InstructorHistoryCest`), de preferência por um único método em `LoginRobots` que todos os `_before` chamem. Se faltar variável, falhe com mensagem clara em vez de tentar logar com vazio. A URL passa a vir de `TAG_BASE_URL` (padrão `http://localhost/`) no `acceptance.suite.yml` (confirmar a sintaxe de parâmetros da versão do Codeception instalada pelo R1-TEST-01).
2. **Esperas.** Crie em `Helper/Acceptance.php` os helpers `waitForAjax()` (`jQuery.active === 0`), `waitForPageReady()` e, se precisar, `waitForUrlContains()`. Substitua cada `sleep(n)` por `waitFor*` com condição observável (texto, elemento visível, URL, AJAX terminado) e timeout explícito. Faça por arquivo, rodando o Cest a cada arquivo; nenhum `sleep()` pode sobrar (nem em `robots` e `builders`).
3. **`ClassroomRemoveCest`.** Corrija o `require_once` para `ClassroomBuilder.php`. Se o encadeamento com `StudentsCest` for instável, crie os dados no próprio Cest com os builders.
4. **`ManageStagesRobots`.** Renomeie a classe para `ManageStagesRobots` e atualize os quatro usos no `ManageStagesCest`.
5. **CI noturno.** Crie `.github/workflows/acceptance-nightly.yml` com `schedule` e `workflow_dispatch`, **fora do gate de PR** (não usar `pull_request`): sobe a stack, aplica o dump de teste, roda `codecept run acceptance` com um serviço `selenium/standalone-chrome` e lê credenciais e URL de `secrets`. Confirmar no Handoff qual stack e qual dump serão usados (o seed demo vem do R1-FRONT-01).
6. Rode `codecept dry-run acceptance`. Depois, com URL, credenciais e aprovação do usuário, rode os Cests na stack local e registre o resultado por Cest.

## Fora de escopo
- Reescrever os Cests em Playwright ou mudar o que cada um testa (R1-FRONT-01).
- Dados reais de alunos: use sempre os builders com Faker.
- Mudar `composer.json`, `docker-compose.yml` ou qualquer arquivo de `app/`.

## Critérios de aceite
- [ ] Nenhum `sleep()` em `tests/acceptance`, `tests/robots` e `tests/builders`.
- [ ] Nenhum Cest tem usuário ou senha no código, exceto os valores propositalmente inválidos dos casos negativos.
- [ ] `ClassroomRemoveCest` e `ManageStagesCest` carregam e usam as classes certas.
- [ ] `codecept dry-run acceptance` passa para os 14 Cests.
- [ ] O workflow noturno existe, não roda em `pull_request` e nenhuma credencial aparece nele em texto claro.
- [ ] Resultado da execução real registrado por Cest (passou, falhou ou bloqueado), sem enfraquecer assert para passar.
- [ ] **O sistema não muda:** nenhum arquivo fora de `tests/` e do workflow foi alterado.

## Como verificar
```bash
git grep -c "sleep(" -- tests/acceptance tests/robots tests/builders      # esperado: sem resultados
git grep -nE '\$(user|secret) = ""' -- tests/acceptance                   # esperado: sem resultados
docker exec tag-app ./vendor/bin/codecept dry-run acceptance
# execução real (peça URL, credenciais e aprovação antes):
TAG_BASE_URL=http://localhost/ TAG_TEST_USER=<usuário> TAG_TEST_PASSWORD=<senha> ./vendor/bin/codecept run acceptance --steps
```

## Handoff
_Preenchido por quem executar._

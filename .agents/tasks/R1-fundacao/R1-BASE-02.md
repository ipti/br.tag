---
id: R1-BASE-02
title: "composer.json: sair das versões dev (prefer-stable) e limpar as dependências de teste e de qualidade"
release: R1
module: plataforma
type: fundacao
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, último item do bloco composer.json)"
write_scope:
  - composer.json
  - composer.lock
read_only:
  - rector.php
  - .php-cs-fixer.php
  - app/tests/**
  - app/modules/sedsp/datasources/**
  - app/modules/sagres/models/SagresConsultModel.php
  - app/modules/foods/controllers/FoodnoticeController.php
forbidden:
  - app/**
  - themes/**
  - .github/**
  - Dockerfile
  - docker-compose.yml
acceptance:
  - "docker exec tag-app composer update"
  - "docker exec tag-app composer show --locked   (só websupport/yii-sentry em versão dev; nenhum beta, alpha ou RC)"
  - "composer lint"
  - "composer analyse"
  - "Smoke de sedsp (Guzzle) e de sagres (JMS Serializer), conforme a seção Como verificar"
---

# R1-BASE-02: composer.json: sair das versões dev (prefer-stable) e limpar as dependências de teste e de qualidade

## Contexto
**Card do coordenador:** só `composer.json` e `composer.lock`. Este PR precisa ser **isolado**, porque um `composer update` completo troca dezenas de pacotes e não pode vir misturado com outra mudança.

Fatos conferidos em `composer.json` e `composer.lock`:
- `"minimum-stability": "dev"` (L2) sem `prefer-stable`. Resultado: **91 dos 117 pacotes** do lock estão em versão dev, beta ou alpha. Os mais relevantes: `phpunit/phpunit 8.5.x-dev`, `codeception/codeception 4.2.3-beta4`, `phpstan/phpstan 2.2.x-dev`, `rector/rector dev-main`, `guzzlehttp/guzzle 6.5.x-dev`, `symfony/validator 4.4.x-dev`, `sentry/sentry 3.x-dev`, `ramsey/uuid 4.x-dev`, `fakerphp/faker 1.24.x-dev`, `jms/metadata dev-master`. O `friendsofphp/php-cs-fixer` está em `v3.9.5`.
- `websupport/yii-sentry` é pedido como `0.x-dev` (≈L13) e vira `dev-master`. Se o pacote não tiver tag estável, ele continua dev: tudo bem.
- `phpunit/phpunit-selenium ^8.0` só é usado por `app/tests/CWebTestCaseCustom.php` (que será removido em R1-TEST-01). O único outro uso é o `use PHPUnit\Extensions\Selenium2TestCase\...` morto em `app/modules/student/views/studentimc/studentIndex.php` (L3), tratado em R1-DOCKER-01.
- `phpunit/php-code-coverage ^7.0` trava a cobertura na era do PHPUnit 8.
- `rector/rector` está em `dev-main`; o `rector.php` usa `LevelSetList` e `SetList::PHP_*` (L6–7 e L25–35), que podem não existir no Rector 2 (confirmar no changelog do Rector 2). A reescrita do arquivo é o R1-BASE-06; até lá, `composer rector` pode ficar quebrado e isso deve ser registrado.
- `goetas-webservices/xsd2php-runtime` aparece duas vezes: em `require` (`^0.2.2`, L9) e em `require-dev` (`0.2.16`, L28).
- Guzzle é usado em `app/modules/sedsp/datasources/sed/*` e em `app/modules/foods/controllers/FoodnoticeController.php`. O JMS Serializer (3.29.1, fixo) é usado em `app/modules/sagres/models/SagresConsultModel.php`.

## Objetivo
O `composer.lock` fica em versões estáveis (exceto `websupport/yii-sentry`), sem `phpunit-selenium` e sem a trava de cobertura, com Rector 2 e php-cs-fixer atual.

## Passos
1. Em `composer.json`, acrescente `"prefer-stable": true` (mantendo `minimum-stability: dev`, por causa do `websupport/yii-sentry`). Registre no Handoff se for viável trocar para `stable` com `websupport/yii-sentry` como `0.x-dev@dev`.
2. Remova `phpunit/phpunit-selenium` e `phpunit/php-code-coverage` de `require-dev`.
3. Troque `rector/rector` para `^2` e `friendsofphp/php-cs-fixer` para a versão 3.x estável atual (`^3`; confirmar qual o Composer resolve).
4. Deixe `goetas-webservices/xsd2php-runtime` só em `require` (`^0.2.2`).
5. Rode `docker exec tag-app composer update`. Se a resolução falhar, **não** afrouxe restrições em silêncio: registre o conflito no Handoff.
6. Confira com `composer show --locked` que não restou versão dev, beta, alpha ou RC além do `websupport/yii-sentry`.
7. Rode `composer lint` e `composer analyse`. Se o php-cs-fixer novo apontar violações que o antigo não apontava, **não** reformate o legado aqui: registre a contagem no Handoff (a configuração é tratada no R1-BASE-06).
8. Faça o smoke de sedsp e de sagres (abaixo) e registre os resultados.

## Fora de escopo
- Subir majors de Guzzle (6→7), Symfony ou JMS: só o que o `prefer-stable` resolver dentro das restrições atuais.
- Codeception 5 e PHPUnit 11/12 (R1-TEST-01).
- Atualizar o `yiisoft/yii` (R1-BASE-03) e reescrever `rector.php` e `.php-cs-fixer.php` (R1-BASE-06).
- Remover `app/tests/` e o `use` morto da view (R1-TEST-01 e R1-DOCKER-01).

## Critérios de aceite
- [ ] `composer.json` tem `prefer-stable: true`, não tem mais `phpunit/phpunit-selenium` nem `phpunit/php-code-coverage`, tem `rector/rector ^2` e o php-cs-fixer 3.x atual.
- [ ] `composer show --locked` não lista beta, alpha, RC nem `x-dev`; `websupport/yii-sentry` é a única exceção.
- [ ] `xsd2php-runtime` aparece uma vez só.
- [ ] **O comportamento não muda:** as telas que usam Guzzle (sedsp, foods/FoodnoticeController) e JMS (sagres) respondem como antes.
- [ ] Pendências conhecidas (Rector 2 quebrando o `rector.php`, novas violações do php-cs-fixer) estão no Handoff.

## Como verificar
```bash
docker exec tag-app composer update
docker exec tag-app composer show --locked | grep -E "dev-|x-dev|alpha|beta|RC"   # esperado: só websupport/yii-sentry
composer lint
composer analyse
# JMS sobe sem erro (sagres)
docker exec tag-app php -r 'require "vendor/autoload.php"; JMS\Serializer\SerializerBuilder::create()->build(); echo "ok\n";'
# Guzzle sobe sem erro (sedsp, foods)
docker exec tag-app php -r 'require "vendor/autoload.php"; echo get_class(new GuzzleHttp\Client()), "\n";'
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes):
- **sedsp:** abrir `?r=sedsp/default/index` e uma ação que consulte a SED (precisa de credencial de homologação; `SEDSP_URL` está no `docker-compose.yml`). Se não houver credencial, registre como bloqueado.
- **sagres:** abrir `?r=sagres/default/index` e gerar a pré-visualização/XML de uma competência.
- **foods:** abrir uma tela de `FoodnoticeController` que chame a API externa, se existir ambiente.

## Handoff
_Preenchido por quem executar._

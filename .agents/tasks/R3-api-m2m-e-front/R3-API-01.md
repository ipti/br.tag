---
id: R3-API-01
title: "API: entrada api.php, rota /api/ no nginx, app/config/api.php e ApiWebApplication"
release: R3
module: plataforma
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R0-INFRA-02, R2-SEC-01]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 3 e seção 6 (B4, como hospedar a API no Yii 1)"
write_scope:
  - api.php
  - app/config/api.php
  - app/src/Shared/Http/**
  - app/src/Shared/Infrastructure/Yii/ApiWebApplication.php
  - docker/nginx/default.conf
  - tests/Unit/Shared/Http/**
  - tests/Integration/Api/**
read_only:
  - index.php
  - instance.php
  - config.php
  - app/config/main.php
  - app/config/parts/imports.php
  - vendor/yiisoft/yii/framework/web/CUrlManager.php
  - vendor/yiisoft/yii/framework/web/CWebApplication.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - app/models/**
  - .github/workflows/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter Shared/Http"
  - "curl: GET /api/v1/inexistente → 404 application/problem+json; GET /api.php?r=administrator/index → 404; OPTIONS /api/v1/x → 204 com cabeçalhos CORS"
---

# R3-API-01: API: entrada api.php, rota /api/ no nginx, app/config/api.php e ApiWebApplication

## Contexto
**Card do coordenador:** `docker/nginx/default.conf` (criado no R0-INFRA-02) é arquivo com dono. Os demais arquivos são novos.

A API roda no mesmo código e no mesmo host do web: o tenant continua vindo do host (`instance.php` ≈L14–L21: subdomínio → banco `<sub>.tag.ong.br`; `localhost` → `demo`; `INSTANCE` em ≈L42). O `index.php` carrega, nesta ordem, `yii.php`, `vendor/autoload.php`, `config.php` e `instance.php` (≈L29–L32) e só então cria a aplicação (`Yii::createWebApplication($config)->run()`, ≈L51). A ordem importa porque `config.php` chama `Yii::getVersion()` (≈L16). Os 13 `require_once` do sagres e o `FeaturesComponent` em `index.php` serão removidos pelo autoload do R1-BASE; **confirmar** o que sobrou antes de copiar.

Armadilhas verificadas no código:
- **`CUrlManager` devolve `$_GET[routeVar]` mesmo quando uma regra casa** (`CUrlManager.php` ≈L372–L374; sem regra e sem `useStrictParsing` devolve o pathInfo). Com `routeVar='r'`, `api.php?r=administrator/index` rodaria um controller web. Daí o `routeVar` neutro **e** a trava em `ApiWebApplication`.
- **Não use `CMap::mergeArray` no `urlManager`:** o `main.php` (≈L145–L209) tem regras em chaves de texto (`'<controller:\w+>/<action:\w+>'`…) que seriam mescladas. Substitua o componente inteiro.
- O `main.php` configura `modules` com o gii (≈L80–L87) e todos os módulos web, `import` de `application.controllers.*` (≈L55) e o Sentry com `before_send` que lê `Yii::app()->user->loginInfos->id` (≈L242–L253), que é `null` num request sem token. Sobrescreva esses três no `api.php`.
- `CWebApplication::createController` só aceita ids `\w+` (≈L322) e, com `caseSensitive=false`, põe o id em minúsculas (≈L324). O arquivo precisa se chamar `ucfirst(id).'Controller.php'`, e o id não tem hífen.
- Em produção `YII_ENABLE_EXCEPTION_HANDLER` pode estar `false` (`config.php` ≈L35–L37). Não dependa dele: capture a exceção em `run()`.
- Os models de módulos (por exemplo `foods.models.*`) só são importados quando o módulo web inicia (`FoodsModule.php` ≈L20). Na API o import vem de `app/config/parts/imports.php` (R1-BASE); **confirmar** que o `foods` está lá.

## Objetivo
Uma entrada HTTP separada, `api.php`, que só despacha rotas `api/...`, devolve erros em `application/problem+json` e não tem sessão, CSRF nem gii.

## Passos
1. `api.php` na raiz: mesma ordem do `index.php`, criando `Yii::createApplication(\Tag\Shared\Infrastructure\Yii\ApiWebApplication::class, $config)->run()` (`YiiBase.php` ≈L130) com `app/config/api.php`.
2. `app/config/api.php`: carregue o `main.php` e **monte** o resultado, sem mesclar:
   - `urlManager`: `urlFormat=path`, `showScriptName=false`, `useStrictParsing=true`, `routeVar` neutro (por exemplo `__route`), regras REST com `verb` (as do `api/v1` entram nos cards seguintes);
   - `modules`: só `api` (declarado no R3-API-02); sem gii, sem módulos web; `import` vindo de `parts/imports.php`, sem `application.controllers.*`;
   - `request`: o R2-SEC-03 configura no `main.php` o `TagHttpRequest` com `enableCsrfValidation=true`, e o Yii valida o CSRF em `onBeginRequest`, antes do roteamento. Substitua o componente inteiro por um `CHttpRequest` sem CSRF nem cookie validation;
   - `session`: o R2-SEC-01 cria o componente no `main.php`; remova-o (`unset`) para a API não emitir `PHPSESSID`;
   - `user`: `\Tag\Shared\Infrastructure\Yii\ApiWebUser` (a classe nasce no R3-API-02; aqui só é referenciada, a instanciação é preguiçosa);
   - `sentry`: `before_send` que não toca em `loginInfos` sem checar;
   - `errorHandler` sem `errorAction` web.
3. `Tag\Shared\Infrastructure\Yii\ApiWebApplication extends CWebApplication`: sobrescreva `createController`/`runController` para aceitar só rotas que começam com `api/` (qualquer outra → 404); trate `OPTIONS` e CORS antes do roteamento (origens permitidas vindas de `API_CORS_ORIGINS` no env; preflight 204 sem autenticação); capture `Throwable` em `run()` e responda com `Tag\Shared\Http\ProblemDetails` (classe pura: `type`, `title`, `status`, `detail`, `instance`), com status correto (400, 401, 403, 404, 405, 422, 500). 500 vai para o Sentry sem vazar stack trace.
4. nginx (`docker/nginx/default.conf`): `location ^~ /api/` encaminha ao PHP-FPM com `SCRIPT_FILENAME $document_root/api.php`. Só `index.php` e `api.php` executam PHP (B0). Confirme que o header `Authorization` chega em `HTTP_AUTHORIZATION`.
5. `tag-qa`: testes unitários do `ProblemDetails` e da trava de rota; smoke de integração (servidor embutido do PHP com router para `api.php`, como o R3-API-04 formaliza).

## Fora de escopo
- O módulo `app/modules/api` e o `ApiController` (R3-API-02), o JWT (R3-API-03), o endpoint de token e o OpenAPI (R3-API-04).
- `limit_req` no nginx (R3-API-04).
- Qualquer mudança em `index.php` e no `main.php`.

## Critérios de aceite
- [ ] `/api/v1/inexistente` responde 404 com `Content-Type: application/problem+json`.
- [ ] `api.php?r=administrator/index` e `api.php?__route=administrator/index` respondem 404; nenhum controller web é alcançável.
- [ ] `OPTIONS` devolve 204 com CORS só para origens permitidas, sem exigir token.
- [ ] Nenhum cookie `PHPSESSID` é emitido por resposta da API.
- [ ] Exceção não tratada vira 500 em `problem+json` sem stack trace, mesmo com `YII_ENABLE_EXCEPTION_HANDLER=false`.
- [ ] `app/src` no PHPStan nível max, sem baseline.

## Como verificar
```bash
curl -i "http://localhost/api/v1/inexistente"                    # 404 application/problem+json
curl -i "http://localhost/api.php?r=administrator/index"         # 404
curl -i -X OPTIONS "http://localhost/api/v1/x" -H "Origin: http://permitida" -H "Access-Control-Request-Method: POST"   # 204
curl -si "http://localhost/api/v1/inexistente" | grep -i set-cookie   # vazio
composer lint && composer analyse && composer test:unit -- --filter Shared/Http
```

## Handoff
_Preenchido por quem executar._

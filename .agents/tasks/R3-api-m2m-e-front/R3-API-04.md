---
id: R3-API-04
title: "API: POST /api/v1/oauth/token (client_credentials), rate limit, OpenAPI e testes de contrato"
release: R3
module: plataforma
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-API-03]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B4: M2M, limite de tentativas, documentação e contrato) e seção 14 (B4/B5)"
write_scope:
  - app/modules/api/controllers/v1/OauthController.php
  - app/modules/api/openapi/**
  - app/src/Shared/Auth/**
  - app/config/api.php
  - docker/nginx/default.conf
  - .github/workflows/**
  - composer.json
  - package.json
  - tests/Api/**
  - tests/Unit/Shared/Auth/**
read_only:
  - api.php
  - instance.php
  - app/src/Shared/Infrastructure/Yii/ApiWebApplication.php
  - app/modules/api/controllers/ApiController.php
forbidden:
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - app/models/**
acceptance:
  - "npm run api:lint   (lint do app/modules/api/openapi/v1.yaml)"
  - "composer test:api   (contrato contra o OpenAPI, com servidor embutido do PHP)"
  - "composer analyse"
  - "curl: 11ª tentativa no mesmo minuto → 429 com Retry-After"
---

# R3-API-04: API: POST /api/v1/oauth/token (client_credentials), rate limit, OpenAPI e testes de contrato

## Contexto
**Card do coordenador:** altera `docker/nginx/default.conf`, `.github/workflows/` (passo de lint do OpenAPI e execução de `tests/Api` no CI) e `composer.json`/`package.json` (script `test:api`, dependência de teste de contrato, `api:lint`). Os demais arquivos são do módulo `api`.

É o primeiro endpoint de verdade e fixa o padrão da skill `tag-add-api-endpoint`: **spec OpenAPI antes do código**, DTO, controller magro e teste de contrato. O M2M usa `client_credentials` (RFC 6749 §4.4), sem refresh token. Emissão e validação de JWT, `api_client` e `api_auth_attempt` vêm do R3-API-03.

Fatos que moldam o desenho:
- `ApiController` (R3-API-02) nunca chama `Yii::app()->end()`; o `OauthController` devolve a resposta pelo retorno.
- Erros do endpoint de token seguem o formato do RFC 6749 §5.2 (`{"error":"invalid_client","error_description":"…"}`), que as bibliotecas OAuth esperam. Os demais endpoints usam `application/problem+json`. Registre a exceção no OpenAPI. Toda resposta de token leva `Cache-Control: no-store` e `Pragma: no-cache`.
- `invalid_client` volta com 401 e `WWW-Authenticate: Basic`. Cliente inexistente e segredo errado têm a mesma resposta e o mesmo custo: rode `password_verify` contra um hash fictício quando o cliente não existe.
- O limite de tentativas é em duas camadas. No PHP: upsert atômico em `api_auth_attempt` (`INSERT … ON DUPLICATE KEY UPDATE attempts = attempts + 1`, chave `bucket` + `window_start`, janela de 60 s) e consulta do contador. No nginx: `limit_req` na rota do token. Atrás de proxy, `$binary_remote_addr` e `REMOTE_ADDR` são o IP do proxy; **confirmar** como o ingress entrega o IP real (`real_ip`/`X-Forwarded-For`) e só confiar em proxy conhecido.
- **Armadilha do servidor embutido:** `instance.php` só cai no tenant `demo` se o host for exatamente `localhost` (≈L14–L21: `explode(".", $_SERVER['HTTP_HOST'])` e `$domain == "localhost"`). Com `localhost:8080` ou `127.0.0.1:8080` o banco vira `localhost:8080.tag.ong.br` ou `127.tag.ong.br`. O router de teste precisa fixar `$_SERVER['HTTP_HOST'] = 'localhost'` antes de incluir `api.php`.
- O banco de teste, as variáveis `HOST_DB_TAG`/`USER_DB_TAG`/`PWD_DB_TAG` e o tenant `demo` vêm do R1-TEST-01 (**confirmar** como o servidor embutido os enxerga).

## Objetivo
`POST /api/v1/oauth/token` emite um access token de 15 minutos para um `api_client`, com limite de tentativas, especificação OpenAPI validada no CI e testes de contrato.

## Passos
1. Escreva `app/modules/api/openapi/v1.yaml` (OpenAPI 3.1) primeiro: `securitySchemes` (`basicAuth` para o token, `bearerAuth`), o schema do problema e do erro OAuth, `POST /oauth/token` e `GET /ping` do R3-API-02. Lint no CI (por exemplo `@redocly/cli`, fixado em `package.json`).
2. Regra no `app/config/api.php`: `POST api/v1/oauth/token` com `verb`. Controller `Tag\Api\Controllers\v1\OauthController::actionToken`, magro.
3. Serviço `Tag\Shared\Auth\IssueClientCredentialsTokenService::handle(Input, UserContext)`: lê o Basic (`client_id:secret`), exige `grant_type=client_credentials` (corpo `application/x-www-form-urlencoded`), valida `scope` pedido ⊆ `api_client.scopes` (padrão: todos), confere `active`, atualiza `last_used_at`, emite JWT com `sub=client:<id>`, sem `school`/`year` e sem papéis, e devolve `access_token`, `token_type=Bearer`, `expires_in=900`, `scope`. Registre a emissão no `TLog` sem o token.
4. Limite: `RateLimiter` no PHP (padrão sugerido: 10 tentativas por minuto por IP + `client_id`, 30 por IP; confirmar números com o coordenador) devolvendo 429 com `Retry-After`; limpeza das janelas com mais de 1 dia. No nginx: `limit_req_zone` e `limit_req` na rota, com `limit_req_status 429`.
5. `tests/Api` (PHPUnit): servidor embutido (`php -S` com `tests/Api/router.php`, que fixa o host e despacha `/api/*` para `api.php`) subido e derrubado pelo `bootstrap`. Valide cada resposta contra o OpenAPI (biblioteca de validação PSR-7; validar com `composer require --dev --dry-run`). Script `composer test:api`.
6. Casos: token válido (JWT verificado com o `JwtVerifier`: `aud`, `sub`, `scope`, `exp - iat = 900`, sem `roles`); segredo errado; cliente inexistente (mesma resposta); cliente inativo; `grant_type` ausente ou não suportado; `invalid_scope`; sem `Authorization`; `GET` → 405; excesso de tentativas → 429; `GET /api/v1/ping` com o token → 200 e sem token → 401 `problem+json`.

## Fora de escopo
- `authorization_code`, `password` e refresh para o app (R4-API-01).
- Os endpoints de foods (R3-API-05) e o painel de gestão de clientes.
- Rate limit de qualquer outra rota.

## Critérios de aceite
- [ ] O OpenAPI passa no lint e descreve o token, os erros, o 429 e o ping.
- [ ] Toda resposta dos testes de contrato é válida contra o OpenAPI.
- [ ] Cliente inexistente e segredo errado são indistinguíveis (corpo, status e cabeçalhos).
- [ ] O token carrega `aud=INSTANCE`, `sub=client:<id>`, `exp` de 15 min e nenhum papel.
- [ ] Excesso de tentativas devolve 429 no PHP e no nginx; a tabela não cresce sem limite.
- [ ] O CI roda o lint do OpenAPI e `composer test:api`.

## Como verificar
```bash
npm run api:lint
composer test:api
curl -i -u "<client_id>:<segredo>" -d "grant_type=client_credentials&scope=foods.requests:write" "http://localhost/api/v1/oauth/token"   # 200
curl -i -u "inexistente:x" -d "grant_type=client_credentials" "http://localhost/api/v1/oauth/token"                                       # 401 invalid_client
for i in $(seq 1 12); do curl -s -o /dev/null -w "%{http_code}\n" -u "x:y" -d "grant_type=client_credentials" "http://localhost/api/v1/oauth/token"; done   # termina em 429
composer analyse
```

## Handoff
_Preenchido por quem executar._

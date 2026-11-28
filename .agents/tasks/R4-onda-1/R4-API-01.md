---
id: R4-API-01
title: "API v1: criar auth/login, auth/refresh, auth/logout e auth/context com o PasswordAuthenticator extraído do UserIdentity"
release: R4
module: plataforma
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-API-04]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B4, JWT e credenciais; B5, auth/login) e seção 3 (arquitetura alvo)"
write_scope:
  - app/src/Shared/Auth/**
  - app/modules/api/controllers/v1/AuthController.php
  - app/modules/api/openapi/v1.yaml
  - app/components/UserIdentity.php
  - tests/Unit/Shared/Auth/**
  - tests/Integration/Shared/Auth/**
  - tests/Api/Auth/**
read_only:
  - app/models/LoginForm.php
  - app/components/PasswordHasher.php
  - app/components/utils/TagUtils.php
  - app/controllers/SiteController.php
forbidden:
  - composer.json
  - app/config/main.php
  - app/config/api.php
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "Testes unitários do PasswordAuthenticator com cobertura de pelo menos 80%"
  - "Testes de contrato de auth/login, auth/refresh, auth/logout e auth/context verdes contra o v1.yaml"
  - "O login web (site/login) continua funcionando e o upgrade MD5 para bcrypt continua acontecendo"
---

# R4-API-01: API v1: criar auth/login, auth/refresh, auth/logout e auth/context com o PasswordAuthenticator extraído do UserIdentity

## Contexto
O app mobile precisa autenticar pessoas na API v1 (seção 6, B5). A infraestrutura de JWT, `ApiWebApplication`, `ApiWebUser` e as tabelas `api_refresh_token` e `api_auth_attempt` vem do R3-API-04 e dos cards anteriores do R3 (confirmar o escopo exato desse card). Este card entrega os quatro endpoints de pessoa.

A regra de senha está em `app/components/UserIdentity.php`, acoplada ao `CUserIdentity` e à sessão:
- **Bug do `$record` (≈L15 e ≈L25):** `authenticate()` lê `$record->password` em `isMd5(...)` (≈L15) **antes** do teste `$record === null` (≈L25). Com usuário inexistente, o PHP 8.3 emite aviso de propriedade em `null`.
- **Upgrade MD5 para bcrypt (≈L15–23):** se o hash é MD5 e confere, regrava com `PasswordHasher::bcriptHash` e dá `save()`, até para usuário inativo (o teste de `active` vem depois). Se o MD5 não confere, grava `ERROR_PASSWORD_INVALID` e o fluxo segue.
- **Escolas (≈L30–40):** quem tem `admin`, `nutritionist`, `reader`, `guardian` ou `superuser` enxerga **todas as escolas ativas** (`situation = '1'`, `hardfoot = false`). Os demais ficam com `$record->usersSchools`, e a escola inicial é a primeira.
- **Sessão (≈L41–44):** guarda o ActiveRecord inteiro em `loginInfos` (com o hash). Nada disso vai para o JWT.

O `LoginForm` exige `username`, `password` e `year` (≈L26); o ano vai de 2014 até o corrente, e do ano seguinte a partir de novembro (`SiteController` ≈L117–126). **Atenção:** `SiteController::actionChangeSchool` (≈L140–149) e `actionChangeYear` (≈L151–158) gravam escola e ano na sessão **sem validar** contra `usersSchools`; o `auth/context` não pode copiar isso.

## Objetivo
Login, renovação, logout e troca de contexto por JWT, sobre um `Tag\Shared\Auth\Application\PasswordAuthenticator` que o login web também usa, sem mudar o comportamento atual.

## Passos
1. **Spec primeiro.** No `v1.yaml`: `POST /auth/login`, `/auth/refresh`, `/auth/logout` e `/auth/context`, schemas e erros `application/problem+json` (401, 403, 422, 429).
2. **Extraia o `PasswordAuthenticator`** para `app/src/Shared/Auth/Application/`:
   - entrada `username` e `password`; saída um DTO `AuthenticatedUser` (id, nome, papéis, escolas permitidas, escola inicial), sem ActiveRecord e com o `Users` lido por repositório em `Infrastructure/Yii`;
   - **corrija o null-check:** `$record === null || !$record->active` antes de olhar o hash; o upgrade MD5 só para usuário ativo e depois de o MD5 conferir;
   - a mesma resposta de erro para usuário inexistente, senha errada e inativo.
3. **O `UserIdentity::authenticate()` delega** ao authenticator e mantém os mesmos `setState` (`hardfoot`, `loginInfos`, `usersSchools`, `school`). O login web não muda.
4. **`auth/login`:** `username`, `password`, `year` (mesma faixa da tela). Emita o access token de 15 minutos (claims da B4, com `school` e `year`, sem papéis) e o refresh token opaco. Registre a tentativa em `api_auth_attempt` e responda 429 acima do limite.
5. **`auth/refresh`:** rotaciona a cada uso; o reuso de um token já trocado revoga **toda a família** (`sid`) e responde 401. **`auth/logout`:** revoga a família do `sid` (204, idempotente).
6. **`auth/context`:** recebe `school` e `year`. A escola precisa estar nas permitidas pelo **mesmo** critério do login (senão 403); o ano na faixa do login (senão 422). Reemita o access token e mantenha a família do refresh.
7. **Testes:** unitários do authenticator com repositório falso (inexistente, inativo, senha errada, bcrypt, MD5 certo e errado, papéis com todas as escolas e com `usersSchools`); integração do upgrade MD5 no MySQL de teste, com transação desfeita; contrato dos quatro endpoints, incluindo rotação, reuso e escola inválida.

## Fora de escopo
- Recuperação de senha, SSO e fluxo de responsáveis (`guardian`).
- Corrigir `actionChangeSchool` e `actionChangeYear` (abrir issue com a evidência).
- A cópia do `UserIdentity` em `app/modules/schoolreport/components/` e o `LoginForm::login()` (usa `_identity` ≈L66 antes do teste ≈L67): registrar no Handoff.
- `GET /me` e `GET /me/classrooms` (R4-API-02) e papéis dentro do token.

## Critérios de aceite
- [ ] Usuário inexistente recebe 401 sem aviso nem exceção do PHP.
- [ ] Hash MD5 correto vira bcrypt para usuário ativo, e nunca para inativo.
- [ ] O login web continua entrando, com escolas e `hardfoot` iguais aos de antes.
- [ ] O JWT não carrega senha, hash nem papéis; o refresh token é guardado como SHA-256.
- [ ] Reuso de refresh token trocado revoga a família (401); `auth/context` com escola fora do conjunto responde 403.
- [ ] O `v1.yaml` passa no lint e os contratos validam as respostas contra ele.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter PasswordAuthenticator        # confirmar o nome do script no R1-TEST-01
composer test:integration -- --filter Auth && composer test:api -- --filter Auth
curl -i -X POST "http://localhost/api/v1/auth/login" -H "Content-Type: application/json" \
  -d '{"username":"<usuário de teste>","password":"<senha de teste>","year":2026}'
# esperado: 200 com access_token e refresh_token; senha errada: 401 problem+json
```
Confira no banco de teste que o usuário com hash MD5 passou a ter bcrypt. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

---
id: R3-API-03
title: "API: JWT por tenant, refresh rotativo, tabelas de autenticação e comando apiclient"
release: R3
module: plataforma
type: api
skill: tag-add-api-endpoint
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-API-02]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B4: JWT e credenciais) e seção 14 (B4/B5)"
write_scope:
  - app/src/Shared/Auth/**
  - app/commands/ApiClientCommand.php
  - app/migrations/*/v*_api_auth_tables.sql
  - composer.json
  - composer.lock
  - tests/Unit/Shared/Auth/**
  - tests/Integration/Shared/Auth/**
read_only:
  - app/src/Shared/Infrastructure/Yii/ApiWebUser.php
  - app/commands/SqlMigrationCommand.php
  - app/components/PasswordHasher.php
  - app/components/UserIdentity.php
  - app/models/Users.php
  - app/config/console.php
forbidden:
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer require lcobucci/jwt:^5 --dry-run   (resolve com o composer.lock atual) e composer validate --strict"
  - "composer migrate:dry app/migrations/<versão>/v<versão>_api_auth_tables.sql -- --dry-run   (sem erro; rodar duas vezes após aplicar: idempotente)"
  - "composer analyse"
  - "composer test:unit -- --filter Auth"
  - "composer test:integration -- --filter Auth"
---

# R3-API-03: API: JWT por tenant, refresh rotativo, tabelas de autenticação e comando apiclient

## Contexto
**Card do coordenador:** altera `composer.json` e `composer.lock` (`lcobucci/jwt`, hoje ausente) e pede variáveis de ambiente no deploy. Mexe em autenticação, tokens e migration: revisão humana obrigatória.

Decisões do plano (B4), todas a implementar aqui:
- HS256 com chave **derivada por tenant**: `hash_hkdf('sha256', $master[$kid], 32, 'tag-jwt:' . INSTANCE)`. `INSTANCE` vem de `instance.php` (≈L42) e muda por subdomínio, então token de um tenant não valida em outro.
- Chaves no env: `JWT_KEYS` (mapa `kid` → segredo mestre, ≥ 32 bytes) e `JWT_ACTIVE_KID`. Rotação por `kid`: assina com o ativo e valida com qualquer um listado. Sem chave ou chave curta, falha fechada (500), nunca um padrão embutido.
- Claims: `iss` = host, `aud` = `INSTANCE`, `sub` = `user:<id>` ou `client:<id>`, `jti` (`ramsey/uuid` já está instalado), `iat`/`nbf`/`exp` (15 min), `scope`, `sid`, e `school`/`year` só em token de pessoa. **Papéis não vão no token** (o `ApiController` os consulta no `authManager`).
- Refresh: opaco de 256 bits, guardado como SHA-256, rotaciona a cada uso; reuso de token já trocado **revoga a família**; expira em 30 dias de ociosidade ou 90 dias absolutos; revoga se o usuário trocar a senha ou for desativado.
- Cada `api_client` tem um usuário de serviço (senha inutilizável, sem papel RBAC) para registrar a autoria.

Fatos do código a considerar:
- `config.php` fixa `date_default_timezone_set('America/Sao_Paulo')` (≈L40). Use relógio em UTC (`DateTimeImmutable` com `UTC`) e injete o relógio (`Psr\Clock\ClockInterface`) para testar expiração.
- `UserIdentity` só aceita senha por `password_verify` (≈L27) e trata hash MD5 de 32 hex como legado (`isMd5`, ≈L5 e ≈L15). A senha do usuário de serviço não pode ser nenhum dos dois formatos válidos. `app/components/PasswordHasher.php` tem `bcriptHash()` (≈L9) para o segredo do cliente, ou use `password_hash` direto.
- A tabela `users` (`app/models/Users.php`, colunas `id`, `name`, `username`, `password`, `active`) é a do usuário de serviço. **Confirmar** o tipo de `users.id` e as colunas `NOT NULL` no schema de teste do R1-TEST-01.
- O console (`app/config/console.php` ≈L3–L8) fixa o banco `demo.tag.ong.br` e não define `INSTANCE`. O comando precisa de `--tenant=<subdominio>` e abrir a conexão do tenant como o `SqlMigrationCommand` faz (PDO em `getTagDatabases()`/`executeSqlOnDatabase()`).
- O `SqlMigrationCommand` roda em todos os bancos `%.tag.ong.br`, divide o arquivo por `;` fora de aspas e **não** usa transação. Comentário SQL com apóstrofo, aspas ou `;` quebra a divisão.
- Convenção de pasta: `app/migrations/<versão>/v<versão>_<nome>.sql`; `TAG_VERSION` está em `config.php` (≈L11). **Confirmar a versão do release R3** com o coordenador.

## Objetivo
Emitir e validar JWT por tenant, rotacionar refresh tokens, ter as três tabelas em todos os tenants e permitir criar clientes M2M pelo console.

## Passos
1. `composer require lcobucci/jwt:^5` (validar antes com `--dry-run`; o projeto tem `minimum-stability: dev` e Guzzle 6).
2. Migration SQL idempotente (`CREATE TABLE IF NOT EXISTS`, `utf8mb4`, InnoDB, chaves únicas):
   - `api_client`: `id`, `client_id` (UUID, único), `name`, `secret_hash`, `scopes`, `allowed_schools` (nulo = todas), `service_user_fk` (→ `users.id`), `active`, `created_at`, `last_used_at`;
   - `api_refresh_token`: `id`, `token_hash` (SHA-256, único), `family_id`, `user_fk`, `sid`, `school_fk`, `year`, `created_at`, `last_used_at`, `idle_expires_at`, `absolute_expires_at`, `used_at`, `revoked_at`, `replaced_by_fk`, `credential_fingerprint`;
   - `api_auth_attempt`: `bucket` (hash do IP + client), `window_start`, `attempts`; chave única (`bucket`, `window_start`) para o upsert atômico do R3-API-04.
3. `Tag\Shared\Auth\Jwt\{KeyRing, JwtIssuer, JwtVerifier, AccessToken}`: o `KeyRing` lê o env e deriva a chave por tenant; o `JwtVerifier` só aceita HS256, exige `kid` conhecido, `aud == INSTANCE`, `iss` do host, `nbf`/`exp` com tolerância de 30 s e rejeita `alg: none`.
4. `Tag\Shared\Auth\RefreshToken\*`: serviço de aplicação (sem Yii) com repositório em `Infrastructure/Yii`. Rotação em transação (`TransactionManager`), detecção de reuso, expirações e revogação. Troca de senha e desativação: grave `credential_fingerprint = HMAC(users.password)` com a chave do tenant e compare no refresh; divergência ou `active = 0` revoga a família. Registre no Handoff se a pessoa responsável prefere outro mecanismo.
5. `app/commands/ApiClientCommand.php` (`yiic apiclient create --name= --scopes= [--allowed-schools=] --tenant=`): cria o usuário de serviço (`username` `api-client-<uuid curto>`, senha `'!' . bin2hex(random_bytes(32))`, `active=1`, sem `auth_assignment` nem `users_school`), gera `client_id` e segredo de 256 bits, grava `password_hash` e **exibe o segredo uma única vez**. Comandos `list` e `revoke` (desativa cliente e usuário).
6. `tag-qa`: testes unitários (derivação por tenant, token do tenant A rejeitado no B, `exp`/`nbf`, `kid` rotacionado, `alg` trocado, reuso de refresh revoga a família, expirações ociosa e absoluta) com relógio falso; de integração (repositório e migration aplicada duas vezes sem erro).

## Fora de escopo
- Os endpoints HTTP `oauth/token`, `auth/login`, `auth/refresh` (R3-API-04 e R4-API-01) e o limite de tentativas.
- Gancho nos fluxos web de troca de senha (`app/models/**` e o systemadmin ficam intocados).
- Colocar segredos no repositório ou no `docker-compose.yml`.

## Critérios de aceite
- [ ] Token emitido para o tenant `DEMO` não valida com `INSTANCE=OUTRO` (teste unitário).
- [ ] Refresh reutilizado revoga a família inteira (teste).
- [ ] A migration roda duas vezes sem erro; `--dry-run` lista todos os tenants.
- [ ] O segredo do cliente só aparece na saída de `create` e só o `password_hash` fica no banco.
- [ ] Nenhum segredo, chave ou token literal no repositório (`git grep`).
- [ ] `composer validate --strict` e `composer analyse` verdes; `app/src` em nível max, sem baseline.

## Como verificar
```bash
composer validate --strict && composer analyse
composer migrate:dry app/migrations/<versão>/v<versão>_api_auth_tables.sql -- --dry-run
docker exec tag-app php /app/app/yiic apiclient create --name=teste --scopes=foods.requests:write --tenant=demo   # imprime o segredo uma vez
composer test:unit -- --filter Auth && composer test:integration -- --filter Auth
```

## Handoff
_Preenchido por quem executar._

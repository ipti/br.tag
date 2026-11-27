---
id: R1-DOCKER-01
title: "Dockerfile multi-stage (dev e prod) e remoção do use do PHPUnit na view studentimc"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco Docker multi-stage)"
write_scope:
  - Dockerfile
  - docker-compose.yml
  - .dockerignore
  - docker/php/conf.d/**
  - app/modules/student/views/studentimc/studentIndex.php
read_only:
  - composer.json
  - composer.lock
  - .github/workflows/docker-build-push.yml
  - docker/php/conf.d/xdebug.ini
forbidden:
  - composer.json
  - app/config/**
  - themes/**
  - .github/**
acceptance:
  - "docker compose build tag   (alvo dev)"
  - "docker build --target prod -t tag-prod-check .   (sem vendor/bin/phpunit, sem tests/, docker/ e scripts/)"
  - "docker run --rm tag-prod-check sh -c 'test ! -d /app/tests && test ! -d /app/scripts && test ! -f /app/vendor/bin/phpunit && echo ok'"
  - "git grep -n 'PHPUnit' -- app/modules/student/views   (sem resultados)"
  - "Smoke: login, home, tela do IMC (studentimc) e um comando yiic funcionam nos dois alvos"
---

# R1-DOCKER-01: Dockerfile multi-stage (dev e prod) e remoção do use do PHPUnit na view studentimc

## Contexto
**Card com revisão humana obrigatória** (mexe em Docker). Hoje há um único estágio.

Fatos:
- `Dockerfile` (L1) usa `FROM ipti/yii2:8.3-fpm`, copia o repositório inteiro (`COPY . /app`), copia o Composer de `composer:latest` (sem versão fixa) e roda `composer install --no-interaction`, **que instala também as dependências de desenvolvimento** (phpunit, codeception, phpstan, rector, phpmd). Não há pcov nem xdebug no `Dockerfile`; o `docker/php/conf.d/xdebug.ini` só é usado se a linha comentada do compose for habilitada (`docker-compose.yml` ≈L27) e o compose tem `PHP_ENABLE_XDEBUG: 0`. **Confirmar** se a imagem base já traz xdebug e se tem `install-php-extensions` ou ferramentas de build para o pcov.
- `docker-compose.yml`: o serviço `tag` usa `build: .` (≈L3), monta `.:/app` e mascara `/app/vendor`, `/app/app/vendor`, `/app/assets`, `/app/db` e `/app/.git` com volumes anônimos. Por isso, depois de mudar dependências, o volume de `vendor` precisa ser recriado (`docker compose up -d --build -V`).
- O CI (`.github/workflows/docker-build-push.yml`, L40) faz `docker build` sem `--target`, então hoje publica o estágio único. **O último estágio do novo Dockerfile precisa ser o `prod`**, para esse comando continuar publicando a imagem de produção.
- O `Dockerfile` ajusta o nginx com `sed` (root `/app/web` → `/app`) e dá permissão a `app/runtime`, `assets`, `app/export` e `app/import`; o `docker-run.sh` vem da imagem base. Esses passos valem para os dois alvos.
- Em produção as ferramentas de desenvolvimento não são usadas: `yiic`, `sqlmigration` e `migrate.sh` dependem do Yii, não de pacotes dev. `require` (produção) já inclui `fakerphp/faker`, `scssphp`, `jms/serializer`, `guzzle`, `sentry`; **confirmar** com um `composer install --no-dev` que nenhum código de produção usa pacote de `require-dev`.
- `app/modules/student/views/studentimc/studentIndex.php` (L3) tem `use PHPUnit\Extensions\Selenium2TestCase\ElementCommand\Value;`. O `Value` não é usado em nenhum outro ponto do arquivo (só a linha do `use`). O pacote `phpunit-selenium` sai no R1-BASE-02.

## Objetivo
O Dockerfile gera `dev` (com pcov, xdebug e dependências de desenvolvimento) e `prod` (`composer install --no-dev -o`, sem `tests/`, `docker/` e `scripts/`), com o compose usando o alvo `dev`.

## Passos
1. Reestruture o `Dockerfile` em estágios: `base` (imagem, nginx, php.ini, diretórios e permissões), `dev` (a partir de `base`: `composer install`, pcov e xdebug desligado por padrão) e `prod` (a partir de `base`: `composer install --no-dev -o --no-interaction` e `rm -rf` de `tests`, `docker` e `scripts`). `prod` é o **último** estágio.
2. Fixe a imagem do Composer (`composer:2` ou digest) em vez de `latest`; registre a versão escolhida.
3. `docker-compose.yml`: `build: { context: ., target: dev }` no serviço `tag`. Não mude portas, volumes nem variáveis.
4. Se for preciso, `.dockerignore` ganha entradas (por exemplo `.git`, `node_modules`, `docker/mysql`) sem excluir nada que o `prod` use (`app/migrations` precisa ir para a imagem).
5. Remova a linha 3 de `studentIndex.php` (e a linha em branco que sobrar), sem tocar no resto do arquivo.
6. Construa os dois alvos, suba o `dev` com o compose e confirme os critérios abaixo.

## Fora de escopo
- Estágio Node e nginx versionado (F1 e R0-INFRA).
- Subir para PHP 8.4, trocar a imagem base ou mudar o `docker-run.sh`.
- Alterar `composer.json` ou `composer.lock` (R1-BASE-02 e R1-TEST-01).
- Alterar o workflow de build (R1-CI-01).

## Critérios de aceite
- [ ] `docker compose build tag` constrói o alvo `dev` e o `docker build .` (sem `--target`) constrói o `prod`.
- [ ] O `prod` não tem `vendor/bin/phpunit`, `tests/`, `docker/` nem `scripts/`, e mantém `app/migrations`.
- [ ] O `dev` tem pcov (`php -m | grep pcov`) e xdebug instalado mas desligado por padrão.
- [ ] `studentIndex.php` não referencia mais `PHPUnit`.
- [ ] **O comportamento não muda:** com o compose (alvo `dev`), login, home e a tela do IMC funcionam; no `prod`, `yiic help` e a página de login respondem.

## Como verificar
```bash
docker compose build tag
docker compose up -d --build -V      # recria o volume de vendor
docker exec tag-app php -m | grep -i -E "pcov|xdebug"
docker build --target prod -t tag-prod-check .
docker run --rm tag-prod-check sh -c 'test ! -d /app/tests && test ! -d /app/scripts && test ! -d /app/docker && test ! -f /app/vendor/bin/phpunit && test -d /app/app/migrations && echo ok'
docker run --rm tag-prod-check php /app/app/yiic help
git grep -n "PHPUnit" -- app/modules/student/views   # esperado: sem resultados
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes): login, home e `?r=student/studentimc/index`. O `prod` não sobe sozinho sem banco: valide só `yiic help` e, se houver MySQL, `?r=site/login`.

## Handoff
_Preenchido por quem executar._

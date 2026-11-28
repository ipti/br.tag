---
id: R1-BASE-01
title: "composer.json: exigir PHP ^8.3, declarar o autoload PSR-4 e remover os require_once do sagres"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco composer.json e bloco Bootstrap)"
write_scope:
  - composer.json
  - composer.lock
  - index.php
  - app/yiic.php
  - app/src/.gitkeep
read_only:
  - app/modules/sagres/**
  - app/components/FeaturesComponent.php
  - app/config/main.php
  - Dockerfile
forbidden:
  - app/config/**
  - app/modules/sagres/**
  - themes/**
  - .github/**
  - Dockerfile
acceptance:
  - "docker exec tag-app composer validate"
  - "docker exec tag-app composer dump-autoload -o"
  - "git grep -n 'require_once $.*_t;' -- index.php   (sem resultados)"
  - "docker exec tag-app php /app/app/yiic help   (lista os comandos sem erro)"
  - "composer lint"
  - "Smoke: ?r=site/login responde 200, login funciona e a tela do sagres abre (seção Como verificar)"
---

# R1-BASE-01: composer.json: exigir PHP ^8.3, declarar o autoload PSR-4 e remover os require_once do sagres

## Contexto
**Card do coordenador:** `composer.json` e `index.php` são arquivos com dono (ver `.agents/tasks/README.md`). Este é o primeiro PR da B1 e é pequeno de propósito.

O que existe hoje:
- `composer.json` declara `"php": ">=5.1.0"` (≈L4), não tem `autoload`, `autoload-dev` nem `config.platform`. O `composer.lock` repete `platform: {php: ">=5.1.0"}`.
- O `Dockerfile` (L1) usa `FROM ipti/yii2:8.3-fpm`, ou seja, PHP 8.3. A versão exata do patch **não está no repositório** (confirmar com `docker run --rm ipti/yii2:8.3-fpm php -v`).
- `index.php` monta 13 caminhos do sagres (≈L11–24: `SagresConsultModel.php` e 12 arquivos `*TType.php` de `app/modules/sagres/soap/src/sagresEdu/`) e os carrega com 13 `require_once` (≈L34–46). Na ≈L48 há ainda `require_once` do `FeaturesComponent.php`.
- As classes do sagres estão em namespace: `SagresEdu\*` em `soap/src/sagresEdu/` (13 arquivos; `Educacao.php` existe mas não está na lista do `index.php`) e `Genericos\*` em `soap/src/genericosClass/` (11 arquivos). `SagresConsultModel` declara `namespace SagresEdu`, mas fica em `app/modules/sagres/models/`, fora do diretório do namespace; por isso precisa de `classmap`. Quem o usa é `app/modules/sagres/controllers/DefaultController.php` (`use SagresEdu\SagresConsultModel`, ≈L3).
- `app/yiic.php` (≈L4–7) só carrega `vendor/yiisoft/yii/framework/yiic.php`. Os comandos de console não enxergam o autoload do Composer.
- `app/src/` ainda não existe.
- `FeaturesComponent` só é referenciado em `app/config/main.php` (≈L132–134, por alias `application.components.FeaturesComponent`), então o `require_once` da ≈L48 provavelmente é redundante.

## Objetivo
O Composer passa a carregar o código com namespace (`app/src` e o sagres), o `index.php` perde os 13 `require_once` e o `yiic` usa o autoload do Composer.

## Passos
1. Descubra o PHP da imagem (`docker run --rm ipti/yii2:8.3-fpm php -v`) e anote a versão no Handoff.
2. Em `composer.json`: `require.php` vira `"^8.3"`; acrescente `config.platform.php` com a versão da imagem e `config.sort-packages: true`. Mantenha `allow-plugins`. **Não** mexa em `minimum-stability` nem em dependências (R1-BASE-02).
3. Acrescente `autoload`:
   - `psr-4`: `Tag\` → `app/src/`; `SagresEdu\` → `app/modules/sagres/soap/src/sagresEdu/`; `Genericos\` → `app/modules/sagres/soap/src/genericosClass/`;
   - `classmap`: `app/modules/sagres/models/SagresConsultModel.php`.
4. Acrescente `autoload-dev`: `Tag\Tests\` → `tests/`.
5. Crie `app/src/.gitkeep`.
6. `index.php`: apague as variáveis `$sagres` e `$*_t` (≈L11–24) e os 13 `require_once` (≈L34–46). Tente apagar também o `require_once` do `FeaturesComponent` (≈L48): se o login ou a home falharem com "Class FeaturesComponent not found", restaure a linha e registre no Handoff.
7. `app/yiic.php`: acrescente `require_once dirname(__FILE__) . '/../vendor/autoload.php';` antes do `require_once $yiic`.
8. Rode `docker exec tag-app composer update --lock` (só recalcula o hash do lock, não atualiza pacotes) e confira que o `git diff composer.lock` mostra apenas `content-hash` e `platform`. Depois rode `composer dump-autoload -o`.

## Fora de escopo
- Mudar versões de pacotes (R1-BASE-02 e R1-BASE-03).
- Colocar namespace nas classes legadas ou renomear arquivos do sagres (são gerados pelo `xsd2php`; ver `sagres-gen` no `composer.json`).
- Imports compartilhados do Yii (R1-BASE-04) e qualquer arquivo de `app/config/`.
- Remover o import `application.modules.sagres.soap.src.sagresEdu.*` do `main.php` (≈L66): é arquivo com dono e fica para quando o autoload estiver validado.

## Critérios de aceite
- [ ] `composer.json` tem `"php": "^8.3"`, `config.platform.php` igual ao da imagem, os quatro mapeamentos de `autoload` e o de `autoload-dev`.
- [ ] `index.php` não tem mais os `require_once` do sagres (e o do `FeaturesComponent` só permanece se comprovadamente necessário, com motivo no Handoff).
- [ ] `php /app/app/yiic help` funciona e `app/yiic.php` carrega `vendor/autoload.php`.
- [ ] As classes `SagresEdu\EscolaTType`, `SagresEdu\SagresConsultModel` e `Genericos\NaContratoTType` são carregadas pelo Composer.
- [ ] **O comportamento não muda:** login, home e a tela do sagres abrem como antes.
- [ ] `git diff` não toca arquivos fora do `write_scope`.

## Como verificar
```bash
docker exec tag-app composer validate
docker exec tag-app composer dump-autoload -o
docker exec tag-app grep -c SagresConsultModel vendor/composer/autoload_classmap.php   # esperado: 1 ou mais
docker exec tag-app php -r 'require "vendor/autoload.php"; var_dump(class_exists("SagresEdu\\EscolaTType"), class_exists("SagresEdu\\SagresConsultModel"), class_exists("Genericos\\NaContratoTType"));'   # esperado: bool(true) x3
git grep -n 'require_once $.*_t;' -- index.php   # esperado: sem resultados
docker exec tag-app php /app/app/yiic help
curl -s -o /dev/null -w "%{http_code}\n" "http://localhost/?r=site/login"   # esperado: 200
composer lint
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes): login, home e a tela inicial do sagres (`?r=sagres/default/index`). Opcional: `composer dump-autoload -o --strict-psr`; se acusar violação nos arquivos gerados do sagres, só registre no Handoff.

## Handoff
_Preenchido por quem executar._

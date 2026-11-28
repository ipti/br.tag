---
id: R2-FRONT-01
title: "Montar a toolchain Vite (Node no Docker, TFrontend e ViteManifest) sem mudar nenhuma página"
release: R2
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-DOCKER-01, R1-BASE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (decisões de base) e F1 (Vite)"
write_scope:
  - package.json
  - package-lock.json
  - vite.config.js
  - vite.bridge.config.js
  - frontend/**
  - docker-compose.yml
  - Dockerfile
  - .gitignore
  - .dockerignore
  - docker/nginx/default.conf
  - composer.json
  - app/src/Shared/Frontend/ViteManifest.php
  - app/components/utils/TFrontend.php
  - app/config/main.php
  - tests/Unit/Shared/Frontend/**
read_only:
  - sass/scss/**
  - themes/default/views/layouts/**
  - docs/sass_commands.md
forbidden:
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter ViteManifest"
  - "composer front:build gera build/.vite/manifest.json e build/bridge/"
  - "docker build . conclui e a imagem contém /app/build"
  - "As páginas existentes não mudam: nenhum layout ou view foi alterado"
---

# R2-FRONT-01: Montar a toolchain Vite (Node no Docker, TFrontend e ViteManifest) sem mudar nenhuma página

## Contexto
Este é um **card do coordenador**: edita `composer.json`, `app/config/main.php`, `Dockerfile` e `docker-compose.yml`, que têm dono. Nada deste card muda uma página. Ele só entrega a infraestrutura; as telas passam a usá-la nos cards seguintes.

Estado atual, verificado:
- `package.json` só tem `selenium-standalone` (usado pelo Codeception). **Mantenha** essa dependência. Não existem `frontend/`, `vite.config.js` nem `build/`.
- O `docker-compose.yml` tem o serviço `sass` (`michalklempa/dart-sass`, em modo watch) e o serviço `tag` com `.:/app`. O `Dockerfile` parte de `ipti/yii2:8.3-fpm`, copia o projeto e roda `composer install`; não há estágio Node. O `composer.json` compila o SCSS com `sass:build` (`docker compose run ... sass`) e `sass:watch`.
- `.dockerignore` não exclui `node_modules`; o `.gitignore` já exclui `node_modules`, mas não `build/`.
- O `main.php` importa `application.components.utils.TUrlBridge` (≈L69). `TUrlBridge` (`app/components/utils/TUrlBridge.php`) é o estilo a seguir: fachada estática com `register()` e `flush()`.
- O `CClientScript` do Yii 1.1.31 aceita atributos no script: `registerScriptFile($url, $position, array $htmlOptions)` (`CClientScript.php` ≈L672). Já `CHtml::script()` envolve o texto em `/*<![CDATA[*/` (`CHtml.php` ≈L304–313), o que quebra JSON; use `CHtml::tag('script', ...)`.
- `docker/nginx/default.conf` é criado pelo R0-INFRA-02. **Confirmar** que existe; se não, pare.

## Objetivo
`composer front:build` gera `build/` com manifest, a imagem Docker já traz esse `build/`, e o PHP sabe resolver entries, estilos e a ponte legada pelo manifest, com o dev server disponível para a fase seguinte.

## Passos
1. `package.json`: `vite` (≥ 7; **confirmar** a versão estável na data), `sass-embedded`, scripts `dev`, `build` (`vite build && vite build -c vite.bridge.config.js`) e `test` (provisório: `echo "sem testes"`). `engines` e `.nvmrc` com a LTS vigente (confirmar: 22 ou 24).
2. `vite.config.js`: `root: 'frontend'`, `base: '/build/'`, `publicDir: false`, `build.outDir: '../build'`, `build.manifest: true` (saída em `build/.vite/manifest.json`), `emptyOutDir: true`. As entries vêm de glob em `pages/**/*.js`, `entries/*.js` e `styles/*.scss`, sem arquivo central de registro. Alias `jquery` → `frontend/vendor/jquery-global.js`, que exporta `window.jQuery` (e falha com mensagem clara se ele não existir). Plugin que grava `build/hot` com a URL do dev server (`http://localhost:5173`) ao subir e o remove ao encerrar. Para o `scss`, use a API moderna e silencie as depreciações do `@import` (o design system ainda usa `@import`).
3. `vite.bridge.config.js`: build clássico (IIFE) de `frontend/legacy-bridge/index.js` para `build/bridge/`, com `emptyOutDir: false` e manifest próprio. Crie o `index.js` mínimo (só `window.TAG = window.TAG || {}`); a ponte de verdade é do R3.
4. `docker-compose.yml`: serviço `node` (imagem LTS, `.:/app`, volume nomeado para `node_modules`, porta `127.0.0.1:5173:5173`, polling de arquivos por causa do bind mount no Windows). **Mantenha o serviço `sass`** até o R2-FRONT-03: os layouts ainda leem `/sass/css/main.css`. Anote no Handoff que isso difere do texto "no lugar do `sass`" do plano.
5. `Dockerfile`: estágio `node AS frontend` (`npm ci` e `npm run build`) e `COPY --from=frontend /app/build /app/build` no estágio final, depois do `COPY . /app`. `.gitignore`: `build/`. `.dockerignore`: `node_modules` e `build`.
6. nginx (`docker/nginx/default.conf`): `location /build/assets/` com `Cache-Control: public, immutable` e `expires 1y`.
7. `Tag\Shared\Frontend\ViteManifest` (puro, sem Yii): carrega o JSON; `entry(string $name)` devolve `file`, `css` e `imports` (resolvidos recursivamente, sem duplicata); `assetUrl()`; erro claro quando a entry não existe (o `entries:check` do R2-FRONT-02 depende dele). Testes unitários com manifest de exemplo: imports aninhados, CSS, duplicatas, entry inexistente.
8. `TFrontend` (`app/components/utils/TFrontend.php`, estilo `TUrlBridge`) e a linha de import no `main.php`: `registerEntry`, `registerStyle`, `legacyBridgeTag`, `assetUrl`, `jsonScript` (via `CHtml::tag('script', ['type' => 'application/json', 'id' => $id], json_encode($v, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT))`) e `layoutConfig`. `registerEntry` usa `registerScriptFile($url, $pos, ['type' => 'module'])`. Com `YII_DEBUG` e `build/hot` presente, aponta para o dev server (em dev, um `.scss` entra como módulo, porque o Vite injeta o CSS por JS).
9. `composer.json`: `front:dev`, `front:build` e `front:test` (todos por `docker compose run --rm node ...`). **Mantenha `sass:build` e `sass:watch` como estão**; o R2-FRONT-03 os redireciona.

## Fora de escopo
- Qualquer alteração em layouts, views, `sass/scss/` ou `common.js`.
- ESLint, Prettier, TypeScript e Vitest (R2-FRONT-02).
- Trocar o CSS do design system (R2-FRONT-03) e o núcleo JS e a ponte (R3).

## Critérios de aceite
- [ ] `composer front:build` gera `build/.vite/manifest.json` e `build/bridge/`; a pasta `build/` está no `.gitignore`.
- [ ] `docker build .` conclui e o `/app/build` existe na imagem.
- [ ] O teste unitário do `ViteManifest` passa, com PHPStan max sem baseline.
- [ ] `TFrontend::jsonScript` escapa `</script>` e `&` (teste).
- [ ] O serviço `node` sobe em `127.0.0.1:5173` e o `build/hot` aparece e some com o servidor.
- [ ] Nenhum layout, view ou SCSS foi alterado.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter ViteManifest
composer front:build && ls build/.vite/manifest.json build/bridge
docker build -t tag-front-test . && docker run --rm tag-front-test ls /app/build
composer front:dev   # em outro terminal: curl -I http://localhost:5173/@vite/client (esperado 200) e cat build/hot
git status --short themes sass   # esperado: vazio
```
- Confirmar: o `jsonScript` com `CHtml::tag` mantém o JSON legível por `JSON.parse(el.textContent)`.
- Confirmar: a URL base do app (raiz do host); `base: '/build/'` e o nginx assumem isso.
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

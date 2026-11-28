---
id: R3-FRONT-01
title: "Núcleo JS em frontend/: http, html, rotas geradas, toast, dialog, form-errors e onPage"
release: R3
module: plataforma
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-FRONT-02]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (decisões de base e F3: núcleo) e seção 8.2"
write_scope:
  - frontend/core/**
  - frontend/ui/**
  - frontend/routes/**
  - frontend/types/**
  - scripts/generate-routes.php
  - sass/scss/components/_toast.scss
  - sass/scss/components/_dialog.scss
  - sass/scss/components/_form-errors.scss
  - sass/scss/main.scss
  - package.json
  - package-lock.json
  - tests/Integration/Frontend/**
read_only:
  - themes/default/js/common.js
  - themes/default/js/util.js
  - app/components/utils/TUrlBridge.php
  - vendor/yiisoft/yii/framework/web/CUrlManager.php
  - sass/scss/_tokens.scss
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - themes/default/js/**
  - .github/workflows/**
acceptance:
  - "npm run lint && npm run typecheck && npm test && npm run build"
  - "composer routes:generate seguido de git diff --exit-code -- frontend/routes 'app/modules/*/*Routes.php' app/components/utils/AppRoutes.php   (geração determinística)"
  - "composer sass:build   (os três parciais novos compilam)"
  - "Vitest: paridade de url() com as fixtures geradas pelo PHP real"
---

# R3-FRONT-01: Núcleo JS em frontend/: http, html, rotas geradas, toast, dialog, form-errors e onPage

## Contexto
**Card do coordenador:** edita `sass/scss/main.scss` (import dos parciais novos antes de `_helpers.scss`, regra do design system). A estrutura `frontend/`, o Vite, o ESLint, o `tsconfig` com `checkJs`, o Vitest e o `TFrontend` (PHP) vêm do R1-FRONT e do R2-FRONT; **confirmar** nomes e o markup de `TFrontend::jsonScript`/`layoutConfig()` no Handoff do R2-FRONT-02. O jQuery entra por um alias para `window.jQuery` (um jQuery só por página).

É a base do que as páginas migradas vão importar. O que o legado faz hoje e o núcleo deve preservar ou corrigir:
- `Controller::init()` (`app/components/Controller.php` ≈L49–L54) só devolve o 401 em JSON `{redirect}` para visitante se a requisição for AJAX (`CHttpRequest::getIsAjaxRequest` exige `X-Requested-With: XMLHttpRequest`, ≈L699). Sem esse header o fetch recebe a página de login.
- As actions leem `$_POST`: o corpo tem de ser `application/x-www-form-urlencoded`, com aninhamento no estilo PHP (`abilities[]`, `Model[attr]`). `URLSearchParams` não aninha: use `$.param`.
- Muitas actions respondem `echo json_encode(...)` com `Content-Type: text/html`; o legado faz `$.parseJSON(response)` (`util.js` ≈L217, L281). O parse não pode depender do tipo de conteúdo.
- `common.js` ≈L178–L245 trata o 401 com `{redirect}` e manda `[AJAX ERROR] <path> - <mensagem>` ao Sentry com tags e `extra`, mas chama `Raven.captureMessage` sem checar (≈L222). O `Raven` só é referenciado ali; ele vem do componente `sentry` (`jsDsn`, `main.php` ≈L232): **confirmar**. Ignora `abort` (≈L216–L220). Mantenha o formato da mensagem para não perder o agrupamento no Sentry.
- Há 3 `escapeHtml` duplicados: `app/modules/macete/resources/macete.js` ≈L4, `js/admin/grades-structure.js` ≈L11 e `js/notifications/notifications.js` ≈L160.
- `url()` precisa reproduzir o `CUrlManager` em formato `get` (`urlFormat => 'get'`, `main.php` ≈L146): `createUrlDefault` (`CUrlManager.php` ≈L338–L352) gera `/?r=<rota>&k=v`, a rota **sem** encode, os parâmetros por `urlencode` do PHP (espaço vira `+`, `~` vira `%7E`; `encodeURIComponent` difere) e `createPathInfo` aninha com `k[sub]=v` (≈L431–L445). `null` vira vazio, `#` vira âncora e o `routeVar` é removido dos parâmetros (≈L285–L294). As regras de URL do `main.php` não valem em formato `get`.
- `scripts/generate-routes.php` já varre `app/modules/*/controllers/*Controller.php` (`extractActions` ≈L31, laço dos módulos ≈L141–L196, raiz ≈L202–L222) e gera `*Routes.php`. As constantes são `strtoupper("{controller}_{action}")`.
- `_tokens.scss`: só há token de fundo e borda para alertas info (≈L34–L37), sucesso (≈L51–L54) e aviso (≈L56–L59); para erro existem só `$colorsAlertCrucialIcon` (≈L33) e `$colorsTextError` (≈L28). Os `$Shadow-*` (≈L164–L170) estão quebrados (`rgb(0,0,0.1)` dá preto opaco) até o R3-CSS-01: use `rgba(0, 0, 0, .1)` localmente e **não invente cores**.

## Objetivo
Um núcleo ES testado em `frontend/` (`core/`, `ui/`, `routes/`) que as próximas páginas importam, sem globais e sem `?r=` escrito à mão.

## Passos
1. `core/http.js`: `request/get/post` com fetch, `credentials: 'same-origin'`, `X-Requested-With`, `X-CSRF-Token` (lido do JSON de `#tag-config`; confirmar a chave) em métodos fora de GET/HEAD/OPTIONS, corpo urlencoded por `$.param`, parse tolerante (texto → `JSON.parse` com fallback), 401 com `{redirect}` navegando e rejeitando sem toast, demais falhas como `HttpError` (status, corpo, URL, `X-Request-ID`) com toast (opção `silent`) e relato ao Sentry protegido (`window.Sentry`/`window.Raven` podem não existir), ignorando `AbortError`.
2. `core/html.js`: tag `html` que escapa tudo, aceita arrays e `null`, e `raw()` para HTML confiável; `sanitize()` com `dompurify` do npm (versão fixa). Troque os 3 `escapeHtml` quando as páginas forem migradas (não aqui).
3. `core/url.js`: `url(route, params)` com `phpUrlEncode`, aninhamento, `#`, `null` e base vinda do `#tag-config` (confirmar se há `baseUrl`; senão vazio).
4. Estenda `scripts/generate-routes.php` para emitir `frontend/routes/<módulo>.js` (e `app.js` para os controllers raiz) com as mesmas constantes do PHP e `export const url`. Mantenha a saída determinística (ordenada) e o cabeçalho "ARQUIVO GERADO"; versione os gerados, como os `*Routes.php`. Não altere o que o script faz nos `*Module.php`.
5. `ui/toast.js` (`success/error/warning/info`, região `aria-live`, `textContent`, fecha sozinho, no máximo 5) e `ui/dialog.js` (`<dialog>` com `showModal`, `confirmDialog()` e `alertDialog()` devolvendo Promise, ESC cancela, foco no botão seguro; sem `showModal`, abre com `open` e a classe `t-dialog--fallback`). SCSS nos três parciais, com classes `t-toast*`, `t-dialog*` e `t-field--error|--warning`, botões `t-button-primary`/`t-button-secondary`.
6. `ui/form-errors.js`: `showFieldError(campo, mensagem)`, `clearFieldError(campo)` e os equivalentes de aviso, de `addError`/`removeError` (`util.js` ≈L446–L466): classe `t-field--error` no contêiner, `aria-invalid`, `aria-describedby` e `<span id="<id>_error" role="alert">` com `textContent` (o legado concatena a mensagem em HTML, ≈L406). Aceite elemento ou seletor.
7. `core/page.js`: `onPage(nome, (root, config) => …)` (roda via `$(fn)`, depois do legado; lê o JSON do mesmo contrato do `TFrontend::jsonScript`), `initWithin(el)` e um registro `registerInitializer(seletor, fn)` que o R3-FRONT-03 usa.
8. `tag-qa`: Vitest com jsdom para cada módulo, mais fixtures de `url()` geradas pelo PHP real (`Yii::app()->createUrl`, ≥ 15 casos: aninhados, espaço, acento, `null`, `#`, rota em maiúsculas), versionadas, com o script que as regenera em `tests/Integration/Frontend/`.

## Fora de escopo
- A ponte legada, `common.js`/`util.js` e o `$.ajaxPrefilter` definitivo (R3-FRONT-04); os validators (R3-FRONT-02); select2, máscara, datepicker e DataTable (R3-FRONT-03).
- Migrar qualquer página para o núcleo.
- Criar tokens de cor para alerta crítico (proponha ao R3-CSS-01).

## Critérios de aceite
- [ ] Os cinco módulos têm testes Vitest verdes, e `url()` coincide com as fixtures do PHP.
- [ ] `http.js` envia `X-Requested-With` e `X-CSRF-Token`, aninha o corpo como PHP e tolera JSON em `text/html`.
- [ ] Nenhum `innerHTML` com texto de usuário; `no-unsanitized` do ESLint passa.
- [ ] `composer routes:generate` não gera diferença em duas execuções seguidas.
- [ ] Nenhum global novo e nenhum `?r=` literal em `frontend/` (regras do ESLint).
- [ ] Os parciais SCSS compilam e estão importados antes de `_helpers.scss`.

## Como verificar
```bash
npm run lint && npm run typecheck && npm test && npm run build
composer routes:generate && git diff --exit-code -- frontend/routes
composer sass:build
```
Teste visual do toast e do dialog: siga `.agents/rules/e2e-testing.md` (peça URL, credenciais e aprovação antes de abrir o navegador).

## Handoff
_Preenchido por quem executar._

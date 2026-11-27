---
id: R3-FRONT-04
title: "Ponte legada: script clássico que substitui common.js e util.js em todos os layouts"
release: R3
module: plataforma
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-01, R3-FRONT-02, R3-FRONT-03]
size: M
risk: alto
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3: ponte legada), seção 13 (riscos) e seção 16 (pergunta 6: ENTER)"
write_scope:
  - frontend/legacy-bridge/**
  - vite.bridge.config.js
  - themes/default/views/layouts/fullmenu.php
  - themes/default/views/layouts/reports.php
  - app/modules/enrollmentonline/layouts/enrollmentonline.php
  - package.json
  - tests/e2e/**
read_only:
  - themes/default/js/common.js
  - themes/default/js/util.js
  - frontend/core/**
  - frontend/ui/**
  - frontend/validators/**
  - frontend/components/**
forbidden:
  - composer.json
  - app/config/main.php
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "npm run lint && npm run typecheck && npm test && npm run build"
  - "Vitest: a superfície global da ponte contém todos os nomes que common.js e util.js definem, com o mesmo typeof"
  - "Playwright: smoke das páginas dos layouts fullmenu, reports e enrollmentonline sem pageerror nem console.error, e visual sem diferença não aprovada"
  - "git grep -n \"js/common.js\\|js/util.js\" -- themes app   (sem resultados nos layouts)"
---

# R3-FRONT-04: Ponte legada: script clássico que substitui common.js e util.js em todos os layouts

## Contexto
**Card do coordenador:** edita layouts de `themes/default/views/layouts/` (arquivos com dono) e o do enrollmentonline.

`themes/default/js/common.js` (271 linhas) e `util.js` (546 linhas) são scripts clássicos carregados em `fullmenu.php` (≈L303 e ≈L304), `reports.php` (≈L68 e ≈L70) e `app/modules/enrollmentonline/layouts/enrollmentonline.php` (≈L53 e ≈L54). `reportsclean`, `login` e `resultsmanagement` não os carregam (**confirmar** com grep). A ponte é **um script clássico (IIFE)**, gerado por `vite.bridge.config.js` à parte do build de módulos, que entra na mesma posição, depois de jQuery, jQuery UI, mask, bootstrap e select2. O jQuery fica externo (`window.jQuery`). Não repita o erro de 2024: nada de empacotar jQuery e plugins num bundle ES único.

Comportamento verificado que a ponte herda (cada item é decisão registrada no Handoff):
- **Globais de `util.js`:** todas as funções de nível raiz (`rule`, `validate*`, `addError`/`removeError`, `addWarning`, `errorMessage`, `addRequired`, `pad`, `isset`, `decodeHtml`, `initDateFieldMaskAndValidation`, as de AJAX etc.), os objetos `dateRules`/`stringRules`/`numberRules` e `buscar`. De `common.js`: `isOpen` (≈L253) e `template` (≈L270). A lista completa deve ser gerada por análise do arquivo (por exemplo `acorn`), não à mão.
- **`origin` e `pathname`** (`util.js` ≈L195, `const` global) só são usados dentro do próprio `util.js` (as quatro funções com AJAX); **confirmar** com `legacy:who-uses`. Saem da ponte.
- **ENTER:** `common.js` ≈L264 faz `$("*").keypress(...)` com `e.keyCode === $.ui.keyCode.ENTER` e `preventDefault()`. O seletor liga só os elementos que existem no carregamento; textarea estático perde a quebra de linha, e elementos criados depois (por exemplo o editor Quill do MACETE) não são afetados. Troque por **um** listener delegado no `document` e use o literal 13 (hoje depende do jQuery UI estar carregado). Regra proposta: bloquear Enter em `input` de formulário; liberar em `textarea`, `[contenteditable]`, botões e links. **Validar com o produto** (pergunta aberta 6).
- **Erros AJAX:** o `$(document).ajaxError` (≈L178–L245) trata 401 com `{redirect}` e envia `[AJAX ERROR] …` ao Sentry, mas chama `Raven.captureMessage` sem checar (≈L222) e quebra onde o SDK não existe. Use o relator do `core/http` com guarda, mantendo o formato da mensagem e as tags.
- **CSRF:** o R2-SEC-03 colocou um `$.ajaxPrefilter` no `common.js` que lê `<meta name="csrf-token">` (métodos fora de GET/HEAD/OPTIONS, só mesmo domínio). A ponte o leva adiante, para os ≈193 `$.ajax` e para `$.post`/`$.getJSON`, sem editá-los, e compartilha a leitura do token com o `core/http`.
- Também executam no carregamento: `.modal({backdrop: "static", ...})` (≈L1), menu e submenus (≈L5–L32), tooltips e popovers, `[data-toggle="print"]`, tabelas `.checkboxs`/`.radioboxs`, `.js-table-sortable`, as inicializações de select2 (≈L152–L166), o cursor `wait` em `ajaxSend`/`ajaxComplete` (≈L169–L176) e o clique em `#button-menu` (≈L254).
- Usos não padrão para trocar por equivalentes: `.size()` (≈L69, L82, L141) por `.length` e `e.srcElement` (≈L48, L101) por `e.target`.
- O `datatablesptbr.js` (`getLanguagePtbr`) e o `js/layout/functions.js` continuam carregados como estão; este card não os toca (o R3-FRONT-05 absorve o segundo).
- `build.target`: consulte o GA4 antes de fixar (risco da seção 13). Se faltar dado, use um alvo conservador e registre.

## Objetivo
Os layouts carregam a ponte no lugar de `common.js` e `util.js`; todo código legado continua funcionando com os mesmos nomes globais, mais `window.TAG`, e o CSRF passa a ir em todo AJAX.

## Passos
1. `vite.bridge.config.js` (formato `iife`, `jquery` como externo/global, saída fora do manifest de módulos) e `frontend/legacy-bridge/index.js`. É o **único** lugar autorizado a atribuir `window.*` (regra do ESLint da F1); ajuste a configuração do lint só para essa pasta, com o coordenador.
2. Reexponha, com os **mesmos nomes**, as funções e os objetos de `util.js` apoiados nos validators (R3-FRONT-02), em `ui/form-errors` (R3-FRONT-01, mantendo `id + '_error'`) e em `components` (R3-FRONT-03); reexponha `isOpen` e `template`. Mantenha os comportamentos do `common.js` listados acima.
3. Exponha `window.TAG = { http, html, url, ui, validators, components, onPage, initWithin, version }`, para o legado e para handlers inline migrarem sem criar globais novos.
4. Implemente o prefilter de CSRF, o relator de erro com guarda de `Raven`/`Sentry`, o 401 com `{redirect}` e o listener único de ENTER. Troque `.size()` e `e.srcElement`.
5. Layouts: no lugar das duas tags, uma só, pelo mecanismo do `TFrontend` (**confirmar** com o R2-FRONT-02 como o arquivo clássico é referenciado e versionado). Mantenha a ordem relativa aos demais scripts (depois de jQuery, jQuery UI, mask e bootstrap; no enrollmentonline o select2 vem depois, o que continua válido porque as inicializações rodam no `ready`).
6. `tag-qa`: (a) Vitest/jsdom carrega `common.js` + `util.js` reais e a ponte e compara as superfícies globais (nomes e `typeof`); (b) testes de comportamento (prefilter, 401, ENTER, `Raven` ausente); (c) Playwright (smoke) nos três layouts e na suíte visual do R1.
7. Só depois de verde e com aprovação humana: apague `common.js` e `util.js`, com grep por qualquer referência (inclusive `$.getScript` e caminhos concatenados).

## Fora de escopo
- Migrar páginas para `onPage` (F4) e o `js/layout/functions.js` e `notifications.js` (R3-FRONT-05).
- Atualizar jQuery ou plugins (F6).
- Corrigir os defeitos dos validators (issues do R3-FRONT-02).

## Critérios de aceite
- [ ] Todo nome global definido por `common.js` e `util.js` existe na ponte, com o mesmo `typeof`; `origin` e `pathname` não existem mais.
- [ ] Nenhum `$.ajax` precisou de edição para enviar o CSRF.
- [ ] Sem `Raven` carregado, um erro AJAX não lança exceção.
- [ ] O ENTER segue a decisão de produto registrada; textarea aceita quebra de linha se assim for decidido.
- [ ] Smoke dos três layouts sem `pageerror`/`console.error` e visual sem diferença não aprovada.
- [ ] A catraca de globais e de `?r=` só cai.

## Como verificar
```bash
npm run lint && npm run typecheck && npm test && npm run build
npm run build:bridge        # script do package.json para o vite.bridge.config.js (nome a confirmar)
git grep -nE "js/(common|util)\.js" -- themes app    # sem resultados nos layouts
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação do plano.

## Handoff
_Preenchido por quem executar._

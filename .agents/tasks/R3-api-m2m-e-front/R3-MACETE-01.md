---
id: R3-MACETE-01
title: "Piloto MACETE: migrar o JS do módulo para frontend/pages/macete e tirar o forceCopy"
release: R3
module: macete
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-04]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3: piloto MACETE e convenção de página) e seção 8.1"
write_scope:
  - frontend/pages/macete/**
  - app/modules/macete/views/**
  - app/modules/macete/MaceteModule.php
  - app/modules/macete/resources/**
  - package.json
  - package-lock.json
  - metrics/baseline/macete.json
  - tests/e2e/macete/**
read_only:
  - frontend/core/**
  - frontend/ui/**
  - frontend/components/**
  - frontend/routes/macete.js
  - app/modules/macete/MaceteRoutes.php
  - app/modules/macete/controllers/**
  - themes/default/js/quill.min.js
  - themes/default/js/purify.min.js
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
  - app/modules/macete/controllers/**
acceptance:
  - "npm run lint && npm run typecheck && npm test && npm run build"
  - "npm run entries:check   (toda entry usada nas views existe no manifest)"
  - "Playwright: smoke das telas do MACETE (planos, modelos, registros) sem pageerror/console.error e visual sem diferença não aprovada"
  - "git grep -nE 'window\\.Macete|MaceteFormConfig|\\?r=' -- app/modules/macete frontend/pages/macete   (sem resultados)"
---

# R3-MACETE-01: Piloto MACETE: migrar o JS do módulo para frontend/pages/macete e tirar o forceCopy

## Contexto
É a **implementação de referência** da skill `tag-js-page-migration`: o que ficar decidido aqui os demais módulos copiam. O módulo é pequeno, está em desenvolvimento ativo e já usa Quill e DOMPurify. Pré-requisitos (R2-FRONT e R3-FRONT-01 a 04): Vite, `TFrontend::registerEntry`/`jsonScript`, `onPage`, `core/http`, `ui/dialog`, `components/` e a ponte; **confirmar** nomes no Handoff deles.

Inventário verificado (`app/modules/macete/resources/`, 557 linhas, 5 arquivos):
- `macete.js` (127): define `window.Macete` (`escapeHtml` ≈L4, `initTabs` ≈L8, `initAbilitySelector` ≈L59 com `?r=macete/ability/search` ≈L69, `addAbility` ≈L92 com HTML concatenado). Select2 3 com `formatResult` que devolve HTML e `escapeMarkup` que não escapa nada.
- `rich-text.js` (75): `window.Macete.initRichText`, Quill e DOMPurify lidos de `window`. Sem `window.DOMPurify` devolve o conteúdo **sem sanitizar** (≈L8–L15); só o layout `fullmenu` carrega o `purify.min.js` (≈L311). O hook de envio usa `#macete-lesson-plan-form, #macete-lesson-record-form` (≈L62), mas o formulário de modelos tem o id `macete-lesson-plan-template-form`; nele só vale o `text-change` (≈L51).
- `lesson-plan.js` (164): lê `window.MaceteFormConfig` (≈L72) com fallback `?r=macete/lessonsplan/getDisciplines` (≈L73), monta `<option>` por concatenação com `window.Macete.escapeHtml` (≈L88), usa `MutationObserver` (≈L158) e `setTimeout(updateSidebar, 0)` (≈L154).
- `lesson-record-days.js` (109): quatro `?r=` escritos à mão (≈L20, L48, L57, L74); monta cartões com jQuery e `.text()` (seguro).
- `lesson-record.js` (82): `?r=macete/lessonsplan/getPlan&id=` (≈L23), `JSON.parse(response)` (≈L25), `window.confirm` e `window.alert` (≈L53 e ≈L65), `window.Macete.addAbility` (≈L34).
- Fora dos 557: o `<script>` inline de `views/lessonsplan/index.php` (≈L241–L379, modal de modelos com busca e filtro) e o `<script type="text/template" id="macete-stage-component-template">` de `lessonsplan/_form.php` (≈L207) e `templates/_form.php` (≈L190).

Como as views carregam hoje: `lessonsplan/_form.php` (≈L19–L23), `lessonsrecord/_form.php` (≈L14–L18), `lessonsrecord/index.php` (≈L7) e `templates/_form.php` (≈L16–L25) registram `quill.snow.css` e `quill.min.js` do tema (Quill **2.0.3**, DOMPurify **2.3.10**; o npm terá DOMPurify 3, **confirmar** que as listas `allowedTags`/`allowedAttributes` continuam valendo), os cinco scripts por `$baseScriptUrl . '/x.js?v=' . TAG_VERSION` e, só em `templates/_form.php`, `window.MaceteFormConfig = {getDisciplinesUrl: …}` por `registerScript` (≈L20–L24, `MaceteRoutes::url(MaceteRoutes::TEMPLATES_GETDISCIPLINES)`).

`MaceteModule::init()` publica `resources` com `forceCopy` (`->publish(…, false, -1, true)`, ≈L15–L16): **copia a pasta a cada request**. `beforeControllerAction` exige `TTask::TASK_MACETE` (≈L25), então o tenant de teste precisa ter a funcionalidade ligada (**confirmar** no demo).

## Objetivo
Todo o JS do MACETE em `frontend/pages/macete/`, no padrão `registerEntry`/`onPage`/`jsonScript`, sem `window.Macete`, sem `?r=` literal, com Quill e DOMPurify do npm e sem o `forceCopy`.

## Passos
1. Antes de mexer, escreva (`tag-qa`) o smoke Playwright das telas do MACETE com o JS atual e grave o baseline visual: lista e formulário de planos, modal de modelos, formulário de modelos, lista de registros (turma → mês → dias), dia e formulário de registro. Cubra: abas (próximo/anterior/salvar), busca de habilidade (≥ 3 letras) que acrescenta `abilities[]`, escolha de etapa que carrega componentes, editor Quill que grava no textarea, exclusão de registro.
2. `frontend/pages/macete/`: `shared/tabs.js`, `shared/abilities.js`, `shared/rich-text.js` (Quill e DOMPurify importados do npm, versões fixas; `import 'quill/dist/quill.snow.css'`), `lesson-plan-form.js`, `lesson-record-form.js`, `lesson-record-days.js` e `lesson-plan-index.js` (o `<script>` inline). Cada página registra `onPage('macete/<nome>', (root, config) => …)`; eventos delegados a partir de `root`; sem globais. As páginas de plano e de modelo compartilham o `lesson-plan-form.js`.
3. Substitua: `$.ajax` por `core/http` com `url(routes.MaceteRoutes…)` de `frontend/routes/macete.js` e `schoolreport.js` (o link de impressão aponta para `schoolreport/reports/MaceteDiaryReport`); HTML por concatenação por `html` ou `new Option`; `window.confirm`/`alert` por `confirmDialog` e `toast.error`; `MaceteFormConfig` por JSON (`TFrontend::jsonScript`) com a **rota** a chamar. Preserve selectors, ids e classes `js-macete-*` e a lógica de `updateSidebar`, do token de requisição por linha e do `MutationObserver`.
4. Views: troque os `registerScriptFile`/`registerCssFile`/`registerScript` por `TFrontend::registerEntry('pages/macete/<nome>.js')`, um `<div data-page="macete/<nome>">` e `jsonScript($config)`. O formulário de modelos deve passar a receber o `#macete-lesson-plan-template-form` no hook de envio (ou documente que o `text-change` basta).
5. Remova o `forceCopy` e a publicação de `resources` do `MaceteModule` (e `$baseScriptUrl` nas views), mantendo `baseUrl` e o `setImport`. Depois do smoke verde e com aprovação humana, apague `app/modules/macete/resources/*.js`. Só apague `themes/default/js/quill.min.js`, `purify.min.js` e `css/quill.snow.css` se um grep provar que nenhum outro módulo os usa (senão, anote).
6. Atualize `metrics/baseline/macete.json` (catraca): zero `?r=` literais e zero globais novos no módulo; os totais só caem.
7. Registre no Handoff o roteiro reaproveitável (decisões de estrutura, nomes, testes) para a skill `tag-js-page-migration`.

## Fora de escopo
- Mudar regra de negócio do MACETE, os controllers e o SCSS (`sass/scss/components/_macete.scss`).
- O layout, o jQuery e o select2 (continuam como estão; select2 3 sai na F6).
- Corrigir os defeitos que o smoke revelar (viram issue).

## Critérios de aceite
- [ ] O smoke Playwright das telas do MACETE passa antes e depois, com o mesmo baseline visual.
- [ ] Nenhum `window.Macete`, `MaceteFormConfig`, `?r=` ou `window.confirm/alert` em `app/modules/macete` e `frontend/pages/macete`.
- [ ] Nenhuma view do módulo usa `registerScriptFile`; todas usam `registerEntry`, e o `entries:check` passa.
- [ ] O Quill e o DOMPurify vêm do npm, e o conteúdo do editor é sempre sanitizado (mesmo nos layouts sem `purify.min.js`).
- [ ] O `MaceteModule` não copia mais a pasta `resources` a cada request.
- [ ] A catraca do módulo marca zero `?r=` e zero globais novos.

## Como verificar
```bash
npm run lint && npm run typecheck && npm test && npm run build && npm run entries:check
git grep -nE "window\.Macete|MaceteFormConfig|\?r=|registerScriptFile" -- app/modules/macete frontend/pages/macete   # sem resultados
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais (usuário com a funcionalidade MACETE) e a aprovação do plano.

## Handoff
_Preenchido por quem executar._

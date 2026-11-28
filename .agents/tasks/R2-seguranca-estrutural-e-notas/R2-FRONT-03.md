---
id: R2-FRONT-03
title: "Servir o CSS do design system pelo Vite e apagar o main.css compilado e o serviço sass"
release: R2
module: plataforma
type: css
skill: .agents/playbooks/ui-refactor.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R2-FRONT-01, R1-FRONT-05]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F1, Troca do CSS do design system) e F5"
write_scope:
  - frontend/styles/main.scss
  - sass/scss/components/_field-multiselect.scss
  - sass/css/main.css
  - sass/css/main.map
  - themes/default/views/layouts/fullmenu.php
  - themes/default/views/layouts/reportsclean.php
  - themes/default/views/site/login.php
  - app/modules/enrollmentonline/layouts/enrollmentonline.php
  - app/modules/aeerecord/views/reports/**
  - app/modules/foods/views/reports/**
  - app/modules/schoolreport/views/forms/**
  - app/modules/systemadmin/views/default/instanceConfig.php
  - app/modules/systemadmin/views/default/manageModules.php
  - docker-compose.yml
  - composer.json
  - docs/sass_commands.md
  - .agents/rules/design-system-usage.md
  - .agents/playbooks/ui-refactor.md
  - AGENTS.md
  - CLAUDE.md
read_only:
  - sass/scss/**
  - frontend/vite.config.js
  - app/components/utils/TFrontend.php
forbidden:
  - sass/scss/main.scss
  - sass/css/notifications.css
  - app/models/**
acceptance:
  - "composer front:build"
  - "git grep -n \"sass/css/main.css\" -- app themes .agents/rules .agents/playbooks docs/sass_commands.md   (sem resultados)"
  - "Suíte Playwright visual e smoke (R1-FRONT-01): sem diferença não aprovada nas páginas com o CSS do design system"
  - "composer lint"
---

# R2-FRONT-03: Servir o CSS do design system pelo Vite e apagar o main.css compilado e o serviço sass

## Contexto
Este é um **card do coordenador**: edita `fullmenu.php`, `reportsclean.php`, `composer.json` e `docker-compose.yml`, que têm dono.

Hoje o design system é compilado para `sass/css/main.css`, **commitado**, com uma linha só (66,6 KB), e conflita em todo PR. As referências, verificadas com grep em 2026-11-27 (releia no dia, porque o R1-FRONT-05 remove algumas views que registram o `main.css` em duplicata):
- Layouts: `themes/default/views/layouts/fullmenu.php` ≈L84 (a ≈L85 carrega também `sass/css/notifications.css`, que **fica**), `reportsclean.php` ≈L22 e `app/modules/enrollmentonline/layouts/enrollmentonline.php` ≈L10.
- Login: `themes/default/views/site/login.php` ≈L14.
- Views: `aeerecord/views/reports/aeeRecordReport.php` ≈L4, `foods/views/reports/FoodMenuReport.php` e `ShoppingListReport.php` ≈L4, `schoolreport/views/forms/StudentIMCReport.php` e `StudentIMCHistoryReport.php` ≈L8, e `systemadmin/views/default/instanceConfig.php` e `manageModules.php` ≈L15 (estas duas montam o caminho sem a barra: `$baseUrl . 'sass/css/main.css'`).
- Compilação: serviço `sass` do `docker-compose.yml`, `sass:build` e `sass:watch` do `composer.json`, `docs/sass_commands.md` e a regra `.agents/rules/design-system-usage.md` ("Build rule").

Detalhes do SCSS:
- `sass/scss/main.scss` usa `@import` e termina com `_helpers.scss` (≈L46), que a regra do design system manda manter por último.
- `components/_field-multiselect.scss` ≈L24 tem `url('../../../themes/default/img/botao-cancelar-multiselect.svg')`, relativo ao CSS compilado, e provavelmente só funciona porque o navegador limita o caminho à raiz do host. As outras imagens e fontes usam caminho absoluto (`_icons.scss` ≈L577 e `_typography.scss` ≈L3: `/themes/default/...`).
- `_base.scss` ≈L1 faz `@import url('https://fonts.googleapis.com/...')`, que o CSS mantém no topo.

O serviço `node` e o `TFrontend` vêm do R2-FRONT-01 (`registerStyle()`).

## Objetivo
O CSS do design system sai do bundle `styles/main.scss` do Vite, com hash no nome, sem arquivo compilado no git, **sem diferença visual**.

## Passos
1. Confirme o R1-FRONT-05 concluído, o `TFrontend::registerStyle` funcionando e a suíte visual verde (baseline) **antes** da troca.
2. `frontend/styles/main.scss`: `@use '../../sass/scss/main';`. Rode `composer front:build` e confira que o CSS do manifest tem as mesmas regras do `sass/css/main.css` atual (compare seletores e propriedades; a ordem tem de ser a mesma).
3. Em `_field-multiselect.scss` ≈L24, troque o caminho por `/themes/default/img/botao-cancelar-multiselect.svg`, igual aos demais. O `vite.config.js` usa `publicDir: false`, então o Vite deixa o caminho absoluto como está (confira que não houve warning novo no build).
4. Troque cada `registerCssFile(... '/sass/css/main.css' ...)` por `TFrontend::registerStyle('styles/main.scss')`, **na mesma posição** do `registerCssFile` (a ordem de registro define a cascata; no `fullmenu`, depois de `form.css` e antes de `notifications.css`).
5. Apague `sass/css/main.css` e `sass/css/main.map` (se ainda existir). **Não** apague `sass/css/notifications.css`.
6. `docker-compose.yml`: remova o serviço `sass`. `composer.json`: `sass:build` passa a chamar `front:build`, e `sass:watch` chama `front:dev` (os nomes continuam funcionando).
7. Documentação: reescreva `docs/sass_commands.md` com os comandos novos, ajuste a "Build rule" de `.agents/rules/design-system-usage.md` e o que mencionar o build antigo em `.agents/playbooks/ui-refactor.md` e no `AGENTS.md`/`CLAUDE.md` (grep `sass:build` e `sass/css`).
8. Rode a suíte visual e o smoke. Qualquer diferença entra em revisão humana, com captura. Confira também a página de login e os relatórios impressos.
9. Atenção à cascata: o `main.css` era um arquivo só, agora é outro `<link>`. Se alguma página ficar diferente, compare a ordem dos `<link>` antes de mexer em SCSS.

## Fora de escopo
- Mudar qualquer regra de CSS, token ou classe (a não ser a URL do passo 3).
- Migrar de `@import` para `@use` (F5/R3) e o PurgeCSS.
- `notifications.css`, `form.css` e os demais CSS soltos.
- Alterar o `sass/scss/main.scss`.

## Critérios de aceite
- [ ] Nenhuma referência a `sass/css/main.css` em `app`, `themes`, `.agents/rules`, `.agents/playbooks`, `docs/sass_commands.md`, `AGENTS.md` e `CLAUDE.md` (o plano e os cards descrevem o estado antigo e não mudam).
- [ ] `sass/css/main.css` e o serviço `sass` foram removidos; `composer sass:build` continua existindo e gera o CSS pelo Vite.
- [ ] O CSS do manifest contém as mesmas regras do arquivo antigo (comparação registrada no Handoff).
- [ ] A suíte visual e o smoke não têm diferença não aprovada, incluindo login, relatórios e IMC.
- [ ] A documentação e a regra do design system apontam para o comando novo.

## Como verificar
```bash
composer front:build && ls build/.vite/manifest.json && grep -o '"styles/main.scss"[^}]*' build/.vite/manifest.json
git grep -n "sass/css/main.css" -- app themes .agents/rules .agents/playbooks docs/sass_commands.md AGENTS.md CLAUDE.md   # esperado: sem resultados
git ls-files sass/css   # esperado: só notifications.css
# no navegador, com a aplicação no ar: DevTools → Network → o <link> do main-<hash>.css responde 200
```
- Confirmar: se algum arquivo JS ou PHP monta o caminho do `main.css` por concatenação (grep por `sass/css` e `main.css`).
- Confirmar: se outro processo (deploy, script) usa `sass/css/main.css` ou o serviço `sass` (pergunta 1 da seção 16 do plano).
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

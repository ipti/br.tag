---
id: R3-CSS-02
title: "SCSS: migrar @import para @use/@forward com sass-migrator e remover o silenceDeprecations"
release: R3
module: plataforma
type: css
skill: .agents/playbooks/ui-refactor.md
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-CSS-01]
size: P
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F5: tokens e @use)"
write_scope:
  - sass/scss/**
  - frontend/vite.config.js
  - package.json
  - package-lock.json
  - tests/e2e/**
read_only:
  - frontend/styles/main.scss
  - app/components/utils/TFrontend.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer front:build   (sem aviso de depreciação do @import; CSS compilado equivalente ao anterior)"
  - "Comparação do CSS compilado antes e depois (expanded, normalizado): mesmos seletores, mesmas declarações, mesma ordem"
  - "Suíte Playwright visual e smoke (R1-FRONT-01): sem diferença não aprovada"
  - "git grep -n \"@import\" -- sass/scss   (só o @import url() de fontes, se mantido)"
---

# R3-CSS-02: SCSS: migrar @import para @use/@forward com sass-migrator e remover o silenceDeprecations

## Contexto
**Card do coordenador:** reescreve `sass/scss/main.scss` (arquivo com dono) e toca todas as parciais. Depende do R3-CSS-01, que criou `sass/scss/pages/` e o parcial `_custom-properties.scss`.

O `@import` está depreciado no Dart Sass. Hoje o build do Vite (R2-FRONT-03, `frontend/styles/main.scss` com `@use '../../sass/scss/main'`) só passa sem ruído porque a configuração do Vite silencia a depreciação (`silenceDeprecations`; **confirmar** o arquivo e as entradas no Handoff do R2-FRONT-01/03, em `frontend/vite.config.js`). Fatos verificados:
- `sass/scss/main.scss` faz cerca de 40 `@import`. A **ordem dos imports é a ordem da cascata**; `_helpers.scss` vem por último (≈L46) e assim deve continuar.
- Vinte arquivos usam `@import`. Doze parciais importam `_tokens.scss` e/ou `_functions.scss` por conta própria (por exemplo `_button.scss` ≈L1–L2, `_grid.scss` ≈L1–L3, `components/_field-text.scss` ≈L1–L2, via `../_tokens.scss`). `_reports.scss` só importa `_tokens.scss`.
- `_functions.scss` define os mixins `device($device)` e `spacings($property, $value)`; este usa o `if()` global (≈L26). Nenhum `!global` (grep).
- `_base.scss` ≈L1 faz `@import url('https://fonts.googleapis.com/…')`, **CSS puro**, que precisa continuar no topo do CSS final. O `@extend` aparece só dentro do mesmo arquivo (`_accordeon`, `_badge`…), o que o módulo novo permite.
- `main.scss` ainda tem imports repetidos (`_accordeon.scss` e `_accordeon`; `reports.scss` e `_reports`). Com `@import`, o CSS sai duas vezes; com `@use`, **uma só**, na posição da primeira carga. O R1-FRONT (limpeza, seção F0) deve ter deixado só a última ocorrência; **confirmar** que não sobrou duplicata, senão a ordem da cascata muda.
- Variáveis globais compartilhadas viram problema no módulo novo: uma parcial que usava variável definida em outra (sem importá-la) deixa de compilar.

## Objetivo
Todo o SCSS usa `@use`/`@forward`, o `silenceDeprecations` do `@import` some, e o CSS compilado é equivalente ao de antes.

## Passos
1. Antes de tudo, compile e guarde o CSS atual em `--style=expanded` (baseline da comparação) e confirme a suíte visual verde.
2. Fixe `sass-migrator` em `devDependencies` e rode primeiro a simulação: `npx sass-migrator module --migrate-deps --dry-run --verbose sass/scss/main.scss`. Leia o relatório; só depois rode de verdade.
3. Ajuste o resultado ao padrão do plano:
   - crie `sass/scss/abstracts/_index.scss` com `@forward '../tokens';` e `@forward '../functions';` (e, se existir, o que mais for só variável ou mixin);
   - cada parcial que usa tokens ou mixins começa com `@use '../abstracts' as *;` (ou `@use 'abstracts' as *;` na raiz). Sem prefixo de namespace nos usos;
   - `_custom-properties.scss` (R3-CSS-01) passa a emitir dentro de `_tokens.scss` (ou continua em parcial próprio, a critério do Handoff), sempre **uma vez**;
   - `main.scss` vira uma lista de `@use` **na mesma ordem** dos imports atuais, `pages` (`@use 'pages'`, com `@forward` por módulo em `pages/_index.scss`) antes de `_helpers`, e `_helpers` por último.
4. O `@import url(…)` das fontes: confirme se o compilador o aceita antes dos `@use` no mesmo arquivo; se não, passe-o para a primeira linha do `main.scss` (CSS puro) ou para o layout, e registre a escolha. Verifique que a linha continua no **topo** do CSS compilado.
5. Corrija o que o compilador acusar: variáveis usadas sem import, mixins não encontrados, e as depreciações que sobrarem (por exemplo o `if()` global de `_functions.scss`, se a versão do Sass da imagem avisar; **confirmar** a versão). O que não puder ser corrigido aqui entra numa lista no Handoff.
6. Remova o `import` (e o que mais deixar de ser necessário) do `silenceDeprecations` em `frontend/vite.config.js`. Se restar silenciamento de outra depreciação, deixe só a entrada exata e comente o porquê.
7. `tag-qa`: compile de novo em `expanded`, normalize (Prettier ou PostCSS) e compare com o baseline: mesmos seletores, declarações e ordem. A única diferença aceitável é a que decorre de duplicatas já removidas, explicada no Handoff. Rode a suíte visual e o smoke.
8. Atualize `.agents/rules/design-system-usage.md` (regra "Import rule": novos parciais entram por `@use`/`@forward`) **só** se o coordenador autorizar; do contrário, deixe a sugestão no Handoff.

## Fora de escopo
- Mudar valores de design, renomear classes ou mover CSS solto para `pages/` (cards de módulo).
- Trocar `rgb`/cores ou reescrever `_functions.scss`.
- Migrar CSS de ilhas e bibliotecas de terceiros.

## Critérios de aceite
- [ ] Nenhum `@import` do Sass em `sass/scss` (exceto o `url()` de fontes, se mantido) e nenhum aviso `import` no build.
- [ ] O `silenceDeprecations` não lista mais o `@import`.
- [ ] O CSS compilado é equivalente ao baseline (seletores, declarações e ordem), e o `@import url` das fontes segue no topo.
- [ ] `:root{--t-` aparece uma vez.
- [ ] Suíte visual e smoke sem diferença não aprovada.
- [ ] As depreciações restantes estão listadas no Handoff.

## Como verificar
```bash
composer front:build 2>&1 | grep -ci "import rules are deprecated"     # 0
git grep -n "@import" -- sass/scss                                      # só o url() de fontes, se houver
head -c 200 build/assets/*.css | head -3                                # o @import url das fontes continua primeiro
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação do plano.

## Handoff
_Preenchido por quem executar._

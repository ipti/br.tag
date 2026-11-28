---
id: R3-CSS-03
title: "PurgeCSS em modo relatório no design system e remoção do que for confirmado sem uso"
release: R3
module: plataforma
type: css
skill: .agents/playbooks/ui-refactor.md
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-CSS-01]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F5: detectar CSS sem uso) e seção 13 (cascata CSS)"
write_scope:
  - scripts/css-report/**
  - docs/frontend/purgecss-design-system.md
  - sass/scss/**
  - package.json
  - package-lock.json
  - tests/e2e/**
read_only:
  - app/**
  - themes/default/views/**
  - js/**
  - themes/default/js/common.js
  - themes/default/js/util.js
  - frontend/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "npm run css:report   (gera o relatório de rejeitados sem alterar nenhum CSS)"
  - "Teste de sanidade: t-button-primary, tag-table-primary, t-cards e t-badge-info NÃO aparecem entre os rejeitados"
  - "Para cada classe removida: git grep do nome e dos fragmentos montados por concatenação sem resultado fora do SCSS"
  - "Suíte Playwright visual e smoke (R1-FRONT-01): sem diferença não aprovada depois das remoções"
---

# R3-CSS-03: PurgeCSS em modo relatório no design system e remoção do que for confirmado sem uso

## Contexto
O plano manda detectar CSS sem uso só em **modo relatório** e remover apenas o que a leitura do código e a suíte visual confirmarem, porque as classes do TAG são montadas de muitas formas e o PurgeCSS, que lê texto, erra para os dois lados. Este card cobre o **design system** (`sass/scss/**`); o AdminKIT (`template.css`, 183 KB) e o resto do CSS de vendor ficam para depois. Os layouts e `main.scss` têm dono: parciais inteiras que ficarem vazias exigem um card de contrato para tirar o `@import`/`@use` do `main.scss`.

Por que o texto engana (exemplos verificados):
- Classe dentro de string de JS: `app/modules/classdiary/resources/legacy/classes/class-contents/functions.js` ≈L3 monta `<div class="t-badge-info t-margin-none--left">` por concatenação.
- Classe vinda de PHP com interpolação: `app/components/utils/TMenu.php` ≈L56–L66 emite `class="t-menu-group {$activeClass}"`, `class="collapse {$collapseClass}"` (`active`, `in`) e `{$item['icon']}`, este vindo dos arquivos de menu em `themes/default/views/layouts/menus/`.
- Classes de plugin e do Bootstrap 2 que o JS liga e desliga: `select2-*`, `dataTables_*`/`dt-*`/`dtr-*`, `ui-*`, `fc-*`, `datepicker*`, `ql-*`, `modal`, `fade`, `in`, `active`, `open`, `collapse`, `show`, `hidden`, `disabled`, `error`.
- Modificadores BEM (`t-*--x`, `t-*__x`) e os prefixos de `t-badge-`, `t-button-`, `t-icon-`.
- As classes novas do R3-CSS-01 (`tag-m-<módulo>`, `tag-p-…`), que só existem em tempo de execução.
- HTML guardado no banco ou gerado por editores (Quill) não está em nenhum arquivo.

O CSS compilado vem do Vite (R2-FRONT-03; o build fica em `build/`, fora do git). `sass/css/main.css` deixou de existir.

## Objetivo
Um relatório reproduzível de seletores do design system sem uso aparente, classificado com evidência, e a remoção, em commits pequenos, só do que foi confirmado sem uso.

## Passos
1. Instale `purgecss` fixo em `devDependencies` e crie `scripts/css-report/purgecss.config.cjs` com `rejected: true`, sem `rejectedCss` aplicado a arquivo algum e sem escrever o CSS final. Script `npm run css:report` (e comando de uso no cabeçalho do arquivo).
2. `content` (todos os lugares que podem citar classe): views PHP (`app/**/views/**/*.php`, `themes/default/views/**/*.php`, `app/views/**`), widgets e helpers (`app/components/widgets/**`, `app/components/utils/TMenu.php`, `app/components/utils/*.php`), arquivos de menu, `js/**`, `app/modules/*/resources/**` (sem `lib/` e `*.min.js`), `themes/default/js/common.js`, `util.js`, `frontend/**`. Exclua `vendor/`, `app/gii/`, `app/extensions/` e `node_modules/`.
3. Extrator **amplo**: pegue qualquer token `[A-Za-z0-9_\-:./%]+` e também os fragmentos de cada token separados por `-`, `_`, `__` e `--` (classe montada por concatenação aparece em pedaços). Prefira falso negativo (marcar como usada) a falso positivo.
4. `safelist` padrão (regex) com comentário do motivo de cada item: `select2-`, `dataTables`, `dt-`, `dtr-`, `ui-`, `fc-`, `datepicker`, `ql-`, `^t-.*(--|__)`, `^t-(badge|button|icon)-`, `^tag-(m|p)-`, e as palavras de estado `modal`, `fade`, `in`, `active`, `open`, `collapse`, `show`, `hidden`, `disabled`, `error`. Acrescente o que o relatório mostrar necessário, sempre justificando.
5. Gere o relatório sobre o CSS compilado do design system e mapeie cada seletor rejeitado de volta à parcial (busca do seletor em `sass/scss/**` ou source map). Escreva `docs/frontend/purgecss-design-system.md` com: método e configuração, safelist e motivos, tabela por parcial (seletor, ocorrências por `git grep`, classificação `sem uso confirmado` / `uso dinâmico provável` / `dúvida`, ação) e os limites conhecidos (HTML do banco, editor, uso futuro).
6. `tag-qa`: teste de sanidade do extrator com classes sabidamente usadas (`t-button-primary`, `tag-table-primary`, `t-cards`, `t-badge-info`, `t-menu-group__link`, `active`) que **não** podem aparecer como rejeitadas, e com uma classe inventada que deve aparecer. Rode o relatório duas vezes e confira que o resultado é igual.
7. Remoção: para cada item `sem uso confirmado`, faça `git grep` do nome inteiro **e** dos fragmentos (prefixo e sufixo montados por concatenação, inclusive `$.getScript` e HTML em JS) em `app`, `themes`, `js`, `frontend` e nos menus. Só remova com zero resultados fora do SCSS. Um commit por parcial, com o ID do card, e rode a suíte visual a cada lote.
8. Registre no Handoff o tamanho do CSS compilado antes e depois e a lista do que ficou como `dúvida` para o card seguinte.

## Fora de escopo
- Aplicar o PurgeCSS no build de produção ou usá-lo como gate do CI (relatório sob demanda).
- AdminKIT/`template.css`, Bootstrap 2, CSS de ilhas e de vendor.
- Remover parciais inteiras (precisa de card de contrato para o `main.scss`) e classes de Bootstrap 2 ainda usadas pelas views.
- Mover CSS solto para `sass/scss/pages/` (cards de módulo).

## Critérios de aceite
- [ ] `npm run css:report` roda duas vezes com o mesmo resultado e não altera nenhum CSS.
- [ ] O teste de sanidade passa: nenhuma classe sabidamente usada aparece como rejeitada.
- [ ] `docs/frontend/purgecss-design-system.md` existe com método, safelist justificada e tabela por parcial.
- [ ] Toda classe removida tem `git grep` sem resultado (nome e fragmentos) fora do SCSS, registrado no Handoff.
- [ ] A suíte visual e o smoke não mostram diferença não aprovada depois das remoções.
- [ ] As classes `dúvida` permanecem no CSS.

## Como verificar
```bash
npm run css:report
git diff --stat main...HEAD -- sass/scss      # só as remoções confirmadas
git grep -nE "t-badge-info|tag-table-primary" -- sass/scss | head -3   # as classes usadas continuam no SCSS
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação do plano.

## Handoff
_Preenchido por quem executar._

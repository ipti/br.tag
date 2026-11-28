---
id: R3-CSS-01
title: "CSS: classes no body por módulo e página, parciais sass/scss/pages e tokens como custom properties"
release: R3
module: plataforma
type: css
skill: .agents/playbooks/ui-refactor.md
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-FRONT-03]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F5: classes no body, tokens e @use) e seção 8.4 (registros por módulo)"
write_scope:
  - app/src/Shared/Frontend/BodyClass.php
  - tests/Unit/Shared/Frontend/**
  - themes/default/views/layouts/fullmenu.php
  - themes/default/views/layouts/reports.php
  - themes/default/views/layouts/reportsclean.php
  - themes/default/views/layouts/login.php
  - themes/default/views/layouts/resultsmanagement.php
  - app/modules/enrollmentonline/layouts/enrollmentonline.php
  - sass/scss/pages/**
  - sass/scss/_custom-properties.scss
  - sass/scss/_tokens.scss
  - sass/scss/main.scss
  - tests/e2e/**
read_only:
  - sass/scss/components/**
  - app/components/utils/TFrontend.php
  - app/config/main.php
forbidden:
  - composer.json
  - app/config/main.php
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter BodyClass"
  - "composer front:build   (CSS compilado: diferença em relação ao anterior = só o bloco :root com --t-*; nada mais)"
  - "Suíte Playwright visual e smoke (R1-FRONT-01): sem diferença não aprovada"
  - "Teste de paridade: todo $token de _tokens.scss tem a custom property correspondente"
---

# R3-CSS-01: CSS: classes no body por módulo e página, parciais sass/scss/pages e tokens como custom properties

## Contexto
**Card do coordenador:** edita os layouts de `themes/default/views/layouts/` e `sass/scss/main.scss` (arquivos com dono). É o contrato que libera os cards de CSS por módulo: depois dele, cada módulo só edita o próprio `sass/scss/pages/_<módulo>.scss`, sem tocar em `main.scss` nem em layout.

O CSS do design system já é servido pelo Vite (R2-FRONT-03: `frontend/styles/main.scss` faz `@use` do `sass/scss/main`). Fatos verificados:
- Os layouts abrem `<body>` sem classe: `fullmenu.php` ≈L151, `reports.php` ≈L209, `reportsclean.php` ≈L25, `login.php` ≈L38, `main.php` ≈L21 (Blueprint legado, em remoção pela F0) e `resultsmanagement.php` ≈L38; o layout do enrollmentonline fica em `app/modules/enrollmentonline/layouts/enrollmentonline.php`.
- A rota vem do controller: `module->id` (nulo nos controllers raiz), `id` e `action->id` (o `TLog` já os usa, `TLog.php` ≈L7–L11). O `urlManager` não diferencia caixa (`caseSensitive => false`, `main.php` ≈L148); `controller->action` pode ser nulo em erro antes da resolução.
- Módulos web declarados em `main.php` (≈L88–L122): `wizard`, `school`, `lunch`, `resultsmanagement`, `schoolreport`, `calendar`, `timesheet`, `curricularmatrix`, `quiz`, `sagres`, `tag`, `professional`, `sedsp`, `classdiary`, `curricularcomponents`, `stages`, `foods`, `dashboard`, `courseplan`, `macete`, `aeerecord`, `grades`, `gradeconcept`, `abilities`, `enrollmentonline`, `gestaopresente`, `systemadmin`, `inventory`, `notifications`, `tools`, `student`, `instructor`, `farmer`, `gradestructure`, `classroom` (35), mais `gii`, que não entra. Os controllers raiz (Site, Administrator, Censo) formam o "módulo" `app`.
- `sass/scss/main.scss` (46 linhas) usa `@import` e termina em `_helpers.scss` (≈L46), que deve ficar por último. Não existe `sass/scss/pages/`.
- **`@import` repete a saída CSS a cada importação.** Doze parciais fazem `@import '_tokens.scss'` (`_button`, `_cards`, `_grid`, `_lists`, `_reports`, `_responsive`, `_tabs`, `_tag`, `components/_field-*`…). Qualquer regra emitida dentro de `_tokens.scss` sairia repetida em cada um. Por isso o bloco `:root` vai num parcial próprio, importado **uma vez**; ele passa a poder viver em `_tokens.scss` só depois do R3-CSS-02 (`@use`).
- `_tokens.scss`: `$borderWidthCard` é declarado duas vezes (≈L123 `1px` e ≈L126 `0.5px`); em Sass vale a última, **0.5px**, e é usada em `_accordeon`, `_drawer`, `_separator`, `_table`, `_topbar` e `components/_macete`. Os tokens `$ShadowSm`, `$Shadow`, `$Shadow-Md`, `-Lg`, `-Xl`, `-2xl` e `-Inner` (≈L164–L170) usam `rgb(0,0,0.05)`/`rgb(0, 0, 0.1)`: são três canais, e o terceiro (azul) vale quase 0, então a cor é **preta opaca**, não 5% ou 10% de preto. Nenhum parcial os usa hoje (grep), então corrigi-los não muda a página, mas passam a servir (o R3-FRONT-01 evita-os por isso).
- Suspeito, a confirmar antes de mexer: `$lineHeightText: 1.4px` (≈L138) parece pretender `1.4` sem unidade.

## Objetivo
Todo `<body>` carrega `tag-m-<módulo> tag-p-<módulo>-<controller>-<action>`, `main.scss` importa um índice de parciais por módulo (vazias) e os tokens existem também como `--t-*`, sem mudar nada visualmente.

## Passos
1. `Tag\Shared\Frontend\BodyClass` (puro, `strict_types`, sem Yii): `forRoute(?string $module, string $controller, ?string $action): string`. Minúsculas, `[^a-z0-9-]` vira `-`, módulo ausente vira `app`, ação nula omite a classe `tag-p-…`. Ex.: `tag-m-macete tag-p-macete-lessonsplan-create`; `tag-m-app tag-p-app-site-index`. Teste unitário com esses casos, caixa mista, ação nula e caracteres estranhos.
2. Layouts: `<body class="<?= CHtml::encode(\Tag\Shared\Frontend\BodyClass::forRoute($this->module?->id, $this->id, $this->action?->id)) ?>">` em fullmenu, reports, reportsclean, login, resultsmanagement e enrollmentonline. Preserve qualquer atributo que o `<body>` já tenha. `main.php` e `column1/2` não entram (remoção pela F0; confirmar que ainda existem).
3. Crie `sass/scss/pages/_<módulo>.scss` para os 35 módulos e `_app.scss`, cada um só com o cabeçalho `// Escopo obrigatório: .tag-m-<módulo>; o card do módulo é o dono deste arquivo` e, se for usar variáveis, os dois `@import '../_tokens.scss'; @import '../_functions.scss';`. Crie `pages/_index.scss` com **uma linha por módulo**, em ordem alfabética (`@import 'abilities';` …). Cuidado com nomes iguais aos de parciais da raiz (`tag`, `reports`): o Sass resolve primeiro na própria pasta; confirme no CSS compilado.
4. `main.scss`: acrescente `@import 'pages/index';` depois dos componentes e **antes** de `_helpers.scss` (regra do design system). As parciais vazias não geram CSS.
5. Custom properties: crie `_custom-properties.scss` com um mapa `$tokens` (nome em kebab-case sem o prefixo → valor do token Sass) e um `@each` que emite `:root { --t-<nome>: #{$valor}; }`, importado **uma vez** em `main.scss`, logo depois de `_tokens.scss`. Nomes: `$colorsBaseProductNormal` → `--t-colors-base-product-normal`, `$Shadow-Md` → `--t-shadow-md`, `$FontSizeHeadingTitle1` → `--t-font-size-heading-title1`. Os valores com `calc()`, listas (`$borderRadiusLeft`, `$FontFamilyTextNormal`) e `$Shadow-None` precisam sair idênticos ao Sass.
6. `_tokens.scss`: remova a primeira declaração morta de `$borderWidthCard` (≈L123) e **mantenha `0.5px`**, o valor em vigor (qualquer outro valor é decisão de design, não deste card). Corrija as sombras com `rgb(0 0 0 / 5%)` etc., mantendo os deslocamentos. Se confirmar o `$lineHeightText`, registre como issue, não corrija aqui.
7. `tag-qa`: teste de paridade (script Node ou PHP) que lê `_tokens.scss`, lista cada `$nome:` e confere se há a `--t-…` correspondente no CSS compilado; confira que `:root{--t-` aparece **uma vez**; compare o CSS antes e depois (seletores e propriedades): a única diferença permitida é o bloco `:root`. Rode a suíte visual e o smoke.

## Fora de escopo
- Mover CSS solto (`grades`, `lunch`, `sagres`, `reports`, `admin.css`, `notifications.css`…) para as parciais (cards de CSS de cada módulo, F5) e `print-reports.css`.
- Trocar `@import` por `@use` (R3-CSS-02) e usar as custom properties nos componentes.
- Criar tokens novos (por exemplo fundo e borda de alerta crítico) ou mudar valores de design.

## Critérios de aceite
- [ ] Os seis layouts emitem as classes; o teste unitário de `BodyClass` passa.
- [ ] Existem 36 parciais em `sass/scss/pages/` e o `_index.scss` as importa, uma por linha.
- [ ] O CSS compilado difere do anterior apenas pelo bloco `:root` com `--t-*` (uma ocorrência).
- [ ] Cada `$token` de `_tokens.scss` tem sua `--t-*` (teste de paridade).
- [ ] `$borderWidthCard` tem uma única declaração (`0.5px`) e as sombras usam alfa; nenhuma regra existente mudou.
- [ ] O `@import url()` das fontes (`_base.scss` ≈L1) continua no topo do CSS compilado, antes do bloco `:root`.
- [ ] Suíte visual e smoke sem diferença não aprovada.

## Como verificar
```bash
composer lint && composer analyse && composer test:unit -- --filter BodyClass
composer front:build
grep -o ":root{--t-" build/assets/*.css | wc -l     # 1 (nome do arquivo pelo manifest)
curl -s "http://localhost/?r=site/login" | grep -o '<body class="[^"]*"'    # tag-m-app tag-p-app-site-login
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação do plano.

## Handoff
_Preenchido por quem executar._

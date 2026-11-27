---
id: R2-FRONT-04
title: "Carregar um único jQuery por página (1.12.4 com Migrate) e tirar o jQuery do CDN das ilhas"
release: R2
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-FRONT-01]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F2, pacote do jQuery, ilhas e scriptMap)"
write_scope:
  - app/config/main.php
  - themes/default/js/vendor/**
  - themes/default/views/layouts/fullmenu.php
  - themes/default/views/layouts/reports.php
  - themes/default/views/layouts/resultsmanagement.php
  - app/modules/schoolreport/views/layouts/schoolreport.php
  - app/modules/schoolreport/views/layouts/login.php
  - app/modules/enrollmentonline/layouts/enrollmentonline.php
  - tests/e2e/jqmigrate/**
  - docs/arquitetura/jqmigrate-relatorio.md
read_only:
  - themes/default/js/jquery.min.js
  - vendor/yiisoft/yii/framework/web/js/**
  - themes/default/js/jquery/fullcalendar/**
forbidden:
  - composer.json
  - app/models/**
  - themes/default/js/common.js
  - themes/default/js/util.js
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -nE \"ajax.googleapis.com/ajax/libs/jquery|js/jquery.min.js\" -- themes app   (sem resultados)"
  - "Suíte Playwright (R1-FRONT-01), smoke e visual completos, sem diferença não aprovada e sem pageerror ou console.error"
  - "docs/arquitetura/jqmigrate-relatorio.md gerado com os avisos JQMIGRATE por página"
---

# R2-FRONT-04: Carregar um único jQuery por página (1.12.4 com Migrate) e tirar o jQuery do CDN das ilhas

## Contexto
Este é um **card do coordenador**: edita `app/config/main.php` e os layouts, que têm dono. É o card de maior risco visual do R2 no front, então a suíte do R1-FRONT-01 precisa estar verde antes.

Hoje cada página pode ter mais de um jQuery (verificado):
- `themes/default/views/layouts/fullmenu.php` ≈L101–102 carrega `js/jquery.min.js` (**1.8.2**, cabeçalho do arquivo) e o `jquery-ba-bbq.js` por tag crua no `<head>`.
- Páginas com `CGridView` ou `CJui*` chamam `registerCoreScript('jquery')`, e o Yii injeta o jQuery **1.12.4** (`vendor/yiisoft/yii/framework/web/js/packages.php` ≈L15–17 e `source/jquery.min.js`) **depois** da tag crua. Plugins carregados antes ficam presos à cópia 1.8.2.
- `layouts/reports.php` ≈L3–9: define `$cs->scriptMap` com chaves `jquery.js?v=...`, que nunca casam com nada (o mapa compara o nome do arquivo e o pacote do Yii é `jquery.min.js`), e depois registra o 1.8.2 por `registerScriptFile`.
- As ilhas usam o jQuery **1.11.3** do CDN do Google: `layouts/resultsmanagement.php` ≈L77 (com jQuery UI 1.11.4, Bootstrap 3 e select2 do CDN), `app/modules/schoolreport/views/layouts/schoolreport.php` ≈L103 e `login.php` ≈L29 (estes dois, com protocolo relativo).
- `app/modules/enrollmentonline/layouts/enrollmentonline.php` não carrega jQuery; ele só chega se algum widget o registrar. Os scripts do layout (≈L48–59) já dependem dele.
- `main.php` não tem o componente `clientScript`. O Yii permite redefinir o pacote com `clientScript.packages['jquery']`, e todos os pacotes que dependem dele passam a usar o novo.
- O `themes/default/js/jquery/fullcalendar/fullcalendar.min.js` (FullCalendar 1.5.4, ≈L309–310 do `fullmenu`) é o plugin mais sensível à troca 1.8.2 → 1.12.4.

## Objetivo
Toda página carrega um único jQuery 1.12.4 (com o Migrate 1.4.1 nas páginas do sistema), por `registerCoreScript('jquery')`, e as ilhas deixam de depender do CDN do jQuery.

## Passos
1. Rode a suíte atual e guarde o baseline. Rode os layouts com o console aberto e anote o que já dá erro hoje.
2. Coloque em `themes/default/js/vendor/` os arquivos `jquery-1.12.4.js` e `.min.js` (copie de `vendor/yiisoft/yii/framework/web/js/source/`) e `jquery-migrate-1.4.1.js` e `.min.js` (do pacote `jquery-migrate@1.4.1` do npm, com a licença). Versões exatas, nada de `latest`.
3. `main.php`: componente `clientScript` com `packages['jquery']` apontando para esses arquivos, em versão **não minificada com `YII_DEBUG`** (inclusive o Migrate, para os avisos legíveis). Prefira `baseUrl` literal (o app roda na raiz do host; confirmar) a `basePath`, que publica no `assetManager` com `forceCopy` em debug (≈L136).
4. `fullmenu.php` e `reports.php`: troque a tag crua por `$cs->registerCoreScript('jquery')` e registre o `jquery-ba-bbq.js` com `registerScriptFile(..., CClientScript::POS_HEAD)` depois. Confira que **nenhum** script inline do `<head>` usa `$` antes do ponto em que o Yii injeta o jQuery. Remova o `scriptMap` morto do `reports.php`.
5. Ilhas (`resultsmanagement.php`, `schoolreport.php` e `login.php` do schoolreport): troque o `<script>` do 1.11.3 do CDN pelo `jquery-1.12.4.min.js` local, **sem** Migrate. Os demais CDNs (UI, Bootstrap 3, select2, Semantic UI) ficam para o R3.
6. `enrollmentonline.php`: chame `registerCoreScript('jquery')` no topo, antes dos scripts do layout.
7. Se o grep mostrar que `themes/default/js/jquery.min.js` (1.8.2) não tem mais referência, apague o arquivo. Se alguma view ainda o referenciar, liste no Handoff.
8. **Relatório do JQMIGRATE:** um teste Playwright em `tests/e2e/jqmigrate/` percorre as páginas do smoke, coleta as mensagens `JQMIGRATE` do console (o Migrate usa `console.warn`) e `jQuery.migrateWarnings`, e gera `docs/arquitetura/jqmigrate-relatorio.md` (página, aviso, quantidade, arquivo de origem). Esse relatório precisa chegar a zero antes do jQuery 3 (F6).
9. Rode a suíte visual e o smoke **completos**. Atenção ao FullCalendar 1.5.4 (calendário e quadro de horários) e às páginas que até agora só enxergavam o 1.8.2. Qualquer diferença vai a revisão humana com captura.

## Fora de escopo
- jQuery UI e datepicker (R2-FRONT-05) e jQuery 3 (R8).
- Corrigir os usos depreciados que o Migrate apontar.
- Trocar os outros CDNs das ilhas e reescrevê-las.
- `common.js`, `util.js` e o carregamento deles.

## Critérios de aceite
- [ ] Cada layout carrega um único jQuery, e `jQuery.fn.jquery` é `1.12.4` em todas as páginas do smoke.
- [ ] Nenhum `<script>` de jQuery 1.8.2 ou 1.11.3 resta em `themes` e `app`.
- [ ] O `scriptMap` do `reports.php` foi removido.
- [ ] Smoke e suíte visual sem diferença não aprovada, sem `pageerror` ou `console.error` novos.
- [ ] O FullCalendar funciona (calendário e quadro de horários).
- [ ] O relatório JQMIGRATE foi gerado e está versionado.

## Como verificar
```bash
composer lint && composer analyse
git grep -nE "ajax.googleapis.com/ajax/libs/jquery|js/jquery.min.js" -- themes app   # esperado: sem resultados
npx playwright test tests/e2e   # com a aplicação no ar; peça URL e credenciais antes
```
No navegador: `jQuery.fn.jquery` no console de cada tipo de página (menu completo, relatório, login, enrollmentonline, resultsmanagement, schoolreport) e conferir que `window.jQuery === $`.

- Confirmar: a ordem em que o Yii injeta os scripts no `<head>` (`renderHead` vem antes de `</head>`), com a página aberta.
- Confirmar: se algum script de módulo no `<head>` usa `$` antes da injeção.
- Confirmar: com o produto, se a mudança 1.11.3 → 1.12.4 nas ilhas é aceita (elas não têm o Migrate).
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

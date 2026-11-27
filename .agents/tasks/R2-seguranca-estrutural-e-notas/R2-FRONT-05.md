---
id: R2-FRONT-05
title: "Unificar o jQuery UI (build 1.12.1 sem tooltip e button) e separar o bootstrap-datepicker em bsDatepicker"
release: R2
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R2-FRONT-04]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F2, jQuery UI e datepicker)"
write_scope:
  - app/config/main.php
  - themes/default/js/vendor/**
  - themes/default/css/vendor/**
  - themes/default/views/layouts/fullmenu.php
  - themes/default/views/layouts/reports.php
  - themes/default/views/layouts/reportsclean.php
  - app/modules/enrollmentonline/layouts/enrollmentonline.php
  - app/modules/calendar/resources/common/js/calendar.js
  - app/modules/foods/resources/components.js
  - app/modules/foods/resources/inventory/_initialization.js
  - app/modules/foods/resources/menuComponents.js
  - app/modules/foods/resources/notice/_initialization.js
  - js/admin/auditory.js
  - js/reports/ElectronicDiary/index.js
read_only:
  - themes/default/js/jquery-ui-1.9.2.custom.min.js
  - themes/default/js/bootstrap-datepicker.min.js
  - app/components/DatePickerWidget.php
  - vendor/yiisoft/yii/framework/zii/widgets/jui/**
forbidden:
  - composer.json
  - app/models/**
  - themes/default/js/common.js
  - themes/default/js/util.js
acceptance:
  - "composer lint"
  - "composer analyse"
  - "git grep -n \"\\.datepicker(\" -- app js themes   (só chamadas da API do jQuery UI: setDate e as de CJuiDatePicker; nenhuma com language/format/autoclose)"
  - "Suíte Playwright (R1-FRONT-01), smoke e visual completos, com as telas de datepicker revisadas e aprovadas por uma pessoa"
---

# R2-FRONT-05: Unificar o jQuery UI (build 1.12.1 sem tooltip e button) e separar o bootstrap-datepicker em bsDatepicker

## Contexto
Este é um **card do coordenador**: edita `app/config/main.php` e layouts, que têm dono. Ele depende do R2-FRONT-04 (um jQuery por página).

Colisão atual, verificada no código:
- O `fullmenu.php` carrega o `jquery-ui-1.9.2.custom.min.js` (≈L298, cuja lista de módulos está no cabeçalho do arquivo), depois `bootstrap.min.js` (≈L300) e `bootstrap-datepicker.min.js` (≈L301, versão 1.6.1, que tem `noConflict`).
- `CJuiDatePicker` (13 usos em `app/modules`, todos via `DatePickerWidget::renderDatePicker` ou opções próprias) herda de `CJuiWidget`. Com os padrões (`scriptFile='jquery-ui.min.js'`, `cssFile='jquery-ui.css'`; `CJuiWidget.php` ≈L50–60 e `registerCoreScripts`), registra o jQuery UI **1.12.1 completo** do Yii em `POS_END`, **depois** dos scripts do layout, e o CSS do tema base. Esse arquivo traz `tooltip`, `button` e `datepicker`, e sobrescreve `$.fn.tooltip`, `$.fn.button` e `$.fn.datepicker` do Bootstrap 2 e do bootstrap-datepicker nessas páginas.
- Chamadas `.datepicker(` (grep em 2026-11-27): **9 do bootstrap-datepicker** (opções `language`, `format`, `autoclose`) em 7 arquivos: `calendar/resources/common/js/calendar.js` ≈L164, `foods/resources/components.js` ≈L204, `foods/resources/inventory/_initialization.js` ≈L95, `foods/resources/menuComponents.js` ≈L32, `foods/resources/notice/_initialization.js` ≈L36, `js/admin/auditory.js` ≈L4 e L36, `js/reports/ElectronicDiary/index.js` ≈L4 e L36. Há também 3 `.datepicker("setDate", null)` em `onclick` PHP (`instructor`, `professional` e `student`, `_form.php`) sobre campos do `CJuiDatePicker`: são API do jQuery UI e **ficam**.
- Usos de widgets do jQuery UI no código (grep): `accordion` (foods, classdiary, student, schoolreport, `js/admin/grades-structure.js`), `dialog` (`js/admin/index/*`, `js/classroom/form/dialogs.js`), `sortable` (`js/classroom/form/*`) e `datepicker`. O FullCalendar 1.5.4 usa interações (draggable e outras). As chamadas `.tooltip(` do código são do Bootstrap 2 (calendar, classdiary, courseplan, foods, timesheet).
- O `main.php` não tem `widgetFactory`. O jQuery UI 1.9.2 atual é uma build customizada com `draggable`, `droppable`, `resizable`, `selectable`, `sortable`, `accordion`, `autocomplete`, `button`, `datepicker`, `dialog` e outros (cabeçalho do arquivo).

## Objetivo
Uma única build do jQuery UI 1.12.1, carregada antes do Bootstrap, e o bootstrap-datepicker acessível como `$.fn.bsDatepicker`, sem colisão.

## Passos
1. Confirme o R2-FRONT-04 concluído. Rode o `git grep` das 9 chamadas e das 3 `setDate` e confirme a lista (arquivo e linha), separando cada chamada pelas opções (`dateFormat` é jQuery UI; `language`, `format`, `autoclose` são bootstrap-datepicker). Procure também `data('datepicker')` e `data-provide="datepicker"`: hoje só o próprio plugin os cita, mas confirme.
2. Monte a build 1.12.1 no Download Builder do jQuery UI com **os módulos da 1.9.2 atual**, **sem `tooltip` e sem `button`**. Confirmar: o `dialog` da 1.12 declara dependência de `button`. Se o Builder o incluir de volta, mantenha a build carregada **antes** do `bootstrap.js` (assim o Bootstrap sobrescreve `$.fn.button`) ou remova o módulo à mão e teste os `dialog` com e sem `buttons`. Registre a escolha no Handoff. Guarde JS e CSS (tema do `base` basta) em `themes/default/js/vendor/` e `themes/default/css/vendor/`, com a licença.
3. Layouts (`fullmenu.php`, `reports.php`, `reportsclean.php`, e o `enrollmentonline.php`, que também tem `CJuiDatePicker`): troque `jquery-ui-1.9.2.custom.min.js` e o CSS `jquery-ui-1.9.2.custom.min.css` pela build nova, **antes** do `bootstrap.min.js`.
4. `main.php`: `widgetFactory` com `CJuiDatePicker` e `scriptFile => false` e `cssFile => false`, para o widget parar de injetar o UI completo e o tema base. Confirme com grep (`zii.widgets.jui`) que não há outro widget `CJui*` em uso; se houver, cubra-o também.
5. Logo depois do `bootstrap-datepicker.min.js` (e do `pt-BR`) nos layouts, acrescente `$.fn.bsDatepicker = $.fn.datepicker.noConflict();`. O `noConflict` do plugin restaura o `datepicker` que existia antes (o do jQuery UI), por isso o UI precisa carregar antes dele. Em seguida, troque as 9 chamadas do bootstrap-datepicker por `.bsDatepicker(`.
6. **Efeito esperado, para a revisão humana:** os campos `CJuiDatePicker` (aluno, instrutor, profissional, escola, matrícula, almoxarifado, enrollmentonline) passam a usar o datepicker do jQuery UI **de verdade**, com as opções do `DatePickerWidget` (`dd/mm/yy`, `changeYear`, nomes em português); antes, na prática, o da vez era o do Bootstrap ou o UI completo carregado por último. Compare cada formulário no navegador e aprove cada diferença.
7. Verifique os `tooltip(`, `button(` e `accordion(` do Bootstrap e do UI nas páginas das listas acima (os `tooltip` deixam de ser sobrescritos nas páginas com `CJuiDatePicker`).
8. Rode a suíte visual e o smoke completos, incluindo os formulários com data e o calendário (FullCalendar).

## Fora de escopo
- jQuery UI 1.13 e jQuery 3 (R8).
- Migrar os calendários do bootstrap-datepicker para outra biblioteca.
- Tirar o `DatePickerWidget` ou reescrever os formulários.
- `common.js` e `util.js`.

## Critérios de aceite
- [ ] Cada página carrega o jQuery UI uma vez só (a build nova), antes do `bootstrap.js`; nenhuma página carrega o `jquery-ui.min.js` do Yii nem o tema base.
- [ ] `$.fn.tooltip` é o do Bootstrap e `$.fn.datepicker` é o do jQuery UI em todas as páginas; `$.fn.bsDatepicker` existe onde o bootstrap-datepicker é carregado.
- [ ] As 9 chamadas do bootstrap-datepicker usam `bsDatepicker` e continuam funcionando (calendário, edital de alimentos, auditoria, diário eletrônico).
- [ ] Os campos `CJuiDatePicker` abrem o calendário do jQuery UI com data em `dd/mm/yyyy`, e as diferenças visuais foram aprovadas por uma pessoa.
- [ ] `dialog`, `sortable`, `accordion` e o FullCalendar funcionam.
- [ ] Smoke e suíte visual sem diferença não aprovada nem `console.error`.

## Como verificar
```bash
composer lint && composer analyse
git grep -n "\.datepicker(" -- app js themes   # esperado: só setDate e opções do jQuery UI (dateFormat)
git grep -n "bsDatepicker" -- app js themes     # esperado: 9 chamadas + a linha do noConflict nos layouts
```
No navegador (peça URL e credenciais antes): no console de uma página com data, `$.fn.datepicker.toString()` deve citar `datepicker` do jQuery UI, `$.fn.bsDatepicker` deve existir, e `$.fn.tooltip` deve ser o do Bootstrap (`Tooltip`).

- Confirmar: se algum arquivo carregado só por módulo (por `registerScriptFile`) inclui `jquery-ui` por conta própria (grep por `jquery-ui` e `jquery.ui`).
- Confirmar: as telas do FullCalendar com a build nova, que precisa ter as interações que a 1.9.2 tinha.
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

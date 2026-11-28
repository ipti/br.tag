---
id: R8-FRONT-02
title: "Subir para jQuery 3.7, Migrate 3 (só em desenvolvimento) e jQuery UI 1.13, com as bibliotecas de terceiros pelo npm"
release: R8
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R8-FRONT-01]
size: M
risk: alto
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F6 e F2), seção 4 (lição de 2024) e seção 14 (relatório do JQMIGRATE zerado)"
# Os layouts e o app/config/main.php são arquivos com dono: neste card quem os edita é o coordenador (README, "Arquivos com dono").
write_scope:
  - package.json
  - package-lock.json
  - frontend/vendor/**
  - themes/default/js/**
  - themes/default/css/**
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - app/modules/enrollmentonline/layouts/**
read_only:
  - js/**
  - app/modules/*/resources/**
forbidden:
  - composer.json
  - app/models/**
acceptance:
  - "npm run lint && npm run typecheck && npm test && npm run build"
  - "Relatório do JQMIGRATE (Migrate 3, em desenvolvimento) sem avisos nas páginas da suíte Playwright"
  - "Suíte visual e smoke sem diferenças nem pageerror, inclusive nas ilhas e no enrollmentonline"
  - "Nenhuma página da suíte carrega mais de uma cópia do jQuery (jQuery.fn.jquery igual a 3.7.x e um único script de jQuery no DOM)"
---

# R8-FRONT-02: Subir para jQuery 3.7, Migrate 3 (só em desenvolvimento) e jQuery UI 1.13, com as bibliotecas de terceiros pelo npm

## Contexto
Depois do R2-FRONT-04 (jQuery 1.12.4 + Migrate 1.4.1 num único pacote `clientScript.packages['jquery']`, jQuery UI 1.12.1 único) e do R8-FRONT-01 (codemods), falta trocar a versão. Estado de partida em 2026-11-27, antes de R2: `themes/default/js/jquery.min.js` é o **jQuery 1.8.2**, `jquery-ui-1.9.2.custom.min.js` é o jQuery UI 1.9.2, e as ilhas carregam o 1.11.3 do CDN. **confirmar:** o estado real da `main` no início deste card.

**Versões de terceiros em `themes/default/js/` (conferidas em 2026-11-27) que precisam de teste com o jQuery 3:**
- **select2 3.4.5** (`select2.js`, 132 KB, mais `select2-locale-pt-BR.js` e `css/select2.css`). Há 159 chamadas `.select2(` em 42 arquivos, e o `resultsmanagement` usa outra cópia (4.0.0, via CDN). Plano B do plano: **select2 4.0.13**, que muda a API (`initSelection`, `.select2('val')`, formato de `data`).
- **jquery.qrcode 0.11.0** (`jquery/jquery.qrcode.min.js`): 2 trechos `.size(` no arquivo. **confirmar:** se são chamadas jQuery (quebram) ou o parâmetro `size` do próprio plugin.
- **Bootstrap 2 JS** (`bootstrap.min.js`, 2012; usa `$.support.transition`), **bootstrap-datepicker 1.6.1**, **jquery.mask 1.13.3**, **DataTables 1.10.7** (`jquery/jquery.dataTables.min.js`) e o `datatables.min.js` do builder, **FullCalendar 1.5.4** (`jquery/fullcalendar/`).
- **jquery-ba-bbq 1.2.1** (`jquery-ba-bbq.js`): usa `$.browser` (1 ocorrência), que o Migrate 3 não restaura. O plano (F0) manda removê-lo só depois de `grep` por `$.bbq`, `$.deparam` e `hashchange`; **confirmar:** se a F0 já o removeu.
- Os layouts que carregam jQuery: `fullmenu.php` (≈L101, jQuery direto), `resultsmanagement.php` (CDN, ≈L77–80), `reports.php`, `reportsclean.php` e `app/modules/enrollmentonline/layouts/enrollmentonline.php`. **confirmar:** como os três últimos carregam o jQuery.
- `package.json` hoje só tem `selenium-standalone`, e o `.snyk` só exclui `framework/**`. As bibliotecas de terceiros ainda não passam pelo Snyk.

## Objetivo
Uma única instância do jQuery 3.7 (com Migrate 3 apenas em desenvolvimento) e jQuery UI 1.13 em todas as páginas, com os plugins testados e as bibliotecas de terceiros vindas do npm.

## Passos
1. Confirme que o R8-FRONT-01 está na `main` e que o relatório do JQMIGRATE está limpo para as três famílias.
2. Adicione ao `package.json` as versões exatas de `jquery` 3.7.x, `jquery-migrate` 3.x e `jquery-ui` 1.13.x, e das bibliotecas que passam ao npm (select2, bootstrap-datepicker, jquery-mask-plugin, datatables.net e o plugin de QR code), com o `package-lock.json`. Confirme que o Snyk lê o `package.json`.
3. Gere um **script `vendor` clássico (IIFE, sem módulo ES)** em `frontend/vendor/` que expõe `window.jQuery`, `window.$` e os plugins na ordem certa, para legado e módulos compartilharem a mesma instância. É a lição de 2024: nada de empacotar legado e plugins num bundle ES.
4. Em desenvolvimento, carregue também o Migrate 3 e colete o relatório de avisos. Em produção, **não** carregue o Migrate.
5. Rode a matriz de plugins na suíte Playwright e no manual:
   - select2 3.4.5: se falhar, migre para 4.0.13 em um card filho por módulo (159 chamadas), com `initSelection` e `val` tratados;
   - qrcode: troque o plugin se o `.size(` for chamada jQuery;
   - Bootstrap 2 JS, datepicker, mask, DataTables e FullCalendar 1.5.4;
   - remova o BBQ se ainda estiver presente.
6. **Edições com dono (coordenador):** apontar `packages['jquery']` em `app/config/main.php` e os layouts para o script `vendor`; tirar o jQuery e o jQuery UI do CDN em `resultsmanagement.php`; tirar a tag direta do `fullmenu.php`.
7. Apague de `themes/default/js/` e `themes/default/css/` as cópias antigas que saíram (jQuery 1.8.2, jQuery UI 1.9.2 e o CSS `jquery-ui-1.9.2.custom.min.css`), depois de `grep` pelo nome e pelo caminho, inclusive em `$.getScript` e em caminhos concatenados.
8. Rode a suíte visual e o smoke completos, inclusive as ilhas, o enrollmentonline e as páginas com CGridView ou CJui.

## Fora de escopo
- Reescrever a página para tirar o jQuery (o plano mantém jQuery organizado em módulos).
- Aplicar a CSP (R8-FRONT-03) ou remover o CSS de vendor (R8-CSS-01).

## Critérios de aceite
- [ ] `jQuery.fn.jquery` é `3.7.x` em toda página da suíte, com uma só instância.
- [ ] O Migrate 3 só carrega em desenvolvimento e o relatório está zerado nas páginas da suíte.
- [ ] jQuery UI 1.13 único; `CJuiDatePicker` e o `bsDatepicker` funcionam.
- [ ] select2, QR code, datepicker, mask, DataTables e FullCalendar passam no smoke; o plano B do select2 só entra se o 3.4.5 falhar.
- [ ] As bibliotecas de terceiros estão no `package-lock.json` e sob o Snyk; as cópias antigas foram removidas.
- [ ] Suíte visual e smoke sem diferenças aprovadas pendentes.

## Como verificar
```bash
npm run lint && npm run typecheck && npm test && npm run build
git grep -n "jquery.min.js\|jquery-ui-1.9.2\|ajax.googleapis.com/ajax/libs/jquery" -- themes app    # sem resultados ao final
snyk test    # confirmar que o package.json é analisado
```
No navegador, abra as páginas da suíte e execute `jQuery.fn.jquery` no console (esperado `3.7.x`). Os testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

---
id: R6-CALENDAR
title: "calendar (calendário escolar): módulo pronto, com JSON e superglobais sob controle"
release: R6
module: calendar
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (onda 3) e seção 7 (F2: atenção ao FullCalendar 1.5.4)"
write_scope:
  - app/modules/calendar/**
  - app/src/Calendar/**
  - tests/**/Calendar/**
  - frontend/pages/calendar/**
  - sass/scss/pages/_calendar.scss
  - app/config/public-routes/calendar.php
  - metrics/baseline/calendar.json
  - phpstan/baseline/calendar.neon
read_only:
  - themes/default/js/jquery/fullcalendar/**
  - themes/default/views/layouts/fullmenu.php
  - app/models/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Calendar (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do calendar sem proteção ou fora de public-routes/calendar.php"
  - "Catraca do módulo (metrics/baseline/calendar.json) sem aumento e com zero ?r= literais"
---

# R6-CALENDAR: calendar (calendário escolar): módulo pronto, com JSON e superglobais sob controle

## Contexto
**Para que serve:** o calendário escolar. O admin cria calendários, cadastra eventos (com tipo), vincula etapas e define os períodos das unidades; os demais usuários consultam. Não há documento funcional em `docs/modules/`; o resumo vem das actions e dos models (`Calendar`, `CalendarEvent`, `CalendarEventType`, `CalendarStages`). **confirmar:** com o produto o papel de cada perfil.

**Linha do inventário:** 1 controller, 486 LOC, 12 actions, 0 sem `accessControl`, 61 superglobais, 11 `createCommand`, 23 saídas JSON, 1 view, 0 `<script>`, 0 `style=`, 2 Bootstrap 2, JS 2 arquivos/591 linhas, 1 CSS, 4 models, 0 services.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php` (486 LOC): `index`, `create`, `event`, `changeEvent` (≈L99–211, a maior), `deleteEvent`, `removeCalendar`, `editCalendar`, `showStages`, `loadCalendarData`, `loadUnityPeriods`, `editUnityPeriods` e `viewPeriods`.
- `filters()` tem `accessControl` e `postOnly + delete` (≈L8–13). `accessRules()` (≈L21–42) libera `index` e `view` para `'*'`, sem checar login, e cita actions que **não existem** (`view`, `update`, `createEvent`, `others`, `SetActual`, `admin`, `delete`). As actions que de fato apagam (`deleteEvent`, `removeCalendar`) **não** estão sob `postOnly`. **confirmar:** se aceitam GET e se `index` quebra para visitante (a view usa `Yii::app()->user->loginInfos->id`).
- A view `views/default/index.php` só mostra o botão de novo calendário se `authManager->checkAccess('admin', loginInfos->id)`; a action em si (`create`) depende só do `'@'`. **confirmar:** se o controle de admin existe no servidor.
- JS: `resources/common/js/calendar.js` (591 linhas) tem 11 `?r=` literais, `$.parseJSON` e 9 usos de `.complete(function`. O `index.js` registrado na view tem 0 bytes. Há uma cópia local do jQuery 1.11.3 em `resources/lib/jquery/` (**confirmar:** não carregada; o plano F0 prevê remover).
- CSS: `resources/common/css/layout.css` (331 linhas).
- **FullCalendar 1.5.4:** vive em `themes/default/js/jquery/fullcalendar/` e é carregado por `themes/default/views/layouts/fullmenu.php` (≈L71–72 e ≈L310) e `reports.php`, arquivos com dono. Trocar a biblioteca é outro card (contrato).
- Models do módulo: `Calendar`, `CalendarEvent`, `CalendarEventType` e `CalendarStages`.

## Objetivo
Calendário com regras de acesso reais, escopo por escola, regras de datas em services testados e JS modular.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-CALENDAR-<nn>`.
2. **SEC**
   - [ ] Reescrever `accessRules()` só com actions que existem; liberar `index` apenas se a decisão de produto for "público", e então listar em `public-routes/calendar.php`.
   - [ ] Exigir POST em `deleteEvent`, `removeCalendar` e nas gravações, com CSRF.
   - [ ] Exigir admin no servidor para criar, editar e remover calendário e período (`TagUtils::isAdmin()`).
   - [ ] Revisar as 61 leituras de superglobais e os 11 `createCommand` (bind e escopo por escola).
3. **GM**
   - [ ] Caracterizar `loadCalendarData` (JSON consumido pelo FullCalendar), `changeEvent`, `editUnityPeriods` e `showStages`, com dataset anonimizado.
4. **SVC**
   - [ ] `app/src/Calendar/Application/`: `SaveCalendarEventService`, `DeleteCalendarService`, `UpdateUnityPeriodsService` e a consulta de dados do calendário; regras de data no `Domain`.
   - [ ] Padronizar as 23 saídas JSON com o `ExceptionMapper` e DTOs de resposta.
5. **API:** nenhuma prevista. **confirmar:** se o app precisa do calendário.
6. **JS**
   - [ ] Migrar `calendar.js` para `frontend/pages/calendar/` (separar a lista de eventos, o modal de evento e os períodos de unidade), com `routes/` gerado no lugar dos 11 `?r=`.
   - [ ] Trocar `$.parseJSON` por `JSON.parse` e `.complete` por `.always`. Manter o FullCalendar 1.5.4 como está.
7. **CSS:** mover `layout.css` para `sass/scss/pages/_calendar.scss` e trocar os 2 usos de Bootstrap 2.

## Fora de escopo
- Atualizar ou trocar o FullCalendar (arquivo com dono, tema).
- Mudar regras de dias letivos ou a integração com notas e frequência.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/calendar.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** os fluxos de evento e de período têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/Calendar/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica, ou os endpoints têm teste de contrato.
- [ ] **JS:** páginas em `frontend/pages/calendar/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_calendar.scss`, sem blocos `<style>`, Bootstrap 2 em zero ou em queda.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Calendar   # confirmar scripts em R1
php scripts/inventory-actions.php         # calendar sem action desprotegida
scripts/frontend-metrics                  # catraca; confirmar o comando em R1
curl -i "http://localhost/?r=calendar/default/deleteEvent"   # sem sessão: 302 para o login
```
Testes de navegador (criar calendário, evento e período) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

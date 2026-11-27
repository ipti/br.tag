---
id: R6-NOTIFICATIONS
title: "notifications: módulo pronto, com envio e exclusão restritos a admin"
release: R6
module: notifications
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R6-REFINE-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3 e F5: notifications.css e notifications.js) e seção 8.6 (onda 3)"
write_scope:
  - app/modules/notifications/**
  - app/src/Notifications/**
  - tests/**/Notifications/**
  - frontend/pages/notifications/**
  - sass/scss/pages/_notifications.scss
  - app/config/public-routes/notifications.php
  - metrics/baseline/notifications.json
  - phpstan/baseline/notifications.neon
read_only:
  - app/components/NotificationService.php
  - app/components/behaviors/NotifyOnEventBehavior.php
  - app/components/auth/TNotificationEvent.php
  - docs/modules/notifications.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "composer test:unit -- --filter Notifications (confirmar o nome do script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do notifications sem proteção ou fora de public-routes/notifications.php"
  - "Catraca do módulo (metrics/baseline/notifications.json) sem aumento e com zero ?r= literais"
---

# R6-NOTIFICATIONS: notifications: módulo pronto, com envio e exclusão restritos a admin

## Contexto
**Para que serve** (`docs/modules/notifications.md`): a secretaria e os gestores enviam avisos a professores, coordenadores e outros perfis, filtrando por escola e perfil, com prazo de validade. Cada usuário tem uma caixa de entrada, um contador de não lidas na barra superior e o menu com as 5 mais recentes. O sistema também pode notificar por evento (`NotifyOnEventBehavior`, enum `TNotificationEvent`), mas o documento descreve essa parte como infraestrutura pronta e ainda não ativada.

**Linha do inventário:** 2 controllers, 251 LOC, 9 actions, 0 sem `accessControl`, 11 superglobais, 0 `createCommand`, 4 JSON, 5 views, 0 `<script>`, 52 `style=`, 0 Bootstrap 2, JS 0 arquivos, 0 CSS, 2 models, 0 services no módulo.

**Detalhamento (conferido em 2026-11-27)**
- `InboxController` (130 LOC): `index`, `unreadCount`, `recent`, `markRead` e `markAllRead`, liberados para qualquer logado, o que é o desenho certo.
- `NotificationsController` (121 LOC): `index`, `create` (≈L47), `view` e `delete` (≈L109). O comentário diz "admin", mas `accessRules()` libera as 4 actions para `'@'` e **não há checagem de admin nem nas regras nem no código dos controllers**. Na prática, qualquer usuário logado pode **criar um aviso para toda a rede** e excluir avisos de outros. **confirmar:** se o menu esconde o item para quem não é admin (o servidor não impede).
- `NotificationService` é o componente `Yii::app()->notifier`, registrado em `app/config/main.php` (≈L128) e implementado em `app/components/NotificationService.php` (229 linhas, métodos `notify`, `broadcast`, `getUnreadCount`, `getUserNotifications`, `markAsRead`, `markAllAsRead`, `purgeExpired`). Está **fora do `write_scope`**: mover o código para `app/src` mexe no componente registrado e exige card de contrato.
- Quem chama `notifier`: os 2 controllers do módulo e `NotifyOnEventBehavior` (≈L94 e ≈L102).
- JS: o módulo não tem JS; o `js/notifications/notifications.js` (169 linhas) é carregado por `themes/default/views/layouts/fullmenu.php` (≈L316) e **sai no R3-FRONT-05**. **confirmar:** o que sobrou depois dele (a página de criação e a inbox).
- CSS: o `notifications.css` vira `components/_notifications.scss` na F5 (plano, seção 7). As views têm 52 `style=`: `notifications/view.php` (19), `notifications/index.php` (13), `inbox/_notification_item.php` (12), `inbox/index.php` (5) e `notifications/create.php` (3).
- Models do módulo: `Notification` e `NotificationRecipient`.

## Objetivo
Só admin envia e exclui avisos; a inbox e o envio têm regras testadas, e as views não carregam estilo inline.

## Passos
1. O card de refinamento (R6-REFINE-01) quebra este épico em cards executáveis `R6-NOTIFICATIONS-<nn>`.
2. **SEC**
   - [ ] Restringir `create`, `view`, `index` e `delete` de `NotificationsController` a admin (e superuser), com `TagUtils::isAdmin() || TagUtils::isSuperuser()` na regra. Decidir com o produto se gestor escolar envia para a própria escola (o documento cita "gestores escolares").
   - [ ] Exigir POST e CSRF em `create`, `delete`, `markRead` e `markAllRead`.
   - [ ] Garantir que `markRead($id)` só marque destinatário do usuário da sessão (IDOR) e que o escopo de escola do filtro seja validado no servidor.
   - [ ] Escapar título e corpo na inbox e na página de visualização (**confirmar:** XSS armazenado).
3. **GM**
   - [ ] Caracterizar envio por perfil e escola, expiração, leitura individual e em massa, e o contador de não lidas.
4. **SVC**
   - [ ] `app/src/Notifications/Application/`: `SendNotificationService`, `MarkNotificationReadService` e a consulta da inbox, usando o `notifier` por trás de uma interface. Mover o `NotificationService` só com card de contrato.
5. **API:** nenhuma prevista aqui. **confirmar:** se o app mobile precisa da inbox (contador e lista) na API de R4/R5.
6. **JS:** depois do R3-FRONT-05, migrar o que restar (criação e inbox) para `frontend/pages/notifications/`.
7. **CSS:** `sass/scss/pages/_notifications.scss` e mover os 52 `style=` das 5 views.

## Fora de escopo
- Ativar as notificações automáticas por evento (infraestrutura existe, mas está desativada).
- Mudar o modelo de dados ou o prazo padrão de 30 dias.
- Mexer em `app/config/main.php`.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/notifications.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- [ ] **GM:** os fluxos de envio e leitura têm teste de caracterização.
- [ ] **SVC:** as actions críticas são magras; as regras estão em `app/src/Notifications/` com pelo menos 80% de cobertura.
- [ ] **API:** não se aplica, ou os endpoints têm teste de contrato.
- [ ] **JS:** páginas em `frontend/pages/notifications/`; catraca com zero `?r=` literais e zero globais novos.
- [ ] **CSS:** estilos em `sass/scss/pages/_notifications.scss`, sem blocos `<style>`, `style=` em queda.
- [ ] Um usuário que não é admin recebe 403 ao tentar criar ou excluir aviso.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Notifications   # confirmar scripts em R1
php scripts/inventory-actions.php              # notifications sem action desprotegida
scripts/frontend-metrics                       # catraca; confirmar o comando em R1
curl -i -b "PHPSESSID=<sessão de professor>" "http://localhost/?r=notifications/notifications/create"   # esperado: 403
```
Testes de navegador (enviar e ler aviso) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

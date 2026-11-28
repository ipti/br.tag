---
id: R3-FRONT-05
title: "entries/app.js absorve js/layout/functions.js e js/notifications/notifications.js"
release: R3
module: plataforma
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-04]
size: P
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3: entries/app.js) e seção 8.3"
write_scope:
  - frontend/entries/app.js
  - frontend/components/layout/**
  - frontend/components/notifications/**
  - themes/default/views/layouts/fullmenu.php
  - js/layout/functions.js
  - js/notifications/notifications.js
  - tests/e2e/**
read_only:
  - frontend/core/**
  - frontend/ui/**
  - frontend/routes/**
  - app/modules/notifications/NotificationsRoutes.php
  - app/components/utils/TMenu.php
  - themes/default/views/layouts/menus/**
forbidden:
  - composer.json
  - app/config/main.php
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "npm run lint && npm run typecheck && npm test && npm run build"
  - "npm run entries:check   (a entry usada no layout existe no manifest)"
  - "Playwright: abrir/fechar o drawer, submenus, sino de notificações (contador, lista, marcar como lida) e imprimir, sem pageerror e sem diferença visual não aprovada"
  - "git grep -nE 'layout/functions\\.js|notifications/notifications\\.js' -- themes app   (sem resultados)"
---

# R3-FRONT-05: entries/app.js absorve js/layout/functions.js e js/notifications/notifications.js

## Contexto
**Card do coordenador:** edita `themes/default/views/layouts/fullmenu.php` (arquivo com dono). Os dois scripts são carregados só pelo `fullmenu` (≈L312 e ≈L316); `reports`, `reportsclean` e `enrollmentonline` não os carregam (**confirmar** com grep). Passam a vir de uma entry de módulo, registrada com `TFrontend::registerEntry('entries/app.js')` (R2-FRONT-02; **confirmar** o nome do helper). Os módulos ES executam deferidos, antes do `DOMContentLoaded`, por isso o código deve usar `$(fn)` ou eventos delegados, e não depender de o elemento existir no instante da execução.

`js/layout/functions.js` (23 linhas):
- no `ready`: `.js-toggle-drawer` e `.menu-cover` alternam `t-drawer--mobile-hidden` em `.js-drawer` e `sidebar-cover` em `.menu-cover` (os elementos estão em `fullmenu.php` ≈L169 e ≈L260); `#menu-electronic-diary-trigger` (`layouts/menus/_superuser_menu.php` ≈L74) alterna `active` em `#menu-electronic-diary`; `.toggle-menu-js` (`app/components/utils/TMenu.php` ≈L64) alterna `active` em `#menu-integrations`;
- **fora** do `ready`: `$("#print").on('click', …window.print())` (≈L21). Vale só para o `#print` que já existe quando o script carrega.
- Armadilha: dezenas de views têm `id="print"` **e** `onclick="imprimirPagina()"` (por exemplo `foods/views/reports/FoodMenuReport.php` ≈L69, com a função definida na própria view ≈L257). O módulo foods usa o layout `fullmenu` (`FoodsModule.php` ≈L32). Essas páginas já podem disparar duas impressões hoje. **Preserve o comportamento atual** (não conserte aqui), confirme no navegador qual é e registre no Handoff; se usar delegação em `document`, ela passa a cobrir também botões criados depois, o que é um superconjunto a anotar.

`js/notifications/notifications.js` (169 linhas, IIFE com jQuery):
- inicia no `ready`, busca `?r=notifications/inbox/unreadCount` na hora e a cada 60 s (≈L62, `POLL_INTERVAL`), abre o dropdown com `?r=notifications/inbox/recent` (≈L88) e marca como lida com `$.post('/?r=notifications/inbox/markRead&id=' + id)` (≈L49). Os literais `?r=` estão nas linhas ≈L49, L66, L88 e L140.
- monta o HTML por concatenação com `style=` inline e cores fixas por tipo (`info`, `warning`, `error`, `success`, ≈L114–L119); escapa título e corpo com um `escapeHtml` próprio (≈L160), mas **não** escapa `item.id` no `data-id` (≈L132) nem `data.unread`.
- elementos: `#notification-bell`, `#notification-bell-trigger`, `#notification-badge` e `#notification-dropdown` (`fullmenu.php` ≈L204–L209).
- As constantes de rota existem em `NotificationsRoutes` (`INBOX_INDEX`, `INBOX_MARKREAD`, `INBOX_RECENT`, `INBOX_UNREADCOUNT`); o R3-FRONT-01 gera `frontend/routes/notifications.js` com os mesmos nomes.
- O POST de `markRead` precisa do CSRF (R3-SEC-02); o `core/http` o envia. O 401 de sessão expirada precisa redirecionar ao login, como o `ajaxError` do legado faz hoje.

## Objetivo
`entries/app.js` entrega a mesma interface de menu, drawer, impressão e sino de notificações, sem `?r=` escrito à mão e sem globais, e os dois arquivos antigos deixam de existir.

## Passos
1. `frontend/components/layout/` (`drawer.js`, `menus.js`, `print.js`): porte cada handler com a mesma lógica de classes e seletores; eventos delegados em `document` e inicialização por `$(fn)`.
2. `frontend/components/notifications/bell.js`: porte o polling, o dropdown e o "marcar como lida" sobre `core/http` e `routes/notifications.js`. Gere o HTML com a tag `html` (escapa tudo, inclusive `item.id`), preservando classes, estrutura e as cores por tipo (por enquanto inline; passar para o SCSS é da F5). Mantenha 60 s e o "99+". Limpe o timer se o sino não existir.
3. `frontend/entries/app.js` importa os módulos acima. Sem globais.
4. `fullmenu.php`: troque as duas tags `<script>` por `TFrontend::registerEntry('entries/app.js')` (**confirmar** a posição e o `type=module`). Mantenha `datatables/init.js`, `datatablesptbr.js` e a ponte como estão.
5. `tag-qa`: Vitest para o sino (render com dados falsos, escape de `id` e título, badge `99+`, 401 redireciona, `markRead` envia o token) e Playwright para os fluxos do Contexto, com a suíte visual do R1. Compare o comportamento de impressão antes e depois.
6. Com tudo verde e aprovação humana, remova `js/layout/functions.js` e `js/notifications/notifications.js` (grep por qualquer referência, inclusive caminho montado por concatenação).

## Fora de escopo
- Consertar o `#print` duplicado, mover os estilos inline do sino para SCSS e trocar o polling por outra estratégia.
- Migrar `js/datatables/init.js` e o `datatablesptbr.js` (F4).
- O JS do módulo notifications além do sino (R6-NOTIFICATIONS).

## Critérios de aceite
- [ ] Drawer, submenus, sino e impressão se comportam como antes (smoke e visual).
- [ ] Nenhum `?r=` literal e nenhum global novo nos arquivos novos; o ESLint passa.
- [ ] Todo valor vindo do servidor entra no HTML escapado.
- [ ] O POST `markRead` envia o CSRF e o 401 redireciona ao login.
- [ ] Os dois arquivos antigos foram removidos e nenhum layout ou view os referencia.
- [ ] `npm run entries:check` passa.

## Como verificar
```bash
npm run lint && npm run typecheck && npm test && npm run build
npm run entries:check
git grep -nE "layout/functions\.js|notifications/notifications\.js" -- themes app    # sem resultados
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação do plano.

## Handoff
_Preenchido por quem executar._

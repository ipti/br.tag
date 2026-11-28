---
id: R3-SEC-02
title: "CSRF: corrigir os formulários escritos à mão e ligar o modo enforce"
release: R3
module: plataforma
type: seguranca
skill: tag-secure-controller
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-SEC-03]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B2, CSRF) e seção 5 (onde as trilhas se encontram)"
write_scope:
  - app/modules/*/views/**
  - app/modules/*/resources/**
  - app/views/**
  - themes/default/views/**
  - themes/default/js/common.js
  - js/**
  - app/config/csrf-exempt.php
  - docs/arquitetura/csrf-inventario.md
  - tests/**
read_only:
  - app/src/Shared/Infrastructure/Yii/TagHttpRequest.php
  - app/config/main.php
forbidden:
  - composer.json
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
  - app/modules/**/controllers/**
acceptance:
  - "composer analyse"
  - "composer test:integration -- --filter Csrf"
  - "Smoke com curl: POST sem token → 400; POST com token (corpo ou header) → resposta normal, com CSRF_MODE=enforce"
  - "Playwright: login, troca de escola/ano, criar aluno, upload de arquivo e exclusão em tabela passam com enforce ligado"
---

# R3-SEC-02: CSRF: corrigir os formulários escritos à mão e ligar o modo enforce

## Contexto
**Card do coordenador:** edita os layouts de `themes/default/views/layouts/` (arquivos com dono) quando um formulário vive neles. Agentes de módulo não o executam.

O R2-SEC-03 entregou o `TagHttpRequest` (`app/src/Shared/Infrastructure/Yii/TagHttpRequest.php`), com `enableCsrfValidation=true`, token aceito no corpo ou no header `X-CSRF-Token`, rotas isentas em `app/config/csrf-exempt.php` e `CSRF_MODE=log|enforce` (padrão `log`). Em `log`, cada POST sem token gera `security.csrf` no log/Sentry. Ele também deixou o `<meta name="csrf-token">` nos layouts, um `$.ajaxPrefilter` provisório em `themes/default/js/common.js` e o inventário em `docs/arquitetura/csrf-inventario.md`, que é a **entrada deste card** (confirmar que existem).

Fatos verificados e pontos de atenção:
- A validação roda em `onBeginRequest`, antes do roteamento, e em modo `enforce` lança `CHttpException(400)`. O status esperado é 400.
- `CHtml::beginForm`, `CActiveForm` e `CHtml::ajax` já levam o token. Os que escapam são `<form>` em HTML puro, `fetch`, `XMLHttpRequest` e `sendBeacon` com POST (o prefilter do jQuery não os cobre).
- **Ilhas fora do prefilter:** `resultsmanagement` e `schoolreport/default` não carregam o `common.js` (R2-SEC-03 as deixou fora). Os POSTs delas precisam de token manual ou de isenção justificada em `csrf-exempt.php`.
- **Chamadas externas sem token:** `foods/foodrequest/updateAcceptedFoods` e `updateReceivedFoods` (br-nham, Bearer; `FoodrequestController.php` ≈L168 e ≈L218) estão em `csrf-exempt.php` até o R3-API-05 as remover. Confirme com o time se existe outro cliente externo que faça POST no web (sagres, SED, webhooks).
- `themes/default/js/common.js` ≈L247–L251 envia o formulário `.school` por JS (`$(".school").submit()`). Confirmar em `fullmenu.php` se esse formulário e o de escola/ano são escritos à mão.
- Formulários públicos (login, pré-matrícula online, cadastro de agricultor) precisam do token mesmo sem sessão, porque o Yii o guarda em cookie próprio.
- O número de `$.ajax` (≈193 no plano) e de `<form` (61 em 30 arquivos no R2-SEC-03) são de datas anteriores: reconfirme com grep.

## Objetivo
Ligar `CSRF_MODE=enforce` em produção sem quebrar nenhum POST legítimo, com rollback por variável de ambiente.

## Passos
1. Leia `docs/arquitetura/csrf-inventario.md` e repita a descoberta (`<form` sem `beginForm`/`CActiveForm`; `fetch(`, `XMLHttpRequest`, `sendBeacon`, `$.ajax`/`$.post` fora de GET), atualizando o inventário.
2. Exporte os eventos `security.csrf` do modo log (Sentry e `app/runtime/<INSTANCE>/<data>/`, ≥ 2 semanas) por rota e por tenant. Cada rota com falha recebe a causa: formulário sem token, AJAX sem header, cliente externo ou ataque.
3. Corrija os formulários escritos à mão com `CHtml::hiddenField(Yii::app()->request->csrfTokenName, Yii::app()->request->csrfToken)`. Nos layouts, só o coordenador edita.
4. Cubra o que o prefilter não alcança: `fetch`/`XMLHttpRequest` com POST (enviar `X-CSRF-Token` lido do `<meta>`), uploads com `FormData`, o CGridView e os POSTs das ilhas.
5. Mantenha em `csrf-exempt.php` só o que tem justificativa (rotas Bearer do foods, com data de remoção ligada ao R3-API-05).
6. `tag-qa`: testes de integração do `TagHttpRequest` (POST sem token → 400; token no corpo ou no header → passa; rota isenta → passa) e smoke Playwright dos POSTs principais.
7. Ensaio em homologação com `CSRF_MODE=enforce`; depois produção em etapas (tenant piloto por 48 h, depois todos).
8. Documente o rollback no Handoff: `CSRF_MODE=log` mais recarga do contêiner `tag-app`; confirme se vale a cada request ou só no boot.

## Fora de escopo
- O `core/http.js` e a ponte legada com o prefilter definitivo (R3-FRONT-01 e R3-FRONT-04).
- Mudar o AJAX da web para JWT (seção 15 do plano).
- Cookie `SameSite`/`secure` da sessão (R2-SEC-01).

## Critérios de aceite
- [ ] Todo `<form>` do inventário envia o token (teste ou grep prova).
- [ ] Todo `fetch`/`XMLHttpRequest`/`sendBeacon` com POST do inventário envia o header; uploads e CGridView confirmados.
- [ ] As ilhas têm token manual ou isenção justificada.
- [ ] `csrf-exempt.php` só contém rotas com justificativa e data de remoção.
- [ ] Produção em `CSRF_MODE=enforce` por 48 h sem evento `security.csrf` legítimo.
- [ ] Rollback para `log` testado em homologação.

## Como verificar
```bash
# POST sem token: bloqueado com 400
curl -i -X POST "http://localhost/?r=site/changeYear" -d "years=2026"
# POST com token: pegue o cookie e o token de uma página GET (meta csrf-token) e envie os dois
curl -i -c jar.txt "http://localhost/?r=site/login"
# rota isenta (Bearer) continua respondendo pelo controller, sem 400
curl -i -X POST "http://localhost/?r=foods/foodrequest/updateReceivedFoods" -H "Authorization: Bearer invalido"
composer analyse && composer test:integration -- --filter Csrf
```
Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

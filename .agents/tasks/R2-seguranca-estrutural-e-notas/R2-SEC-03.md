---
id: R2-SEC-03
title: "Ligar a validação de CSRF em modo log com TagHttpRequest, meta no layout e token nos AJAX"
release: R2
module: plataforma
type: seguranca
skill: tag-secure-controller
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-07]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 5), seção 5 (onde as trilhas se encontram) e seção 6 (B2, CSRF)"
write_scope:
  - app/src/Shared/Infrastructure/Yii/TagHttpRequest.php
  - app/config/csrf-exempt.php
  - app/config/main.php
  - themes/default/js/common.js
  - themes/default/views/layouts/fullmenu.php
  - themes/default/views/layouts/reports.php
  - themes/default/views/layouts/reportsclean.php
  - themes/default/views/layouts/login.php
  - app/modules/enrollmentonline/layouts/enrollmentonline.php
  - tests/Unit/Shared/**
  - tests/Integration/Shared/**
  - docs/arquitetura/csrf-inventario.md
read_only:
  - app/modules/**/views/**
  - app/modules/**/resources/**
  - js/**
forbidden:
  - composer.json
  - app/models/**
  - app/modules/**/controllers/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter Csrf"
  - "composer test:integration -- --filter Csrf"
  - "Com CSRF_MODE=log, POST sem token funciona e gera log security.csrf; POST com o token do meta, no corpo ou no header, não gera log"
---

# R2-SEC-03: Ligar a validação de CSRF em modo log com TagHttpRequest, meta no layout e token nos AJAX

## Contexto
Este é um **card do coordenador**: edita `app/config/main.php` e os layouts de `themes/default/views/layouts/`, que têm dono.

Fatos verificados no código:
- **CSRF desligado:** `main.php` não configura o componente `request`, então vale o `CHttpRequest` padrão, com `enableCsrfValidation=false`.
- **Como o Yii valida:** `CHttpRequest::validateCsrfToken()` (`vendor/yiisoft/yii/framework/web/CHttpRequest.php` ≈L1364–1402) é ligado ao evento `onBeginRequest`, **antes do roteamento**. Lê o token do corpo (`YII_CSRF_TOKEN`) em POST, PUT, PATCH e DELETE, compara com o cookie de mesmo nome e lança `CHttpException(400)` se falhar. O token é gerado em `getCsrfToken()` (≈L1321), e o cookie nasce na primeira chamada.
- **Formulários:** `CHtml::beginForm` e `CActiveForm` incluem o token sozinhos quando a validação está ligada. O que escapa são os `<form>` escritos à mão: 61 ocorrências em 30 arquivos de `app/modules`, `app/views` e `themes` (grep em 2026-11-27), com 17 em `schoolreport/views/reports/index.php`.
- **AJAX:** o plano fala em 193 `$.ajax` (seção 7, F3). `common.js` não tem `ajaxSetup` nem `ajaxPrefilter` (só `ajaxError`, ≈L178, e a manipulação do ENTER, ≈L264).
- **Já tratam o token:** `classdiary/views/entryLock/index.php` ≈L35–36 e `tools/views/default/index.php` ≈L314. Use-os como padrão.
- **Chamadas sem cookie:** `FoodrequestController::actionUpdateAcceptedFoods` (≈L168) e `actionUpdateReceivedFoods` (≈L218) recebem POST do br-nham com Bearer e nunca terão token.
- **Layouts que carregam `common.js`:** `fullmenu.php` ≈L303, `reports.php` ≈L68 e o layout do enrollmentonline ≈L53. As ilhas (`resultsmanagement` e `schoolreport`) não carregam.

## Objetivo
O CSRF está ativo em modo `log`: toda requisição de escrita sem token válido é registrada, nada é bloqueado, e os AJAX do `common.js` já enviam o token.

## Passos
1. Crie `TagHttpRequest extends CHttpRequest` e configure `request` em `main.php` com a classe via `::class`, `enableCsrfValidation => true`, `csrfCookie` com `httpOnly` e `sameSite` Lax (o `CHttpCookie` tem `sameSite`).
2. Sobrescreva `validateCsrfToken()`: calcule a validade aceitando o token no corpo **ou** no header `X-CSRF-Token`, comparando pelo `securityManager->unmaskToken` como o Yii faz. Extraia a comparação para um método protegido que o teste consiga chamar.
3. Isenção por rota: como o roteamento ainda não ocorreu, resolva a rota com `Yii::app()->getUrlManager()->parseUrl($this)` e compare com `app/config/csrf-exempt.php` (lista de rotas, com justificativa). Comece com as duas actions do br-nham, que saem da lista quando o R3 migrar a API.
4. `CSRF_MODE=log|enforce` (env, padrão `log`; inválido vale `log`). Em `log`, registre `Yii::log(..., CLogger::LEVEL_WARNING, 'security.csrf')` com rota, método e se é AJAX, **sem o token**, e deixe a requisição seguir. Em `enforce`, lance a exceção do pai. Acrescente `security.*` às categorias do log de arquivo de `main.php` (se o R2-SEC-02 já fez, não duplique).
5. Layouts: acrescente `<meta name="csrf-token" content="<?= CHtml::encode(Yii::app()->request->csrfToken) ?>">` em `fullmenu`, `reports`, `reportsclean`, `login` e no layout do enrollmentonline.
6. `common.js`: instale o token com **`$.ajaxPrefilter`** e não com `$.ajaxSetup`, para métodos fora de GET, HEAD e OPTIONS e só em requisição do mesmo domínio (`!options.crossDomain`), lendo o `<meta>`. Motivo: o `beforeSend` passado numa chamada substitui o do `ajaxSetup`, e várias chamadas definem o próprio `beforeSend` (por exemplo `grades/resources/legacy/grades/_initialization.js` ≈L205). O prefilter não é substituído, e `$.post` e `$.getJSON` passam por ele. Registre no Handoff esse desvio do texto do plano.
7. Inventário em `docs/arquitetura/csrf-inventario.md`, com a tabela de cada caso (arquivo, linha, tipo, tratamento sugerido):
   - os `<form` escritos à mão sem campo de token;
   - os `fetch(`, `XMLHttpRequest` e `sendBeacon` com POST (por exemplo `grades/views/default/batchclose.php` ≈L35), que o prefilter do jQuery não cobre;
   - os POST das ilhas;
   - os endpoints servidor a servidor.
8. Deixe a correção dos formulários para cards seguintes (por módulo), com o inventário como entrada.

## Fora de escopo
- Ligar o `enforce` (R3-SEC) e corrigir formulários e JS de módulo.
- O `core/http.js` do R3, que substitui este prefilter provisório.
- Token nas ilhas `schoolreport` e `resultsmanagement`.

## Critérios de aceite
- [ ] Em `log`, POST sem token funciona como antes e gera uma linha `security.csrf` sem o valor do token.
- [ ] POST com o token no corpo ou em `X-CSRF-Token` não gera log.
- [ ] POST nas rotas de `csrf-exempt.php` não gera log.
- [ ] As páginas do `fullmenu` têm o `<meta name="csrf-token">`, e um `$.ajax` com `beforeSend` próprio envia o header.
- [ ] O prefilter não envia o token em requisição para outro domínio.
- [ ] O inventário existe e cobre as quatro categorias do passo 7.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Csrf
composer test:integration -- --filter Csrf

# modo log: POST sem token passa, e o log registra
curl -i -X POST "http://localhost/?r=site/login" -d "LoginForm[username]=x&LoginForm[password]=y"
# esperado: resposta normal da tela de login e uma linha security.csrf (sem o token) no log do dia
```
Passo manual no navegador (peça URL e credenciais antes): abra o DevTools, dispare um `$.ajax` POST de uma tela do `fullmenu` e confira o header `X-CSRF-Token` na aba Rede.

- Confirmar: contagem de `$.ajax` e de `fetch(` com grep no momento da execução, porque os números do plano são de uma data anterior.

## Handoff
_Preenchido por quem executar._

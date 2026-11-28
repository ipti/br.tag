---
id: R0-INFRA-03
title: "Ligar o exception handler do Yii em produção sem vazar stack e com 403/404 corretos"
release: R0
module: plataforma
type: seguranca
skill: .agents/playbooks/bugfix.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (achado 9) e seção 6 (B0, item 0.3)"
write_scope:
  - config.php
  - app/config/main.php
  - themes/default/views/site/error.php
  - app/modules/lunch/views/default/error.php
  - app/modules/schoolreport/views/default/error.php
  - app/controllers/SiteController.php
read_only:
  - vendor/yiisoft/yii/framework/base/CErrorHandler.php
  - vendor/yiisoft/yii/framework/base/CApplication.php
  - vendor/websupport/yii-sentry/src/**
  - themes/default/views/layouts/login.php
forbidden:
  - composer.json
  - app/models/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "Matriz de curl da seção Como verificar: 404 e 403 com o status certo, sem file/line/trace no corpo, em YII_DEBUG=0"
  - "Erro de teste em homologação chega ao Sentry uma única vez; 404 e 403 não geram evento"
---

# R0-INFRA-03: Ligar o exception handler do Yii em produção sem vazar stack e com 403/404 corretos

## Contexto
**Card do coordenador.** Mexe em `config.php` e em `app/config/main.php` (arquivo com dono). O `SiteController.php` também é tocado pelo R0-SQL-01, em trechos diferentes: quem entrar depois faz rebase.

**O problema** (achado 9): `config.php` (≈L27–38), com `YII_DEBUG` falso, define `YII_ENABLE_ERROR_HANDLER` e `YII_ENABLE_EXCEPTION_HANDLER` como `false`. Sem o handler do Yii (`CApplication::initSystemHandlers`, ≈L968–974), todo `CHttpException` (400, 403, 404) é exceção não tratada: o PHP devolve 500 em branco (`display_errors=0`, `error_reporting(0)`, ≈L29–35). Os cards de segurança do R0 usam 403 e 400, que hoje aparecem como 500 em produção.

**Armadilhas encontradas ao ler o código** (confirmar cada uma):
1. **O view de erro imprime tudo.** `themes/default/views/site/error.php` (≈L49) faz `foreach ($error as $key => $err)` e mostra `file`, `line`, `trace` e `traces`. O `CErrorHandler::handleException` (`vendor/yiisoft/yii/framework/base/CErrorHandler.php` ≈L201–210) monta essas chaves sempre. Com o handler ligado em produção, **a stack vazaria para o usuário**, ao contrário do que o plano supõe. Os views `app/modules/lunch/views/default/error.php` (≈L35) e `app/modules/schoolreport/views/default/error.php` (≈L37) fazem `json_encode($error)`, com o mesmo vazamento. `SiteController::actionError` (≈L55–64) devolve `$error['message']` em AJAX, e a mensagem de uma `CDbException` traz o SQL.
2. **`YII_DEBUG=false` liga o debug.** `config.php` ≈L4 faz `(bool) getenv('YII_DEBUG')`, e `(bool) "false"` é `true` (testado: `YII_DEBUG=false` resulta em debug ligado; `0`, vazio e ausente resultam em desligado). Verifique o valor real da variável em cada ambiente antes de corrigir.
3. **O handler de erros do PHP deve continuar desligado.** O `initSystemHandlers` registra `set_error_handler(..., error_reporting())`, e `handleError` encerra a requisição a cada aviso. O legado tem muitos avisos. Ligue só o handler de **exceções**.
4. **Visitante.** O erro é renderizado pelo `SiteController` com o layout padrão `fullmenu`, que redireciona visitante para o login (`themes/default/views/layouts/fullmenu.php` ≈L5–7). Um 404 para visitante viraria 302.
5. **Sentry.** `CApplication::handleException` (≈L724–740) registra **toda** exceção, inclusive `CHttpException`, em nível `error` com categoria `exception.<Classe>[.<status>]`. A rota do Sentry (`main.php` ≈L27–31, `levels error,warning`, `enabled => !YII_DEBUG`) sem filtro de categoria enviaria cada 403 e 404. A rota de arquivo (≈L13–16) só aceita `application,system.*`, então as exceções deixariam de aparecer no log local. O SDK do Sentry também instala handlers próprios (`Client::init`, `installPhpErrorReporting`): **confirmar** se um erro gera um ou dois eventos.
6. `errorHandler` (`main.php` ≈L226–228) só define `errorAction => 'site/error'`. Os módulos `lunch` e `schoolreport` trocam essa action por `lunch/default/error` e `schoolreport/default/error`.

Pelo `renderException` (≈L347–359), `CHttpException` ou `YII_DEBUG` falso usam `errorAction`; só com debug ligado e exceção comum aparece a tela verbosa do Yii.

## Objetivo
Em produção, erros viram uma página de erro com o status HTTP correto e sem detalhes técnicos, e o Sentry continua recebendo os erros reais sem ruído de 403 e 404.

## Passos
1. **Levantamento:** registre no Handoff o valor de `YII_DEBUG` de homologação e de produção. Se algum for a string `false`, avise antes de seguir: a correção do item 2 desliga o debug onde ele estava ligado sem querer.
2. **`config.php`:** trate `YII_DEBUG` com `filter_var(getenv('YII_DEBUG'), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false` (sem o `(bool)`). No ramo de produção, deixe `YII_ENABLE_EXCEPTION_HANDLER` ligado por padrão e permita desligar por `YII_ENABLE_EXCEPTION_HANDLER=0` no ambiente (volta atrás sem novo deploy). Mantenha `YII_ENABLE_ERROR_HANDLER` falso.
3. **Views de erro** (os três): fora de debug, mostre só o código, uma mensagem fixa por status (por exemplo 403 "Você não tem permissão", 404 "Página não encontrada", demais "Erro inesperado") e o link de suporte que já existe. Mostre a `message` apenas quando `$error['type'] === 'CHttpException'`. O bloco técnico (`file`, `line`, `trace`) só aparece com `YII_DEBUG` verdadeiro. Em `SiteController::actionError`, aplique a mesma regra à resposta AJAX.
4. **Visitante:** em `actionError`, use `$this->layout = 'login'` quando `Yii::app()->user->isGuest` (confirmar que o layout `login` renderiza sem sessão, como já faz em `actionLogin`).
5. **Sentry e log** (`main.php`): na rota do Sentry, acrescente `'except' => ['exception.CHttpException.4*']` para não enviar 4xx. Na rota de arquivo, acrescente `exception.*` em `categories`, para os 4xx e 500 ficarem no log local.
6. **Eventos duplicados:** em homologação, dispare um erro de teste (uma exceção lançada numa action temporária, sem commitar) e confirme **um** evento no Sentry. Se vierem dois, ajuste a rota ou a inicialização do SDK e registre a decisão.
7. Rode a matriz da seção Como verificar com `YII_DEBUG=0` e `YII_DEBUG=1`, para usuário logado e para visitante.

## Fora de escopo
- Ligar o handler de erros do PHP (aviso e notice) ou limpar avisos do legado.
- Redesenhar as páginas de erro ou as das ilhas (F5 do plano).
- Trocar o Sentry ou mudar amostragem e `release`.
- Corrigir os controllers que lançam `CHttpException`.

## Critérios de aceite
- [ ] Com `YII_DEBUG=0`, rota inexistente responde **404** e uma action que lança 403 responde **403**, ambos com a página de erro do TAG.
- [ ] Nenhum corpo de erro em `YII_DEBUG=0` contém caminho de arquivo, número de linha, `Stack trace` ou texto de SQL.
- [ ] Exceção comum (500) responde 500 com mensagem fixa e é registrada.
- [ ] Visitante em rota inexistente vê a página de erro com o status certo (não um 302 para o login).
- [ ] `YII_DEBUG=false` (texto) passa a significar debug desligado.
- [ ] O Sentry recebe o erro de teste uma vez e não recebe 403 nem 404; os 4xx aparecem no log local.
- [ ] O valor de `YII_DEBUG` de cada ambiente consta no Handoff.

## Como verificar
```bash
H=http://localhost    # com YII_DEBUG=0 no ambiente; repita com YII_DEBUG=1
curl -i "$H/?r=site/rotaInexistente"                          # visitante: esperado 404, sem file/line/trace
curl -i -b "PHPSESSID=<sessão>" "$H/?r=site/rotaInexistente"  # logado: esperado 404
curl -i -b "PHPSESSID=<sessão de professor>" "$H/?r=systemadmin/default/index"   # esperado 403 (após o R0-SYSADMIN-01)
curl -s -b "PHPSESSID=<sessão>" "$H/?r=site/rotaInexistente" | grep -ciE "stack trace|\.php|trace:|line:"   # esperado 0
curl -i -H "X-Requested-With: XMLHttpRequest" -b "PHPSESSID=<sessão>" "$H/?r=site/rotaInexistente"            # corpo curto, sem SQL

YII_DEBUG=false php -r '$d = filter_var(getenv("YII_DEBUG"), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? false; var_dump($d);'   # esperado: bool(false)
composer lint && composer analyse
```
Para o Sentry, use homologação: crie uma action temporária que lança `Exception('teste sentry')`, chame uma vez e confira no painel; chame também uma rota 404 e confirme que não gera evento. Não faça commit da action. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

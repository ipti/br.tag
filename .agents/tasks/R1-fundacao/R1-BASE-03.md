---
id: R1-BASE-03
title: "Atualizar o yiisoft/yii de 1.1.31 para 1.1.32 (suporte a PHP 8.4)"
release: R1
module: plataforma
type: fundacao
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-02]
size: P
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco Yii e PHP) e seção 9"
write_scope:
  - composer.json
  - composer.lock
read_only:
  - vendor/yiisoft/yii/CHANGELOG
  - config.php
  - app/config/main.php
  - themes/default/views/layouts/**
forbidden:
  - app/**
  - themes/**
  - .github/**
  - Dockerfile
acceptance:
  - "docker exec tag-app composer update yiisoft/yii"
  - "docker exec tag-app php -r 'require \"vendor/yiisoft/yii/framework/yii.php\"; echo Yii::getVersion();'   (esperado: 1.1.32)"
  - "composer lint"
  - "composer analyse"
  - "Smoke das telas da seção Como verificar, sem diferença de comportamento"
---

# R1-BASE-03: Atualizar o yiisoft/yii de 1.1.31 para 1.1.32 (suporte a PHP 8.4)

## Contexto
**Card do coordenador:** só `composer.json` e `composer.lock`, num PR **isolado**, depois do R1-BASE-02 (que já deixou o lock estável).

Fatos:
- `composer.json` fixa `"yiisoft/yii": "1.1.31"` (≈L5). O `composer.lock` tem `yiisoft/yii 1.1.31`.
- O `vendor/yiisoft/yii/CHANGELOG` local termina na **1.1.31 (10/04/2025)**, que já traz a correção da CVE-2025-32027. As notas da 1.1.32 não estão no repositório: **confirmar:** ler o CHANGELOG da 1.1.32 (GitHub `yiisoft/yii` ou Packagist) e anotar no Handoff os itens que tocam em `CHtml`, `CActiveForm`, `CClientScript` (jQuery e `bbq`), `CDbCommand`, `CUploadedFile`, `CWebUser`, sessão e `CDbCache`.
- O plano (seção 9) registra que a 1.1.32 (dezembro de 2025) dá suporte ao PHP 8.4 e que o suporte de segurança do PHP 8.3 acaba em 31/12/2027. A subida para o PHP 8.4 **não** é feita aqui.
- `config.php` define `YII_VERSION` com `Yii::getVersion()`; o `index.php` carrega o framework de `vendor/yiisoft/yii/framework/yii.php`.
- `app/config/main.php` usa `assetManager.forceCopy = YII_DEBUG`. Os scripts core do Yii (jquery.yiigridview, bbq etc.) são publicados em `/assets`, então um framework novo exige limpar essa pasta (`composer clear-assets`).

## Objetivo
O framework fica na 1.1.32 e as telas continuam se comportando como antes.

## Passos
1. Leia o CHANGELOG da 1.1.32 e anote os itens relevantes (ver Contexto).
2. Em `composer.json`, troque para `"yiisoft/yii": "1.1.32"`.
3. Rode `docker exec tag-app composer update yiisoft/yii` e confirme que o `git diff composer.lock` só mexe no `yiisoft/yii` (e no `content-hash`). Se o Composer puxar outros pacotes, registre e justifique.
4. Rode `composer clear-assets` e recarregue as telas (os assets core são republicados).
5. Rode `composer lint` e `composer analyse`.
6. Faça o smoke da seção Como verificar. Qualquer diferença visual ou de comportamento bloqueia o PR: registre no Handoff e **não** contorne no código de aplicação neste card.

## Fora de escopo
- Subir para o PHP 8.4 ou alterar `config.platform.php`.
- Corrigir deprecations novos que apareçam no log: só registrar.
- Qualquer mudança em `app/`, `themes/` ou `index.php`.

## Critérios de aceite
- [ ] `composer.json` e `composer.lock` apontam para `yiisoft/yii 1.1.32` e nenhum outro pacote mudou sem justificativa.
- [ ] `Yii::getVersion()` retorna `1.1.32` dentro do container.
- [ ] **O comportamento não muda:** nenhuma tela do smoke apresenta erro novo, estilo quebrado ou erro de JavaScript no console.
- [ ] Os itens do CHANGELOG da 1.1.32 que tocam o TAG estão listados no Handoff.

## Como verificar
```bash
docker exec tag-app composer update yiisoft/yii
docker exec tag-app php -r 'require "vendor/yiisoft/yii/framework/yii.php"; echo Yii::getVersion(), "\n";'   # esperado: 1.1.32
composer clear-assets
composer lint && composer analyse
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes). Abrir, salvar quando houver formulário, e olhar o console do navegador:
- login e logout, home com os gráficos;
- lista e formulário de aluno (`CActiveForm`, validação e gravação);
- lista com paginação (`CGridView`/`CListView`) e filtro;
- upload de arquivo (foto do aluno ou logo da escola, `CUploadedFile`);
- lançamento de notas (`grades`) e diário de classe (`classdiary`);
- um relatório com layout `reports` e um com `reportsclean`;
- troca de escola e de ano (`site/changeSchool`, `site/changeYear`).

## Handoff
_Preenchido por quem executar._

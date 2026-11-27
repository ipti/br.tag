---
id: R1-BASE-04
title: "Centralizar os imports do Yii em app/config/parts/imports.php"
release: R1
module: plataforma
type: fundacao
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-01]
size: P
risk: baixo
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 3.1 (Como o legado é carregado) e seção 6 (B1, bloco Bootstrap)"
write_scope:
  - app/config/parts/imports.php
  - app/config/main.php
  - app/config/console.php
read_only:
  - app/config/test.php
  - app/models/**
  - app/components/**
forbidden:
  - composer.json
  - app/modules/**
  - themes/**
  - .github/**
acceptance:
  - "docker exec tag-app php /app/app/yiic help   (console sobe com os imports compartilhados)"
  - "docker exec tag-app php -l /app/app/config/parts/imports.php"
  - "composer lint"
  - "composer analyse"
  - "Smoke: login e home abrem como antes (seção Como verificar)"
---

# R1-BASE-04: Centralizar os imports do Yii em app/config/parts/imports.php

## Contexto
**Card do coordenador:** `app/config/main.php` é arquivo com dono.

Hoje a lista de imports está duplicada e desigual:
- `app/config/main.php` tem 24 entradas em `'import'` (≈L53–78): `application.models.*`, `controllers.*`, `components.*`, `components.widgets.*`, os models de `wizard`, `tag`, `calendar`, `curricularmatrix` e `quiz`, `sedsp.datasources.sed.*`, `sagres.soap.src.sagresEdu.*`, os utilitários (`TagUtils`, `TMenu`, `TUrlBridge`, `AppRoutes`, `TLog`), as classes `auth` (`TFeature`, `TTask`, `TRole`, `TNotificationEvent`) e as extensões `ext.bncc-import.BNCCImport` e `ext.imc.IMC`.
- `app/config/console.php` tem só 6 entradas (`application.models.*`, `controllers.*`, `components.*` e os models de `wizard`, `calendar` e `quiz`), todas contidas na lista do `main.php`. Por isso comandos como `sqlmigration` não enxergam o restante.
- `app/config/test.php` não tem lista própria: faz `CMap::mergeArray(require main.php, ...)` e por isso herda os imports. Ele está quebrado por outro motivo (depende de `INSTANCE` e `TAG_VERSION`) e será reescrito no R1-TEST-01, que passa a usar o arquivo criado aqui.
- A futura configuração da API (`app/config/api.php`, R3) também vai usar o mesmo arquivo.
- O plano (seção 3.1) avisa que existem 9 nomes de classe duplicados entre `app/models` e os models dos módulos (por exemplo `FoodMenu`); a **ordem** dos imports decide qual vence. Não pode mudar.

## Objetivo
Uma única lista de imports (`app/config/parts/imports.php`), usada por `main.php` e `console.php`, na mesma ordem de hoje.

## Passos
1. Crie `app/config/parts/imports.php` com `return [...]` contendo **exatamente** as 24 entradas do `main.php`, na mesma ordem.
2. Em `main.php`, troque o array de `'import'` por `require __DIR__ . '/parts/imports.php'`.
3. Em `console.php`, faça o mesmo. A lista do console cresce de 6 para 24 entradas; isso só registra caminhos de autoload (o Yii não carrega nada até a classe ser pedida).
4. Antes de abrir o PR, procure nomes de classe repetidos entre os diretórios que o console passa a importar e os que já importava (ex.: `FoodMenu`). Se o console passar a resolver uma classe duplicada de outro jeito, tire a entrada extra do console e registre.
5. Não mexa em `test.php`: ele herda via `main.php`. Deixe uma linha no Handoff lembrando o R1-TEST-01 de usar o arquivo novo.

## Fora de escopo
- Remover entradas obsoletas (por exemplo `sagres.soap.src.sagresEdu.*`, ≈L66 do `main.php`) ou criar `classmap` para `app/models`: é a etapa seguinte, depois de renomear os nomes duplicados (plano, seção 3.1).
- Criar `api.php` (R3) ou reescrever o `test.php` (R1-TEST-01).
- Qualquer outra mudança em `main.php`.

## Critérios de aceite
- [ ] `app/config/parts/imports.php` existe, retorna um array com 24 entradas na ordem original.
- [ ] `main.php` e `console.php` usam o arquivo e não têm mais lista própria.
- [ ] `php /app/app/yiic help` e `sqlmigration` (em `--dry-run`) funcionam.
- [ ] **O comportamento não muda:** login e home abrem como antes; nenhuma classe duplicada passou a resolver para outro arquivo.

## Como verificar
```bash
docker exec tag-app php -l /app/app/config/parts/imports.php
docker exec tag-app php -r 'echo count(require "/app/app/config/parts/imports.php"), "\n";'   # esperado: 24
docker exec tag-app php /app/app/yiic help
# dry-run só lista os bancos *.tag.ong.br (SHOW DATABASES), não altera nada
docker exec tag-app php /app/app/yiic sqlmigration run /app/app/migrations/3.15.6/v3.15.6_diary_entry_lock.sql --dry-run
composer lint && composer analyse
git diff --stat   # só os três arquivos do write_scope
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes): login, home e uma tela de cada um destes módulos, que dependem de classes importadas: `student`, `grades`, `schoolreport` e `sedsp`.

## Handoff
_Preenchido por quem executar._

---
id: R1-AGT-03
title: "scripts/inventory-actions.php: inventário JSON por controller e action"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.3 (cards gerados a partir de inventário) e seção 8.6 (ondas); .agents/tasks/README.md (inventário por módulo)"
write_scope:
  - scripts/inventory-actions.php
  - scripts/lib/Inventory/**
  - tests/_data/inventory-fixtures/**
  - composer.json
read_only:
  - scripts/generate-routes.php
  - app/controllers/**
  - app/modules/*/controllers/**
  - .agents/tasks/README.md
forbidden:
  - app/**
  - themes/**
  - .github/**
  - composer.lock
acceptance:
  - "docker exec tag-app php /app/scripts/inventory-actions.php --out=/tmp/inventory.json"
  - "docker exec tag-app php /app/scripts/inventory-actions.php --root=tests/_data/inventory-fixtures --out=- | diff - tests/_data/inventory-fixtures/expected.json   (sem diferença)"
  - "composer lint"
  - "composer analyse"
---

# R1-AGT-03: scripts/inventory-actions.php: inventário JSON por controller e action

## Contexto
**Card do coordenador:** só por causa do atalho em `composer.json`; o script em si é ferramenta e não toca em `app/`.

Os épicos R4 a R7 nascem como `rascunho` e o card de refinamento de cada onda os quebra em cards executáveis usando este inventário (`.agents/tasks/README.md`). Hoje o levantamento foi feito com grep e está só como tabela no README (data 2026-11-27); falta uma versão reprodutível e por action.

Fatos do código:
- Controllers: `app/controllers/*Controller.php` (3 raiz, views em `themes/default/views/<controller>/`) e `app/modules/*/controllers/*Controller.php`. Contagem por grep hoje: **67 arquivos** `*Controller.php` e **665** ocorrências de `function action[A-Z]...`, contra "66 controllers e 663 actions" no plano (seção 1). A diferença (um controller e duas actions) deve ser explicada no Handoff; pode ter sido adicionada depois do levantamento.
- Há `public function actions()` (actions externas) em pelo menos um controller (`SiteController`); essas actions não aparecem como `function actionX`.
- O README lista **24 controllers sem `accessControl`** (3 raiz mais aeerecord 1, dashboard 1, enrollmentonline 1, foods 4, gestaopresente 1, lunch 3, quiz 1, resultsmanagement 2, sagres 1, schoolreport 3, sedsp 1 e wizard 2). O inventário tem de reproduzir essa lista.
- Controle de acesso no Yii 1.1: `filters()` com `'accessControl'` e `accessRules()` com regras `allow`/`deny` que usam `actions`, `users` (`'*'`, `'@'`), `roles` e `expression`. Seis controllers definem `accessRules()` sem `filters()` (as regras nunca rodam; plano, seção 2, achado 3).
- `scripts/generate-routes.php` já varre os controllers com regex (`public function action([A-Z]...)`) e serve de ponto de partida. Aqui use o **tokenizador do PHP** (`token_get_all`), que delimita o corpo de cada método sem confundir chaves em strings.

## Objetivo
Um script PHP, sem dependências, que lê todos os controllers e escreve um JSON com os números de cada action, para alimentar os cards de refinamento.

## Passos
1. Crie `scripts/inventory-actions.php` (CLI) com as opções `--out=<arquivo>` (`-` para stdout), `--root=<pasta>` (padrão: raiz do repositório) e `--module=<id>` (filtra). A lógica fica em `scripts/lib/Inventory/`.
2. Para cada controller, registre: módulo (ou `root`), classe, caminho, LOC, `filters()` presente, se `filters()` inclui `accessControl`, `accessRules()` presente e se há `actions()`.
3. Para cada `actionX`, registre: rota `modulo/controller/acao` (id do controller em minúsculas, ação com a primeira letra minúscula), LOC do corpo, contagem de superglobais (`$_POST`, `$_GET`, `$_REQUEST`, `$_FILES`, `$_SERVER`, `$_SESSION`, `$_COOKIE`), SQL cru (`createCommand`, `->query(`, `queryAll`, `queryRow`, `->execute(`, e `CDbCriteria->condition` com string) com um indicador de **concatenação ou interpolação** no argumento (candidato a injection), saída JSON (`json_encode`, `CJSON::encode`) e chamadas a `TagUtils::`.
4. **Acesso por action (heurística, marcada como tal):** `none` (sem `accessControl`), `public` (alguma regra `allow` com `users => ['*']` cobre a action), `authenticated`, `restricted` (por `roles` ou `expression`), `unknown` (regras que o script não conseguiu interpretar, p. ex. `actions` montado por variável). Inclua um campo `publicRoute` verdadeiro para `none` e `public`, para alimentar `app/config/public-routes/<módulo>.php` na R2.
5. Escreva o cabeçalho do JSON com `generatedAt`, `gitCommit`, totais (controllers, actions, LOC, sem `accessControl`) e a lista `controllers`.
6. Crie `tests/_data/inventory-fixtures/` com 3 ou 4 controllers mínimos (um protegido, um sem filtro, um com `accessRules` sem `filters`, um com `actions()`) e o `expected.json`. O R1-TEST-01 pode converter essa checagem em teste PHPUnit depois.
7. Rode no repositório real e **compare com o README**: o total de controllers sem `accessControl` deve ser 24 e a lista deve bater. Registre no Handoff qualquer divergência e a explicação da diferença 67/665 contra 66/663.
8. Não versione o JSON gerado. Acrescente o atalho `composer inventory:actions` (`docker exec tag-app php /app/scripts/inventory-actions.php`).

## Fora de escopo
- Gerar os cards das ondas (é do card de refinamento de cada onda, feito por agente e revisado por pessoa).
- Corrigir qualquer controller, ou mexer em `app/config/public-routes/` (R2).
- Inventário de JS, CSS e views (catraca do R1-FRONT-02).

## Critérios de aceite
- [ ] O script gera JSON válido com todos os controllers e actions, com os campos do passo 3.
- [ ] A lista de controllers sem `accessControl` bate com a do README (24) ou a diferença está explicada.
- [ ] A checagem por fixtures passa e as heurísticas de acesso estão documentadas no cabeçalho do script.
- [ ] O script só lê arquivos: não altera nada em `app/`.
- [ ] **O sistema não muda:** nenhum arquivo de `app/` ou `themes/` foi alterado.

## Como verificar
```bash
docker exec tag-app php /app/scripts/inventory-actions.php --out=/tmp/inventory.json
docker exec tag-app php -r '$j=json_decode(file_get_contents("/tmp/inventory.json"),true); echo $j["totals"]["controllers"], " ", $j["totals"]["actions"], " ", $j["totals"]["withoutAccessControl"], "\n";'
docker exec tag-app php /app/scripts/inventory-actions.php --root=tests/_data/inventory-fixtures --out=- | diff - tests/_data/inventory-fixtures/expected.json
composer lint && composer analyse
```

## Handoff
_Preenchido por quem executar._

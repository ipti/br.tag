---
id: R1-FRONT-02
title: "Catraca de métricas do front com baseline por módulo"
release: R1
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 4 (catraca no CI) e seção 7 (F0, bloco Catraca)"
write_scope:
  - scripts/frontend-metrics
  - scripts/lib/FrontendMetrics/**
  - metrics/**
  - tests/_data/frontend-metrics/**
  - .github/workflows/frontend-metrics.yml
  - composer.json
read_only:
  - js/**
  - themes/default/js/**
  - themes/default/views/**
  - app/modules/*/views/**
  - app/modules/*/resources/**
  - .agents/tasks/README.md
forbidden:
  - app/**
  - themes/**
  - js/**
  - composer.lock
  - Dockerfile
acceptance:
  - "docker exec tag-app php /app/scripts/frontend-metrics --update   (gera metrics/baseline/<módulo>.json)"
  - "docker exec tag-app php /app/scripts/frontend-metrics   (sai com 0 logo depois do update)"
  - "docker exec tag-app php /app/scripts/frontend-metrics --root=tests/_data/frontend-metrics --out=- | diff - tests/_data/frontend-metrics/expected.json   (sem diferença)"
  - "docker run --rm -v \"$PWD:/repo\" -w /repo rhysd/actionlint:latest   (frontend-metrics.yml sem erro)"
---

# R1-FRONT-02: Catraca de métricas do front com baseline por módulo

## Contexto
**Card do coordenador:** cria um workflow em `.github/workflows/` e um atalho em `composer.json`.

O plano (seção 4) quer "catracas no CI": contadores de dívida que só podem cair. Para o front, a catraca nasce em PHP, para rodar no container `tag-app`, e pode virar Node depois da F1 (seção 7, F0). Regras do plano: o **total não pode subir**; **arquivo novo nasce com contagem zero**; **arquivo alterado não pode ganhar classes do Bootstrap 2**. A baseline é por módulo (`metrics/baseline/<módulo>.json`) para dois agentes em módulos diferentes não conflitarem (seção 8.4).

Fatos do código:
- JS próprio fica em `js/` (pastas `admin`, `censo`, `classroom`, `courseplan`, `datatables`, `enrollment`, `instructor`, `layout`, `notifications`, `reports`, `site`), em `themes/default/js/` (`common.js` e `util.js` são nossos; o resto é biblioteca) e em `app/modules/<módulo>/resources/` (sem contar `lib/` e `*.min.js`, como o README das tasks define).
- Views PHP ficam em `app/modules/*/views/`, `app/modules/*/layouts/` e `themes/default/views/` (layouts em `layouts/`, views de controllers raiz em `<controller>/`).
- O README das tasks já define a coluna "Bootstrap 2" como `spanN`, `row-fluid`, `control-group` e `innerLR`; o plano acrescenta `btn` e `widget`. O inventário do README (2026-11-27) serve de conferência: por exemplo, `schoolreport` tem 1209 `style=` e 244 usos de Bootstrap 2.
- O plano cita 212 URLs `?r=` escritas à mão em JS, cerca de 300 funções globais e 24,5 mil linhas de JS procedural.
- `scripts/generate-routes.php` é o exemplo de script PHP do repositório (roda em container com `composer run`).

## Objetivo
Um script que mede o front por módulo, grava a baseline atual e, no CI, reprova o PR que piore as métricas.

## Passos
1. Crie `scripts/frontend-metrics` (PHP, `#!/usr/bin/env php`, sem dependências), com lógica em `scripts/lib/FrontendMetrics/`. Opções: `--root=<pasta>` (padrão: raiz do repositório), `--base=<ref>` (padrão `origin/main`), `--update`, `--module=<id>`, `--out=<arquivo|->`.
2. **Mapeamento pasta → módulo** (constante no script ou `metrics/modules.json`; registre as decisões no Handoff): `app/modules/<m>/...` → `<m>`; `js/admin` → `systemadmin`; `js/classroom` → `classroom`; `js/instructor` → `instructor`; `js/courseplan` → `courseplan`; `js/notifications` → `notifications`; `js/censo` e `js/site` → `raiz`; `js/enrollment` e `js/reports` → **confirmar** (têm arquivos de `grades`, `student` e `schoolreport`); `themes/default/js`, `js/layout`, `js/datatables` e `themes/default/views/layouts` → `plataforma`; `themes/default/views/<controller>` → `raiz`.
3. **Métricas por arquivo** (todas com regex documentadas no cabeçalho do script; são aproximações e devem ser tratadas como tal):
   - `route_literal`: `?r=` em arquivos `.js` e em blocos `<script>` de views;
   - `js_toplevel`: declarações no nível zero de JS clássico (`function nome`, `var`, `let`, `const` fora de qualquer bloco), ignorando módulos ES (`import`/`export`) e `*.min.js`;
   - `inline_script`: `<script>` sem `src` e sem tipo JSON ou template, e chamadas `registerScript(`;
   - `inline_handler`: atributos `on*=` (HTML) e `'onclick' =>` (arrays PHP);
   - `dialogs`: `alert(` e `confirm(`;
   - `jquery_legacy`: `.success(`, `$.parseJSON` e `.size()`;
   - `inline_style`: `style=` e `<style`;
   - `bs2_span`, `bs2_row_fluid`, `bs2_control_group`, `bs2_btn`, `bs2_widget` e `bs2_innerLR`, contados em valores de `class="..."` e `'class' => '...'` (confirme com o time se `btn-*` entra em `bs2_btn`);
   - `register_script_file`: `registerScriptFile(`.
4. **Baseline:** `metrics/baseline/<módulo>.json` com `totals` e, por arquivo, só as métricas diferentes de zero. `--update` grava a baseline **somente se nada subiu** (senão falha listando o que piorou); arquivos apagados saem da baseline.
5. **Checagem (padrão):** compara o estado atual com a baseline e com `git diff --name-status --no-renames <base>...HEAD`: (a) o total de cada métrica por módulo não pode subir; (b) arquivo **novo** (`A`) precisa ter zero em todas as métricas; (c) arquivo **alterado** (`M`) não pode ter mais classes `bs2_*` do que na versão da `--base`. Saída em tabela e código 1 com `arquivo: métrica antes → depois` por violação.
6. Crie `tests/_data/frontend-metrics/` com poucos arquivos de exemplo (JS clássico, JS ES, view com `<style>`, view com Bootstrap 2), uma baseline e o `expected.json`, e a checagem por `diff` do critério de aceite. O R1-TEST-01 pode convertê-la em PHPUnit depois.
7. Rode `--update` no repositório real e commite `metrics/baseline/*.json`. Registre no Handoff os totais por métrica e compare com o plano (212 `?r=`, ~300 globais) e com o README (`style=` e Bootstrap 2 por módulo); explique diferenças grandes.
8. **CI:** `.github/workflows/frontend-metrics.yml`, em `pull_request`, com `fetch-depth: 0`, `setup-php` 8.3 e `php scripts/frontend-metrics --base=origin/${{ github.base_ref }}` (não precisa de Composer). O R1-CI-01 pode incorporar o job ao `ci.yml` depois.
9. Atalhos no `composer.json`: `front:metrics` e `front:metrics:update` (`docker exec tag-app php /app/scripts/frontend-metrics`).

## Fora de escopo
- Corrigir qualquer ocorrência encontrada (só medir) e reduzir as métricas.
- Metas de queda por trimestre ou limites absolutos.
- Medir SCSS, PHP de back-end ou imagens.
- Reescrever em Node (depois da F1).

## Critérios de aceite
- [ ] O script mede todas as métricas do passo 3 por módulo e grava `metrics/baseline/<módulo>.json`.
- [ ] `--update` recusa baseline maior; a checagem falha quando o total sobe, quando um arquivo novo tem contagem diferente de zero e quando um arquivo alterado ganha Bootstrap 2.
- [ ] A checagem por fixtures passa e as regex estão documentadas.
- [ ] O workflow roda em PR e falha nos três casos acima (demonstrado com um PR de teste ou localmente com `--base`).
- [ ] **O sistema não muda:** nenhum arquivo de `app/`, `themes/` ou `js/` foi alterado.

## Como verificar
```bash
docker exec tag-app php /app/scripts/frontend-metrics --update
docker exec tag-app php /app/scripts/frontend-metrics; echo "saída: $?"    # esperado: 0
# piora proposital (não commitar): acrescentar class="span6" a uma view alterada e rodar de novo; esperado: saída 1
docker exec tag-app php /app/scripts/frontend-metrics --root=tests/_data/frontend-metrics --out=- | diff - tests/_data/frontend-metrics/expected.json
ls metrics/baseline
docker run --rm -v "$PWD:/repo" -w /repo rhysd/actionlint:latest
```

## Handoff
_Preenchido por quem executar._

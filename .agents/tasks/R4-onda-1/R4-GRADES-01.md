---
id: R4-GRADES-01
title: "grades: migrar o JS de gradesRelease e reportCard para frontend/pages/grades e eliminar as cópias duplicadas"
release: R4
module: grades
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-04, R2-GRADES-06]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F4, ordem sugerida, item 1: gradesRelease e reportCard)"
write_scope:
  - frontend/pages/grades/**
  - app/modules/grades/views/default/gradesRelease.php
  - app/modules/grades/views/default/reportCard.php
  - app/modules/grades/views/entry/gradesRelease.php
  - app/modules/grades/views/entry/reportCard.php
  - app/modules/grades/resources/legacy/gradesRelease/**
  - app/modules/grades/resources/legacy/reportCard/**
  - js/enrollment/gradesRelease/**
  - js/enrollment/reportCard/**
  - tests/e2e/grades/**
  - metrics/baseline/grades.json
read_only:
  - app/modules/grades/controllers/**
  - app/modules/grades/views/default/grades.php
  - js/enrollment/grades/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - app/models/**
acceptance:
  - "Vitest das funções puras extraídas (buildInputOrSelect e montagem das tabelas)"
  - "Smoke Playwright de gradesRelease e reportCard nas duas rotas (default e entry), com a suíte visual sem diferença"
  - "A catraca de grades marca zero ?r= literais e zero globais novos nessas duas páginas"
  - "git grep -n \"enrollment/gradesRelease\\|enrollment/reportCard\\|legacy/gradesRelease\\|legacy/reportCard\" -- app themes js   (sem resultados)"
---

# R4-GRADES-01: grades: migrar o JS de gradesRelease e reportCard para frontend/pages/grades e eliminar as cópias duplicadas

## Contexto
A F4 do plano começa pelas duas telas de notas que têm JS duplicado. Hoje há **duas cópias de cada página**, e as views escolhem a cópia pela rota:
- `app/modules/grades/views/default/{gradesRelease,reportCard}.php` carregam `app/modules/grades/resources/legacy/<página>/` (por `assetManager->publish` e `baseScriptUrl`).
- `app/modules/grades/views/entry/{gradesRelease,reportCard}.php` carregam `js/enrollment/<página>/` (por `baseUrl` e `filemtime`).
- As duas rotas estão vivas: `DefaultController` (17 actions) e `EntryController` (8 actions).

**Correção sobre o enunciado do plano: as cópias não são idênticas.**
- `functions.js` é idêntico nas duas pastas (31 linhas cada; `buildInputOrSelect`, que usa a função global `template`).
- `_initialization.js` **divergiu** (gradesRelease: 334 e 333 linhas; reportCard: 268 e 267):
  - **Tratamento do 403 ao salvar (≈L275–279 no gradesRelease, ≈L236–241 no reportCard):** a cópia de `js/enrollment` mostra a mensagem na área `.js-grades-alert` e distingue 403 de outros erros; a cópia do módulo dispara `alert(...)` só para 403. É o bloqueio de lançamentos da branch `feat/bloqueio-lancamentos-ano-letivo`, aplicado de forma diferente.
  - **Rotas do gradesRelease:** a cópia de `js/enrollment` chama `?r=grades/entry/getDisciplines` (≈L16) e `?r=grades/entry/calculateFinalMedia` (≈L311); a do módulo chama `grades/default/...` (≈L16 e ≈L310). `getGradesRelease` e `saveGradesRelease` apontam para `grades/default` nas duas.
  - **reportCard:** só o trecho do 403 muda; as rotas são as mesmas (`grades/default/getDisciplines`, `getReportCardGrades`, `saveGradesReportCard`).

As views definem `var getGradesUrl = '<?= createUrl('grades/default/getGrades') ?>'` por `registerScript('variables', …)` (≈L11–14). O JS tem `let frequency`, `let totalFaults`, `getFrequency()` e `setFrequency()` no escopo global, e `?r=` literal. Também há `?r=schoolreport/forms/IndividualRecord` no reportCard (≈L118).

A página `grades` (lançamento por avaliação) também tem cópia dupla, mas **divergiu muito** (`functions.js`: 23 linhas em `js/enrollment` contra 451 no módulo). Ela fica para outro card.

## Objetivo
Uma única implementação de `gradesRelease` e de `reportCard` em `frontend/pages/grades/`, usada pelas rotas `default` e `entry`, sem cópias em `js/enrollment/` nem em `legacy/`, sem mudar o que a pessoa vê.

## Passos
1. **Caracterize antes de mover (tag-qa).** Escreva o smoke Playwright das duas páginas nas duas rotas: escolher turma e disciplina, carregar a tabela, salvar, e o 403 de lançamento bloqueado. Registre o que cada cópia faz hoje, em especial as diferenças acima.
2. **Decida com uma pessoa** qual comportamento fica: a mensagem em `.js-grades-alert` (cópia `js/enrollment`) ou o `alert`. Não escolha sozinho; registre a decisão no Handoff. O mesmo vale para as rotas `entry/getDisciplines` e `entry/calculateFinalMedia`: o controller de cada rota continua o mesmo, e a página recebe as URLs por configuração.
3. **Crie as entries** `frontend/pages/grades/grades-release.js` e `report-card.js` com `onPage('grades/grades-release', (root, config) => …)`, eventos delegados a partir de `root` e sem globais. Extraia `buildInputOrSelect` e a montagem das tabelas como funções puras, com Vitest.
4. **Troque as views:** `TFrontend::registerEntry(...)` mais `<div data-page="…">` com `TFrontend::jsonScript($config)`, no lugar de `registerScriptFile` e de `var getGradesUrl`. O `$config` leva todas as URLs (`createUrl`), de modo que as rotas `default` e `entry` usem o mesmo JS.
5. **Troque os `?r=` literais** pelo `url()` do módulo `routes/` gerado (R3-FRONT), inclusive o link do `schoolreport/forms/IndividualRecord`.
6. **Remova** `js/enrollment/{gradesRelease,reportCard}/` e `app/modules/grades/resources/legacy/{gradesRelease,reportCard}/` só depois do smoke verde nas duas rotas. Remoção de arquivos exige revisão humana.
7. **Atualize a catraca** (`metrics/baseline/grades.json`) e rode a suíte visual.

## Fora de escopo
- A página `grades` e o `js/enrollment/grades/` (cópia divergente; card próprio).
- `js/enrollment/form/**` (é do épico de aluno).
- Mudar regra de nota, cálculo de média ou o contrato dos endpoints.
- Mexer no `css/grades.css`.

## Critérios de aceite
- [ ] Não existe mais cópia do JS de gradesRelease nem de reportCard fora de `frontend/pages/grades/`.
- [ ] As rotas `grades/default/*` e `grades/entry/*` usam a mesma entry, com as URLs vindas de `jsonScript`.
- [ ] O comportamento do 403 está unificado conforme a decisão registrada.
- [ ] Zero `?r=` literal e zero global novo (catraca de grades) nessas duas páginas.
- [ ] Smoke e suíte visual sem diferença; Vitest verde.

## Como verificar
```bash
npm run test -- grades                       # Vitest; confirmar o script no R1-FRONT-01
npx playwright test tests/e2e/grades         # smoke das duas páginas nas duas rotas
git grep -n "enrollment/gradesRelease\|enrollment/reportCard\|legacy/gradesRelease\|legacy/reportCard" -- app themes js
# esperado: sem resultados
```
Teste manual: salvar com um lançamento bloqueado (403) e conferir a mensagem. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

---
id: R1-FRONT-01
title: "Playwright: suíte de ~25 páginas com screenshots e smoke de erros de console"
release: R1
module: plataforma
type: testes
skill: .agents/playbooks/e2e-testing.md
agents: [tag-qa, tag-plan-guardian]
status: pronto
depends_on: []
size: M
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F0, bloco Playwright) e seção 8.5"
write_scope:
  - tests/e2e/**
  - scripts/e2e-seed.sh
  - .github/workflows/e2e.yml
read_only:
  - tests/acceptance/**
  - tests/robots/**
  - tests/providers/CustomProvider.php
  - instance.php
  - docker-compose.yml
  - themes/default/views/layouts/**
  - .agents/rules/e2e-testing.md
forbidden:
  - app/**
  - themes/**
  - composer.json
  - package.json
  - docker-compose.yml
  - Dockerfile
acceptance:
  - "cd tests/e2e && npx playwright test --list   (lista ~25 páginas nos projetos smoke e visual)"
  - "npx playwright test (em tests/e2e), contra a stack local, com URL, credenciais e aprovação pedidas ao usuário: smoke e visual passam e os snapshots-base ficam commitados"
  - "docker run --rm -v \"$PWD:/repo\" -w /repo rhysd/actionlint:latest   (e2e.yml sem erro)"
---

# R1-FRONT-01: Playwright: suíte de ~25 páginas com screenshots e smoke de erros de console

## Contexto
É a **rede de segurança visual** das limpezas de front (R1-FRONT-03 a 05) e, mais adiante, do bloqueio de acesso e do CSRF (R2/R3). O plano (F0) pede Playwright rodando contra o docker-compose, com tenant demo semeado de um dump fixo, `toHaveScreenshot` mascarando datas e contadores e smoke que falha em qualquer `pageerror` ou `console.error`. Os 14 Cests do Codeception continuam até serem portados; a meta é um único framework de E2E.

Fatos:
- Hoje não há Playwright nem Node no projeto. O `package.json` da raiz só tem `selenium-standalone ^9.5.0`; a F1 vai usá-lo para o Vite. **Decisão deste card:** o Playwright vive isolado em `tests/e2e/` com `package.json` e lockfile próprios (e o `package.json` da raiz fica intocado). Registre a decisão no Handoff.
- `tests/acceptance.suite.yml` usa Chrome 1280x960 em `http://localhost/`; reaproveite o tamanho de janela. O `InstructorHistoryCest` lê `TAG_TEST_USER` e `TAG_TEST_PASSWORD`: use os mesmos nomes de variável.
- `instance.php` mapeia `localhost` para o tenant `demo.tag.ong.br` (banco do compose). O fuso é `America/Sao_Paulo` (`config.php`). A home e vários formulários usam o ano corrente (`SiteController` ≈L117), então o seed precisa fixar o ano letivo.
- O `layouts/fullmenu.php` carrega o Google Tag (`googletagmanager.com`) e o Sentry (`websupport/yii-sentry` injeta o Raven de `cdn.ravenjs.com`): bloqueie esses domínios nos testes para o smoke ser determinístico e offline.
- O `themes/default/js/common.js` (≈L222) chama `Raven.captureMessage` sem checar a existência de `Raven`; com o CDN bloqueado, um erro AJAX gera `ReferenceError`. Isso é um bug conhecido, tratado no R1-FRONT-05; o smoke deve **registrar** o caso, e o allowlist de ruído precisa ser explícito.
- Rotas reais (conferidas por `function action...` nos controllers): `site/login`, `site/index`, `student/student/index|create|update`, `instructor/default/index|create`, `school/school/index|update`, `classroom/default/index|create`, `grades/default/grades` e `grades/entry/grades`, `classdiary/default/index`, `classdiary/classes/frequency`, `courseplan/courseplan/index|create`, `macete/lessonsplan/index`, `foods/foodmenu/index`, `inventory/item/index`, `timesheet/timesheet/index`, `calendar/default/index`, `notifications/notifications/index`, `systemadmin/default/index`, `resultsmanagement/default/index`, `schoolreport/default/select`, `schoolreport/reports/index`, `enrollmentonline/default/index`. Os relatórios `reports` (layout `reports.php`, por exemplo as actions de `schoolreport/forms`) e `reportsclean` (`foods/reports`, `aeerecord/reports`) precisam de parâmetros: **confirmar** as URLs e os ids de dados no seed.
- Regra do repositório (`.agents/rules/e2e-testing.md`): rodar localmente exige pedir antes URL base, credenciais, dados necessários e aprovação do plano de testes.

## Objetivo
Uma suíte Playwright com cerca de 25 páginas, em dois projetos (`smoke`: sem erro de JS; `visual`: screenshots comparados), um seed reproduzível e uma stack descartável no CI.

## Passos
1. Crie `tests/e2e/` com `package.json`, `playwright.config.ts` e lockfile. Fixe a versão do Playwright. Config: viewport 1280x960, `locale: pt-BR`, `timezoneId: America/Sao_Paulo`, `animations: disabled`, `baseURL` de `TAG_BASE_URL`, e dois projetos (`smoke`, `visual`). Snapshots gerados e comparados na imagem oficial `mcr.microsoft.com/playwright` (Linux), para não variar por máquina.
2. **Login por `storageState`:** um `global-setup` autentica com `TAG_TEST_USER`/`TAG_TEST_PASSWORD` e escolhe escola e ano fixos; sem as variáveis, a suíte falha com mensagem clara.
3. **Seed:** `scripts/e2e-seed.sh` carrega um dump **fixo e anonimizado** (`tests/e2e/seed/`) no tenant `demo.tag.ong.br`. **Confirmar com o time** a origem e quem anonimiza o dump: nunca versionar dado real de aluno, professor ou responsável (use o Faker pt_BR e o `CustomProvider` se for preciso gerar massa).
4. **As ~25 páginas:** login; home; lista e formulário de aluno; lista e formulário de professor; formulário de escola; lista e formulário de turma; notas; frequência; diário de classe; plano de curso; MACETE; cardápio; almoxarifado; quadro de horários; calendário; um relatório `reports`; um relatório `reportsclean`; notificações; systemadmin; e as ilhas `resultsmanagement`, `schoolreport` e `enrollmentonline`. Uma página por teste, em arquivo por módulo.
5. **Smoke:** cada página registra `pageerror` e `console.error` e falha se houver; bloqueie `googletagmanager.com` e `cdn.ravenjs.com` e documente o allowlist (ruído conhecido) em um único arquivo.
6. **Visual:** `toHaveScreenshot` por página, com `mask` nos elementos de data, hora e contadores (gráficos da home, "Bem vindo", logs) e `maxDiffPixelRatio` baixo e explícito. Commite os snapshots-base (`--update-snapshots` uma vez, revisados por uma pessoa).
7. **CI:** `.github/workflows/e2e.yml` sobe uma stack descartável (`docker compose up -d tag mysql`, seed, Playwright na imagem oficial) e publica relatório e traces como artefato; roda em `pull_request` que toque em `themes/**`, `css/**`, `js/**`, `sass/**` ou `app/modules/**/views/**`. O R1-CI-01 pode incorporá-lo depois.
8. Rode localmente, **pedindo antes URL, credenciais e aprovação**, e registre passou/falhou por página.

## Fora de escopo
- Portar ou apagar os Cests do Codeception (R1-TEST-02 só os estabiliza).
- Corrigir qualquer erro de JS ou de CSS que o smoke achar: só registrar (R1-FRONT-05 e os cards de módulo).
- Vitest e testes de unidade de JS (F1).
- Alterar `docker-compose.yml`, `Dockerfile` ou arquivos de `app/` e `themes/`.

## Critérios de aceite
- [ ] A suíte cobre ≥ 25 páginas, nos projetos `smoke` e `visual`, com login por `storageState`.
- [ ] O smoke falha em `pageerror` e `console.error`, com allowlist explícito e documentado.
- [ ] Os screenshots mascaram datas e contadores, os snapshots-base estão versionados e geram diferença zero em duas execuções seguidas.
- [ ] O seed é reproduzível e anonimizado; nenhuma credencial está no repositório.
- [ ] O workflow `e2e.yml` roda numa stack descartável.
- [ ] **O sistema não muda:** nenhum arquivo de `app/` ou `themes/` foi alterado.

## Como verificar
```bash
cd tests/e2e && npm ci && npx playwright test --list
# execução local: peça URL base, credenciais e aprovação do plano antes (regra e2e-testing)
TAG_BASE_URL=http://localhost TAG_TEST_USER=<usuário> TAG_TEST_PASSWORD=<senha> npx playwright test
# estabilidade: rodar duas vezes seguidas, sem --update-snapshots; esperado: zero diferenças
docker run --rm -v "$PWD:/repo" -w /repo rhysd/actionlint:latest
```
Entregue no Handoff: tabela página → rota → resultado (smoke e visual) e a lista do allowlist de ruído.

## Handoff
_Preenchido por quem executar._

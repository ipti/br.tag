---
id: R8-FRONT-01
title: "Aplicar os codemods do jQuery: success/error/complete, parseJSON e size()"
release: R8
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-04, R2-FRONT-04]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F6, codemods) e seção 14 (relatório do JQMIGRATE zerado antes da F6)"
write_scope:
  - js/**
  - app/modules/*/resources/**
  - app/modules/*/views/**
  - themes/default/js/common.js
  - themes/default/js/util.js
  - frontend/**
  - tests/**
read_only:
  - themes/default/js/**/*.min.js
  - app/modules/*/resources/lib/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "npm run lint && npm run typecheck && npm test && npm run build"
  - "ESLint com as regras de depreciação do eslint-plugin-no-jquery (ajax-events, parse-json, size) sem nenhuma ocorrência"
  - "Relatório do JQMIGRATE sem avisos de .success/.error/.complete, $.parseJSON e .size()"
  - "Smoke Playwright das páginas tocadas sem pageerror nem console.error"
---

# R8-FRONT-01: Aplicar os codemods do jQuery: success/error/complete, parseJSON e size()

## Contexto
O jQuery 3 remove `jqXHR.success`, `.error` e `.complete` e o `.size()`, e marca o `$.parseJSON` como depreciado (plano, seção 7, F6). Esses três usos precisam sumir **antes** de trocar a versão (R8-FRONT-02), com o jQuery 1.12.4 + Migrate 1.4.1 do R2-FRONT-04 ainda no lugar. As contagens abaixo são de 2026-11-27, antes de R2 a R7; **confirmar:** a contagem nova, porque as ondas migram páginas para `frontend/pages/`.

- **`.success(function` e `.error(function`:** 88 ocorrências fora do jQuery UI minificado, em 33 arquivos (o plano diz 88). Maiores concentrações: `app/modules/foods/resources/**` (cerca de 27), `calendar/resources/common/js/calendar.js` (10), `farmer/resources/farmer/_initialization.js` (7) e `timesheet/resources/common/js/timesheet.js` (6). Em `themes/default/js/util.js` são 4. Só 1 é `.error(` (**confirmar:** se é ajax ou o atalho de evento de imagem, que **não** muda).
- **`.complete(function`:** mais 17 ocorrências que o plano não conta: `calendar.js` (9), `timesheet.js` (5), e 1 em cada de `lunch/.../stock.js`, `js/admin/instance-config.js` e `js/classroom/form/functions.js`. Também quebram no jQuery 3.
- **`$.parseJSON`:** 21 ocorrências em 19 arquivos, entre eles `js/classroom/form/{functions,_initialization}.js`, 7 de `js/reports/*/_initialization.js`, `js/site/index.js`, `themes/default/js/util.js`, `calendar.js`, `classdiary/resources/legacy/classes/class-contents/_initialization.js`, 4 de `resultsmanagement/.../js/` (frequency e performance), `timesheet/.../instructors.js` e `school/views/school/_form.php`.
- **`.size()`:** 7 ocorrências: 6 em `themes/default/js/common.js` (≈L69, L70, L82, L88, L89 e L141, que a ponte legada da F3 substitui; **confirmar:** onde estão depois dela) e 1 em `schoolreport/resources/common/js/grades.js` (≈L25). O `jquery.qrcode.min.js` tem 2 trechos `.size(`, que são tratados no R8-FRONT-02.
- **Equivalências (mesmos argumentos):** `.success(fn)` → `.done(fn)`; `.error(fn)` → `.fail(fn)`; `.complete(fn)` → `.always(fn)`; `.size()` → `.length`.
- **Atenção ao `parseJSON`:** o `$.parseJSON` do jQuery 1.x devolve `null` para texto vazio ou não string; o `JSON.parse` **lança exceção**. Cada troca precisa de teste com resposta vazia e de uma proteção explícita onde o código dependia do `null`.
- Ferramentas: o ESLint de `frontend/` e do legado com `eslint-plugin-no-jquery` (plano, F1) e o relatório do JQMIGRATE (R3-FRONT-04; **confirmar:** o nome do script e onde o relatório é gerado).

## Objetivo
Zero uso de `.success`/`.error`/`.complete` em chamadas ajax, de `$.parseJSON` e de `.size()` no código próprio, com comportamento idêntico.

## Passos
1. Gere a lista atual de ocorrências com o ESLint (regras de depreciação do `no-jquery`) e compare com os números acima. Registre no Handoff os arquivos que já saíram.
2. Aplique as trocas em lotes por pasta (um PR por módulo ou grupo de `js/<área>/`), de preferência com autofix das regras que o tiverem (**confirmar:** quais regras do `no-jquery` corrigem sozinhas) e revisão manual do resto.
3. Em cada `parseJSON`, escreva um teste Vitest ou de caracterização para a resposta vazia, e proteja os casos que dependiam do `null`.
4. Em `.error`, confirme que é `jqXHR` antes de trocar; não mexa em atalhos de evento.
5. Rode o smoke Playwright das páginas de cada lote e o relatório do JQMIGRATE; os avisos dessas três APIs devem sumir.
6. Remova da baseline do ESLint as supressões que o lote zerou, para a contagem só poder cair.

## Fora de escopo
- Trocar a versão do jQuery, do Migrate ou do jQuery UI (R8-FRONT-02).
- Editar arquivos `*.min.js` e bibliotecas de terceiros.
- Corrigir outras APIs depreciadas além das três (`.live`, `.bind` e afins têm card próprio, se aparecerem no relatório).

## Critérios de aceite
- [ ] `git grep -nE "\.(success|error|complete)\(\s*function"` não retorna nenhuma chamada ajax no código próprio.
- [ ] `git grep -n "parseJSON"` e `git grep -n "\.size()"` não retornam nada no código próprio (fora de `*.min.js`).
- [ ] O ESLint passa sem as supressões dessas regras.
- [ ] O relatório do JQMIGRATE não lista mais as três famílias.
- [ ] Os casos de resposta vazia têm teste.

## Como verificar
```bash
npm run lint && npm run typecheck && npm test && npm run build
git grep -nE "\.(success|error|complete)\(\s*function" -- js app/modules themes/default/js ':!*.min.js'
git grep -n "parseJSON" -- js app/modules themes/default/js ':!*.min.js'
git grep -n "\.size()" -- js app/modules themes/default/js ':!*.min.js'
```
Os três `git grep` devem voltar vazios. O smoke e o JQMIGRATE rodam no navegador e seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

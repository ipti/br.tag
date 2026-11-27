---
id: R2-FRONT-02
title: "Instalar ESLint, Prettier, checagem de tipos, Vitest e o workflow de CI do front"
release: R2
module: plataforma
type: frontend
skill: tag-task-runner
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R2-FRONT-01]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F1, Qualidade e CI)"
write_scope:
  - package.json
  - package-lock.json
  - eslint.config.js
  - eslint-suppressions.json
  - .prettierrc.json
  - .prettierignore
  - tsconfig.json
  - vitest.config.js
  - frontend/types/**
  - frontend/test/**
  - scripts/entries-check.mjs
  - .github/workflows/frontend.yml
  - .github/workflows/sonarcloud-analysis.yml
read_only:
  - .editorconfig
  - js/**
  - themes/default/js/**
  - app/modules/**/resources/**
  - scripts/frontend-metrics
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - js/**
  - themes/default/js/**
  - app/modules/**/resources/**
acceptance:
  - "npm run lint"
  - "npm run typecheck"
  - "npm test"
  - "npm run build"
  - "npm run entries:check"
  - "O workflow frontend.yml passa num PR de teste e falha quando se acrescenta um alert( ou um ?r= em frontend/"
---

# R2-FRONT-02: Instalar ESLint, Prettier, checagem de tipos, Vitest e o workflow de CI do front

## Contexto
Este é um **card do coordenador**: cria um workflow em `.github/workflows/` e mexe no `sonarcloud-analysis.yml`, que têm dono. Ele existe para que o código novo em `frontend/` nasça com os controles da seção F1 e para que o legado não piore.

Fatos verificados:
- O `.editorconfig` define `indent_size = 4` para `**.js`. O Prettier lê o `.editorconfig`, mas confirme o `tabWidth` no resultado.
- O JS legado está em `js/**`, `themes/default/js/{common,util}.js` e `app/modules/*/resources/**` (com cópias de bibliotecas em `lib/` e `*.min.js`, que não são lintadas). O inventário do README do backlog (coluna "JS") mostra dezenas de arquivos por módulo. O `common.js` usa `$` e `Raven` como globais (≈L222).
- O plano fixa o jQuery do front em 1.12.4 (seção F2), e os plugins são select2 3.4.5, jquery.mask, DataTables e o modal do Bootstrap 2.
- `.github/workflows/` tem hoje `docker-build-push.yml` e `sonarcloud-analysis.yml`. O `docker-build-push.yml` não espera nenhuma verificação.
- A catraca é o `scripts/frontend-metrics`, do R1 (README do backlog, "Catraca"). **Confirmar** nome e forma de invocação no card do R1 antes de chamá-la no CI.
- `frontend/pages/**` e `entries/*` ainda não têm arquivos; os comandos precisam passar com a pasta (quase) vazia.

## Objetivo
`npm run lint`, `typecheck`, `test`, `build` e `entries:check` rodam local e no CI, barram os padrões proibidos no código novo e congelam as violações do legado.

## Passos
1. **ESLint flat** (`eslint.config.js`) em `frontend/`:
   - `no-alert`;
   - `$` e `jQuery` globais proibidos (é preciso importar `jquery`);
   - `?r=` escrito à mão proibido (`no-restricted-syntax` em `Literal` e `TemplateElement`);
   - atribuição a `window.*` proibida fora de `frontend/legacy-bridge/**`;
   - `eslint-plugin-no-unsanitized` para `innerHTML` e afins, mais uma regra `no-restricted-syntax` para `.html(`, `.append(`, `.prepend(`, `.after(` e `.before(` com argumento que não seja literal nem o template `html`. **Confirmar:** que o `no-unsanitized` não cobre chamadas do jQuery; é por isso da regra extra;
   - `eslint-plugin-no-jquery`, com o preset de APIs depreciadas da versão instalada (confirmar o nome).
2. **ESLint no legado** com **bulk suppressions** (`eslint --suppress-all` grava `eslint-suppressions.json`; exige ESLint ≥ 9.24, **confirmar**). Escopo: `js/**`, `themes/default/js/{common,util}.js` e `app/modules/*/resources/**`, sem `lib/` nem `*.min.js`. As violações de hoje ficam congeladas, e as novas falham o CI. O legado **não** é reformatado.
3. **Prettier** só em `frontend/` (`tabWidth: 4`, igual ao `.editorconfig`), com `.prettierignore` para `build/` e `node_modules/`.
4. **Tipos:** `tsconfig.json` com `checkJs`, `strict` e `noEmit`, incluindo `frontend/**/*.js`. `frontend/types/plugins.d.ts` descreve `select2` (3.x, com chamada por string), `mask`, `DataTable`/`dataTable` e o plugin `modal`, `tooltip` e `collapse` do Bootstrap 2. JSDoc obrigatório nas funções exportadas (regra do ESLint).
5. **Vitest + jsdom** (`vitest.config.js`): o `setup` carrega o jQuery **1.12.4** (devDependency fixada, só para teste) e expõe `window.jQuery`. Cobertura em lcov (`coverage/lcov.info`). Um teste de exemplo em `frontend/test/` que mostra o padrão.
6. **`entries:check`** (`scripts/entries-check.mjs`): procura nos `.php` de `app/` e `themes/` as chamadas literais `TFrontend::registerEntry('...')` e `registerStyle('...')` e confere se cada nome existe no `build/.vite/manifest.json`. Sai com erro listando as que faltam.
7. **CI** (`.github/workflows/frontend.yml`): Node LTS fixa, `npm ci`, lint, typecheck, test, build, catraca e `entries:check`, em PR e no push para `main`. Acrescente o lcov ao Sonar no `sonarcloud-analysis.yml` (`sonar.javascript.lcov.reportPaths`); **confirmar** onde o Sonar lê as propriedades hoje.
8. Para provar que os controles pegam, abra um PR de teste (ou rode local) com `alert('x')`, `$('.a')` sem import e `'?r=site/login'` em `frontend/`: o lint deve falhar nos três.

## Fora de escopo
- Reformatar ou corrigir o JS legado.
- Migrar qualquer página para `frontend/` (R3 em diante).
- A ponte legada, o núcleo (`core/http.js`) e a catraca em si (R1).
- Tornar o workflow obrigatório na proteção de branch (decisão do time).

## Critérios de aceite
- [ ] `npm run lint`, `typecheck`, `test`, `build` e `entries:check` passam com `frontend/` quase vazio.
- [ ] Um `alert(`, um `$`/`jQuery` global e um `?r=` em `frontend/` falham o lint.
- [ ] O legado passa o lint com as violações congeladas, e uma violação **nova** nele falha.
- [ ] O `tsconfig.json` roda com `checkJs` e `strict`, com os tipos dos plugins.
- [ ] O Vitest gera `coverage/lcov.info`, e o Sonar o recebe.
- [ ] O workflow `frontend.yml` roda no PR e executa a catraca.

## Como verificar
```bash
npm ci && npm run lint && npm run typecheck && npm test && npm run build && npm run entries:check
# entries:check falha quando uma view chama TFrontend::registerEntry('pages/nao-existe.js'):
echo "<?php TFrontend::registerEntry('pages/nao-existe.js');" > /tmp/x.php   # numa cópia de teste de app/
# lint barra o proibido:
printf "alert('x');\n\$('.a');\nconst u = '?r=site/login';\n" > frontend/pages/tmp.js && npm run lint   # esperado: 3 erros; apague o arquivo
```
- Confirmar: se o `eslint-suppressions.json` fica estável entre máquinas (caminhos relativos e ordem). Se oscilar, registre.
- Confirmar: a versão do jQuery para o setup do Vitest (1.12.4) com o card R2-FRONT-04.

## Handoff
_Preenchido por quem executar._

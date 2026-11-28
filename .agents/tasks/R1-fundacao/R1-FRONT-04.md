---
id: R1-FRONT-04
title: "Remover CSS, JS e bibliotecas órfãs depois de provar que não têm referência"
release: R1
module: plataforma
type: css
skill: .agents/playbooks/ui-refactor.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-FRONT-01]
size: P
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F0, bloco Limpeza: CSS órfão, bibliotecas órfãs e JS órfão)"
write_scope:
  - css/scss/main.css
  - sass/css/main.map
  - themes/default/css/template.min.css
  - themes/default/css/select2-bootstrap.css
  - themes/default/css/jquery.dataTables.min.css
  - themes/default/css/dataTables.fontAwesome.css
  - themes/default/js/jquery/jquery.dataTables.min.js
  - themes/default/js/jquery/fullcalendar/fullcalendar.js
  - themes/default/js/jquery/fullcalendar/gcal.js
  - app/modules/schoolreport/resources/common/css/style.css
  - app/modules/lunch/resources/lib/semantic/**
  - app/modules/calendar/resources/lib/jquery/**
  - app/modules/curricularmatrix/resources/lib/jquery/**
  - app/modules/lunch/resources/lib/jquery/**
  - app/modules/quiz/resources/lib/jquery/**
  - app/modules/timesheet/resources/lib/jquery/**
  - js/courseplan/form/**
  - js/datatables/config.js
  - js/reports/DisciplineAndInstructorRelationReport/_initialization.js
  - js/reports/ResultBoardReport/_initialization.js
  - js/reports/StatisticalDataReport/_initialization.js
  - js/reports/StudentsUsingSchoolTransportationRelationReport/_initialization.js
read_only:
  - themes/default/views/layouts/**
  - app/modules/courseplan/views/courseplan/_form.php
  - app/modules/resultsmanagement/resources/common/css/style.css
  - app/gii/**
  - tests/e2e/**
forbidden:
  - sass/scss/**
  - sass/css/main.css
  - themes/default/css/template.css
  - themes/default/js/datatables.min.js
  - themes/default/js/jquery/fullcalendar/fullcalendar.min.js
  - composer.json
  - .github/**
acceptance:
  - "Para cada arquivo removido: grep por nome e por caminho em app, themes, js, sass, css e index.php sem referência viva (tabela no Handoff)"
  - "git ls-files css/scss/main.css sass/css/main.map themes/default/css/template.min.css   (sem resultados)"
  - "cd tests/e2e && npx playwright test   (smoke e visual do R1-FRONT-01 sem diferença nova, contra a stack local, com URL, credenciais e aprovação pedidas ao usuário)"
  - "composer lint"
---

# R1-FRONT-04: Remover CSS, JS e bibliotecas órfãs depois de provar que não têm referência

## Contexto
**Card com revisão humana obrigatória** (remove arquivos). Só depois do Playwright do R1-FRONT-01. Cada remoção exige grep prévio por **nome e caminho**, incluindo `$.getScript`, `registerScriptFile`/`registerCssFile` e caminhos montados por concatenação (plano, seção 7).

Levantamento em 2026-11-27 (grep fora de `vendor/`, `docs/` e `.agents/`):
- **CSS compilado órfão:** `css/scss/main.css` (43,5 KB) e `sass/css/main.map` (71,9 KB; o `composer sass:build` usa `--no-source-map`): nenhuma referência.
- **CSS do tema sem referência:** `themes/default/css/template.min.css` (126,8 KB; o `fullmenu.php` registra `template.css`, ≈L79) e `select2-bootstrap.css`. `jquery.dataTables.min.css` e `dataTables.fontAwesome.css` só aparecem em **linhas comentadas** de `app/modules/courseplan/views/courseplan/_form.php` (≈L17–18); comentário não conta como referência e **não** é editado aqui.
- **Cópia do schoolreport:** `app/modules/schoolreport/resources/common/css/style.css` (165,2 KB). O layout `schoolreport.php` carrega `common/css/layout.css` e o `login.php` carrega `login.css` (não o `style.css`). O único `common/css/style.css` referenciado é o do layout `resultsmanagement.php` (≈L34), que usa o arquivo **do módulo resultsmanagement** (165,3 KB). Os dois arquivos **não são idênticos** (`cmp` acusa diferença): confirmar que a diferença não é usada antes de apagar.
- **Semantic UI do lunch:** `app/modules/lunch/resources/lib/semantic/` não é citado por nenhuma view ou JS do módulo; os outros resultados do grep estão em `app/gii/**` (templates de gerador de código, que ficam).
- **Cinco cópias do jQuery 1.11.3:** `app/modules/{calendar,curricularmatrix,lunch,quiz,timesheet}/resources/lib/jquery/jquery-1.11.3.min.js` são byte a byte iguais entre si. A única referência a `jquery-1.11.3` está em `app/gii/.../views/layouts/error.php` (template do Gii).
- **DataTables 1.10.7 do tema:** `themes/default/js/jquery/jquery.dataTables.min.js` (cabeçalho `DataTables 1.10.7`) só tem referência comentada no mesmo `_form.php` do courseplan (≈L16). O `fullmenu.php` (≈L314) carrega `themes/default/js/datatables.min.js`, outro arquivo, que **fica**.
- **FullCalendar:** só o `fullcalendar.min.js` é referenciado (`fullmenu.php` ≈L310 e `reports.php` ≈L84), junto de `fullcalendar.css` e `fullcalendar.print.css`. `fullcalendar.js` (121 KB) e `gcal.js` não têm referência.
- **JS órfão:** `js/courseplan/form/` (`_initialization.js`, `functions.js`, `pagination.js` e `validations.js`) e `js/datatables/config.js` (5,1 KB) sem referência. Em `js/reports/` há 34 arquivos `_initialization.js`; quatro não são citados por nenhuma view: `DisciplineAndInstructorRelationReport`, `ResultBoardReport`, `StatisticalDataReport` e `StudentsUsingSchoolTransportationRelationReport`. As views carregam esses arquivos com `registerScriptFile($baseUrl . '/js/reports/<Relatório>/_initialization.js')`.
- **`js/datatables/init.js`** é carregado pelo `fullmenu.php` (≈L313) e **fica**; este card só remove o `config.js`, que nenhum arquivo cita.

## Objetivo
Os arquivos acima saem do repositório, com a prova de que ninguém os usa, e nenhuma tela muda.

## Passos
1. Para cada arquivo da lista, rode o grep por nome e por caminho completo (e por trechos que o código possa montar, como `'/css/' . $nome`, `'/lib/' . $x` ou `glob`) em `app`, `themes`, `js`, `sass`, `css` e `index.php`, e registre numa tabela do Handoff: arquivo, comando, resultado. Use `codegraph explore "<nome do arquivo>"` para achar chamadores indiretos.
2. Trate à parte o que depende de decisão: a diferença entre as duas cópias de `style.css`. Em dúvida, **não remova** e registre.
3. Remova os arquivos com `git rm`, em commits separados por grupo (CSS compilado, CSS do tema, cópias de jQuery e Semantic UI, JS órfão), para facilitar o revert de um grupo só.
4. Rode o Playwright do R1-FRONT-01 e confira o console do navegador (404 de CSS ou JS): diferença nova é bloqueante.
5. Se o R1-FRONT-05 rodar em paralelo, ele não deve consolidar os `_initialization.js` removidos aqui.

## Fora de escopo
- Referências comentadas (`courseplan/_form.php`): apenas registrar.
- `sass/css/main.css` (é a saída do design system), `themes/default/css/template.css`, `themes/default/js/datatables.min.js` e `fullcalendar.min.js` (em uso).
- Duplicados de JS idênticos e `main.scss` (R1-FRONT-05); CSS Blueprint (R1-FRONT-03).
- Atualizar bibliotecas, unificar jQuery (R2) e tocar em `app/gii/**` (templates de gerador).

## Critérios de aceite
- [ ] Todos os arquivos da lista com prova de órfão foram removidos; os que ficaram têm motivo no Handoff.
- [ ] A tabela de grep do Handoff cobre cada arquivo removido, incluindo caminhos montados por concatenação.
- [ ] Nada em `app/gii/**`, `sass/scss/**` ou nos arquivos em uso foi alterado.
- [ ] **O sistema não muda:** o Playwright (smoke e visual) não mostra diferença nova e o console não tem 404 de recurso.

## Como verificar
```bash
git ls-files css/scss/main.css sass/css/main.map themes/default/css/template.min.css app/modules/lunch/resources/lib/semantic | head   # esperado: vazio
for n in template.min.css select2-bootstrap jquery.dataTables.min.css dataTables.fontAwesome "css/scss/main" jquery-1.11.3 "lib/semantic" "courseplan/form/" "datatables/config"; do
  echo "== $n"; git grep -n -e "$n" -- app themes js sass css index.php | grep -v "^app/gii" | head -3
done
git status --short | grep -v "^D "   # esperado: só o card (status/Handoff)
# Playwright (peça URL, credenciais e aprovação, conforme .agents/rules/e2e-testing.md)
cd tests/e2e && TAG_BASE_URL=http://localhost TAG_TEST_USER=<usuário> TAG_TEST_PASSWORD=<senha> npx playwright test
```

## Handoff
_Preenchido por quem executar._

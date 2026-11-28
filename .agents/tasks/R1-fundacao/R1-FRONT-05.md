---
id: R1-FRONT-05
title: "main.scss e JS duplicados: imports repetidos, parciais vazios, views com main.css em dobro e arquivos idênticos"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F0, blocos main.scss, Arquivos JS duplicados e Ajustes pontuais)"
write_scope:
  - sass/scss/main.scss
  - sass/scss/_filter-bar.scss
  - sass/scss/_sidebar.scss
  - sass/scss/components/_field-multiselect.scss
  - sass/css/main.css
  - app/modules/systemadmin/views/default/instanceConfig.php
  - app/modules/systemadmin/views/default/manageModules.php
  - app/modules/aeerecord/views/reports/aeeRecordReport.php
  - app/modules/foods/views/reports/FoodMenuReport.php
  - app/modules/foods/views/reports/ShoppingListReport.php
  - app/modules/grades/views/entry/gradesRelease.php
  - app/modules/grades/views/entry/reportCard.php
  - js/enrollment/*/functions.js
  - app/modules/grades/resources/legacy/*/functions.js
  - js/reports/*/_initialization.js
  - app/modules/schoolreport/views/reports/**
  - app/modules/*/resources/lib/js/jquery.select2.pt-br.js
  - themes/default/js/common.js
read_only:
  - themes/default/views/layouts/**
  - themes/default/js/jquery-ba-bbq.js
forbidden:
  - composer.json
  - themes/default/views/layouts/**
acceptance:
  - "composer sass:build   (gera sass/css/main.css e o diff só tira blocos repetidos)"
  - "Comparação dos seletores compilados antes e depois (expanded): o último bloco de cada seletor é idêntico (script no Handoff)"
  - "git ls-files sass/scss/_filter-bar.scss sass/scss/_sidebar.scss   (sem resultados)"
  - "cd tests/e2e && npx playwright test   (smoke e visual do R1-FRONT-01 sem diferença nova, contra a stack local, com URL, credenciais e aprovação pedidas ao usuário)"
  - "composer lint"
---

# R1-FRONT-05: main.scss e JS duplicados: imports repetidos, parciais vazios, views com main.css em dobro e arquivos idênticos

## Contexto
**Card do coordenador e com revisão humana obrigatória:** `sass/scss/main.scss` é arquivo com dono e o card remove arquivos. Só depois do Playwright do R1-FRONT-01. A regra do repositório é rodar `composer run sass:build` depois de editar `.scss`.

Fatos conferidos:
- **`sass/scss/main.scss` (46 linhas):** `_accordeon` é importado duas vezes (L23 e L35); `reports.scss` (L30) e `_reports` (L32) apontam para o **mesmo** parcial `_reports.scss` (185 B); `components/_field-multiselect.scss` começa com `@import './field-select2'` (L1), que já foi importado em `main.scss` (L9, `components/field-select2.scss`). Como o Sass não deduplica `@import`, o CSS sai repetido. A regra do plano é manter a **última** ocorrência (a que vale na cascata) e comparar os seletores compilados antes e depois.
- **Parciais de 0 B:** `_filter-bar.scss` e `_sidebar.scss`. O `main.scss` importa `_filter_bar` (underscore, 353 B, L24): não confundir.
- **Saída compilada:** `sass/css/main.css` (66,6 KB, uma linha só) é **versionado** e gerado por `composer sass:build` (`docker compose run ... sass /sass/main.scss:/css/main.css --no-source-map --style=compressed`).
- **`main.css` registrado duas vezes:** o `fullmenu.php` (≈L84) registra `sass/css/main.css?v=TAG_VERSION` e o `reportsclean.php` (≈L22) também; as views abaixo registram a mesma folha **sem** a query `?v=`, e como a URL é diferente o navegador carrega as duas. Elas rodam nesses layouts: `systemadmin` (módulo com layout `fullmenu`) `instanceConfig.php` e `manageModules.php` (≈L15), `aeerecord/views/reports/aeeRecordReport.php`, `foods/views/reports/FoodMenuReport.php` e `ShoppingListReport.php` (≈L4; os controllers de relatório usam `reportsclean`). **Não remover:** `schoolreport/views/forms/StudentIMCReport.php` e `StudentIMCHistoryReport.php` (≈L8) usam o layout `reports`, que não registra o `main.css`; `site/login.php` e o layout `enrollmentonline` também precisam do seu registro.
- **JS idêntico (6 grupos, conferidos por hash, fora o jQuery do R1-FRONT-04):**
  1. `js/reports/{IncompatibleStudentAgeByClassroomReport, SchoolProfessionalNumberByClassroomReport, StudentInstructorNumbersRelationReport, StudentsByClassroomReport, StudentsInAlphabeticalOrderRelationReport, StudentsUsingSchoolTransportationRelationReport}/_initialization.js` (6 arquivos);
  2. `functions.js` de `gradesRelease` e `reportCard`, nas duas árvores: `app/modules/grades/resources/legacy/` e `js/enrollment/` (4 arquivos iguais);
  3. `js/reports/{NumberStudentsPerClassroomReport, OutOfTownStudentsReport, ResultBoardReport}/_initialization.js`;
  4. `js/reports/{ComplementarActivityAssistantByClassroomReport, DisciplineAndInstructorRelationReport, EnrollmentComparativeAnalysisReport}/_initialization.js`;
  5. `js/reports/{EnrollmentNotification, TransferForm}/_initialization.js`;
  6. `jquery.select2.pt-br.js` em `app/modules/resultsmanagement/resources/lib/js/` e `app/modules/schoolreport/resources/lib/js/` (difere do `themes/default/js/select2-locale-pt-BR.js`).
- **Como os arquivos são carregados:** os relatórios fazem `registerScriptFile($baseUrl . '/js/reports/<Nome>/_initialization.js')`; as views `default/gradesRelease.php` e `default/reportCard.php` usam `$this->module->baseScriptUrl . '/legacy/...'`, e as `entry/*.php` usam `Yii::app()->baseUrl . '/js/enrollment/...'`. Quatro `_initialization.js` dos grupos 1, 3 e 4 (`StudentsUsingSchoolTransportationRelationReport`, `ResultBoardReport`, `DisciplineAndInstructorRelationReport`) não têm view que os cite e são removidos no R1-FRONT-04: se aquele card já estiver na `main`, recalcule os grupos; senão, não consolide esses arquivos.
- **`Raven` sem guarda:** `themes/default/js/common.js` (≈L222) chama `Raven.captureMessage(...)` no tratador de erro AJAX, mas o `Raven` só existe se o CDN do `websupport/yii-sentry` (`Client.php` ≈L60, `cdn.ravenjs.com`) carregou; senão vira `ReferenceError`.
- **BBQ:** `themes/default/js/jquery-ba-bbq.js` (41,5 KB) é carregado por `fullmenu.php` (≈L102) e `reports.php` (≈L9); o `reports.php` ainda desliga o `jquery.ba-bbq.js` do Yii no `scriptMap` (≈L5). O grep por `$.bbq`, `$.deparam`, `hashchange`, `CGridView` e `CListView` não achou uso fora da biblioteca, mas remover o carregamento exige editar **layouts** (`forbidden` aqui).

## Objetivo
`main.scss` sem repetição nem parciais vazios, views sem o segundo registro do `main.css`, cada grupo de JS idêntico com uma cópia só e o tratador de erro AJAX sem `ReferenceError`, sem diferença visual.

## Passos
1. **Baseline:** compile o estado atual em modo expandido (`docker compose run --rm --entrypoint /opt/dart-sass/sass sass /sass/main.scss:/css/_antes.css --no-source-map --style=expanded`; apague o temporário depois) e rode o Playwright.
2. **`main.scss`:** remova a primeira ocorrência de `_accordeon` (L23), mantenha a última (L35); remova `@import 'reports.scss'` (L30) e mantenha `_reports` (L32); como o `_field-multiselect.scss` reimporta `field-select2`, remova o import de L9 do `main.scss` (a última ocorrência passa a ser a de dentro do multiselect). **Antes de apagar o bloco antigo, confirme que ele é idêntico ao da última ocorrência**: se a primeira cópia tiver uma declaração ausente na última, apagá-la mudaria o resultado; nesse caso **não** remova e registre.
3. `git rm` de `_filter-bar.scss` e `_sidebar.scss` (0 B), após grep confirmando que ninguém os importa.
4. Rode `composer sass:build`, compile de novo em modo expandido (`_depois.css`) e **compare os seletores**: para cada seletor, o último bloco precisa ser idêntico antes e depois; só os blocos repetidos podem sumir. Registre o script usado e o resultado no Handoff, e commite o novo `sass/css/main.css`.
5. **Views:** apague a linha `$cs->registerCssFile(... 'sass/css/main.css')` das cinco views do Contexto. Confirme em cada uma que o layout efetivo registra o `main.css` (a tabela de layouts do R1-FRONT-03 serve). Não toque nas views do IMC, no `login.php` nem no layout `enrollmentonline`.
6. **JS idêntico:** para cada grupo, escolha o arquivo canônico e aponte os demais `registerScriptFile` para ele; depois apague as cópias.
   - Grupos de `js/reports`: crie uma pasta compartilhada (por exemplo `js/reports/_shared/`) com um arquivo por grupo e atualize o `registerScriptFile` de cada view de `app/modules/schoolreport/views/reports/`.
   - Grupo 2: mantenha `app/modules/grades/resources/legacy/` (publicado) e faça `entry/gradesRelease.php` e `entry/reportCard.php` usarem `$this->module->baseScriptUrl`, como as views `default/`; só então apague `js/enrollment/*/functions.js` e uma das cópias duplicadas, **sem mexer** nos `_initialization.js`, que não são idênticos.
   - Grupo 6: **confirme** com uma pessoa se dois módulos (cada um publica o seu `resources/`) podem compartilhar o arquivo; se não, mantenha e registre.
7. **`common.js`:** troque `Raven.captureMessage(...)` por uma versão protegida (`window.Raven && typeof window.Raven.captureMessage === "function"`), sem mudar mais nada do tratador.
8. **BBQ:** não remova; registre o grep e a pendência (card de contrato nos layouts e no `scriptMap` do `reports.php`). Rode o Playwright (smoke e visual) e compare com o passo 1.

## Fora de escopo
- Editar layouts (`fullmenu.php`, `reports.php`, `reportsclean.php`; arquivos com dono).
- Reorganizar o SCSS ou remover regras (só repetição); remover o BBQ, unificar jQuery (R2 e R8); os órfãos (R1-FRONT-04) e o Blueprint (R1-FRONT-03).

## Critérios de aceite
- [ ] `main.scss` não repete `_accordeon`, `_reports` nem `field-select2`; `_filter-bar.scss` e `_sidebar.scss` saíram; `sass/css/main.css` foi regenerado.
- [ ] O último bloco de cada seletor é idêntico antes e depois (comparação no Handoff).
- [ ] As cinco views não registram mais o `main.css`; o IMC, o login e o layout `enrollmentonline` continuam registrando.
- [ ] Cada grupo de JS idêntico tem uma cópia só (ou o motivo de manter está no Handoff) e as views apontam para ela.
- [ ] `common.js` não gera `ReferenceError` quando `Raven` não existe (teste com `cdn.ravenjs.com` bloqueado).
- [ ] **O sistema não muda:** Playwright sem diferença nova; console sem 404.

## Como verificar
```bash
composer sass:build
git ls-files sass/scss/_filter-bar.scss sass/scss/_sidebar.scss      # esperado: vazio
git grep -n "sass/css/main.css" -- app themes                         # esperado: só layouts, login, IMC e enrollmentonline
composer lint
# Playwright (peça URL, credenciais e aprovação, conforme .agents/rules/e2e-testing.md)
cd tests/e2e && TAG_BASE_URL=http://localhost TAG_TEST_USER=<usuário> TAG_TEST_PASSWORD=<senha> npx playwright test
```
Com `cdn.ravenjs.com` bloqueado, provoque um erro AJAX e confirme que não aparece `ReferenceError: Raven is not defined`.

## Handoff
_Preenchido por quem executar._

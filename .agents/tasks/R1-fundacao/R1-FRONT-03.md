---
id: R1-FRONT-03
title: "Remover o CSS Blueprint e os layouts main, column1 e column2, e trocar o layout padrão para fullmenu"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F0, bloco Limpeza: CSS Blueprint)"
write_scope:
  - css/screen.css
  - css/ie.css
  - css/print.css
  - css/main.css
  - css/bg.gif
  - themes/default/views/layouts/main.php
  - themes/default/views/layouts/column1.php
  - themes/default/views/layouts/column2.php
  - app/components/Controller.php
  - app/modules/abilities/controllers/CourseclassabilitiesController.php
  - app/modules/aeerecord/controllers/DefaultController.php
  - app/modules/enrollmentonline/controllers/EnrollmentonlinepreenrollmenteventonlineController.php
  - app/modules/enrollmentonline/controllers/EnrollmentonlinestudentidentificationController.php
  - app/modules/foods/controllers/FoodinventoryController.php
  - app/modules/foods/controllers/FoodnoticeController.php
  - app/modules/professional/controllers/DefaultController.php
  - app/modules/student/controllers/StudentimcController.php
read_only:
  - app/modules/*/*Module.php
  - app/controllers/**
  - themes/default/views/layouts/fullmenu.php
  - css/form.css
  - app/gii/**
forbidden:
  - css/form.css
  - css/admin.css
  - css/grades.css
  - css/lunch.css
  - css/reports.css
  - css/sagres.css
  - css/sedsp.css
  - sass/**
  - composer.json
  - .github/**
acceptance:
  - "git grep -nE \"layouts[./](main|column1|column2)\\b\" -- app themes   (só sobram os templates de app/gii)"
  - "git grep -nE \"css/(screen|ie|print|main)\\.css\" -- app themes   (só devem sobrar themes/default/css/print.css e sass/css/main.css, que são outros arquivos)"
  - "cd tests/e2e && npx playwright test   (smoke e visual do R1-FRONT-01 sem diferença nova, contra a stack local, com URL, credenciais e aprovação pedidas ao usuário)"
  - "composer lint"
  - "composer analyse"
---

# R1-FRONT-03: Remover o CSS Blueprint e os layouts main, column1 e column2, e trocar o layout padrão para fullmenu

## Contexto
**Card do coordenador e com revisão humana obrigatória** (remove arquivos, mexe em layouts e em `app/components/Controller.php`). Só começa depois do Playwright do R1-FRONT-01, que é a rede visual.

Fatos conferidos:
- **Os arquivos do Blueprint** (raiz `css/`): `screen.css` (10,5 KB), `ie.css`, `print.css` e `main.css` (2,5 KB). Só o layout `themes/default/views/layouts/main.php` os carrega (≈L9–15), mais `form.css` (que **não** sai: o `fullmenu.php` ainda o registra, ≈L83).
- **Cuidado com nomes iguais:** `css/main.css` (Blueprint) **não** é o `sass/css/main.css` (design system, usado em quase todas as telas), e `css/print.css` não é o `themes/default/css/print.css` (usado em `fullmenu.php` ≈L65 e em `reports.php`). Ao procurar referências, use o caminho completo, não só o nome.
- **Os layouts:** `column1.php` e `column2.php` fazem `beginContent('//layouts/main')`; `column2.php` usa `CPortlet` e `CMenu` do `zii`. `main.php` é o modelo cru do Yii (cabeçalho "My Company").
- **Padrão atual:** `app/components/Controller.php` (≈L16–19) tem `public $layout = '//layouts/column1';`, com docblock que cita `column1`.
- **Quem troca de layout:** quase todo módulo faz `$controller->layout = 'webroot.themes.default.views.layouts.fullmenu'` em `Module::beforeControllerAction` (34 arquivos `*Module.php`; `resultsmanagement` usa o layout `resultsmanagement`; `schoolreport` usa `schoolreport` via `layoutPath`). Os controllers raiz declaram `fullmenu` (`Site`, `Administrator`, `Censo`) e `Site` usa `login` no login.
- **Exceção importante:** `app/modules/gradestructure/` tem só `controllers/` e `views/`, **sem classe `*Module.php`**, embora esteja em `main.php` (≈L121). Ele não tem o override de layout: confirmar se o módulo é alcançável e qual layout seus controllers usam de fato.
- **Referências diretas a `column2`:** oito controllers declaram `public $layout = '//layouts/column2'`: `abilities/CourseclassabilitiesController`, `aeerecord/DefaultController`, `enrollmentonline/EnrollmentonlinepreenrollmenteventonlineController` e `...studentidentificationController`, `foods/FoodinventoryController` e `FoodnoticeController`, `professional/DefaultController` e `student/StudentimcController`. Hoje o override do módulo vence, mas o texto fica pendurado. Vários outros controllers só têm o **comentário** do Gii citando `column2`.
- **O `fullmenu.php` redireciona visitante para o login** (≈L5–7). Um controller que caia no layout padrão sem override e seja aberto sem login hoje mostraria o layout Blueprint e passaria a ser redirecionado.

## Objetivo
Sem o CSS Blueprint e sem os layouts `main`, `column1` e `column2`; o padrão de `Controller::$layout` passa a ser `//layouts/fullmenu`, e o sistema se comporta como antes.

## Passos
1. **Auditoria do layout efetivo.** Para cada um dos controllers (raiz e de módulos), determine o layout que ele usa de fato: layout definido na action, depois `beforeControllerAction` do módulo, depois `$layout` do controller, depois o padrão. Use `codegraph explore "beforeControllerAction layout"` e o grep de `layout`. Monte uma tabela no Handoff e destaque qualquer controller cujo layout efetivo seja `main`, `column1` ou `column2`, e o caso do `gradestructure`.
2. Se a auditoria achar algum controller que **depende** do padrão (por exemplo um acessível a visitante), **pare**: não mude o padrão; registre no Handoff e proponha um card (a troca para `fullmenu` redirecionaria o visitante).
3. Nos oito controllers, troque `public $layout = '//layouts/column2';` pelo layout que o módulo já aplica na prática (ou remova a propriedade, se o override do módulo já garante o layout) e ajuste o docblock. Os comentários do Gii em outros controllers ficam como estão (registre).
4. `Controller.php`: `public $layout = '//layouts/fullmenu';` e docblock corrigido.
5. Faça um `grep` com caminho completo e inclua `$.getScript` e caminhos concatenados antes de apagar `css/screen.css`, `css/ie.css`, `css/print.css`, `css/main.css`. Confirme também `css/bg.gif` (usado só pelo `css/main.css`? **confirmar**) antes de removê-lo.
6. Apague `main.php`, `column1.php` e `column2.php` de `themes/default/views/layouts/`.
7. Rode o Playwright do R1-FRONT-01 (smoke e visual) e compare; qualquer diferença é bloqueante. Registre os resultados.

## Fora de escopo
- Remover os demais `css/*.css` (`form`, `admin`, `grades`, `lunch`, `reports`, `sagres`, `sedsp`): em uso ou fora deste card.
- Alterar `fullmenu.php` ou outro layout, os `*Module.php` e os comentários do Gii.
- Criar `Module` para o `gradestructure` (só registrar) e os templates de `app/gii/**`, que citam `column1`/`column2` mas são geradores.

## Critérios de aceite
- [ ] `css/screen.css`, `css/ie.css`, `css/print.css`, `css/main.css` e os layouts `main`, `column1` e `column2` não existem mais.
- [ ] `Controller::$layout` é `//layouts/fullmenu`, e os oito controllers não citam mais `column2`.
- [ ] A tabela de layout efetivo está no Handoff e nenhum controller dependia do padrão (ou a exceção foi tratada por decisão humana).
- [ ] `css/form.css`, `themes/default/css/print.css` e `sass/css/main.css` continuam em uso.
- [ ] **O sistema não muda:** o smoke e o visual do Playwright não têm diferença nova, e o console não mostra 404 de CSS.

## Como verificar
```bash
git grep -nE "layouts[./](main|column1|column2)\b" -- app themes      # esperado: só app/gii
git grep -nE "css/(screen|ie|print|main)\.css" -- app themes          # esperado: só sass/css/main.css e themes/default/css/print.css (outros arquivos); nenhuma referência a css/screen.css, css/ie.css ou aos css/print.css e css/main.css da raiz
git grep -n "layout = '//layouts" -- app/components app/modules
ls css themes/default/views/layouts
composer lint && composer analyse
# Playwright (peça URL, credenciais e aprovação, conforme .agents/rules/e2e-testing.md)
cd tests/e2e && TAG_BASE_URL=http://localhost TAG_TEST_USER=<usuário> TAG_TEST_PASSWORD=<senha> npx playwright test
```

## Handoff
_Preenchido por quem executar._

---
id: R4-FRONT-01
title: "Criar o componente datatable em frontend/components e emitir as opções por data-tag-table no DataTableGridView"
release: R4
module: plataforma
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-03]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3, components; F4, ordem sugerida, item 2)"
write_scope:
  - frontend/components/datatable.js
  - frontend/components/datatable.test.js
  - app/components/widgets/DataTableGridView.php
  - js/datatables/**
  - tests/e2e/datatable/**
read_only:
  - themes/default/js/datatablesptbr.js
  - themes/default/views/layouts/fullmenu.php
  - app/modules/student/controllers/StudentController.php
  - app/modules/*/views/**
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - app/models/**
  - .github/workflows/**
acceptance:
  - "Vitest do componente: leitura de data-tag-table, mobile, coluna de ações e o caso serverSide de aluno"
  - "Smoke Playwright das listagens de escola, turma, aluno, professor, plano de curso e estoque, sem diferença visual"
  - "window.initDatatable continua existindo e reinicializa as tabelas depois do afterAjaxUpdate"
  - "git grep -n \"action.includes\" -- js/datatables frontend/components   (sem resultados)"
---

# R4-FRONT-01: Criar o componente datatable em frontend/components e emitir as opções por data-tag-table no DataTableGridView

## Contexto
`js/datatables/init.js` (270 linhas) é carregado só pelo layout `themes/default/views/layouts/fullmenu.php` (≈L313) e inicializa todas as tabelas com a classe `js-tag-table`. O comportamento é escolhido **pela URL**, o que o torna frágil:
- **Seleção por `window.location.search` (≈L1 e ≈L143–160):** `const action = window.location.search` e uma cadeia de `action.includes("school" | "instructor" | "classroom" | "student" | "inventory" | …)` decide o índice da coluna de ações (`indexActionButtons`: `[2]`, `[3]`, `[4]` ou a última coluna) e qual ramo usar.
- **Ramo de aluno (≈L160–179):** com `student` na URL (e sem `studentimc`, `enrollmentonline` ou `studentAllergies`) usa `serverSide: true` e o `$.fn.dataTable.pipeline` (≈L9–116), chamando `?r=student/student/getstudentajax` por POST (`StudentController::actionGetStudentAjax` ≈L207).
- **Recarga do idioma (≈L131):** `$.getScript('themes/default/js/datatablesptbr.js', …)` baixa de novo o arquivo que o `fullmenu.php` já carrega (≈L315).
- **Textos de busca e ícones (≈L224–266):** um mapa `searchPlaceholders` por trecho de URL e imagens com caminho relativo `../../../themes/default/img/`.
- **API global:** `initDatatable()` é chamada por outros arquivos: `app/modules/courseplan/resources/_initialization.js` (≈L628 e ≈L643), `js/courseplan/form/_initialization.js` (≈L4), `app/modules/enrollmentonline/resources/functions.js` (≈L220), `app/modules/foods/views/foodnotice/index.php` (≈L86), `app/modules/student/resources/health/functions.js` (≈L46) e pelo `afterAjaxUpdate` do `DataTableGridView` (≈L61).

`app/components/widgets/DataTableGridView.php` é o wrapper do `CGridView` (≈L21–95): só acrescenta a classe `js-tag-table` e o `afterAjaxUpdate`. O comentário do `run()` (≈L86–94) diz que o `init.js` "cuida de tudo". Há 50 ocorrências de `js-tag-table` em 44 arquivos PHP (módulos e o widget).

`js/datatables/config.js` (125 linhas) é um rascunho da mesma lógica em forma de mapa de configurações (`pageConfigs`). **Confirmar:** nenhuma view ou layout o carrega (nenhuma referência em PHP ou JS).

## Objetivo
O comportamento de cada tabela passa a vir de um atributo `data-tag-table`, emitido pelo `DataTableGridView`, e o `init.js` vira o componente `frontend/components/datatable.js`, sem depender da URL.

## Passos
1. **Caracterize antes (tag-qa).** Smoke Playwright das listagens que hoje usam ramos diferentes: escola, turma, aluno (server-side), professor, plano de curso, matriz curricular, inventory, estoque do lunch e um `CGridView` com filtros. Registre colunas de ação, placeholder da busca e paginação.
2. **Defina o contrato `data-tag-table`:** um JSON no atributo da tabela com `actionColumns`, `searchPlaceholder`, `serverSide` e, quando houver, `ajax` (`url`, `method`, `pages`). Sem o atributo, o comportamento padrão atual (última coluna, responsivo).
3. **Emita o atributo no `DataTableGridView`:** novas propriedades públicas opcionais (`tableOptions`) mescladas em `htmlOptions` do `items`. As 44 views continuam funcionando sem alteração, usando o padrão.
4. **Escreva `frontend/components/datatable.js`:**
   - lê o atributo, monta a configuração e mantém a classe `initialized` para não inicializar duas vezes;
   - leva o `pipeline` (cache de 5 páginas) e o `clearPipeline()` para o componente;
   - usa o pt-BR do `datatablesptbr.js` sem `$.getScript` (importar o arquivo ou reproduzir o objeto);
   - expõe `window.initDatatable` como ponte para os chamadores legados listados acima e para o `afterAjaxUpdate`.
5. **Migre os ramos por URL para atributo:** aluno (server-side), `stock`, `inventory` com filtros e as colunas de ação por módulo. Cada ramo vira a configuração emitida pela view ou pelo `DataTableGridView`, e o `action.includes` desaparece.
6. **Corrija os recursos relativos:** ícones por `Yii::app()->theme->baseUrl` (ou import no bundle), não por `../../../`.
7. **Remova** `js/datatables/init.js` e `js/datatables/config.js` quando o smoke estiver verde. O layout `fullmenu.php` é arquivo com dono: a troca da tag `<script>` pelo entry do Vite fica a cargo do coordenador (abrir card de contrato; o Handoff registra o pedido).

## Fora de escopo
- Mudar a aparência das tabelas ou o `tag-table-primary` no SASS.
- Reescrever o `StudentController::actionGetStudentAjax`.
- Trocar o DataTables por outra biblioteca ou atualizar sua versão.
- Editar o layout `fullmenu.php`.

## Critérios de aceite
- [ ] Nenhum `action.includes` ou leitura de `window.location.search` no componente.
- [ ] A tabela de aluno continua server-side, com a mesma paginação e busca.
- [ ] `window.initDatatable` existe e é chamada com sucesso pelos módulos legados e pelo `afterAjaxUpdate`.
- [ ] O idioma pt-BR é aplicado sem segunda requisição de `datatablesptbr.js`.
- [ ] As listagens do smoke ficam sem diferença visual.
- [ ] As views que usam `js-tag-table` sem opções continuam com o comportamento padrão.

## Como verificar
```bash
npm run test -- datatable                        # Vitest; confirmar o script no R1-FRONT-01
npx playwright test tests/e2e/datatable          # smoke das listagens
git grep -n "action.includes" -- js/datatables frontend/components   # esperado: sem resultados
```
Abra `?r=student/student/index` e confira, no painel de rede, uma única chamada a `getstudentajax` por página. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

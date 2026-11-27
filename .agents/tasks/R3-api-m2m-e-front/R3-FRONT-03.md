---
id: R3-FRONT-03
title: "frontend/components: inicializadores de select2, mask, datepicker e DataTable com initWithin"
release: R3
module: plataforma
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-01, R2-FRONT-05]
size: M
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3: components) e seção 7 (F2: bsDatepicker)"
write_scope:
  - frontend/components/**
  - frontend/types/**
read_only:
  - frontend/core/**
  - themes/default/js/datatablesptbr.js
  - themes/default/js/common.js
  - js/datatables/init.js
  - app/components/widgets/DataTableGridView.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - themes/default/js/**
  - js/**
  - sass/scss/main.scss
  - .github/workflows/**
acceptance:
  - "npm run lint && npm run typecheck && npm test -- components"
  - "npm run build"
  - "Vitest: o dicionário pt-BR do DataTable é idêntico ao devolvido por getLanguagePtbr() do arquivo legado"
---

# R3-FRONT-03: frontend/components: inicializadores de select2, mask, datepicker e DataTable com initWithin

## Contexto
Hoje cada página repete a configuração dos mesmos quatro plugins. Este card concentra a configuração em módulos ES, sem migrar nenhuma página e sem inicializar nada sozinho. Os plugins continuam scripts clássicos pendurados em `window.jQuery` (um jQuery só, R2-FRONT-02), que o alias `jquery` expõe aos módulos.

O que existe hoje (verificado):
- **select2 3.4.5** (`themes/default/js/select2.js` e `select2-locale-pt-BR.js`): `themes/default/js/common.js` ≈L152–L166 inicia `.select-search-off` (`minimumResultsForSearch: -1`), `.select-search-on`, `.select-schools, .select-ComplementaryAT` (`maximumSelectionSize: 6`), `.select-disciplines` (`maximumSelectionSize: 13`) e `.select-school` (`dropdownCssClass: "school-dropdown"`). Há 164 chamadas `.select2(` em 43 arquivos. O nome da opção (`maximumSelectionSize`) muda no select2 4 (`maximumSelectionLength`): o plano B da F6 deve mexer só neste módulo.
- **mask** (`themes/default/js/jquery/jquery.mask.min.js`): 47 chamadas em 21 arquivos. Padrões repetidos em `app/modules/student/resources/js/form/validations.js`: CPF `000.000.000-00` (≈L77), CEP `00000-000` (≈L846), data `00/00/0000` e `99/99/9999` com placeholder `dd/mm/aaaa` (≈L977 e `util.js` ≈L534), certidão `000000.00.00.0000.0.00000.000.0000000-XX` (≈L873) e telefone com comportamento dinâmico (`SPMaskBehavior`, ≈L51–L61: 11 dígitos → `(00) 00000-0000`, senão `(00) 0000-00009`).
- **datepicker** (bootstrap-datepicker): as chamadas repetem `{language: "pt-BR", format: "dd/mm/yyyy", autoclose: true, todayHighlight: true, allowInputToggle: true, disableTouchKeyboard: true}` (foods, `js/admin/auditory.js`, `js/reports/ElectronicDiary/index.js`). `app/modules/foods/resources/components.js` ≈L205 escreve `locate` em vez de `language` (a opção é ignorada e o calendário abre em inglês); o calendário passa `container` para uso em modal (`calendar.js` ≈L164). O R2-FRONT-05 renomeia o plugin para `$.fn.bsDatepicker` (`noConflict`): **confirmar** no Handoff dele.
- **DataTable:** `js/datatables/init.js` (270 linhas) roda `$(initDatatable)` (≈L2), define `$.fn.dataTable.pipeline` (≈L9–L116) e `clearPipeline()` (≈L120), decide as colunas olhando `window.location.search` (≈L143–L160), tem a URL `?r=student/student/getstudentajax` escrita à mão (≈L166) e carrega o `datatablesptbr.js` por `$.getScript` com caminho relativo (≈L131), embora o `fullmenu.php` já o carregue (≈L315). O `DataTableGridView` (`app/components/widgets/DataTableGridView.php`) marca a tabela com `js-tag-table` e chama o global `initDatatable()` no `afterAjaxUpdate`. O dicionário pt-BR está em `datatablesptbr.js` (247 linhas, função `getLanguagePtbr()`); `copyKeys` traz `u2318` sem a barra (≈L44).
- Os módulos de página (`init.js`, `common.js`) continuam rodando até serem migrados: um inicializador novo que dispare sozinho causaria **inicialização dupla** (select2 3 quebra ao ser iniciado duas vezes).

## Objetivo
Módulos `frontend/components/*` testados que configuram select2, máscara, datepicker e DataTable com os mesmos padrões de hoje, registrados no `initWithin` do R3-FRONT-01 e ativados só quando uma página os chama.

## Passos
1. `components/select2.js`: `initSelect2(el, preset)` com presets nomeados (`searchOff`, `searchOn`, `schools`, `disciplines`, `school`) com as opções acima e um mapa interno de nomes de opção por versão do plugin. Pule elementos que já têm `data('select2')`.
2. `components/mask.js`: presets `cpf`, `cep`, `date`, `phone` (com `SPMaskBehavior`), `civilRegister` e `applyMask(el, preset)`. Mantenha máscaras e placeholders exatos.
3. `components/datepicker.js`: `initDatepicker(el, overrides)` sobre `bsDatepicker`, com os padrões acima e `language: 'pt-BR'` (sem copiar o erro de digitação `locate`; registre no Handoff que a tela de `foods/components.js` passa a abrir em português ao migrar). Aceite `container`.
4. `components/datatable-language.js`: o dicionário de `getLanguagePtbr()` como objeto exportado; `components/datatable.js`: `initDataTable(tabela, opcoes)` (usa o dicionário, `responsive: true`, `select: {items: 'cell'}`, pula tabelas com a classe `dataTable` ou `initialized`, como o `init.js` ≈L134), o `pipeline` portado sem mudança de lógica (o `url` vem do chamador, nunca escrito à mão) e `clearPipeline()`. **Não** porte a escolha de colunas por `window.location.search` nem os placeholders por rota: ficam para a migração de cada página (R4 em diante).
5. `components/index.js`: `registerDefaultComponents()` que registra os inicializadores no `registerInitializer`/`initWithin` do R3-FRONT-01 (seletores: `.select-*`, `[data-mask]`, `.js-date`, `.js-tag-table`). Nada é registrado ao importar o módulo. Documente no JSDoc que a página deve chamar a função e remover o código legado equivalente no mesmo PR.
6. Acrescente a `frontend/types/plugins.d.ts` o que faltar para select2, mask, DataTables e bootstrap-datepicker (o `checkJs` estrito precisa passar).
7. `tag-qa`: Vitest com jsdom. O dicionário é comparado com o resultado real de `getLanguagePtbr()` (carregue `datatablesptbr.js` por `window.eval`). Mask e datepicker rodam com os plugins reais (são só DOM). Se o select2 3.4.5 não funcionar no jsdom, use um dublê do plugin, registre a limitação no Handoff e deixe a cobertura real para o smoke Playwright do R3-MACETE-01.

## Fora de escopo
- Remover ou alterar `js/datatables/init.js`, `common.js`, `datatablesptbr.js` e as páginas (R3-FRONT-04 e a F4).
- Trocar versão de select2 ou de qualquer plugin (F6).
- Migrar a lógica por rota do `init.js`, o `DataTableGridView` e o SCSS dos plugins.

## Critérios de aceite
- [ ] O dicionário pt-BR do DataTable é idêntico ao do arquivo legado (teste).
- [ ] Os presets de máscara e de select2 reproduzem os valores do código legado listados no Contexto.
- [ ] Importar `frontend/components` não inicializa nenhum plugin; `registerDefaultComponents()` registra e `initWithin(el)` aplica só dentro de `el`.
- [ ] Chamar um inicializador duas vezes no mesmo elemento não duplica o plugin.
- [ ] Nenhum global novo, nenhum `?r=` e nenhum `$.getScript` em `frontend/components`.
- [ ] `npm run lint`, `typecheck`, `test` e `build` passam.

## Como verificar
```bash
npm run lint && npm run typecheck
npm test -- components
npm run build
git grep -nE '\?r=|getScript|window\.[A-Za-z]+ *=' -- frontend/components   # sem resultados
```

## Handoff
_Preenchido por quem executar._

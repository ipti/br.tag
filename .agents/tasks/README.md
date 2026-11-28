# Backlog do Plano de Modernização

Este é o quadro de tarefas do [Plano de Modernização](../../docs/arquitetura/plano-modernizacao.md).

Cada arquivo `.md` dentro de uma pasta de lançamento é um **card**. O card é autocontido: qualquer agente (Claude Code, Codex, Antigravity) ou pessoa consegue executá-lo sem o histórico da conversa em que ele foi criado.

## Como usar

### Pessoas
1. Escolha o lançamento atual (tabela abaixo) e um card com `status: pronto` cujas `depends_on` estejam todas concluídas.
2. Mude o `status` para `em-andamento`, crie a branch `task/<ID>` e execute o card, ou delegue a um agente.
3. Ao terminar, mude para `status: em-revisao`. Depois da revisão e do merge, mude para `status: concluido`.

### Agentes
| Ferramenta | Como pedir |
|---|---|
| Claude Code | "use o subagente `tag-refactorer` para executar o card R0-SQL-03" |
| Codex | "spawn the `tag_refactorer` agent to execute card R0-SQL-03" |
| Antigravity | `@tag-refactorer execute o card R0-SQL-03`, ou pelo painel `/agents` |

Fluxo padrão de um card:
```
tag-qa (caracterização) → tag-refactorer (implementa) → tag-qa (testes + gates)
  → tag-plan-guardian (conformidade, de preferência em outro provedor) → pessoa aprova o merge
```

A definição de cada papel está em [`.agents/agents/`](../agents/). Têm revisão humana obrigatória:
- cards de segurança;
- cards de notas, frequência ou aprovação;
- migrations SQL;
- qualquer divergência do golden master.

## Convenções
- **ID:** `R<lançamento>-<ÁREA ou MÓDULO>-<nn>`, por exemplo `R0-SQL-03` ou `R2-GRADES-05`. Épicos de módulo não levam número (`R4-CLASSDIARY`). O nome do arquivo é o ID.
- **Branch e commits:** a branch é `task/<ID>`, e todo commit traz o ID na mensagem.
- **Status:** `rascunho → pronto → em-andamento → em-revisao → concluido`, ou `bloqueado` (com o motivo no Handoff).
  - A fonte de verdade do status é o frontmatter do card. As tabelas deste README são só índice.
- **Tipos:** `seguranca`, `fundacao`, `refactor`, `testes`, `api`, `frontend`, `css`, `docs`, `epico`, `refinamento`.
- **Tamanho:** P (dias), M (1 a 4 semanas), G (mais de 1 mês).
- **Arquivos com dono:** só o coordenador edita; um card de módulo que precise deles abre um card de contrato. São eles:
  - `composer.json`
  - `app/config/main.php`
  - os layouts em `themes/default/views/layouts/`
  - `sass/scss/main.scss`
  - `.github/workflows/`
  - os models compartilhados em `app/models/`
- **Modelo de card:** [`_TEMPLATE.md`](_TEMPLATE.md). O card de referência é o [`R0-seguranca/R0-SYSADMIN-01.md`](R0-seguranca/R0-SYSADMIN-01.md).
- **Épicos (R4 a R7):** nascem com `status: rascunho`. O card de refinamento de cada onda os quebra em cards executáveis com `scripts/inventory-actions.php` (card R1-AGT-03), porque até lá o código terá mudado.

## Lançamentos

Cada lançamento pode ir para produção sozinho. A numeração de versão fica a critério do time; uma sugestão é R0 → 3.15.x e R1 → 3.16.0.

| Lançamento | Pasta | Objetivo | Critério de saída |
|---|---|---|---|
| R0 Segurança urgente | [R0-seguranca](R0-seguranca/) | Fechar as falhas críticas da seção 2 do plano | Nenhuma rota cria usuário sem login; os 9 pontos de SQL injection usam bind; segredos saem do código e são rotacionados; arquivos sensíveis deixam de ser servidos; erros HTTP voltam com o status correto |
| R1 Fundação | [R1-fundacao](R1-fundacao/) | Autoload, PHPStan, CI, Docker, testes, skills, rede visual e limpeza, sem mudar comportamento | PHPStan (src e legado) e testes unitários e de integração rodando no CI; build só publica com a qualidade verde; skills e scripts de agente prontos; suíte Playwright com baseline; limpeza sem diferença visual |
| R2 Segurança estrutural + piloto Notas | [R2-seguranca-estrutural-e-notas](R2-seguranca-estrutural-e-notas/) | Sessão por tenant; negar por padrão e CSRF em modo log; services de notas; Vite; jQuery único | Modos log ativos e monitorados; motor novo de notas igual ao golden master; CSS do design system servido pelo Vite; um jQuery por página |
| R3 Bloqueio + API M2M + piloto front | [R3-api-m2m-e-front](R3-api-m2m-e-front/) | Bloqueio ligado; API JWT para o br-nham; núcleo JS e ponte legada; MACETE; tokens, `@use` e PurgeCSS | Bloqueio e CSRF ligados sem regressão; br-nham na API nova; `common.js` e `util.js` substituídos pela ponte; MACETE migrado |
| R4 Onda 1 | [R4-onda-1](R4-onda-1/) | classdiary, student e classroom, mais a API do app | Módulos da onda "prontos" (ver abaixo); login, me e turmas na API |
| R5 Onda 2 | [R5-onda-2](R5-onda-2/) | school, instructor, timesheet, courseplan, enrollmentonline, foods e o restante de grades, mais a API de notas e frequência | Módulos da onda "prontos" |
| R6 Onda 3 | [R6-onda-3](R6-onda-3/) | Demais módulos | Módulos da onda "prontos" |
| R7 Alto risco | [R7-alto-risco](R7-alto-risco/) | Censo, Administrator/Site, systemadmin, sedsp, sagres, resultsmanagement e gestaopresente, com uma pessoa liderando | Controllers raiz migrados; integrações cobertas por golden master; destino das ilhas decidido |
| R8 Front final | [R8-front-final](R8-front-final/) | jQuery 3, CSP e remoção do CSS de vendor | jQuery 3 em todas as páginas; CSP aplicada; CSS de vendor removido onde a catraca zerou |

### Quando um módulo está pronto (critério dos épicos)
- **SEC:** toda action está protegida ou listada em `app/config/public-routes/<módulo>.php`; o SQL usa bind; os formulários enviam CSRF; não há segredo no código.
- **GM:** os fluxos críticos do módulo têm golden master ou testes de caracterização.
- **SVC:** as actions críticas são magras; as regras estão em `app/src/<Contexto>/` com testes, e o código novo tem pelo menos 80% de cobertura.
- **API:** quando o app precisa, os endpoints existem e têm teste de contrato.
- **JS:** as páginas do módulo estão em `frontend/pages/<módulo>/`, e a catraca do módulo marca zero `?r=` literais e zero globais novos.
- **CSS:** o CSS do módulo está em `sass/scss/pages/_<módulo>.scss`, as views não têm blocos `<style>`, e o uso de Bootstrap 2 está zerado ou em queda na catraca.

## Índice por módulo
| Módulo | Cards |
|---|---|
| plataforma | R0-INFRA-01 a 03 · todos os de R1 · R2-SEC-01 a 03 · R2-FRONT-01 a 05 · R3-SEC-01 e 02 · R3-API-01 a 04 · R3-FRONT-01 a 05 · R3-CSS-01 a 03 · R4-API-01 e 02 · R4-FRONT-01 · todos os de R8 |
| raiz (Site, Administrator, Censo) | R0-ADMIN-01 · R0-SQL-01 · R0-SQL-02 · R3-SEC-03 · R7-CENSO · R7-ADMIN-SITE |
| abilities, aeerecord, curricularcomponents, dashboard, gradeconcept, gradestructure, stages, tag, tools | R6-PEQUENOS |
| calendar | R6-CALENDAR |
| classdiary | R4-CLASSDIARY · R5-API-01 |
| classroom | R0-SQL-08 · R4-CLASSROOM |
| courseplan | R5-COURSEPLAN |
| curricularmatrix | R6-CURRICULARMATRIX |
| enrollmentonline | R5-ENROLLMENTONLINE |
| farmer | R6-FARMER |
| foods | R0-INFRA-01 · R3-SEC-03 · R3-FOODS-01 e 02 · R3-API-05 · R5-FOODS |
| gestaopresente | R7-GESTAOPRESENTE |
| grades | R0-SQL-03 · R2-GRADES-01 a 08 · R4-GRADES-01 · R5-API-02 · R5-GRADES |
| instructor | R5-INSTRUCTOR |
| inventory | R6-INVENTORY |
| lunch | R6-LUNCH |
| macete | R3-MACETE-01 · R6-MACETE |
| notifications | R6-NOTIFICATIONS |
| professional | R6-PROFESSIONAL |
| quiz | R6-QUIZ |
| resultsmanagement | R0-SQL-05 · R7-RESULTSMANAGEMENT |
| sagres | R7-SAGRES |
| school | R0-SQL-06 · R5-SCHOOL |
| schoolreport | R0-SQL-07 · R3-SEC-03 · R6-SCHOOLREPORT |
| sedsp | R0-INFRA-01 · R7-SEDSP |
| student | R0-SQL-04 · R4-STUDENT |
| systemadmin | R0-SYSADMIN-01 · R7-SYSTEMADMIN |
| timesheet | R5-TIMESHEET |
| wizard | R0-SQL-09 · R6-PEQUENOS |

## Inventário por módulo (levantado em 2026-11-27)

Contagens feitas com grep. Servem para priorizar e acompanhar a evolução, não são uma medida exata.

| Módulo | Ctrl | LOC ctrl | Actions | Sem `accessControl` | Superglobais | `createCommand` | JSON | Views | `<script>` | `style=` | Bootstrap 2 | JS (arq/LOC) | CSS | Models | Services/usecases |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| abilities | 1 | 178 | 4 | 0 | 7 | 0 | 0 | 4 | 0 | 0 | 4 | 0/0 | 0 | 0 | 0 |
| aeerecord | 2 | 359 | 11 | 1 | 2 | 5 | 4 | 6 | 1 | 0 | 0 | 2/104 | 0 | 1 | 0 |
| calendar | 1 | 486 | 12 | 0 | 61 | 11 | 23 | 1 | 0 | 0 | 2 | 2/591 | 1 | 4 | 0 |
| classdiary | 3 | 1561 | 30 | 0 | 102 | 24 | 11 | 18 | 0 | 15 | 10 | 9/1790 | 0 | 0 | 21 |
| classroom | 1 | 1032 | 20 | 0 | 55 | 3 | 11 | 8 | 2 | 33 | 27 | 0/0 (JS em `js/classroom`) | 0 | 0 | 0 |
| courseplan | 2 | 1237 | 29 | 0 | 20 | 9 | 16 | 8 | 1 | 30 | 12 | 4/1479 | 0 | 0 | 0 |
| curricularcomponents | 1 | 179 | 5 | 0 | 10 | 0 | 0 | 4 | 0 | 1 | 4 | 1/9 | 0 | 0 | 0 |
| curricularmatrix | 1 | 185 | 4 | 0 | 4 | 0 | 3 | 1 | 0 | 5 | 9 | 2/67 | 1 | 1 | 0 |
| dashboard | 1 | 16 | 1 | 1 | 0 | 0 | 0 | 1 | 1 | 0 | 0 | 0/0 | 0 | 0 | 2 |
| enrollmentonline | 3 | 767 | 19 | 1 | 16 | 0 | 2 | 12 | 0 | 8 | 9 | 3/412 | 0 | 4 | 1 |
| farmer | 1 | 447 | 16 | 0 | 8 | 0 | 8 | 7 | 0 | 4 | 0 | 3/450 | 0 | 0 | 0 |
| foods | 7 | 2451 | 62 | 4 | 42 | 16 | 32 | 31 | 5 | 16 | 5 | 13/3300 | 0 | 25 | 15 |
| gestaopresente | 1 | 416 | 4 | 1 | 0 | 3 | 0 | 1 | 2 | 7 | 0 | 0/0 | 0 | 0 | 0 |
| gradeconcept | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 1/37 | 0 | 0 | 0 |
| grades | 4 | 1807 | 35 | 0 | 28 | 13 | 16 | 11 | 2 | 18 | 6 | 7/1353 | 0 | 0 | 16 |
| gradestructure | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0 | 0/0 | 0 | 0 | 0 |
| instructor | 1 | 1034 | 21 | 0 | 38 | 16 | 7 | 9 | 2 | 189 | 83 | 0/0 (JS em `js/instructor`) | 0 | 0 | 1 |
| inventory | 5 | 890 | 28 | 0 | 41 | 0 | 0 | 26 | 2 | 4 | 0 | 1/23 | 0 | 7 | 0 |
| lunch | 3 | 426 | 17 | 3 | 0 | 1 | 1 | 7 | 0 | 59 | 83 | 3/127 | 2 | 11 | 0 |
| macete | 4 | 910 | 22 | 0 | 13 | 7 | 13 | 12 | 3 | 1 | 8 | 5/557 | 0 | 14 | 7 |
| notifications | 2 | 251 | 9 | 0 | 11 | 0 | 4 | 5 | 0 | 52 | 0 | 0/0 | 0 | 2 | 0 |
| professional | 1 | 365 | 8 | 0 | 11 | 2 | 12 | 5 | 2 | 7 | 2 | 2/287 | 0 | 1 | 0 |
| quiz | 1 | 499 | 23 | 1 | 45 | 3 | 5 | 18 | 0 | 7 | 24 | 1/386 | 1 | 8 | 0 |
| resultsmanagement | 2 | 321 | 12 | 2 | 0 | 12 | 8 | 8 | 0 | 3 | 0 | 8/778 | 2 | 0 | 0 |
| sagres | 1 | 111 | 5 | 1 | 2 | 33 | 0 | 5 | 2 | 21 | 8 | 1/15 | 0 | 4 | 0 |
| school | 1 | 691 | 19 | 0 | 55 | 0 | 5 | 15 | 3 | 8 | 3 | 4/910 | 0 | 0 | 0 |
| schoolreport | 3 | 1235 | 96 | 3 | 2 | 94 | 6 | 102 | 68 | 1209 | 244 | 2/49 | 4 | 1 | 2 |
| sedsp | 1 | 354 | 15 | 1 | 9 | 2 | 0 | 2 | 3 | 40 | 6 | 1/104 | 0 | 81 | 32 |
| stages | 1 | 178 | 6 | 0 | 0 | 0 | 0 | 4 | 0 | 0 | 2 | 1/3 | 0 | 0 | 0 |
| student | 3 | 1919 | 34 | 0 | 88 | 2 | 11 | 15 | 3 | 63 | 13 | 9/1915 | 0 | 3 | 0 |
| systemadmin | 1 | 1310 | 26 | 0 | 37 | 17 | 3 | 15 | 5 | 33 | 38 | 0/0 (JS em `js/admin`) | 0 | 0 | 0 |
| tag | 0 | 0 | 0 | 0 | 0 | 11 | 0 | 0 | 0 | 0 | 0 | 0/0 | 0 | 2 | 0 |
| timesheet | 1 | 1143 | 16 | 0 | 66 | 10 | 14 | 2 | 0 | 20 | 41 | 4/1041 | 1 | 6 | 0 |
| tools | 1 | 192 | 4 | 0 | 0 | 0 | 1 | 2 | 1 | 23 | 0 | 0/0 | 0 | 0 | 0 |
| wizard | 2 | 210 | 6 | 2 | 11 | 0 | 0 | 5 | 1 | 6 | 42 | 0/0 | 0 | 1 | 0 |

**Controllers raiz** (`app/controllers/`, views em `themes/default/views/<controller>/`)

| Controller | LOC | Actions | `accessControl` | Superglobais | `createCommand` | JSON |
|---|---|---|---|---|---|---|
| AdministratorController | 1609 | 18 | não | 12 | 19 | 3 |
| CensoController | 3120 | 11 | não | 9 | 22 | 0 |
| SiteController | 555 | 15 | não | 18 | 0 | 5 |

**Colunas**
- **Superglobais:** usos de `$_POST`, `$_GET`, `$_REQUEST` e `$_FILES` nos controllers.
- **`createCommand`:** contagem em todo o módulo.
- **JSON:** `json_encode` e `CJSON::encode` nos controllers.
- **Bootstrap 2:** `spanN`, `row-fluid`, `control-group` e `innerLR` nas views.
- **JS:** arquivos em `resources/`, sem contar `lib/` e `*.min.js`.

**Controllers sem `accessControl` (24):**
- raiz: `AdministratorController`, `CensoController`, `SiteController`
- aeerecord: `ReportsController`
- dashboard: `DefaultController`
- enrollmentonline: `DefaultController`
- foods: `DefaultController`, `FoodmenuController`, `FoodrequestController`, `ReportsController`
- gestaopresente: `DefaultController`
- lunch: `DefaultController`, `LunchController`, `StockController`
- quiz: `DefaultController`
- resultsmanagement: `DefaultController`, `ManagementschoolController`
- sagres: `DefaultController`
- schoolreport: `DefaultController`, `FormsController`, `ReportsController`
- sedsp: `DefaultController`
- wizard: `ConfigurationController`, `DefaultController`

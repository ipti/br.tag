---
id: R6-REFINE-01
title: "Refinar a onda 3: rodar o inventário e quebrar os épicos R6-* em cards executáveis"
release: R6
module: plataforma
type: refinamento
skill: tag-task-runner
agents: [tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R5-REFINE-01]
size: M
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seções 8.3 (cards gerados a partir de inventário) e 8.6 (onda 3)"
write_scope:
  - .agents/tasks/R6-onda-3/**
  - .agents/tasks/README.md
read_only:
  - app/**
  - themes/**
  - frontend/**
  - sass/**
  - metrics/**
  - phpstan/**
  - scripts/**
  - docs/arquitetura/plano-modernizacao.md
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "php scripts/inventory-actions.php gera o JSON de todos os módulos da onda 3 sem erro"
  - "Todo épico R6-* tem cards R6-<MODULO>-<nn> com write_scope sem cruzamento entre cards do mesmo módulo"
  - "O índice por módulo do README lista os cards novos"
---

# R6-REFINE-01: Refinar a onda 3: rodar o inventário e quebrar os épicos R6-* em cards executáveis

## Contexto
Os épicos da onda 3 (`R6-INVENTORY`, `R6-SCHOOLREPORT`, `R6-CALENDAR`, `R6-CURRICULARMATRIX`, `R6-FARMER`, `R6-LUNCH`, `R6-QUIZ`, `R6-PROFESSIONAL`, `R6-NOTIFICATIONS`, `R6-MACETE` e `R6-PEQUENOS`) foram escritos em 2026-11-27 com contagens de grep (tabela "Inventário por módulo" do `README.md`). Entre aquela data e o início da onda 3 passam R1 a R5, e o código muda. Os efeitos mais prováveis, que o refinamento precisa conferir (todos são **confirmar:**):
- `Yii::app()->user->checkAccess()` foi corrigido (R2-SEC-01), o que muda as regras de gestor no inventory.
- `app/config/public-routes/<módulo>.php` existe para os módulos tocados em R3-SEC-03 (foods, schoolreport e raiz).
- O `js/notifications/notifications.js` (169 linhas, carregado em `themes/default/views/layouts/fullmenu.php` ≈L316) saiu em R3-FRONT-05, e o JS do MACETE saiu em R3-MACETE-01.
- Os módulos de R4 e R5 já mostram o padrão a copiar (`app/src/<Contexto>`, `frontend/pages/<módulo>/`, `sass/scss/pages/_<módulo>.scss`).

O plano (seção 8.3) define que o inventário alimenta a criação dos cards: `scripts/inventory-actions.php` (card R1-AGT-03) gera um JSON por controller e action, com superglobais, SQL cru, saída JSON, linhas de código e controle de acesso. Um agente gera os rascunhos e uma pessoa revisa.

## Objetivo
Cada épico R6-* vira uma lista de cards executáveis (`R6-<MODULO>-<nn>`), com escopo, dependências e critérios próprios, e o índice do README reflete isso.

## Passos
1. Atualize a `main` e rode `php scripts/inventory-actions.php` (**confirmar:** argumentos e saída no card R1-AGT-03). Guarde o JSON fora do git.
2. Compare o JSON com a tabela do README. Para cada módulo da onda, anote no corpo do épico o que mudou (controllers novos, actions removidas, JS e CSS que já saíram).
3. Rode a catraca (`scripts/frontend-metrics`) e leia `metrics/baseline/<módulo>.json` de cada módulo, para saber o que já está zerado.
4. Para cada épico, crie os cards na ordem **SEC → GM → SVC-n → API-n → JS-n → CSS**:
   - SEC: um card por controller sem `accessControl` ou com `accessRules()` sem `filters()`;
   - GM: um card por fluxo crítico, antes de qualquer SVC;
   - SVC: um card por grupo de actions (ver `tag-extract-service`);
   - JS: um card por página; CSS: um card por módulo, ou por view grande (schoolreport).
5. Nomeie os cards `R6-<MODULO>-<nn>`, com `depends_on` entre eles e `write_scope` mínimo. Dois cards do mesmo módulo só ficam em paralelo se o `write_scope` não se cruzar (seção 8.4).
6. Sugira a ordem de execução: os cards SEC de todos os módulos são pequenos e independentes e podem rodar primeiro, em paralelo, como foi em R0.
7. Liste, num comentário no fim de cada épico, as **decisões de produto** que bloqueiam cards (ver Contexto dos épicos: login de responsáveis do schoolreport, ações públicas do farmer, cálculo de estoque do inventory).
8. Marque cada card novo como `pronto` só se as dependências e a skill existirem; os demais ficam `rascunho`.
9. Atualize a tabela "Índice por módulo" do `README.md` e o status dos épicos (`rascunho` → `em-andamento`).

## Fora de escopo
- Executar qualquer card da onda 3.
- Refinar R7 (card R7-REFINE-01) ou R8.
- Editar código, configuração ou catraca.

## Critérios de aceite
- [ ] O JSON do inventário foi gerado e as diferenças em relação ao README estão registradas.
- [ ] Todo épico R6-* tem pelo menos um card SEC, e os pacotes GM, SVC, JS e CSS aplicáveis, ou uma justificativa de que não se aplicam.
- [ ] Nenhum par de cards do mesmo módulo tem `write_scope` cruzado sem `depends_on`.
- [ ] Todo card novo tem frontmatter completo, na ordem do template.
- [ ] O índice do README lista todos os cards novos.
- [ ] Uma pessoa revisou a lista e as decisões de produto pendentes.

## Como verificar
```bash
php scripts/inventory-actions.php   # sem erro; confirmar flags no card R1-AGT-03
git status --short .agents/tasks    # só arquivos em R6-onda-3/ e o README
git diff --stat -- . ':!.agents/tasks'   # vazio: nada fora do escopo
```
Confira, abrindo 3 cards sorteados, que o `write_scope` aponta para arquivos que existem (`ls`). Sem testes de navegador neste card.

## Handoff
_Preenchido por quem executar._

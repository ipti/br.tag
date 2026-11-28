---
id: R5-REFINE-01
title: "Refinar a onda 2: quebrar os épicos R5-* em cards executáveis"
release: R5
module: plataforma
type: refinamento
skill: tag-task-runner
agents: [tag-plan-guardian, tag-qa]
status: pronto
depends_on: [R4-REFINE-01]
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.3 (cards gerados a partir de inventário) e seção 8.6 (onda 2)"
write_scope:
  - .agents/tasks/R5-onda-2/**
  - .agents/tasks/README.md
read_only:
  - scripts/inventory-actions.php
  - app/modules/school/**
  - app/modules/instructor/**
  - app/modules/timesheet/**
  - app/modules/courseplan/**
  - app/modules/enrollmentonline/**
  - app/modules/foods/**
  - app/modules/grades/**
  - .agents/tasks/R4-onda-1/**
  - docs/arquitetura/plano-modernizacao.md
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "Cada épico R5-* tem os cards filhos com status pronto (ou bloqueado, com o motivo) e depends_on coerente"
  - "O índice por módulo do .agents/tasks/README.md lista os novos IDs"
  - "Uma pessoa revisou e aprovou a quebra antes de qualquer card filho ser iniciado"
---

# R5-REFINE-01: Refinar a onda 2: quebrar os épicos R5-* em cards executáveis

## Contexto
Os épicos da onda 2 ([R5-SCHOOL](R5-SCHOOL.md), [R5-INSTRUCTOR](R5-INSTRUCTOR.md), [R5-TIMESHEET](R5-TIMESHEET.md), [R5-COURSEPLAN](R5-COURSEPLAN.md), [R5-ENROLLMENTONLINE](R5-ENROLLMENTONLINE.md), [R5-FOODS](R5-FOODS.md) e [R5-GRADES](R5-GRADES.md)) estão como `rascunho`, com o inventário de 2026-11-27. Ao fim da onda 1 o código terá mudado, e os módulos da onda 2 compartilham dependências com ela: o `instructor` repete as actions de frequência do `classdiary`, o `timesheet` e o `classroom` dividem o model `Classroom`, e o `foods` e o `grades` já tiveram cards no R2 e no R3.

O processo é o do [R4-REFINE-01](../R4-onda-1/R4-REFINE-01.md): rodar o `scripts/inventory-actions.php` (card R1-AGT-03), comparar com o épico, quebrar nos pacotes SEC → GM → SVC → API → JS → CSS e gerar os cards no formato do [`_TEMPLATE.md`](../_TEMPLATE.md). As lições da onda 1 (o que o refinamento deixou escapar, que `write_scope` cruzaram) entram aqui.

Este card não escreve código de produção.

## Objetivo
Cada épico da onda 2 vira uma lista de cards pequenos, com dependências ordenadas e sem `write_scope` cruzados no mesmo módulo. Uma pessoa aprova a quebra.

## Passos
1. Confirme que o R4-REFINE-01 foi concluído e leia o Handoff dele (ajustes de processo e problemas do script de inventário).
2. Rode `scripts/inventory-actions.php` para os sete módulos e compare com o inventário do README e com o "Contexto" de cada épico. Registre divergências no próprio épico.
3. Para cada épico, quebre os pacotes na ordem SEC → GM → SVC → API → JS → CSS:
   - **SEC:** um card por controller. Os riscos conhecidos de cada épico já estão nos "Passos" dele (regras `'*'`, `delete` sem `postOnly`, SQL concatenado, rotas públicas legítimas).
   - **SVC:** um card por grupo de actions, com o contexto em `app/src/<Contexto>`.
   - **JS e CSS:** um card por página e por parcial.
4. **Dependências entre épicos:**
   - O `R5-GRADES` e o `R5-INSTRUCTOR` mexem em frequência e notas: alinhar com o `R5-API-01` e o `R5-API-02`.
   - O `R5-TIMESHEET` renomeia o `Schedule` do módulo: abrir antes um card de contrato, porque `app/models/**` tem dono.
   - O `R5-FOODS` e o `R5-GRADES` descontam o que já saiu no R3 e no R2; conferir os cards concluídos.
5. Nomeie os cards `R5-<MODULO>-<nn>`, preencha todos os campos do template e marque `human_review: obrigatoria` em notas, frequência, matrícula, migrations e segurança.
6. Atualize o índice por módulo do `.agents/tasks/README.md` e peça a revisão de uma pessoa. Registre o resultado no Handoff.

## Fora de escopo
- Executar qualquer card filho.
- Refinar os épicos da onda 3 (R6-*) ou os de alto risco (R7-*).
- Alterar código de produção, testes ou configuração.

## Critérios de aceite
- [ ] Os sete épicos têm cards filhos para os pacotes aplicáveis, com YAML válido e `write_scope`, `forbidden` e `acceptance` preenchidos.
- [ ] Nenhum par de cards do mesmo módulo com `write_scope` cruzado está marcado para rodar em paralelo.
- [ ] Os cards de contrato para arquivos com dono (renomear `Schedule`, `main.php` etc.) estão abertos e ligados nos `depends_on`.
- [ ] O índice do `.agents/tasks/README.md` lista os novos IDs por módulo.
- [ ] Uma pessoa aprovou a quebra (registrado no Handoff).

## Como verificar
```bash
php scripts/inventory-actions.php school instructor timesheet courseplan enrollmentonline foods grades   # confirmar os argumentos no R1-AGT-03
ls .agents/tasks/R5-onda-2/
git grep -n "^status:" -- .agents/tasks/R5-onda-2    # esperado: filhos em pronto ou bloqueado
```
Confira à mão que os IDs de `depends_on` existem como arquivos em `.agents/tasks/`.

## Handoff
_Preenchido por quem executar._

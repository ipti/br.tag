# TAG — Guia para o Claude

Este projeto usa `.agents/` como mapa operacional. Leia as regras abaixo antes de qualquer tarefa.

## Regras sempre ativas

@.agents/rules/system-overview.md
@.agents/rules/php-standards.md
@.agents/rules/design-system-usage.md
@.agents/rules/migrations.md
@.agents/rules/legacy-routing-and-assets.md
@.agents/rules/e2e-testing.md

## Plano de modernização, backlog e agentes especialistas

- O plano aprovado está em `docs/arquitetura/plano-modernizacao.md` e substitui `docs/namespaces.md` e `docs/php83_yii1_static_analysis_plan.md`.
- O backlog fica em `.agents/tasks/README.md`, organizado em lançamentos de R0 a R8. Cada card tem sua branch `task/<ID>`; respeite o `write_scope`, o `forbidden` e o `acceptance` do card.
- Os subagentes do projeto ficam em `.claude/agents/`. As instruções completas de cada um estão em `.agents/agents/`.

| Subagente | Papel |
|---|---|
| `tag-plan-guardian` | Revisa se a mudança segue o plano. Só lê. |
| `tag-refactorer` | Executa o card dentro do escopo, sem mudar regra de negócio. |
| `tag-qa` | Cuida do golden master, dos testes e dos gates. Não altera código de produção. |

- Fluxo de um card: `tag-qa` → `tag-refactorer` → `tag-qa` → `tag-plan-guardian` (de preferência em outro provedor) → aprovação humana.

## Playbooks disponíveis

Antes de iniciar uma tarefa, identifique o playbook correspondente e siga-o:

| Tarefa | Playbook |
|---|---|
| Corrigir bug | `.agents/playbooks/bugfix.md` |
| Verificar qualidade de código | `.agents/playbooks/code-quality.md` |
| Criar ou aplicar migration SQL | `.agents/playbooks/database-migration.md` |
| Migrar controller legado para módulo | `.agents/playbooks/module-migration.md` |
| Refatorar UI para o design system TAG | `.agents/playbooks/ui-refactor.md` |
| Preparar release / PR | `.agents/playbooks/release.md` |
| Executar testes E2E | `.agents/playbooks/e2e-testing.md` |

## Checklists de encerramento

Antes de declarar qualquer tarefa concluída, aplique o checklist adequado:

- Qualquer tarefa → `.agents/checklists/task-done.md`
- Migração de módulo → `.agents/checklists/module-migration.md`
- Revisão de PR → `.agents/checklists/pr-review.md`

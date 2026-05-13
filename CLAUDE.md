# TAG — Guia para o Claude

Este projeto usa `.agents/` como mapa operacional. Leia as regras abaixo antes de qualquer tarefa.

## Regras sempre ativas

@.agents/rules/system-overview.md
@.agents/rules/php-standards.md
@.agents/rules/design-system-usage.md
@.agents/rules/migrations.md
@.agents/rules/legacy-routing-and-assets.md
@.agents/rules/e2e-testing.md

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

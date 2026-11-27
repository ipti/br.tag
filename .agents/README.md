# `.agents` Operating Map

This folder helps AI agents (Claude Code, Codex, Antigravity) and people act consistently in TAG without relying on tribal knowledge.

## Layout

- `rules/`: stable project facts and constraints (each file keeps the Antigravity `trigger` frontmatter)
- `playbooks/`: how to execute common tasks (will become skills, see card R1-AGT-04)
- `checklists/`: what "done" means for common task types
- `workflows/`: compatibility aliases pointing to the playbooks (Antigravity workflows are deprecated in favor of skills)
- `agents/`: canonical definitions of the specialist agents — plan guardian, refactorer, QA. Antigravity reads this folder natively; adapters live in `.claude/agents/` (Claude Code) and `.codex/agents/` (Codex)
- `tasks/`: backlog of the modernization plan, organized in releases R0–R8 (start at `tasks/README.md`)
- `skills/`: (from card R1-AGT-01) skills in the open Agent Skills format; Codex and Antigravity read them natively, and `scripts/sync-agent-skills` copies them to `.claude/skills/`

The approved plan lives in `docs/arquitetura/plano-modernizacao.md`.

## What each tool reads

| Tool | Instructions | Agents | Skills |
|---|---|---|---|
| Claude Code | `CLAUDE.md` (imports `rules/`) | `.claude/agents/` | `.claude/skills/` |
| Codex | `AGENTS.md` | `.codex/agents/` | `.agents/skills/` |
| Antigravity | `AGENTS.md` + `.agents/rules/` | `.agents/agents/` | `.agents/skills/` |

## How agents should use this folder

1. Read the relevant rule files first.
2. If the work comes from the backlog, read the task card and the plan sections it cites.
3. Pick the skill or playbook the card (or task) points to.
4. Use a checklist before closing work.
5. Prefer repository facts over generic framework advice.

## Design goals

- Fewer generic instructions
- More repo-verified commands
- Clear separation between always-on rules and task-specific steps
- Verification steps that can actually be executed in this codebase
- Explicit acknowledgement of legacy exceptions instead of hiding them
- One source of truth per concept, with thin adapters per AI tool

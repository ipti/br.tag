# `.agents` Operating Map

This folder is meant to help Codex act consistently in TAG without relying on tribal knowledge.

## Layout

- `rules/`: stable project facts and constraints
- `playbooks/`: how to execute common tasks
- `checklists/`: what "done" means for common task types
- `workflows/`: compatibility aliases pointing to the newer playbooks

## How Codex should use this folder

1. Read the relevant rule files first.
2. Pick one playbook that matches the current task.
3. Use a checklist before closing work.
4. Prefer repository facts over generic framework advice.

## Design goals

- Fewer generic instructions
- More repo-verified commands
- Clear separation between always-on rules and task-specific steps
- Verification steps that can actually be executed in this codebase
- Explicit acknowledgement of legacy exceptions instead of hiding them

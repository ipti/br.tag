# TAG Codex Guide

This repository keeps project-specific guidance in `.agents/`.

Start here:
- Read `.agents/README.md`.
- Treat `.agents/rules/*.md` as stable constraints.
- Treat `.agents/playbooks/*.md` as task-specific execution guides.
- Treat `.agents/checklists/*.md` as completion criteria.

Operating rules for Codex in this repo:
- Always inspect existing code before proposing architecture changes.
- Prefer existing Composer scripts over ad-hoc Docker commands when a script already exists.
- New features belong in `app/modules/<module_name>/`, not in root `app/controllers/`.
- Match the nearest existing module pattern before introducing a new one.
- Keep controllers thin; place business rules in models or services.
- Never describe a feature as active unless code references confirm it is actually used.
- For browser E2E, ask for URL, credentials, and approval before executing tests.
- After editing `.scss`, run `composer run sass:build`.
- For PHP changes, prefer this verification order when relevant:
  1. `composer run lint`
  2. `composer run analyse`
  3. `composer run mess`
  4. `composer run test`

Command convention:
- This repo uses `rtk` as a shell proxy. Prefix shell commands with `rtk` when possible.

Legacy reality:
- Routing is mixed between `Yii::app()->createUrl(...)`, raw `?r=...`, and `$_SERVER['REQUEST_URI']` checks in menus/layouts.
- Module assets are also mixed: some modules use `baseScriptUrl`, some use direct resource paths, and some keep shared models in `app/models/`.
- Because of that, search adjacent code before normalizing patterns.

When instructions conflict:
1. Root `AGENTS.md`
2. `.agents/rules/*`
3. `.agents/playbooks/*`
4. `.agents/checklists/*`

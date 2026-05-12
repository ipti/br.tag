---
description: Compatibility alias for .agents/playbooks/code-quality.md
---

Use `.agents/playbooks/code-quality.md`.

Preferred order:
1. `composer run lint`
2. `composer run analyse`
3. `composer run mess`
4. `composer run test`

If styles changed, also run `composer run sass:build`.


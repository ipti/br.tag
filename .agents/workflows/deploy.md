---
description: Deploy
---

All steps use semver versioning. The version must match `TAG_VERSION` in `config.php`.

1. **Update Version**:
   - Bump `TAG_VERSION` in `config.php` following semver (e.g., `3.11.0` → `3.12.0`).
2. **Update CHANGELOG**:
   - Add a new entry at the top of `CHANGELOG.md` in simple language for the end user.
   - Format: `## [Versão X.Y.Z]` followed by bullet points describing changes.
3. **List Migrations** *(if applicable)*:
   - Note any `.sql` migration files created for this release (matches PR template `✨ Migrations Utilizadas`).
4. **Create Branch** *(if on main)*:
   - If the current branch is `main` or `dev`, create a new feature/release/fix branch.
5. **Commit and Push**:
// turbo
   - Generate commit, push, and add upstream if needed.
6. **Open Pull Request**:
// turbo
   - Open PR using `gh pr create` (if gh-cli is available), using `.github/pull_request_template.md`.
7. **PR Checklist** (from template):
   - [ ] Version bumped in `config.php`?
   - [ ] CHANGELOG updated?
   - [ ] SonarLint passed?
   - [ ] Branch name follows naming convention?
   - [ ] Documentation updated if user flow changed?

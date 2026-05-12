# Playbook: Release

Use this when preparing a release or release-like patch.

## Steps

1. Confirm target version in `config.php`.
2. Update `CHANGELOG.md` with user-facing language.
3. List migrations involved, if any.
4. Prepare the PR information expected by `.github/pull_request_template.md`:
   - motivation
   - high-level changes
   - prerequisites
   - test flow
   - migrations used
5. Run relevant quality checks.
6. Prepare branch, commit, and PR workflow only if requested.

## Verification

- version bump matches release intent
- changelog reflects actual shipped changes
- migration list is explicit
- PR summary can be filled without inventing missing test evidence

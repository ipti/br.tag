# Playbook: Bugfix

Use this for normal feature or defect work.

## Steps

1. Reproduce or localize the bug from code and surrounding flow.
2. Search for similar behavior in the same module.
3. Fix the smallest correct layer:
   - model/service for business rules
   - controller for flow wiring
   - view/JS only for presentation or interaction issues
4. Run the relevant checklist and quality steps.

## Verify

- direct scenario fixed
- adjacent flow reviewed
- no obvious route or asset regressions introduced
- if the bug touched legacy routing or assets, layout/menu references were checked too

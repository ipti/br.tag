# Playbook: Code Quality

Use this after meaningful code changes.

## Goal

Run the cheapest relevant verification first, then escalate only if needed.

## Default order

1. `composer run lint`
2. `composer run analyse`
3. `composer run mess`
4. `composer run test`

## Environment notes

- `lint`, `analyse`, and `mess` already route through Dockerized commands in `composer.json`.
- `test` currently runs local Codeception acceptance tests and depends on a working WebDriver/browser environment.
- If acceptance automation is unavailable, say that clearly instead of implying full verification.

## Heuristics

- If only SASS changed, run `composer run sass:build` and validate impacted pages.
- If only docs changed, code-quality commands may be skipped.
- If a command is too expensive or blocked by environment, report that clearly.

## Output expectation

Before closing work, summarize:
- what was run
- what passed
- what could not be run

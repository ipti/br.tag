---
description: Run all code quality checks and tests
---

Perform these steps before pushing any code to the repository. All commands run inside Docker.

1. **Linting (PHPMD)**:
// turbo
   - Run `docker exec tag-app ./vendor/bin/phpmd` to identify architectural issues.
2. **Formatting (PHP-CS-Fixer)**:
// turbo
   - Run `docker exec tag-app ./vendor/bin/php-cs-fixer fix` to ensure project style compliance.
3. **Compile Styles**:
// turbo
   - Run `docker compose run --rm --entrypoint /opt/dart-sass/sass sass /sass/main.scss:/css/main.css --no-source-map --style=compressed` if any styles were changed.
4. **Run Tests**:
// turbo
   - Run `docker exec tag-app ./vendor/bin/codecept run acceptance --steps` to execute acceptance tests.
5. **Verification**: Ensure all checks pass (green output).

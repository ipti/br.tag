---
description: Deploy
---

using semversion to define the code version:
- Update de CHANGELOG.md file;
- Update TAG_VERSION on config.php;
- if the currente branch is main, create a new branch;
- Generate commit push, add upstream if needed;
- Open pull request if gh-cli avaliable using the pull_request_template.md;
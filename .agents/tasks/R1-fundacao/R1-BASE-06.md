---
id: R1-BASE-06
title: "Rector só em app/src e tests (dry-run) e php-cs-fixer com configurações separadas para o legado e para app/src"
release: R1
module: plataforma
type: fundacao
skill: .agents/playbooks/code-quality.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-02]
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco Rector e php-cs-fixer)"
write_scope:
  - rector.php
  - .php-cs-fixer.php
  - .php-cs-fixer.dist.php
  - .php-cs-fixer.src.php
  - .php-cs-fixer.cache
  - .gitignore
  - composer.json
  - Makefile
read_only:
  - app/**
  - tests/**
  - ruleset.xml
forbidden:
  - app/**
  - themes/**
  - .github/**
  - Dockerfile
acceptance:
  - "composer lint"
  - "composer rector   (agora em --dry-run, sem alterar arquivos)"
  - "git ls-files .php-cs-fixer.cache   (sem resultados)"
  - "git status --short app   (vazio: nenhum arquivo do legado foi reformatado)"
---

# R1-BASE-06: Rector só em app/src e tests (dry-run) e php-cs-fixer com configurações separadas para o legado e para app/src

## Contexto
**Card do coordenador:** edita `composer.json` (scripts).

Estado atual:
- `rector.php` usa `Rector\Set\ValueObject\LevelSetList` e `SetList` (`PHP_74` a `PHP_83`, `CODE_QUALITY`, `NAMING`, `DEAD_CODE`, `TYPE_DECLARATION`) e tem `paths` em `app`, `tests` e `themes`. O set `NAMING` renomearia variáveis e métodos do legado inteiro. Com Rector 2 (R1-BASE-02) `LevelSetList` e os `SetList::PHP_*` podem não existir: confirmar no changelog do Rector 2 e usar a API `RectorConfig::configure()` (`withPaths`, `withSkip`, `withPhpSets`, `withPreparedSets`).
- `composer.json`: `"rector": "docker exec tag-app ./vendor/bin/rector process"`, ou seja, **grava** nos arquivos. O `Makefile` tem `rector-dry` (`--dry-run`) e `rector-run`.
- `.php-cs-fixer.php` (raiz) lê só `app/` e define `@PSR12` mais regras soltas (inclui `braces`, que é depreciada nas versões 3.x novas). Os scripts `lint`, `fix` e `lint:fix` rodam `php-cs-fixer fix` sem `--config`, então detectam esse arquivo.
- `.php-cs-fixer.cache` (69,9 KB) está **versionado** (`git ls-files` o lista) e não está no `.gitignore`.
- `app/src/` existe a partir do R1-BASE-01, mas este card não depende dele. O finder do `.src.php` precisa tolerar pasta ausente.

## Objetivo
Rector age só no código novo e só em `--dry-run`; o php-cs-fixer tem uma configuração para o legado (sem tocar em `app/src`) e outra, estrita, para `app/src`.

## Passos
1. **Rector.** Reescreva `rector.php` com `paths` = `app/src` e `tests` (somente diretórios existentes), `skip` para `tests/_support`, `tests/_output`, `tests/acceptance`, `tests/robots`, `tests/builders`, `tests/scripts`, `vendor` e `app/vendor`; sets de PHP 8.3, `CODE_QUALITY`, `DEAD_CODE` e `TYPE_DECLARATION`, **sem** `NAMING`. Em `composer.json`, `rector` vira `... rector process --dry-run`; no `Makefile`, `rector-run` continua sendo a forma de aplicar.
2. **Legado.** `git mv .php-cs-fixer.php .php-cs-fixer.dist.php`, mantendo as regras como estão e acrescentando `->exclude('src')` ao finder de `app/` (relativo a `app/`, exclui só `app/src`). Não mude regras: não pode haver reformatação em massa.
3. **`app/src`.** Crie `.php-cs-fixer.src.php` com `@PER-CS2.0`, `declare_strict_types`, `ordered_imports`, `no_unused_imports` e `global_namespace_import` (importar classes, funções e constantes globais, para que `GradeRules` e afins ganhem o `use`). O finder usa `array_filter` com `is_dir` sobre `app/src`, `tests/Unit`, `tests/Integration` e `tests/Support`. Confirme que o `@PER-CS2.0` existe na versão instalada.
4. **Composer.** `lint` e `fix` passam a rodar as duas configurações, cada uma com `--config`, mantendo `PHP_CS_FIXER_IGNORE_ENV=1` e o `docker exec`. Ajuste `lint:fix` (alias de `fix`) da mesma forma.
5. **Cache.** `git rm --cached .php-cs-fixer.cache` e acrescente `.php-cs-fixer.cache` (e `.php-cs-fixer.*.cache`) ao `.gitignore`. Defina `setCacheFile` nas duas configs para um arquivo ignorado.
6. Rode `composer lint` e `composer rector` e confirme que `git status --short app` está vazio.

## Fora de escopo
- Reformatar o legado ou ligar regras novas nele.
- Rodar o Rector para valer em qualquer pasta.
- Criar o job de CI de lint (R1-CI-01).

## Critérios de aceite
- [ ] `rector.php` só cobre `app/src` e `tests`, sem o set `NAMING`, e `composer rector` não altera arquivos.
- [ ] Existem `.php-cs-fixer.dist.php` (legado, sem `app/src`) e `.php-cs-fixer.src.php` com as cinco regras pedidas; `.php-cs-fixer.php` deixou de existir.
- [ ] `.php-cs-fixer.cache` saiu do git e está no `.gitignore`.
- [ ] `composer lint` roda as duas configurações com o mesmo resultado de antes no legado.
- [ ] **O comportamento não muda:** nenhum arquivo de `app/`, `themes/` ou `tests/` foi modificado.

## Como verificar
```bash
composer lint
composer rector            # dry-run: mostra o diff e não grava
git status --short app tests   # esperado: vazio
git ls-files .php-cs-fixer.cache   # esperado: vazio
git check-ignore -v .php-cs-fixer.cache
# prova de que a config src funciona (arquivo temporário, não commitar):
#   app/src/Tmp/Foo.php sem declare(strict_types=1) => composer lint deve falhar; apagar em seguida
```

## Handoff
_Preenchido por quem executar._

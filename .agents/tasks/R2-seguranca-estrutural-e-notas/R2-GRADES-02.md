---
id: R2-GRADES-02
title: "Extrair o FormulaCalculator e o enum GradeFormula do ApplyFormulaOnGradesUsecase"
release: R2
module: grades
type: refactor
skill: tag-extract-service
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-GRADES-01, R1-BASE-07]
size: P
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, extração 1) e seção 3"
write_scope:
  - app/src/Grades/Domain/FormulaCalculator.php
  - app/src/Grades/Domain/GradeFormula.php
  - tests/Unit/Grades/**
  - app/modules/grades/usecases/ApplyFormulaOnGradesUsecase.php
read_only:
  - app/models/GradeCalculation.php
  - app/modules/grades/usecases/**
  - tests/Integration/Grades/GoldenMaster/**
  - tests/_data/grades-golden-master/**
forbidden:
  - composer.json
  - app/config/main.php
  - app/models/**
  - themes/**
  - "phpstan*.neon"
  - tests/Integration/Grades/GoldenMaster/**
  - tests/_data/grades-golden-master/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter Grades"
  - "composer test:integration -- --filter GradesGoldenMaster"
---

# R2-GRADES-02: Extrair o FormulaCalculator e o enum GradeFormula do ApplyFormulaOnGradesUsecase

## Contexto
Primeiro passo do piloto de services (`app/src/Grades`) e primeiro teste unitário do projeto. O `ApplyFormulaOnGradesUsecase` (`app/modules/grades/usecases/ApplyFormulaOnGradesUsecase.php`, 75 linhas) é quase puro. Ele é chamado só por `CalculateFinalMediaUsecase::applyCalculation()` (≈L160–166), que o usa na média final e na recuperação final. Comportamento atual de `exec()` (≈L32–74):
- `empty($this->grades)` lança `Exception('Não foram adicionadas notas...')` (≈L34–36).
- O `switch` é feito sobre `$this->calculation->id`, com as constantes `GradeCalculation::OP_*` (`app/models/GradeCalculation.php` ≈L18–22): 1 soma, 2 média, 3 média ponderada, 4 maior, 5 menor. O `default` **cai na soma** (≈L40–41).
- Soma e média usam `floatval` em cada nota. Maior, menor e ponderada usam os valores crus.
- A ponderada acumula `nota * peso` e a soma dos pesos e divide (≈L62–70). Com pesos somando zero, o `/` do PHP 8 lança `DivisionByZeroError` (que **não** é `Exception`). Peso ausente na posição vira `null`, e `null` conta como 0 (hoje só emite warning, e `config.php` ≈L35 desliga `error_reporting` em produção).
- O resultado é **truncado** para uma casa: `floor($result * 10) / 10` (≈L73). O `CalculateNumericGradeUsecase`, por contraste, usa `round`; essa diferença é o bug do R2-GRADES-08 e **não** se corrige aqui.

O golden master do R2-GRADES-01 é o árbitro. Ele não pode ser alterado por este card.

## Objetivo
A regra de cálculo vive em `Tag\Grades\Domain`, pura e testada, e o usecase antigo vira uma fachada que delega a ela sem mudar nenhum resultado.

## Passos
1. Confirme que o golden master está verde no código atual (`composer test:integration -- --filter GradesGoldenMaster`). Se não estiver, pare.
2. Crie `Tag\Grades\Domain\GradeFormula` (enum `int` com `Sum = 1`, `Average = 2`, `WeightedAverage = 3`, `Max = 4`, `Min = 5`) com `fromId(int): self`. Id desconhecido devolve `Sum`, para preservar o `default` do `switch`; comente isso e registre no Handoff.
3. Crie `Tag\Grades\Domain\FormulaCalculator` (`final`, `declare(strict_types=1)`, sem `Yii::app()`, sem ActiveRecord): `calculate(GradeFormula $formula, array $grades, array $weights = []): float`.
   - Reproduza a conversão do legado fórmula por fórmula (`floatval` em soma e média; maior, menor e ponderada sobre os valores originais). Se o PHPStan max não aceitar, converta com `(float)` e registre a diferença possível (nota `''`: no legado `'' * peso` pode lançar `TypeError`; no novo vira 0.0). O golden master diz se há divergência observável.
   - Preserve o truncamento `floor($result * 10) / 10` e **não** proteja a divisão por zero: o teste afirma `DivisionByZeroError`. Registre a decisão no Handoff.
   - Tipos para o PHPStan max (`non-empty-list`, `list<float|int|string|null>`), sem `@phpstan-ignore` e sem baseline.
4. Teste unitário (PHPUnit, sem banco e sem Yii) em `tests/Unit/Grades/FormulaCalculatorTest.php`: cada fórmula, truncamento contra arredondamento (6.95 → 6.9; 7.99 → 7.9), maior e menor com nulo, peso nulo, pesos somando zero, peso ausente, id desconhecido, nota nula e zero. Use o `Clock` falso se precisar de data (não deve precisar).
5. Transforme o `ApplyFormulaOnGradesUsecase` em fachada. Mantenha a classe, o construtor `($calculation, $weights = [])`, `setGrades()`, `setWeights()` e o `exec()`. Mantenha o `Exception` de notas vazias. Troque o `switch` por `FormulaCalculator::calculate(GradeFormula::fromId((int) $this->calculation->id), ...)`.
6. Rode os gates. Se o golden master divergir, **pare** e registre, sem ajustar teste nem código para bater.

## Fora de escopo
- Corrigir ou unificar o arredondamento entre `ApplyFormula` e `CalculateNumericGradeUsecase` (R2-GRADES-08).
- Mexer em `SituationPolicy`, `FinalMediaPolicy` ou nos demais usecases.
- Remover a fachada (acontece só depois da última extração).

## Critérios de aceite
- [ ] `FormulaCalculator` e `GradeFormula` estão em `app/src/Grades/Domain` sem `Yii::app()` nem ActiveRecord, com `declare(strict_types=1)` e PHPStan max sem baseline.
- [ ] Os testes unitários cobrem as cinco fórmulas, o truncamento e a divisão por zero com a exceção atual.
- [ ] O golden master passa **sem alteração** do teste e do snapshot.
- [ ] Os chamadores do `ApplyFormulaOnGradesUsecase` não foram alterados.
- [ ] O Handoff lista as decisões de preservação (`default` em soma, divisão por zero, nota vazia).

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Grades
composer test:integration -- --filter GradesGoldenMaster
git diff --stat main -- tests/Integration/Grades/GoldenMaster tests/_data   # esperado: vazio
git grep -n "Yii::app\|\$_POST" -- app/src/Grades/Domain   # esperado: sem resultados
```
- Confirmar: rode `php -r 'echo 1 / 0;'`, `php -r 'var_dump(min([null, 5]));'` e `php -r 'echo "" * 1;'` no container (PHP 8.3) para fixar o comportamento que os testes assumem (`DivisionByZeroError`, `null` como menor valor e `TypeError` com string vazia).

## Handoff
_Preenchido por quem executar._

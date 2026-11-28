---
id: R2-GRADES-04
title: "Extrair a FinalMediaPolicy do CalculateFinalMediaUsecase"
release: R2
module: grades
type: refactor
skill: tag-extract-service
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-GRADES-02]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, extração 3)"
write_scope:
  - app/src/Grades/Domain/FinalMediaPolicy.php
  - app/src/Grades/Domain/FinalMediaInput.php
  - app/src/Grades/Domain/FinalMediaResult.php
  - app/src/Grades/Domain/CalculationKind.php
  - tests/Unit/Grades/**
  - app/modules/grades/usecases/CalculateFinalMediaUsecase.php
read_only:
  - app/src/Grades/Domain/FormulaCalculator.php
  - app/models/GradeUnity.php
  - app/models/GradeResults.php
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

# R2-GRADES-04: Extrair a FinalMediaPolicy do CalculateFinalMediaUsecase

## Contexto
O `CalculateFinalMediaUsecase` (`app/modules/grades/usecases/CalculateFinalMediaUsecase.php`, 244 linhas) mistura a regra de decisão com leituras e gravações de ActiveRecord. Este card move **só a decisão** para o domínio. As leituras (`GradeUnity`, `Grade`, `GradeCalculation`) e o `save()` ficam na fachada até o R2-GRADES-05.

O que o código faz hoje:
- **Escolha pelo nome da fórmula** (`gradeCalculationFk->name`): `'Média Semestral'` (≈L27), `'Peso'` (≈L44) e qualquer outra (≈L59). O nome é dado, não código; mantenha o mapeamento num só lugar (`CalculationKind::fromName`) e registre a dívida.
- **Média Semestral (≈L28–43):** `max(sem_avarage_N, sem_rec_partial_N)` com `sem_rec_partial` não numérico virando 0. O `!== null` das ≈L34 e L38 só funciona porque `max(null, 0)` devolve o primeiro valor (confirme, ver abaixo). Depois aplica a fórmula `Média`, buscada por nome (≈L42).
- **Peso (≈L45–58):** os pesos vêm das `GradeUnity` da regra, com peso nulo valendo 1 (≈L55).
- **Seleção de notas (≈L168–243):** `extractGrades` aplica a recuperação parcial com `max($grade, $recuperação)` e, no caso `Peso`, só na menor nota do semestre (`isLowestGradeOnSemester`, com os desempates por peso).
- **Recuperação final (≈L65–98 e L114–141):** só se `has_final_recovery` e `final_media < approvation_media` (≈L114–119). A nota de entrada depende de `final_recovery_avarage_formula == 'Médias dos Semestres'` (≈L75). A nota de recuperação vem de `getFinalRevoveryGrade()` (≈L143–158), que lê `->grade` do resultado de um `find()` que pode ser nulo; no PHP 8 isso é warning, devolve `null`, e `null` conta como 0.
- **Pesos da recuperação final (≈L131–134):** `[weight_final_recovery, weight_final_media]` são aplicados às notas `[média, recuperação]` na ordem do `array_push` (≈L126). Pode estar invertido. **Não corrija**; registre para o R2-GRADES-08 (confirmar com o produto).
- **Chamadores:** `DefaultController` ≈L372, L734 e L817, e `EntryController` ≈L188. O `actionSaveGradesRelease` **não** passa `$gradesStudent` (≈L372–376), então a seleção por recuperação parcial se comporta como sem recuperação nesse fluxo. Preserve.

## Objetivo
A decisão da média final (qual fórmula, quais notas, quando e como aplicar a recuperação final) vive em `Tag\Grades\Domain`, pura e testada, e o usecase antigo delega a ela sem mudar resultado.

## Passos
1. Confirme o golden master verde no código atual.
2. Modele a entrada em DTOs puros (`FinalMediaInput`): tipo de cálculo (`CalculationKind`), notas por unidade com semestre, peso e ordem da recuperação parcial, médias semestrais e recuperações semestrais, `approvation_media`, `has_final_recovery` e a configuração da recuperação final (fórmula, pesos, nota lançada, `final_recovery_avarage_formula`). Saída: `FinalMediaResult` com `finalMedia` e `recFinal` (nulo quando não houve recuperação final). Nada de `Yii::app()` nem ActiveRecord em `Domain/`.
3. Implemente `FinalMediaPolicy::calculate(FinalMediaInput): FinalMediaResult` usando o `FormulaCalculator` (R2-GRADES-02). Mova para ela `extractGrades`, `applyPartialRecovery`, `isLowestGradeOnSemester`, `shouldApplyFinalRecovery` e `applyFinalRecovery`.
4. Reproduza, sem consertar: `max` com `null`, nota de recuperação nula valendo 0, peso nulo valendo 1, a ausência de `$gradesStudent`, e a regra com `has_final_recovery` sem unidade `RF` (no código atual cai em soma com nota nula; confirme com o golden master).
5. Na fachada, mantenha a assinatura `__construct($gradesResult, $gradeRule, $countUnities, $gradesStudent = null)` e o `exec()`: carregue os dados do ActiveRecord, monte o `FinalMediaInput`, chame a política e grave `final_media` e `rec_final` (≈L102–112) como hoje.
6. Testes unitários em `tests/Unit/Grades/FinalMediaPolicyTest.php`: as três formas de cálculo; recuperação parcial por semestre; `Subistituir Menor Nota` com empate de nota e de peso; recuperação final nas três variantes (Média, Peso, Médias dos Semestres); fronteira da média igual à de aprovação; os casos nulos acima. Os comportamentos suspeitos viram teste incompleto `markTestIncomplete('Bug: R2-GRADES-08')`.
7. Rode os gates. Se o golden master divergir, **pare** e registre.

## Fora de escopo
- Repositórios, transação e o service `RecalculateFinalMedia` (R2-GRADES-05).
- Corrigir os pesos da recuperação final ou renomear fórmulas.
- Alterar controllers, `GradeUnity` e demais models.

## Critérios de aceite
- [ ] `FinalMediaPolicy` e os DTOs estão em `app/src/Grades/Domain`, sem Yii nem ActiveRecord, com PHPStan max sem baseline.
- [ ] Os testes unitários cobrem as três formas, as três variantes de recuperação final e os casos nulos.
- [ ] O golden master passa sem alteração do teste e do snapshot.
- [ ] O mapeamento por nome existe num único ponto.
- [ ] Os comportamentos suspeitos (pesos da recuperação final, ausência de `$gradesStudent`) estão no Handoff como issue sugerida.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Grades
composer test:integration -- --filter GradesGoldenMaster
git grep -n "Yii::app\|GradeUnity::model\|->save(" -- app/src/Grades/Domain   # esperado: sem resultados
```
- Confirmar: `php -r 'var_dump(max(null, 0), max(0, null), max(null, 5));'` no container (PHP 8.3), que fixa o `max` com `null` assumido nos testes.
- Confirmar: `GradeUnity.weight_final_recovery` e `weight_final_media` (semântica de cada coluna) com o produto ou na tela de estrutura de notas, antes de abrir a issue de inversão.

## Handoff
_Preenchido por quem executar._

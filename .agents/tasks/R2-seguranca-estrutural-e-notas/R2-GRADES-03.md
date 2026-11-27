---
id: R2-GRADES-03
title: "Extrair a SituationPolicy do ChageStudentStatusByGradeUsecase"
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
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, extração 2) e seção 3.1 (exemplo do $stage)"
write_scope:
  - app/src/Grades/Domain/SituationPolicy.php
  - app/src/Grades/Domain/Situation.php
  - tests/Unit/Grades/**
  - app/modules/grades/usecases/ChageStudentStatusByGradeUsecase.php
read_only:
  - app/models/StudentEnrollment.php
  - app/modules/grades/controllers/**
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

# R2-GRADES-03: Extrair a SituationPolicy do ChageStudentStatusByGradeUsecase

## Contexto
Esta regra decide a aprovação do aluno, então qualquer mudança de resultado é inaceitável. O comportamento **atual**, errado ou não, é o que o golden master (R2-GRADES-01) grava, e o que deve ser mantido.

Em `app/modules/grades/usecases/ChageStudentStatusByGradeUsecase.php`:
- **Construtor (≈L22–29):** `__construct($gradeResult, $gradeRule, $numUnities, $stage, $frequency = null)` termina com `$this->$stage = $stage;` (≈L28), que cria uma **propriedade dinâmica** com o nome do valor de `$stage` (depreciado no PHP 8.2). Nenhuma leitura usa essa propriedade.
- **Fluxo (≈L31–50):** matrícula com status não permitido grava a situação do status e sai (≈L37–41); nem todas as notas preenchidas grava `StudentEnrollment::STATUS_ACTIVE` e sai (≈L43–47); senão chama `updateStudentSituation()`.
- **Decisão (≈L93–117), que é o que se extrai:**
  - `REPROVADO` por padrão;
  - `final_media >= approvation_media` e `($frequency >= '75' || $frequency == null)` → `APROVADO`;
  - senão, se `has_final_recovery` e a mesma condição de frequência: sem `rec_final` (`isset` e `!== ''`) → `RECUPERAÇÃO`; `rec_final >= final_recover_media` → `APROVADO`;
  - senão `frequency < '75'` → `REPROVADO`.
- **Frequência 0% (≈L103 e L105):** `$frequency == null` é verdadeiro para `0`, `0.0` e `null`, mas falso para `'0'`. Um aluno com 0% e média suficiente sai `APROVADO`. O tipo recebido depende do chamador (`round()` devolve `float`).
- **Persistência (≈L119–122):** `save()` e `ChangeEnrollmentStatusUsecase`. Não é decisão: fica na fachada.
- **Chamadores:** `DefaultController` ≈L380 (`actionSaveGradesRelease`, que passa a frequência na posição de `$stage`), ≈L736 e ≈L819 (corretos), e `EntryController` ≈L196 e ≈L381.

## Objetivo
A decisão de situação vive em `Tag\Grades\Domain` como política pura, tipada e testada, e o usecase antigo delega a ela sem mudar nenhum resultado.

## Passos
1. Confirme o golden master verde no código atual.
2. Crie `Tag\Grades\Domain\Situation` (enum com valores string `APROVADO`, `REPROVADO`, `RECUPERAÇÃO`) e `SituationPolicy::decide(float $finalMedia, float $approvationMedia, ?float $frequency, bool $hasFinalRecovery, ?float $recFinal, float $finalRecoverMedia): Situation`, sem `Yii::app()` e sem ActiveRecord. A política trata `null` como "sem frequência" e **não** conhece o quirk do 0%.
3. Na fachada, converta a frequência para `?float` **reproduzindo `$frequency == null`** (`0`, `0.0` e `null` viram `null`; `'0'` vira `0.0`). Comente o motivo e cite o R2-GRADES-08. Assim o bug fica no adaptador, e inverter depois da decisão de produto é uma linha.
4. Mantenha na fachada: `isEnrollmentStatusAllowed`, `hasAllGrades`, a exceção `'Aluno não tem média final'` (≈L89–91), o `save()` e o `ChangeEnrollmentStatusUsecase`. Troque só o bloco ≈L93–117 pela chamada à política e mapeie o `Situation` para o texto atual.
5. Mantenha o construtor aceitando os cinco argumentos, na mesma ordem, porque `EntryController` e `actionSaveGradesRelease` chamam com argumentos diferentes e esse comportamento não muda aqui. Remova a atribuição dinâmica da ≈L28 (nunca lida; é `Deprecated` no PHP 8.2+) e registre no Handoff.
6. Testes unitários em `tests/Unit/Grades/SituationPolicyTest.php` (provider de dados): cada ramo; fronteiras (média igual à aprovação, `rec_final` igual a `final_recover_media`, frequência 74, 75 e 76); `null` como sem frequência; regra sem recuperação final; `rec_final` igual a 0 (conta como informada). Na fachada, o teste do 0% como `int`, `float` e `'0'` (caracterização) e um segundo teste, com o comportamento esperado, marcado `markTestIncomplete('Bug: R2-GRADES-08')`.
7. Rode os gates. Se o golden master divergir, **pare** e registre.

## Fora de escopo
- Corrigir o 0% de frequência e o `$stage`/`$frequency` trocados de `actionSaveGradesRelease` (R2-GRADES-06 preserva; R2-GRADES-08 decide).
- Persistência e `ChangeEnrollmentStatusUsecase` (ficam para o R2-GRADES-05 e 06).
- Alterar os controllers.

## Critérios de aceite
- [ ] A política está em `app/src/Grades/Domain`, sem Yii nem ActiveRecord, com PHPStan max sem baseline.
- [ ] Os testes unitários cobrem todos os ramos e as fronteiras listadas.
- [ ] O golden master passa sem alteração do teste e do snapshot.
- [ ] O 0% como `int`, `float` e `'0'` tem teste de caracterização, e o bug está registrado com teste incompleto.
- [ ] Nenhum controller foi alterado.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Grades
composer test:integration -- --filter GradesGoldenMaster
git diff --stat main -- app/modules/grades/controllers   # esperado: vazio
```
- Confirmar: os tipos de `final_media`, `approvation_media` e `final_recover_media` que o ActiveRecord devolve (string numérica ou número), porque a comparação atual é frouxa (`>=` entre string e número). Teste a política com os dois formatos na fachada.
- Confirmar: se `approvation_media` pode ser `null` numa regra. `final_media >= null` compara como booleano; se existir no golden master, a fachada deve reproduzir.

## Handoff
_Preenchido por quem executar._

---
id: R2-GRADES-01
title: "Montar o golden master do cálculo de notas e da situação do aluno"
release: R2
module: grades
type: testes
skill: tag-golden-master
agents: [tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-TEST-01]
size: M
risk: medio
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, passo 1 do piloto) e seção 4 (golden master antes de refatorar)"
write_scope:
  - tests/Integration/Grades/GoldenMaster/**
  - tests/_data/grades-golden-master/**
  - tests/Support/Builders/**
read_only:
  - app/modules/grades/**
  - app/models/**
  - app/components/utils/TLog.php
  - tests/providers/CustomProvider.php
forbidden:
  - app/**
  - composer.json
  - phpunit.xml.dist
  - themes/**
acceptance:
  - "composer test:integration -- --filter GradesGoldenMaster"
  - "Rodar duas vezes seguidas gera o mesmo resultado (determinístico) e o snapshot não muda"
  - "composer lint"
---

# R2-GRADES-01: Montar o golden master do cálculo de notas e da situação do aluno

## Contexto
Todos os cards de extração (R2-GRADES-02 a 07) só podem começar com este golden master verde sobre o código **atual**. O `tag-qa` não altera código de produção: se algo impedir o teste, registre como pendência para o `tag-refactorer`.

O fluxo que será gravado, com o código lido em `app/modules/grades`:
- **Cálculo por unidade:** `CalculateGradeResultsUsecase` (≈L21–58) escolhe a regra da turma por join em `classroom_vs_grade_rules` e `grade_rules_vs_edcenso_stage_vs_modality`. `rule_type` `N` vai para `CalculateNumericGradeUsecase` e `C` para `CalculateConceptGradeUsecase`.
- **Média final:** `CalculateFinalMediaUsecase` (≈L24–100) decide pelo **nome** da fórmula (`Média Semestral`, `Peso`, outras) e aplica a recuperação final (≈L65–98) via `ApplyFormulaOnGradesUsecase`.
- **Situação:** `ChageStudentStatusByGradeUsecase` (≈L87–124) grava `APROVADO`, `REPROVADO` ou `RECUPERAÇÃO` e chama `ChangeEnrollmentStatusUsecase`.
- **Orquestração:** `DefaultController::actionCalculateFinalMedia` (≈L765–827), `actionClassClosure` (≈L681–747) e `actionSaveGradesRelease` (≈L299–409). Esta última chama o usecase de situação com a frequência na posição de `$stage` (≈L379–386), então a frequência é ignorada. O teste deve **gravar esse comportamento**, não corrigi-lo.

Riscos do ambiente:
- `TLog` (`app/components/utils/TLog.php` ≈L8–11) lê `Yii::app()->controller->module->id` e `->action->id`. Numa `CConsoleApplication`, `Yii::app()->controller` lança `CException`. Os usecases chamam `TLog` o tempo todo. Resolva no harness (aplicação web mínima com controller falso); se não der, registre a pendência.
- `TagModel::save()` (`app/models/TagModel.php` ≈L19–31) e `delete()` consultam `Yii::app()->user` fora do console.
- Os timestamps (`CONVERT_TZ(NOW(), ...)`) e os ids mudam a cada execução, então ficam fora do snapshot.

## Objetivo
Um teste de integração que recalcula, com o código atual, uma turma por regra mais os casos-limite e compara com um snapshot JSON versionado.

## Passos
1. Monte o dataset anonimizado (builders em `tests/Support/Builders`, Faker pt_BR com semente fixa, `CustomProvider`). Nenhum dado real. Uma turma por regra, com 2 a 3 alunos cada:
   - **Média** (`OP_MEDIA`, 4 unidades) e, em turmas pequenas, **Soma**, **Maior** e **Menor**;
   - **Peso**, com uma unidade de peso nulo (que o código trata como 1, ≈L55);
   - **Média Semestral** com recuperação parcial por semestre;
   - **Peso** com recuperação parcial `Subistituir Menor Nota` (≈L211–243);
   - **Recuperação final** nas três variantes: Média, Peso (`weight_final_recovery` e `weight_final_media`) e `Médias dos Semestres` (≈L75);
   - **Conceito** (`rule_type` `C`).
2. Casos-limite, cada um num aluno próprio:
   - nota nula, zero e máxima;
   - pesos somando zero (o `ApplyFormulaOnGradesUsecase` ≈L69 divide por zero; o `CalculateNumericGradeUsecase` ≈L349 devolve 0);
   - arredondamento (`floor(x*10)/10` na média final contra `round(x, 1)` na unidade), por exemplo 6.95;
   - frequência 0%, nula e 100%, e o 0 como `int`, `float` e `'0'` (o `$frequency == null` do usecase trata os dois primeiros como "sem frequência");
   - recuperação maior e menor que a média;
   - aluno transferido, inativo e com unidades incompletas (≈L43–47);
   - regra com recuperação final e sem unidade `RF`.
3. Escreva o `LegacyGradesRunner`, uma **cópia literal e comentada** (com as linhas de origem) da orquestração dos três métodos do controller. Nenhuma lógica nova. Rode os usecases atuais e capture os campos de `grade_results` (notas, recuperações, médias semestrais, `rec_final`, `final_media`, `situation`, conceitos) e o `status` da matrícula. Capture também exceção: `{"exception": "<classe>", "message": "..."}`.
4. Grave `tests/_data/grades-golden-master/snapshot.json`, ordenado por cenário, aluno e disciplina, sem ids, datas ou timestamps.
5. Escreva `GradesGoldenMasterTest`: carrega o dataset numa transação desfeita ao fim, roda o runner e compara com o snapshot. Regravar exige `GM_UPDATE=1` e **aprovação humana registrada neste card**.
6. Escreva `tests/Integration/Grades/GoldenMaster/README.md` com como rodar, como regravar e a lista dos cenários.
7. Os comportamentos errados viram, em arquivo separado, testes do comportamento esperado com `markTestIncomplete('Bug: R2-GRADES-08')`.

## Fora de escopo
- Qualquer alteração em `app/**` (inclusive o `TLog`) e em `phpunit.xml.dist`.
- Corrigir bugs: os quatro da seção 2 do plano ficam para o R2-GRADES-08.
- Os endpoints HTTP: o golden master roda os usecases, não o controller.

## Critérios de aceite
- [ ] Cada regra do passo 1 e cada caso-limite do passo 2 tem cenário e linha no snapshot.
- [ ] O teste passa no código atual e duas execuções seguidas dão o mesmo snapshot.
- [ ] O snapshot registra a exceção (por exemplo `DivisionByZeroError`) onde o código atual a lança.
- [ ] Nenhum arquivo de `app/**` foi alterado.
- [ ] O README descreve como rodar e como regravar.
- [ ] As pendências para o `tag-refactorer` (TLog, se for o caso) estão no Handoff.

## Como verificar
```bash
composer test:integration -- --filter GradesGoldenMaster
composer test:integration -- --filter GradesGoldenMaster   # segunda vez: mesmo resultado
git status --short app   # esperado: vazio
composer lint
```
- Confirmar: tipos que `studentEnrolmentFrequencyPerDiscipline()` e `totalStudentEnrolmentFrequency()` devolvem (`StudentEnrollment.php` ≈L345 e L434), porque o `$frequency == null` depende do tipo.
- Confirmar: se `grade_rules.edcenso_stage_vs_modality_fk` é a coluna que o `actionSaveGradesRelease` usa (≈L316) para achar a regra, enquanto os outros fluxos usam o join.

## Handoff
_Preenchido por quem executar._

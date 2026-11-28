---
id: R2-GRADES-06
title: "Extrair o SaveGradesReleaseService da actionSaveGradesRelease preservando o bug da frequência"
release: R2
module: grades
type: refactor
skill: tag-extract-service
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-GRADES-05]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, extração 5) e seção 2 (bug da frequência)"
write_scope:
  - app/src/Grades/Application/SaveGradesReleaseService.php
  - app/src/Grades/Application/SaveGradesReleaseInput.php
  - app/src/Grades/Application/SaveGradesReleaseResult.php
  - app/src/Grades/Infrastructure/Yii/GradeResultRepository.php
  - tests/Unit/Grades/**
  - tests/Integration/Grades/Infrastructure/**
  - app/modules/grades/controllers/DefaultController.php
read_only:
  - app/src/Grades/Domain/**
  - app/modules/classdiary/services/DiaryEntryLock.php
  - app/modules/grades/resources/**
  - tests/Integration/Grades/GoldenMaster/**
forbidden:
  - composer.json
  - app/config/main.php
  - app/components/Controller.php
  - app/models/**
  - themes/**
  - "phpstan*.neon"
  - tests/Integration/Grades/GoldenMaster/**
  - tests/_data/grades-golden-master/**
acceptance:
  - "composer lint"
  - "composer analyse"
  - "composer test:unit -- --filter Grades"
  - "composer test:integration -- --filter Grades"
  - "Cobertura do código novo em app/src/Grades de pelo menos 80%"
---

# R2-GRADES-06: Extrair o SaveGradesReleaseService da actionSaveGradesRelease preservando o bug da frequência

## Contexto
`DefaultController::actionSaveGradesRelease` (`app/modules/grades/controllers/DefaultController.php` ≈L299–409) grava o lançamento de notas do diário e recalcula a situação. Ela usa `$_POST` direto (`discipline`, `classroom`, `students`, `rule`, ≈L301–304).

Comportamento atual, a preservar:
- `DiaryEntryLock::assertReportCardChanges(...)` abre a action (≈L306).
- A regra vem de `GradeRules::findByAttributes(['edcenso_stage_vs_modality_fk' => etapa da turma])` (≈L316–318), que **não** é a consulta com join das outras actions.
- Por aluno (≈L320–405): `GetStudentGradesResultUsecase`; grava `rec_final` e `final_concept`; nas unidades grava `grade_concept_N` (regra `C`) ou `grade_N`, mais `grade_faults_N` e `given_classes_N`; `validate()` falha com `CHttpException('400', ...)` (≈L347–352); `save()`.
- **Frequência (≈L360–364):** `round((($given - $faults) / $given ?: 1) * 100)`. Pela precedência, o `?: 1` age sobre a divisão: quem faltou a todas as aulas (resultado 0) vira 100%. O valor **nunca é usado** (ver a seguir), então o defeito é latente.
- **Bug da frequência (≈L379–386):** `new ChageStudentStatusByGradeUsecase($gradeResult, $gradeRules, count($std['grades']), $frequency)` passa a frequência na posição de `$stage`. O `$frequency` do construtor fica `null`, e a decisão de situação (`$frequency >= '75' || $frequency == null`) ignora a frequência real.
- **Média final sem `$gradesStudent`** (≈L372–376): a recuperação parcial não entra no cálculo neste fluxo, ao contrário de `actionCalculateFinalMedia` e `actionClassClosure`.
- Sem todos os valores: `situation = 'MATRICULADO'` e `final_media = null` (≈L388–391). Com `final_concept` preenchido e todos os valores: `APROVADO` (≈L393–395). Fecha com `save()`, `Log::saveAction('grade', ...)` (≈L407) e `{"valid":true}`.
- **Não há transação:** uma falha no meio deixa os alunos anteriores salvos.
- **Outros chamadores:** `EntryController` (≈L196–200 e ≈L381–385) chama o mesmo usecase com argumentos que não batem com o construtor; isso é investigado no R2-GRADES-08, não aqui.

## Objetivo
O lançamento de notas é um service com testes e a action fica magra, **sem mudar nenhum resultado**, nem o bug da frequência, que fica explícito e rastreado até a decisão de produto (R2-GRADES-08).

## Passos
1. Confirme o golden master verde (inclusive o cenário da frequência ignorada no `actionSaveGradesRelease`) e o card R2-GRADES-05 concluído.
2. `SaveGradesReleaseInput::fromArray()` valida a entrada que hoje é lida sem checagem (`$_POST['students']`, etc.) e lança `ValidationFailed` com os erros por campo.
3. `SaveGradesReleaseService::handle(Input, UserContext): Result` com a mesma sequência por aluno, usando `FinalMediaPolicy` e `SituationPolicy` e o `GradeResultRepository` (amplie-o). Reproduza o `validate()` que falha com 400, a busca de regra do ≈L316 (um método próprio do repositório, com nome que deixe claro que é outra consulta) e o `MATRICULADO` / `APROVADO` dos ≈L388–395.
4. **Bug preservado, explícito:** chame a situação com `frequency: null` e um comentário `// BUG PRESERVADO: a frequência não entra no cálculo deste fluxo. Ver R2-GRADES-08.` Não calcule a frequência (ela é descartada hoje). Escreva dois testes: um de caracterização (aluno com frequência baixa e média suficiente sai `APROVADO`) e outro com o comportamento esperado (`REPROVADO`), marcado `markTestIncomplete('Bug: R2-GRADES-08')`.
5. Passe `gradesStudent` vazio à média final, como hoje, com comentário e o mesmo teste incompleto.
6. **Sem transação:** preserve o comportamento atual (gravação por aluno). Registre no Handoff a sugestão de usar `TransactionManager` depois, como o `saveGrades` já faz.
7. A action: `DiaryEntryLock::assertReportCardChanges` (como hoje), `service(...)->handle(Input::fromArray($_POST), $this->userContext())` e `renderJson(['valid' => true])`. `Log::saveAction` fica na action ou num port; não pode ficar em `Domain`.
8. Mapeamento de erro: o legado devolve 400 em falha de validação. O `ExceptionMapper` traduz `ValidationFailed` em 422. Confirme no JS que chama a action se ele distingue 400 de 422 e registre.
9. Testes: unitários do service com repositórios em memória (regra `N` e `C`, todos os valores e valores faltando, `final_concept`, validação com falha, o bug da frequência) e integração do repositório; golden master verde.

## Fora de escopo
- Corrigir a frequência, a precedência do `?: 1` e a ausência do `gradesStudent` (R2-GRADES-08).
- `actionSaveGrades`, `EntryController` e o JS do módulo.
- Fazer o service transacional.

## Critérios de aceite
- [ ] A action tem só a trava, a leitura da entrada, a chamada ao service e a resposta.
- [ ] O golden master passa sem alteração; o comportamento da frequência ignorada está igual ao de antes.
- [ ] O `frequency: null` está comentado, com os dois testes do passo 4.
- [ ] A busca de regra do ≈L316 está isolada e identificada como diferente das demais.
- [ ] Cobertura do código novo de pelo menos 80% e PHPStan max sem baseline em `app/src`.
- [ ] O Handoff registra o 400 contra 422, a falta de transação e o `gradesStudent` ausente.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Grades
composer test:integration -- --filter Grades
git grep -n "_POST" -- app/src/Grades   # esperado: sem resultados
```
- Confirmar: qual tela envia `rule` e `students` para essa action (`grades/views/default/gradesRelease.php` e o JS do módulo) e o formato exato dos campos, antes de escrever o `Input`.
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

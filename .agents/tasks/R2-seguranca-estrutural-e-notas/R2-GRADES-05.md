---
id: R2-GRADES-05
title: "Criar o RecalculateFinalMediaService, os repositórios e a actionCalculateFinalMedia magra"
release: R2
module: grades
type: refactor
skill: tag-extract-service
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-GRADES-03, R2-GRADES-04]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, extração 4 e action magra) e seção 2 (bugs de negócio)"
write_scope:
  - app/src/Grades/Application/RecalculateFinalMediaService.php
  - app/src/Grades/Application/RecalculateFinalMediaInput.php
  - app/src/Grades/Application/RecalculateFinalMediaResult.php
  - app/src/Grades/Infrastructure/Yii/GradeRulesRepository.php
  - app/src/Grades/Infrastructure/Yii/GradeResultRepository.php
  - tests/Unit/Grades/**
  - tests/Integration/Grades/Infrastructure/**
  - app/modules/grades/controllers/DefaultController.php
  - app/modules/grades/usecases/CalculateFinalMediaUsecase.php
  - app/modules/grades/usecases/ChageStudentStatusByGradeUsecase.php
read_only:
  - app/src/Shared/**
  - app/components/Controller.php
  - app/modules/grades/resources/**
  - app/modules/classdiary/services/DiaryEntryLock.php
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

# R2-GRADES-05: Criar o RecalculateFinalMediaService, os repositórios e a actionCalculateFinalMedia magra

## Contexto
`DefaultController::actionCalculateFinalMedia` (`app/modules/grades/controllers/DefaultController.php` ≈L765–827) é a action que o botão "calcular média" chama. Ela faz tudo: trava, regra, matrículas, frequência, usecases, transação e erro.

Fatos verificados:
- **Trava:** `DiaryEntryLock::assertUnlocked` fica **fora** do `try` (≈L768).
- **Regra da turma:** a `CDbCriteria` com join em `grade_rules_vs_edcenso_stage_vs_modality` e `classroom_vs_grade_rules` (≈L781–787) se repete em `actionClassClosure` (≈L711–717) e em `CalculateGradeResultsUsecase` (≈L27–35). **Cuidado:** `actionSaveGradesRelease` (≈L316) acha a regra por `findByAttributes(['edcenso_stage_vs_modality_fk' => ...])`, que é outra consulta. Não unifique em silêncio.
- **Matrículas:** em turma multietapa com `isClassroomStage == 0` filtra por etapa (≈L790–800).
- **Por matrícula (≈L801–821):** `GetGradeUnitiesByDisciplineUsecase`, `GetStudentGradesResultUsecase`, frequência (por disciplina ou total, conforme `TagUtils::isStageMinorEducation`, ≈L810–815), `CalculateFinalMediaUsecase` e, só se `rule_type === 'N'`, `ChageStudentStatusByGradeUsecase`.
- **O erro some:** `catch (Exception $e)` faz `rollback`, `TLog::error` e termina sem resposta, ou seja, **200 com corpo vazio** (≈L823–826). `Error` (`DivisionByZeroError`, `TypeError`) **não** é `Exception`: escapa do `catch` e vira 500 em branco, porque `config.php` ≈L36–37 desliga o exception handler em produção.
- **JS que chama a action:** `app/modules/grades/resources/legacy/grades/_initialization.js` ≈L195–218 e `.../legacy/gradesRelease/_initialization.js` ≈L306–333. Os dois já têm `error:`, com mensagem específica para 403 e genérica para o resto, então tratam resposta não-200. O segundo envia `classroomId` e `disciplineId`, e a action lê `classroom` e `discipline`. `js/enrollment/gradesRelease/_initialization.js` ≈L311 chama outra action (`grades/entry/calculateFinalMedia`).
- **Contratos:** o plano (seção 8.1) fixa `UserContext`, `ExceptionMapper`, `TransactionManager` e `Controller::service()`, `userContext()` e `renderJson()` como contratos do coordenador. **Confirmar:** que eles existem. Se faltar algum, **pare** e abra card de contrato.

## Objetivo
O recálculo da média final é um service com testes, a action fica magra, e uma falha volta com o status HTTP certo em vez de 200 vazio.

## Passos
1. Confirme os contratos acima e o golden master verde. `Controller.php` tem dono: não o edite.
2. `GradeRulesRepository::forClassroomStage(int $classroomId, int $stage): GradeRulesData` com a `CDbCriteria` da ≈L781–787 (parâmetros ligados) e `GradeResultRepository` (leitura e gravação de `grade_results` por matrícula e disciplina). Eles devolvem DTOs; nenhum ActiveRecord sai de `Infrastructure/Yii`.
3. `RecalculateFinalMediaInput::fromArray()` valida `classroom`, `stage` (vazio vale a etapa da turma, ≈L777–779), `discipline` e `isClassroomStage` e lança `ValidationFailed` com os erros por campo. `RecalculateFinalMediaService::handle(Input, UserContext): Result` roda o fluxo por matrícula com `FinalMediaPolicy` e `SituationPolicy` (a persistência continua pelas fachadas dos usecases, se for mais seguro), dentro de `TransactionManager::transactional()`. Reproduza a frequência e a regra `rule_type === 'N'`.
4. Falhas: o service **não** engole exceção. Mapeie pelo `ExceptionMapper` (404, 422, 403, 409; o resto 500 com Sentry) e registre a decisão para `DivisionByZeroError` (pesos somando zero) e `UndefinedRuleTypeException`, que hoje chegam como 500 em branco. Decida com a pessoa revisora.
5. A trava (`DiaryEntryLock::assertUnlocked`) fica na action, como hoje (usa `CHttpException`).
6. A action: `service(...)->handle(Input::fromArray(...), $this->userContext())` e `renderJson(['valid' => true])`. O corpo antes era vazio e o JS ignora `data`.
7. Registre no Handoff, sem alterar o JS (que é do módulo): o que precisa mudar. Em especial: o 403 e o erro genérico já são tratados; um 401 cai no `ajaxError` do `common.js`; o desencontro `classroomId` × `classroom` do `gradesRelease`.
8. Fachadas: `CalculateFinalMediaUsecase` e `ChageStudentStatusByGradeUsecase` seguem funcionando para os outros chamadores.
9. Testes: unitários do service com repositórios em memória (sucesso, regra `C` sem status, turma multietapa, falha no meio com rollback, input inválido); integração dos repositórios; golden master verde.

## Fora de escopo
- `actionSaveGradesRelease` (R2-GRADES-06) e `actionClassClosure` (R2-GRADES-07).
- Editar o JS do módulo e o `EntryController`.
- Unificar as duas consultas de regra de nota.
- Corrigir os bugs da seção 2 do plano.

## Critérios de aceite
- [ ] `actionCalculateFinalMedia` tem só leitura da entrada, chamada ao service e resposta.
- [ ] Uma falha no cálculo responde com status 4xx ou 5xx e **desfaz** a transação; nenhum caminho devolve 200 vazio.
- [ ] A consulta da regra por turma e etapa existe uma só vez no repositório, parametrizada.
- [ ] O golden master passa sem alteração; cobertura do código novo de pelo menos 80%.
- [ ] O Handoff lista o que o JS precisa mudar (ou que nada precisa).

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Grades
composer test:integration -- --filter Grades
curl -i -X POST -b "PHPSESSID=<sessão>" "http://localhost/?r=grades/default/calculateFinalMedia" -d "classroom=<turma>&stage=&discipline=<disciplina>&isClassroomStage=0"
# esperado: 200 com {"valid":true}; com disciplina inválida, 4xx ou 5xx (antes: 200 vazio)
```
- Confirmar: qual view carrega `legacy/gradesRelease/_initialization.js` e se o botão `#close-grades-diary` chega à action com parâmetros errados.
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

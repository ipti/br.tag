---
id: R2-GRADES-07
title: "Extrair o CloseClassroomService do fechamento de turma (actionClassClosure)"
release: R2
module: grades
type: refactor
skill: tag-extract-service
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R2-GRADES-06]
size: M
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B3, extração 6: fechamento de turma)"
write_scope:
  - app/src/Grades/Application/CloseClassroomService.php
  - app/src/Grades/Application/CloseClassroomInput.php
  - app/src/Grades/Application/CloseClassroomResult.php
  - app/src/Grades/Infrastructure/Yii/ClassroomClosureRepository.php
  - tests/Unit/Grades/**
  - tests/Integration/Grades/ClassClosure/**
  - app/modules/grades/controllers/DefaultController.php
read_only:
  - app/src/Grades/**
  - app/modules/grades/views/default/batchclose.php
  - app/modules/classroom/views/default/_form.php
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

# R2-GRADES-07: Extrair o CloseClassroomService do fechamento de turma (actionClassClosure)

## Contexto
O plano pede "fechamento de turma (encerramento e cálculo do resultado final)". Localização confirmada no código:
- **Só existe em `grades`:** `DefaultController::actionClassClosure($classroomId)` (`app/modules/grades/controllers/DefaultController.php` ≈L681–747), `actionClassClosureList` (≈L749–758) e `actionBatchClassClose` (≈L760), que só renderiza a view. O campo `is_closed` só é lido ou gravado nesses métodos (grep em `app` e `app/models`). O `resultsmanagement` **não** tem fechamento.
- **Chamadores:** `grades/views/default/batchclose.php` (≈L35 faz POST por turma em `?r=grades/default/ClassClosure&classroomId=` e ≈L45 lê a lista) e o link em `classroom/views/default/_form.php` ≈L874.

O que a action faz hoje:
- Com `classroomId` vazio ou 0 (o parâmetro é obrigatório na assinatura, então sem ele o Yii responde 400), pega até 10 turmas da escola e do ano da sessão com `aee = 0` e `is_closed = 0` (≈L686–688); com ele, pega aquela turma da escola da sessão, sem filtro de ano ou de fechamento (≈L689).
- Para cada turma, `DiaryEntryLock::assertUnlocked(..., GRADE)` (≈L692–694).
- **Só para turma que não é multietapa** (`TagUtils::isMultiStage(...) === false`, ≈L697): lista as disciplinas de `curricular_matrix` da etapa e do ano da sessão com `ed.id < 100` (≈L698–704) e, para cada uma, roda `saveGradeResults` (→ `CalculateGradeResultsUsecase`), busca a regra (join, ≈L711–717) e, por matrícula ativa, `CalculateFinalMediaUsecase` e (`rule_type === 'N'`) `ChageStudentStatusByGradeUsecase` com `$stage` e `$frequency` na ordem **correta** (≈L736).
- Grava `is_closed = 1` (≈L741–742), **dentro** do `if` da turma não multietapa, então as multietapa **nunca** fecham.
- Não usa transação, e `GetGradeUnitiesByDisciplineUsecase` roda dentro do loop de matrículas (≈L721–723).
- Responde `{"success": true}`.

O golden master do R2-GRADES-01 cobre o cálculo por matrícula, mas **não** esta orquestração (consulta das disciplinas, `is_closed`, limite de 10). O `tag-qa` abre este card com um teste de caracterização dela.

## Objetivo
O fechamento de turma é um service com testes, a action fica magra, e o resultado (notas, situações, `is_closed`) é idêntico ao atual.

## Passos
1. O `tag-qa` escreve primeiro, em `tests/Integration/Grades/ClassClosure/`, o teste de caracterização da action atual: turma não multietapa fechada, multietapa não fechada, `classroomId` informado e ausente (limite de 10), turma travada pelo `DiaryEntryLock`, regra `C` sem situação. Ele precisa passar no código atual (mesmo cuidado com o `TLog` descrito no R2-GRADES-01).
2. `CloseClassroomInput::fromArray()` (`classroomId` opcional, inteiro) e `CloseClassroomService::handle(Input, UserContext): Result`. A escola e o ano vêm do `UserContext`, não de `Yii::app()->user`.
3. `ClassroomClosureRepository`: turmas para fechar (as duas consultas das ≈L686–689), disciplinas da matriz (a consulta das ≈L698–704, com **bind**) e gravação do `is_closed`. Reaproveite `GradeRulesRepository::forClassroomStage()` e o cálculo por matrícula do `RecalculateFinalMediaService` (R2-GRADES-05); não duplique.
4. Preserve: o `if` que deixa as multietapa abertas, o limite de 10, a ordem `saveGradeResults` → média final → situação, e o fato de não haver transação. Registre no Handoff a sugestão de transação por turma.
5. Opcional, só se o golden master e o teste do passo 1 garantirem: carregar `GetGradeUnitiesByDisciplineUsecase` uma vez por disciplina e não por matrícula.
6. A action: a trava do `DiaryEntryLock` (como hoje), `service(...)->handle(...)` e `renderJson(['success' => true])`, o mesmo formato que o `batchclose.php` espera. Confira como a view trata a resposta e registre.
7. `actionClassClosureList` pode ficar como está, ou virar um método do repositório. Não mude o formato do JSON.
8. Testes: unitários do service com repositórios em memória e a integração do repositório; o teste do passo 1 e o golden master passam sem alteração.

## Fora de escopo
- Fechar turmas multietapa (comportamento atual preservado) e tornar o fechamento transacional.
- `actionBatchClassClose`, a view `batchclose.php` e o link do `classroom`.
- Corrigir os bugs do R2-GRADES-08.

## Critérios de aceite
- [ ] O teste de caracterização do passo 1 foi escrito antes e passa antes e depois da extração.
- [ ] `actionClassClosure` tem só a trava, a chamada ao service e a resposta `{"success": true}`.
- [ ] A consulta das disciplinas usa bind e está só no repositório.
- [ ] As turmas multietapa continuam sem `is_closed`; o limite de 10 continua.
- [ ] O golden master passa sem alteração; cobertura do código novo de pelo menos 80% e PHPStan max sem baseline.

## Como verificar
```bash
composer lint && composer analyse
composer test:unit -- --filter Grades
composer test:integration -- --filter Grades
curl -i -X POST -b "PHPSESSID=<sessão>" "http://localhost/?r=grades/default/ClassClosure&classroomId=<turma de teste>"
# esperado: 200 com {"success":true}; is_closed = 1 no banco de teste, só para turma não multietapa
```
- Confirmar: se algum outro módulo calcula resultado final de turma sem passar por `grades` (grep de `CalculateFinalMediaUsecase` mostrou só `DefaultController` e `EntryController`).
- Confirmar: o que o `batchclose.php` faz com respostas de erro (a action hoje não captura exceção).
- Testes de navegador seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes de rodar.

## Handoff
_Preenchido por quem executar._

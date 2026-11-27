---
id: R2-GRADES-08
title: "Rascunhar as issues dos bugs de notas para decisão de produto"
release: R2
module: grades
type: docs
skill: tag-task-runner
agents: [tag-plan-guardian]
status: pronto
depends_on: []
size: P
risk: baixo
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 2 (bugs de negócio) e seção 16 (item 6)"
write_scope:
  - .agents/tasks/R2-seguranca-estrutural-e-notas/R2-GRADES-08.md
read_only:
  - app/modules/grades/**
  - app/models/StudentEnrollment.php
  - app/models/GradeUnity.php
forbidden:
  - app/**
  - tests/**
  - themes/**
  - docs/**
  - composer.json
acceptance:
  - "O Handoff tem um rascunho de issue para cada um dos 4 bugs obrigatórios, com os campos do passo 2"
  - "git diff --stat main mostra só este arquivo alterado"
  - "Cada referência de arquivo e linha do rascunho foi conferida no código na data da execução"
---

# R2-GRADES-08: Rascunhar as issues dos bugs de notas para decisão de produto

## Contexto
Os bugs abaixo mudam notas, frequência ou aprovação de alunos, então **nenhum é corrigido em silêncio** durante o refactor (seção 4 do plano). Os cards R2-GRADES-02 a 07 os **preservam** e os registram em testes incompletos (`markTestIncomplete('Bug: R2-GRADES-08')`). Este card prepara o texto para a decisão de produto. Quem abre as issues é uma pessoa; o agente só rascunha no Handoff.

**Bugs obrigatórios** (linhas aproximadas; confirme todas na execução):
1. **Frequência na posição de `$stage`.** `DefaultController::actionSaveGradesRelease` (`app/modules/grades/controllers/DefaultController.php` ≈L379–386) chama `new ChageStudentStatusByGradeUsecase($gradeResult, $gradeRules, count(...), $frequency)`, mas o construtor é `($gradeResult, $gradeRule, $numUnities, $stage, $frequency = null)` (`usecases/ChageStudentStatusByGradeUsecase.php` ≈L22–29, com `$this->$stage = $stage` na ≈L28). A frequência cai em `$stage`, e a decisão de situação a ignora. As outras duas chamadas (≈L736 e ≈L819) estão corretas.
2. **Arredondamento diferente.** `ApplyFormulaOnGradesUsecase` trunca em uma casa (`floor($result * 10) / 10`, ≈L73). `CalculateNumericGradeUsecase` usa `round` (média com `round(x, 2)` ≈L329; unidades com `round(x, 1)` ≈L59 e L146; médias semestrais ≈L92 e L96) e, em `Peso`, devolve sem arredondar (≈L353) antes do `round(..., 1)`. Exemplo para o texto: média 6,95 vira 7,0 na nota da unidade e 6,9 na média final.
3. **Frequência 0% tratada como "sem frequência".** Em `ChageStudentStatusByGradeUsecase` ≈L103 e L105, `$frequency == null` é verdadeiro para `0` e `0.0`. Aluno com 0% e média suficiente sai `APROVADO`. O resultado depende do tipo (`'0'` não cai nessa regra).
4. **Exceção engolida.** `actionCalculateFinalMedia` ≈L823–826 faz `rollback` e `TLog::error` e termina com 200 e corpo vazio; o JS (`legacy/grades/_initialization.js` ≈L212) trata como sucesso. `Error` (`DivisionByZeroError`) escapa do `catch (Exception)`.

**Candidatos a confirmar antes de virar issue** (achados na leitura do código, ainda sem decisão):
5. `actionSaveGradesRelease` ≈L361: `round((($g - $f) / $g ?: 1) * 100)`. Pela precedência, 0 presença vira 100%. Latente enquanto o bug 1 existir.
6. `CalculateFinalMediaUsecase` ≈L126 e L131–134: as notas `[média, recuperação]` recebem os pesos `[weight_final_recovery, weight_final_media]`. Pode estar invertido.
7. `actionSaveGradesRelease` ≈L372–376 não passa `$gradesStudent` ao `CalculateFinalMediaUsecase`, então a recuperação parcial não entra nesse fluxo (as outras actions passam).
8. `EntryController` (`grades/entry/*`): ≈L196–200 passa 3 argumentos a um construtor de 4 obrigatórios, e ≈L381–385 passa `$enrollment->id` e `$disciplineId` onde o construtor espera `$gradeResult` e `$gradeRule`. Confirme se a rota `grades/entry/*` está em uso (há `GradesRoutes.php` ≈L44–51, views e `js/enrollment/**`).
9. O `gradesRelease` legado envia `classroomId` e `disciplineId` (`legacy/gradesRelease/_initialization.js` ≈L312–315), e a action lê `classroom` e `discipline`.

## Objetivo
Um rascunho de issue por bug, com evidência verificada, impacto e a pergunta de decisão, pronto para uma pessoa abrir.

## Passos
1. Releia cada ponto acima no código atual e atualize a linha. Para os itens 5 a 9, diga se se confirmam. O que não der para confirmar sem rodar o sistema vira "confirmar:".
2. Para cada bug, escreva no Handoff um rascunho com: título; onde (arquivo e linha); passos para reproduzir; comportamento atual × esperado (proposto); quem é afetado e como (aluno aprovado ou reprovado indevidamente? nota arredondada diferente?); a decisão que o produto precisa tomar; opções (por exemplo, para o bug 2: truncar em todo lugar, arredondar em todo lugar ou manter); o teste que fixa hoje o comportamento (`tests/...`) e o `markTestIncomplete` que o espera.
3. Para o bug 2, monte uma tabela com 3 a 5 valores (6,95; 7,99; 5,55) e o resultado de cada caminho, calculada à mão ou pelo golden master (R2-GRADES-01), sem rodar produção.
4. Para o bug 3, diga se o caso de 0% de frequência existe de fato nos dados (a pessoa pode consultar com uma query de leitura no banco de homologação; **não** rode nada em produção).
5. Liste no fim do Handoff a ordem sugerida de decisão (impacto primeiro) e quais bugs bloqueiam mudanças em quais cards (por exemplo, o bug 1 e o R2-GRADES-06).

## Fora de escopo
- Abrir as issues no GitHub ou no Jira (uma pessoa faz).
- Corrigir qualquer bug, escrever ou alterar testes ou código.
- Decidir o comportamento esperado: o agente propõe, o produto decide.

## Critérios de aceite
- [ ] Há um rascunho completo para cada um dos bugs 1 a 4, e uma conclusão (confirmado ou "confirmar:") para cada um dos itens 5 a 9.
- [ ] Todas as referências de arquivo e linha foram conferidas na data da execução.
- [ ] O bug 2 tem a tabela de valores.
- [ ] Cada rascunho aponta o teste que fixa o comportamento atual.
- [ ] Nenhum arquivo além deste card foi alterado.

## Como verificar
```bash
git diff --stat main   # esperado: só .agents/tasks/R2-seguranca-estrutural-e-notas/R2-GRADES-08.md
git grep -n "markTestIncomplete('Bug: R2-GRADES-08')" -- tests   # os testes dos cards 02 a 07 apontam para este card
```
A revisão humana é obrigatória: quem decide o comportamento é o produto.

## Handoff
_Preenchido por quem executar._

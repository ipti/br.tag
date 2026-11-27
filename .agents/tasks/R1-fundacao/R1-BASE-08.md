---
id: R1-BASE-08
title: "Higiene de autoload: um tipo por arquivo e primeiro tipo com o nome do arquivo"
release: R1
module: plataforma
type: fundacao
skill: .agents/playbooks/code-quality.md
agents: [tag-refactorer, tag-qa, tag-plan-guardian]
status: pronto
depends_on: [R1-BASE-05]
size: P
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 6 (B1, bloco Higiene que prepara o autoload futuro)"
write_scope:
  - app/models/Classroom.php
  - app/models/PeriodOptions.php
  - app/models/OrganizationFormOptions.php
  - app/modules/gestaopresente/controllers/DefaultController.php
  - app/modules/gestaopresente/models/**
  - app/modules/sedsp/models/Student/InCertidaoNasc.php
  - app/modules/sedsp/models/Student/CertidaoNasc.php
  - app/modules/grades/usecases/**
  - app/modules/foods/services/**
  - phpstan/baseline/**
read_only:
  - app/libraries/Educacenso/**
  - app/controllers/CensoController.php
  - app/modules/gestaopresente/GestaopresenteModule.php
  - app/modules/grades/GradesModule.php
  - app/modules/foods/FoodsModule.php
  - app/modules/sedsp/SedspModule.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/**
  - .github/**
acceptance:
  - "composer analyse"
  - "composer lint"
  - "git grep -nE '^(enum|class|interface|trait) ' -- app/models/Classroom.php   (exatamente 1 linha)"
  - "Smoke das telas de turma, de notas, de cardápio e de gestaopresente, sem diferença de comportamento"
---

# R1-BASE-08: Higiene de autoload: um tipo por arquivo e primeiro tipo com o nome do arquivo

## Contexto
**Card do coordenador:** `app/models/Classroom.php` é model compartilhado (arquivo com dono).

O autoload do Yii procura a classe pelo **nome do arquivo** dentro das pastas importadas. Arquivo que começa com outro tipo, ou que junta vários, só funciona se alguém carregou o arquivo antes; isso impede o `classmap` futuro (plano, seção 3.1). Levantamento por script (primeiro tipo declarado ≠ nome do arquivo, sem diferenciar maiúsculas; comentários ignorados; `views` e `soap` fora):

**Primeiro tipo diferente do nome do arquivo (5):**
- `app/models/Classroom.php`: começa com `enum PeriodOptions` (≈L110) e `enum OrganizationFormOptions` (≈L140), só depois vem `class Classroom` (≈L176).
- `app/modules/gestaopresente/controllers/DefaultController.php`: `enum EnrollemntStatusTAG` (≈L3) e `enum StudentEnrollmentStatusSGP` (≈L20) antes de `class DefaultController` (≈L60). Os dois enums só são usados nesse arquivo.
- `app/modules/sedsp/models/Student/InCertidaoNasc.php`: declara `class CertidaoNasc` (≈L3), que não é referenciada em nenhum outro ponto de `app/`.
- `app/libraries/Educacenso/RegisterIdentification.php`: começa com `enum RegisterIdentificationType` (≈L3), depois `class RegisterIdentification` (≈L9). A pasta `libraries` **não** é importada pelo Yii; o `CensoController` carrega `Educacenso.php` com `include` (≈L2734 e ≈L2762).
- `app/extensions/opcache-gui/index.php` (painel de terceiros, vários tipos): provável exceção; decidir com uma pessoa.

**Arquivos com vários tipos:**
- `app/modules/grades/usecases/GetStudentGradesByDisciplineUsecase.php`: 6 classes (a do nome mais `StructClassromGradeResult` ≈L433, `StudentGradesResult` ≈L589, `GradeUnityResult` ≈L675, `GradePartialRecoveryResult` ≈L711 e `GradeByModalityResult` ≈L751). `GradesModule` importa `grades.usecases.*`.
- `app/modules/foods/services/FoodMenuService.php`: 5 classes (`FoodMenuService` mais `FoodMenuObject` ≈L363, `MealObject` ≈L417, `MealComponentObject` ≈L455 e `IngredientObject` ≈L477). `FoodsModule` importa `foods.services.*`.
- Os de `Classroom.php`, `gestaopresente/.../DefaultController.php`, `RegisterIdentification.php` e `opcache-gui` já aparecem acima.

Também há 10 arquivos com diferença **só de caixa** entre classe e arquivo (por exemplo `StudentIMC.php` declara `StudentImc`, `FoodnoticeController.php` declara `FoodNoticeController`). Funcionam no Windows e quebram no Linux se o autoload for estrito: **registre no Handoff, não renomeie aqui**.

## Objetivo
Cada tipo da lista fica em um arquivo com o seu nome, em pasta que o Yii já importa, sem mudar o comportamento.

## Passos
1. Antes de mover cada tipo, confirme a **pasta de destino importada** (`Module::init`/`setImport` ou `main.php`). `models/` de `app/models`, `grades/usecases` e `foods/services` são importadas. `controllers/` de módulo **não** é: os enums do gestaopresente vão para o `models/` do módulo (crie a pasta e confirme o import em `GestaopresenteModule.php`; se não houver, acrescente a entrada correspondente em `GestaopresenteModule::init`, que é do módulo e não do `main.php`).
2. `Classroom.php`: crie `PeriodOptions.php` e `OrganizationFormOptions.php` em `app/models/` com o código dos enums, sem alterar nada além do `<?php`; deixe só `class Classroom` em `Classroom.php`.
3. `InCertidaoNasc.php`: como `CertidaoNasc` não é usada, renomeie o arquivo para `CertidaoNasc.php` (`git mv`) para casar com a classe. Registre a decisão no Handoff.
4. `GetStudentGradesByDisciplineUsecase.php` e `FoodMenuService.php`: um arquivo por classe, na mesma pasta, preservando o conteúdo (incluindo comentários e a ordem dos métodos).
5. `RegisterIdentification.php`: só mexa se conseguir mostrar como o arquivo é carregado hoje; como `libraries` é carregada por `include` no `CensoController` (área do R7-CENSO), a decisão padrão é **não mover** e registrar no Handoff.
6. Rode a baseline do PHPStan legado: nenhum erro novo de classe inexistente; se uma baseline listar um caminho que mudou, regenere só a baseline do módulo afetado.
7. Registre no Handoff os 10 casos de diferença de caixa e o `opcache-gui`.

## Fora de escopo
- Renomear classes ou arquivos por diferença de caixa; renomear os 9 nomes de classe duplicados; criar `classmap` para `app/models` (etapas seguintes do plano).
- Mudar lógica, visibilidade ou assinatura de qualquer classe.
- Mexer em `CensoController` ou em `app/libraries/Educacenso/` sem decisão humana.

## Critérios de aceite
- [ ] `Classroom.php` tem só `class Classroom`; os enums estão em arquivos próprios de `app/models/`.
- [ ] O gestaopresente, o `CertidaoNasc` e os dois arquivos grandes (`grades` e `foods`) seguem a regra de um tipo por arquivo, em pasta importada.
- [ ] `composer analyse` sem erro novo e `composer lint` verde.
- [ ] **O comportamento não muda:** as telas abaixo abrem e gravam como antes.
- [ ] O Handoff lista o que ficou de fora (libraries, opcache-gui, diferenças de caixa).

## Como verificar
```bash
composer analyse && composer lint
git grep -nE '^(enum|class|interface|trait) ' -- app/models/Classroom.php   # esperado: 1 linha
git grep -nE '^(enum|class|interface|trait) ' -- app/modules/foods/services/FoodMenuService.php   # esperado: 1 linha
git diff --stat -M   # moves detectados, sem alteração de conteúdo além do necessário
```
Smoke no navegador (seguem `.agents/rules/e2e-testing.md`: peça URL, credenciais e aprovação antes): lista e formulário de turma (usa `PeriodOptions` e `OrganizationFormOptions`), a tela de notas por disciplina (`grades`), o cardápio (`foods/foodmenu`), a tela inicial do `gestaopresente` e a consulta de aluno do `sedsp`.

## Handoff
_Preenchido por quem executar._

---
id: R7-SAGRES
title: "sagres: golden master do XML e quebra do SagresConsultModel em consultas e serviço de exportação"
release: R7
module: sagres
type: epico
skill: tag-task-runner
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho
depends_on: [R7-REFINE-01]
size: G
risk: alto
human_review: obrigatoria
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 8.6 (alto risco), seção 13 (smoke do sagres com JMS) e seção 4 (golden master antes de refatorar)"
write_scope:
  - app/modules/sagres/**
  - app/src/Sagres/**
  - tests/**/Sagres/**
  - frontend/pages/sagres/**
  - sass/scss/pages/_sagres.scss
  - app/config/public-routes/sagres.php
  - metrics/baseline/sagres.json
  - phpstan/baseline/sagres.neon
read_only:
  - app/modules/professional/models/**
forbidden:
  - composer.json
  - app/config/main.php
  - "themes/default/views/layouts/**"
  - sass/scss/main.scss
  - .github/workflows/**
  - app/models/**
acceptance:
  - "composer lint && composer analyse"
  - "O XML gerado bate com o golden master e valida contra app/modules/sagres/soap/Educacao.xsd (composer test:integration -- --filter Sagres; confirmar o script em R1)"
  - "php scripts/inventory-actions.php: nenhuma action do sagres sem proteção ou fora de public-routes/sagres.php"
---

# R7-SAGRES: sagres: golden master do XML e quebra do SagresConsultModel em consultas e serviço de exportação

## Contexto
**Uma pessoa lidera e o agente apoia.** Responsável: _a definir no R7-REFINE-01_. O XML vai para o órgão de controle externo; um campo errado vira inconsistência na prestação de contas da prefeitura.

**Para que serve:** exportar o arquivo "Educação" do SAGRES (XML dentro de um ZIP) a partir dos dados da escola, listar inconsistências antes do envio e manter o cadastro da unidade gestora (`ProvisionAccounts`: unidade gestora, responsável e gestor, com CPF). Os namespaces do XSD apontam para `www.tce.se.gov.br/sagres2025` (**confirmar:** órgão e versão do leiaute em vigor).

**Linha do inventário:** 1 controller (111 LOC, 5 actions, sem `accessControl`), 2 superglobais, **33 `createCommand`**, 0 JSON, 5 views, 2 `<script>`, 21 `style=`, 8 Bootstrap 2, JS 1 arquivo/15 linhas, 4 models.

**Detalhamento (conferido em 2026-11-27)**
- `controllers/DefaultController.php`: `index`, `createOrUpdate`, `inconsistencySagres`, `export($month, $finalClass, $noMovement, $withoutCpf)` e `download`. **Sem `filters()`.**
- `createOrUpdate` (≈L13–46): se a unidade gestora não existir, a action tenta redirecionar usando `$model->cod_unidade_gestora` com `$model` falso (erro). O `export` lê `$month`, `$finalClass` e as flags como parâmetros de URL, sobe o `memory_limit` para 2048M e chama `SagresConsultModel`. O `download` apaga o arquivo depois de entregar (`unlink`), em `app/export/SagresEdu/File_<INSTANCE>/Educacao.zip`.
- **`models/SagresConsultModel.php` (3039 linhas, 49 funções, 31 `createCommand`)** está no namespace `SagresEdu` e é, na prática, um **serviço de exportação**: `cleanInconsistences` (≈L66), `getInconsistenciesCount` (≈L752), `getSagresEdu`, `generatesSagresEduXML` (≈L2820) e `actionExportSagresXML` (≈L2853, nome de action dentro de um model). Mais 2 `createCommand` em `SagresValidations.php` (23 KB).
- **Código gerado:** o `config.yml` do xsd2php define os namespaces `TabInternas`, `SagresEdu` e `Genericos`, mas gera em `soap\src2` e `soap\metadata2`, enquanto o repositório versiona `soap/src` (24 arquivos PHP) e `soap/metadata` (24 arquivos). O script `sagres-gen` está em `composer.json` (≈L42). O `generatesSagresEduXML` carrega os metadados de `app/modules/sagres/soap/metadata/sagresEduMetadata`, então `src` e `metadata` são os ativos e o `config.yml` parece desatualizado. **confirmar:** se os conjuntos `src2` e `metadata2` existem em algum lugar. Os XSDs ficam em `soap/` (`Educacao.xsd` 195 KB, `Genericos.xsd` 55 KB, `TabelasInternas.xsd` 3 MB). O módulo importa `sagres.soap.src.sagresEdu.*` (`SagresModule`).
- Dependências cruzadas: importa `application.modules.professional.models.*`. A saída usa o JMS Serializer (risco da seção 13 do plano).
- Views: `index`, `inconsistencys`, `_form`, `create` e `update`; JS `resources/validations.js`. **Não há teste**: `grep` por `sagres` em `tests/` não retorna nada.

## Objetivo
O XML do SAGRES é reproduzido byte a byte por um serviço em `app/src/Sagres`, validado contra o XSD, com o `SagresConsultModel` desmontado em consultas pequenas.

## Passos
1. O card de refinamento (R7-REFINE-01) quebra este épico em cards `R7-SAGRES-<nn>`.
2. **SEC**
   - [ ] `filters()` com `accessControl` (secretaria e admin) e tratamento do `$model` inexistente em `createOrUpdate`.
   - [ ] Validar `$month`, `$finalClass`, `$noMovement` e `$withoutCpf` (tipos e faixas); POST e CSRF nas gravações.
   - [ ] Garantir escopo da escola e do ano nas 33 consultas, com bind.
3. **GM**
   - [ ] Gerar o XML de um tenant demo anonimizado, por mês e por combinação de flags, e congelá-lo.
   - [ ] Validar o XML contra `Educacao.xsd` no teste (falha se o esquema mudar).
4. **SVC**
   - [ ] Dividir o `SagresConsultModel` em classes de consulta em `app/src/Sagres/Infrastructure/Yii/` (escola, turma, aluno, professor, matrícula, inconsistências), um `SagresXmlBuilder` e um `ExportSagresService` com método `handle()`.
   - [ ] Não editar as classes geradas; decidir a pasta ativa (`src` ou `src2`) e corrigir o `config.yml`, por card próprio.
5. **API:** nenhuma prevista.
6. **JS:** `validations.js` e os `<script>` das views para `frontend/pages/sagres/`.
7. **CSS:** `sass/scss/pages/_sagres.scss`; mover os 21 `style=` e trocar os 8 usos de Bootstrap 2.

## Fora de escopo
- Mudar o leiaute do SAGRES ou as regras de negócio do órgão.
- Regenerar o código do xsd2php (card próprio, com aprovação da pessoa responsável).
- Trocar JMS Serializer ou xsd2php de versão.

## Critérios de aceite
- [ ] **SEC:** toda action está protegida ou listada em `app/config/public-routes/sagres.php`; o SQL usa bind; os formulários enviam CSRF.
- [ ] **GM:** o XML bate com o golden master e valida contra o XSD.
- [ ] **SVC:** as actions são magras; as regras estão em `app/src/Sagres/` com pelo menos 80% de cobertura; sem `createCommand` no controller nem no antigo model.
- [ ] **API:** não se aplica.
- [ ] **JS e CSS:** páginas em `frontend/pages/sagres/` e `_sagres.scss`; catraca sem `?r=` literais.
- [ ] A pessoa responsável aprovou cada PR.

## Como verificar
```bash
composer lint && composer analyse
composer test:integration -- --filter Sagres   # confirmar o script em R1
php scripts/inventory-actions.php
grep -c createCommand app/modules/sagres/models/SagresConsultModel.php   # esperado: 0 ou arquivo removido
curl -i "http://localhost/?r=sagres/default/download"   # visitante: 302 para o login
```
Testes de navegador (exportar e baixar) seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar._

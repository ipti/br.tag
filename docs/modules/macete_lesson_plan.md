# Modulo MACETE - Plano de Aula e Registro de Aula

Status: implementacao em andamento

Data: 2026-06-10

---

## Objetivo

Criar um novo modulo Yii em `app/modules/macete` para registrar planos de aula e registros de aula seguindo a metodologia MACETE.

O modulo deve nascer com estrutura propria e novas tabelas de banco de dados. Ele nao deve reutilizar as tabelas legadas de plano de aula como estrutura principal. O reaproveitamento previsto e apenas de elementos comuns do sistema, especialmente o cadastro/seletor de habilidades BNCC.

## Referencias analisadas

- Documento modelo: `Luana - Ingles - 1 planoo.docx`
- Modulo atual de plano de aula: `app/modules/courseplan`
- Diario de classe/aulas ministradas: `app/modules/classdiary`
- Cadastro global de habilidades BNCC: `app/models/CourseClassAbilities.php`
- Administracao de habilidades: `app/modules/abilities`
- Menus e permissao do Diario Eletronico:
  - `themes/default/views/layouts/menus/_admin_menu.php`
  - `themes/default/views/layouts/menus/_superuser_menu.php`
  - `app/components/auth/TFeature.php`
  - `app/components/auth/RbacSeeder.php`

## Modelo funcional MACETE

O documento modelo indica que o plano deve registrar, no minimo:

- Identificacao do plano
- Professor
- Escola
- Componente curricular
- Unidade/periodo
- Tema da aula
- Contextualizacao da localidade/territorio
- Objeto do conhecimento BNCC
- Texto de contextualizacao por etapa selecionada
- Habilidades BNCC
- Metodologia em fases de Aprendizagem Baseada em Desafios:
  - Envolver
  - Investigar
  - Agir
- Objetivos de aprendizagem
- Recursos didaticos
  - Caixa MACETE
  - Materiais adicionais
- Avaliacao continua, observacional e processual
- Adaptacoes
  - Criancas neurodivergentes
  - Recomposicao de aprendizagem
  - Turma multisseriada
  - Falta de material
- Referencias
- Sugestoes/anexos, como fichas de atividades e jogos pedagogicos prontos

## Escopo inicial sugerido

### Plano de aula MACETE

Permitir que professor ou equipe pedagogica cadastre um plano estruturado, com selecao de escola, uma ou mais etapas, turma opcional, componente curricular e habilidades BNCC.

O plano deve suportar secoes textuais extensas, pois a metodologia depende de contextualizacao territorial, adaptacoes e estrategias por etapa selecionada.

### Registro de aula MACETE

Permitir registrar a execucao de uma aula vinculada a um plano MACETE. O registro deve guardar data, turma, componente curricular, professor, plano utilizado, observacoes da execucao e eventuais ajustes em relacao ao plano.

No primeiro corte, o registro pode ser independente do fluxo legado de `class_contents`. Uma integracao com a contagem oficial de Aulas Ministradas deve ser tratada como etapa especifica, porque o fluxo atual do diario depende diretamente de `course_plan` e `course_class`.

### Materiais e anexos

Prever cadastro de materiais/sugestoes do plano, incluindo fichas e jogos pedagogicos. A primeira versao pode guardar metadados e caminho/arquivo anexo, sem precisar recriar visualmente as fichas dentro do formulario.

## Arquitetura proposta

Criar o modulo:

```text
app/modules/macete/
  MaceteModule.php
  MaceteRoutes.php
  controllers/
    LessonPlanController.php
    LessonRecordController.php
    AbilityController.php
  models/
    MaceteLessonPlan.php
    MaceteLessonPlanAbility.php
    MaceteLessonPlanSection.php
    MaceteLessonPlanStage.php
    MaceteLessonPlanResource.php
    MaceteLessonMaterial.php
    MaceteLessonRecord.php
    MaceteLessonRecordAbility.php
  services/
    MaceteLessonPlanService.php
    MaceteLessonRecordService.php
    MaceteAbilityService.php
  resources/
    lesson-plan.js
    lesson-record.js
  views/
    lessonPlan/
      index.php
      create.php
      update.php
      _form.php
      _table.php
    lessonRecord/
      index.php
      create.php
      update.php
      _form.php
```

O modulo deve seguir o padrao de assets publicados usado por `courseplan` e `aeerecord`, com `baseScriptUrl` apontando para `resources/`.

## Banco de dados proposto

Criar uma migration SQL em `app/migrations/3.13.13/`, seguindo o padrao atual da release.

### `macete_lesson_plan`

Tabela principal do plano.

Campos sugeridos:

- `id`
- `name`
- `theme`
- `school_inep_fk`
- `classroom_fk` nullable
- `edcenso_stage_vs_modality_fk`
- `edcenso_discipline_fk` nullable para casos de educacao infantil/unificada
- `users_fk`
- `school_year`
- `unit`
- `territory_context`
- `knowledge_object`
- `evaluation`
- `references_text`
- `status`
- `created_at`
- `updated_at`

Observacao: `edcenso_stage_vs_modality_fk` permanece como etapa principal de compatibilidade. As etapas realmente selecionadas pelo professor ficam no relacionamento `macete_lesson_plan_stage`.

### `macete_lesson_plan_stage`

Relacionamento entre plano MACETE e as etapas escolhidas pelo professor.

Campos sugeridos:

- `id`
- `lesson_plan_fk`
- `edcenso_stage_vs_modality_fk`
- `created_at`
- `updated_at`

### `macete_lesson_plan_ability`

Relacionamento entre plano MACETE e habilidades BNCC.

Campos sugeridos:

- `id`
- `lesson_plan_fk`
- `ability_fk`
- `created_at`
- `updated_at`

`ability_fk` deve referenciar `course_class_abilities(id)`.

### `macete_lesson_plan_section`

Secoes flexiveis do plano.

Campos sugeridos:

- `id`
- `lesson_plan_fk`
- `section_type`
- `title`
- `target_group` usando o formato `stage_<id>` quando a secao for especifica de uma etapa
- `content`
- `position`
- `created_at`
- `updated_at`

Tipos iniciais de `section_type`:

- `YEAR_CONTEXT`
- `METHODOLOGY_INVOLVE`
- `METHODOLOGY_INVESTIGATE`
- `METHODOLOGY_ACT`
- `LEARNING_OBJECTIVE`
- `ADAPTATION_NEURODIVERGENT`
- `ADAPTATION_RECOVERY`
- `ADAPTATION_MULTIGRADE`
- `ADAPTATION_MISSING_MATERIAL`

### `macete_lesson_plan_resource`

Recursos didaticos do plano.

Campos sugeridos:

- `id`
- `lesson_plan_fk`
- `resource_type`
- `name`
- `amount`
- `description`
- `created_at`
- `updated_at`

Tipos iniciais:

- `MACETE_BOX`
- `ADDITIONAL`

### `macete_lesson_material`

Fichas, jogos e materiais anexos.

Campos sugeridos:

- `id`
- `lesson_plan_fk`
- `title`
- `material_type`
- `description`
- `file_path`
- `created_at`
- `updated_at`

Tipos iniciais:

- `ACTIVITY_SHEET`
- `GAME`
- `SUPPORT_MATERIAL`

### `macete_lesson_record`

Registro da aula executada.

Campos sugeridos:

- `id`
- `lesson_plan_fk`
- `school_inep_fk`
- `classroom_fk`
- `edcenso_stage_vs_modality_fk`
- `edcenso_discipline_fk` nullable
- `users_fk`
- `lesson_date`
- `executed_content`
- `methodology_notes`
- `evaluation_notes`
- `adaptation_notes`
- `status`
- `created_at`
- `updated_at`

### `macete_lesson_record_ability`

Opcional para permitir que a aula executada registre habilidades diferentes das planejadas.

Campos sugeridos:

- `id`
- `lesson_record_fk`
- `ability_fk`
- `created_at`
- `updated_at`

## Reaproveitamento de BNCC

O modulo novo deve consultar `CourseClassAbilities` diretamente ou por meio de `MaceteAbilityService`.

Nao e recomendado chamar os endpoints de `courseplan/courseplan/getAbilities*`, porque isso criaria acoplamento do modulo MACETE ao modulo legado. A logica pode ser adaptada para endpoints proprios em `macete/ability`.

Endpoints sugeridos:

- `macete/ability/search`
- `macete/ability/initialStructure`
- `macete/ability/nextStructure`

## UI e design system

Usar o design system TAG.

Classes preferenciais:

- Botoes: `t-button-primary`, `t-button-secondary`, `t-button-danger`
- Tabelas: `tag-table-primary`
- Campos: `t-field-text`, `t-field-select`, `t-field-tarea`
- Abas/etapas: `t-tabs`
- Badges: `t-badge-info`, `t-badge-success`, `t-badge-warning`, `t-badge-critical`

Evitar adicionar novas classes Bootstrap. Classes legadas podem aparecer por compatibilidade, mas nao devem guiar a UI nova.

Se forem adicionados estilos SCSS, rodar:

```bash
composer run sass:build
```

## Menus e permissao

O menu atual do Diario Eletronico possui:

- Plano de Aula usando `FEAT_DIARY_LESSON_PLAN`
- Aulas Ministradas usando `FEAT_DIARY_CLASSES`

Decisao pendente:

- Usar as features existentes para o modulo MACETE, se ele for uma nova experiencia dentro do mesmo dominio.
- Criar features novas, por exemplo `FEAT_DIARY_MACETE_PLAN` e `FEAT_DIARY_MACETE_RECORD`, se o acesso precisar ser controlado separadamente.

## Integracao com aulas ministradas legadas

O `classdiary` atual registra aulas ministradas em `class_contents`, vinculando diretamente `schedule_fk` e `course_class_fk`.

Como o modulo MACETE tera tabelas novas, existem duas opcoes:

1. Primeira versao independente:
   - `macete_lesson_record` guarda a execucao da aula.
   - Nao interfere nos relatorios atuais de `class_contents`.

2. Integracao com diario oficial:
   - Criar ponte explicita entre `macete_lesson_record` e o fluxo de `class_contents`.
   - Definir se o registro MACETE deve gerar ou referenciar um `course_class` legado.
   - Revisar relatorios de aulas ministradas antes de ativar.

Recomendacao inicial: implementar a primeira versao independente e tratar a integracao oficial como segunda etapa.

## Passos de implementacao

1. Criar migration SQL em `app/migrations/3.13.13/`.
2. Criar `app/modules/macete`.
3. Registrar o modulo em `app/config/main.php`.
4. Criar models locais do modulo.
5. Criar services para salvar plano e registro em transacao.
6. Criar endpoints proprios para habilidades BNCC.
7. Criar views de listagem, criacao e edicao.
8. Adicionar entrada no menu do Diario Eletronico.
9. Definir e aplicar regra de permissao.
10. Gerar rotas:

```bash
composer run routes:generate -- macete
```

11. Executar dry-run da migration:

```bash
composer run migrate:dry app/migrations/3.13.13/<arquivo>.sql -- --dry-run
```

12. Executar verificacoes PHP relevantes:

```bash
composer run lint
composer run analyse
composer run mess
```

## Decisoes pendentes

- Confirmar nome do modulo: `macete`.
- Confirmar se o registro MACETE deve contar imediatamente como Aulas Ministradas oficiais.
- Confirmar se o menu deve substituir o Plano de Aula atual ou aparecer como nova entrada.
- Confirmar se as permissoes serao reaproveitadas ou se serao criadas features novas.
- Confirmar politica de anexos: upload local, somente caminho de arquivo, ou integracao posterior com outro armazenamento.

<?php
/** @var $this LessonPlanController
 *  @var $lessonPlan MaceteLessonPlan
 *  @var $stages array
 *  @var $selectedStageIds array
 *  @var $selectedStages EdcensoStageVsModality[]
 *  @var $disciplines array
 *  @var $sectionValues array
 *  @var $resourceValues array
 *  @var $materialValues array
 *  @var $selectedAbilities CourseClassAbilities[]
 *  @var $schoolName string
 *  @var $professorName string
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/macete.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/lesson-plan.js?v=' . TAG_VERSION, CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', [
    'id' => 'macete-lesson-plan-form',
    'enableAjaxValidation' => false,
]);

$sectionValue = static function (string $type, string $target = 'general') use ($sectionValues): string {
    return $sectionValues[$type][$target] ?? '';
};

$resourceValue = static function (string $type) use ($resourceValues): string {
    return $resourceValues[$type] ?? '';
};

$materialValue = static function (string $type, string $field) use ($materialValues): string {
    return $materialValues[$type][$field] ?? '';
};

$selectedStageMap = array_flip(array_map('intval', $selectedStageIds ?? []));
$stageTarget = static function ($stageId): string {
    return 'stage_' . (int) $stageId;
};
$isStageSelected = static function ($stageId) use ($selectedStageMap): bool {
    return isset($selectedStageMap[(int) $stageId]);
};

$selectedAbilitiesCount = count($selectedAbilities);
?>

<div class="main">
    <div class="mobile-row">
        <div class="column">
            <h1><?php echo $lessonPlan->isNewRecord ? 'Novo Plano MACETE' : 'Editar Plano MACETE'; ?></h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <a class="t-button-secondary"
                href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONPLAN_INDEX); ?>">Voltar</a>
            <?php if (!$lessonPlan->isNewRecord): ?>
                <a class="t-button-secondary"
                    href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONRECORD_CREATE, ['lessonPlanId' => $lessonPlan->id]); ?>">
                    Registrar aula
                </a>
            <?php endif; ?>
            <button class="t-button-primary" type="submit">Salvar</button>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <?php echo $form->errorSummary($lessonPlan); ?>

    <div class="macete-form-layout">
        <div class="macete-form-layout__content">

            <div class="t-tabs js-macete-tabs">
                <ul class="t-tabs__list">
                    <li class="t-tabs__item active">
                        <a class="t-tabs__link js-macete-tab-link" href="#macete-identification">
                            <span class="t-tabs__numeration">1</span> Identificação
                        </a>
                    </li>
                    <li class="t-tabs__item">
                        <a class="t-tabs__link js-macete-tab-link" href="#macete-methodology">
                            <span class="t-tabs__numeration">2</span> Metodologia
                        </a>
                    </li>
                    <li class="t-tabs__item">
                        <a class="t-tabs__link js-macete-tab-link" href="#macete-complementary">
                            <span class="t-tabs__numeration">3</span> Complementar
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tag-inner">

                <!-- Tab 1: Identificação -->
                <div id="macete-identification" class="js-macete-tab-panel">

                    <div class="columns">
                        <div class="column is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label($lessonPlan, 'name', ['class' => 't-field-text__label--required']); ?>
                                <?php echo $form->textField($lessonPlan, 'name', [
                                    'class' => 't-field-text__input js-macete-name',
                                    'maxlength' => 150,
                                    'placeholder' => 'Ex.: Inglês - Alfabeto no meu lugar',
                                ]); ?>
                                <?php echo $form->error($lessonPlan, 'name'); ?>
                            </div>
                        </div>
                        <div class="column is-one-fifth">
                            <div class="t-field-text">
                                <?php echo $form->labelEx($lessonPlan, 'unit', ['class' => 't-field-text__label']); ?>
                                <?php echo $form->textField($lessonPlan, 'unit', [
                                    'class' => 't-field-text__input js-macete-unit',
                                    'maxlength' => 50,
                                    'placeholder' => 'Ex.: II Unidade',
                                ]); ?>
                                <?php echo $form->error($lessonPlan, 'unit'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="columns" style="align-items: flex-start;">
                        <div class="column is-one-third">
                            <div class="t-field-select">
                                <?php echo CHtml::label('Etapas', 'stage_ids', ['class' => 't-field-select__label--required']); ?>
                                <?php echo CHtml::dropDownList(
                                    'stage_ids[]',
                                    $selectedStageIds,
                                    CHtml::listData($stages, 'id', 'name'),
                                    [
                                        'class' => 'select-search-on t-field-select__input js-macete-stage',
                                        'multiple' => 'multiple',
                                        'id' => 'stage_ids',
                                        'style' => 'width:100%',
                                    ]
                                ); ?>
                                <?php echo $form->error($lessonPlan, 'edcenso_stage_vs_modality_fk'); ?>
                            </div>
                        </div>
                        <div class="column is-one-third">
                            <div class="t-field-select">
                                <?php echo $form->labelEx($lessonPlan, 'edcenso_discipline_fk', ['class' => 't-field-select__label']); ?>
                                <?php echo $form->dropDownList(
                                    $lessonPlan,
                                    'edcenso_discipline_fk',
                                    CHtml::listData($disciplines, 'id', 'name'),
                                    [
                                        'class' => 'select-search-on t-field-select__input js-macete-discipline',
                                        'prompt' => 'Selecione o componente',
                                        'data-initial-value' => $lessonPlan->edcenso_discipline_fk,
                                        'style' => 'width:100%',
                                    ]
                                ); ?>
                                <?php echo $form->error($lessonPlan, 'edcenso_discipline_fk'); ?>
                            </div>
                        </div>
                        <div class="column is-one-fifth">
                            <div class="t-field-select">
                                <?php echo $form->label($lessonPlan, 'status', ['class' => 't-field-select__label--required']); ?>
                                <?php echo $form->dropDownList(
                                    $lessonPlan,
                                    'status',
                                    MaceteLessonPlan::statusLabels(),
                                    [
                                        'class' => 'select-search-on t-field-select__input js-macete-status',
                                        'style' => 'width:100%',
                                    ]
                                ); ?>
                                <?php echo $form->error($lessonPlan, 'status'); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tab 2: Metodologia -->
                <div id="macete-methodology" class="js-macete-tab-panel hide">

                    <div class="columns">
                        <div class="column is-two-fifths">
                            <div class="t-field-text">
                                <?php echo $form->label($lessonPlan, 'theme', ['class' => 't-field-text__label--required']); ?>
                                <?php echo $form->textField($lessonPlan, 'theme', [
                                    'class' => 't-field-text__input',
                                    'maxlength' => 255,
                                    'placeholder' => 'Tema da aula',
                                ]); ?>
                                <?php echo $form->error($lessonPlan, 'theme'); ?>
                            </div>
                        </div>
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'territory_context', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'territory_context', [
                                    'class' => 't-field-tarea__input',
                                    'rows' => 3,
                                    'placeholder' => 'Contextualize a escola, comunidade e território.',
                                ]); ?>
                                <?php echo $form->error($lessonPlan, 'territory_context'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column is-three-fifths">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'knowledge_object', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'knowledge_object', [
                                    'class' => 't-field-tarea__input large',
                                    'rows' => 4,
                                    'placeholder' => 'Objeto do conhecimento BNCC.',
                                ]); ?>
                                <?php echo $form->error($lessonPlan, 'knowledge_object'); ?>
                            </div>
                        </div>
                        <div class="column is-two-fifths">
                            <div class="t-field-select">
                                <label class="t-field-select__label">Habilidades BNCC</label>
                                <input type="hidden" class="js-macete-ability-search" style="width:100%;">
                                <div class="courseplan-abilities-selected js-macete-abilities-selected">
                                    <?php foreach ($selectedAbilities as $ability): ?>
                                        <div class="ability-panel-option">
                                            <input type="hidden" class="ability-panel-option-id" name="abilities[]"
                                                value="<?php echo (int) $ability->id; ?>">
                                            <i class="fa fa-check-square"></i>
                                            <span>(<b><?php echo CHtml::encode($ability->code); ?></b>)
                                                <?php echo CHtml::encode($ability->description); ?></span>
                                            <i class="fa fa-remove remove-abilitie js-macete-remove-ability"></i>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2>Metodologia MACETE (Aprendizagem Baseada em Desafios)</h2>

                    <div class="macete-phase-cards">
                        <?php foreach ([
                            MaceteLessonPlanSection::TYPE_METHODOLOGY_INVOLVE => ['title' => 'Envolver', 'icon' => 'fa-users'],
                            MaceteLessonPlanSection::TYPE_METHODOLOGY_INVESTIGATE => ['title' => 'Investigar', 'icon' => 'fa-search'],
                            MaceteLessonPlanSection::TYPE_METHODOLOGY_ACT => ['title' => 'Agir', 'icon' => 'fa-bullseye'],
                        ] as $type => $phase): ?>
                            <div class="macete-phase-card">
                                <div class="macete-phase-card__header">
                                    <i class="fa <?php echo $phase['icon']; ?>"></i>
                                    <?php echo CHtml::encode($phase['title']); ?>
                                </div>
                                <div class="macete-phase-card__body">
                                    <div class="js-macete-stage-empty <?php echo empty($selectedStageIds) ? '' : 'hide'; ?>">
                                        Selecione etapas na aba Identificação.
                                    </div>
                                    <?php foreach ($stages as $stage): ?>
                                        <?php
                                        $stageId = (int) $stage['id'];
                                        $target = $stageTarget($stageId);
                                        $selected = $isStageSelected($stageId);
                                        ?>
                                        <div class="js-macete-stage-field <?php echo $selected ? '' : 'hide'; ?>" data-stage-id="<?php echo $stageId; ?>">
                                            <label class="t-field-tarea__label"><?php echo CHtml::encode($stage['name']); ?></label>
                                            <?php echo CHtml::textArea(
                                                'sections[' . $type . '][' . $target . ']',
                                                $sectionValue($type, $target),
                                                array_merge(
                                                    ['class' => 't-field-tarea__input', 'rows' => 5],
                                                    $selected ? [] : ['disabled' => 'disabled']
                                                )
                                            ); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h3>Contextualização por etapa</h3>
                    <div class="columns" style="flex-wrap: wrap;">
                        <div class="column is-full js-macete-stage-empty <?php echo empty($selectedStageIds) ? '' : 'hide'; ?>">
                            <p>Selecione etapas na aba Identificação para preencher os textos por etapa.</p>
                        </div>
                        <?php foreach ($stages as $stage): ?>
                            <?php
                            $stageId = (int) $stage['id'];
                            $target = $stageTarget($stageId);
                            $selected = $isStageSelected($stageId);
                            ?>
                            <div class="column is-two-fifths js-macete-stage-field <?php echo $selected ? '' : 'hide'; ?>" data-stage-id="<?php echo $stageId; ?>">
                                <div class="t-field-tarea">
                                    <label class="t-field-tarea__label">
                                        Contextualização — <?php echo CHtml::encode($stage['name']); ?>
                                    </label>
                                    <?php echo CHtml::textArea(
                                        'sections[' . MaceteLessonPlanSection::TYPE_YEAR_CONTEXT . '][' . $target . ']',
                                        $sectionValue(MaceteLessonPlanSection::TYPE_YEAR_CONTEXT, $target),
                                        array_merge(
                                            ['class' => 't-field-tarea__input', 'rows' => 4],
                                            $selected ? [] : ['disabled' => 'disabled']
                                        )
                                    ); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="columns">
                        <div class="column is-three-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Objetivos de aprendizagem</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_LEARNING_OBJECTIVE . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_LEARNING_OBJECTIVE),
                                    ['class' => 't-field-tarea__input large', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Tab 3: Complementar — recursos, materiais, adaptações, avaliação, referências -->
                <div id="macete-complementary" class="js-macete-tab-panel hide">

                    <h2>Recursos</h2>
                    <div class="columns">
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Caixa MACETE</label>
                                <?php echo CHtml::textArea(
                                    'resources[' . MaceteLessonPlanResource::TYPE_MACETE_BOX . ']',
                                    $resourceValue(MaceteLessonPlanResource::TYPE_MACETE_BOX),
                                    ['class' => 't-field-tarea__input', 'rows' => 6, 'placeholder' => 'Liste os materiais da caixa MACETE usados na aula.']
                                ); ?>
                            </div>
                        </div>
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Materiais adicionais</label>
                                <?php echo CHtml::textArea(
                                    'resources[' . MaceteLessonPlanResource::TYPE_ADDITIONAL . ']',
                                    $resourceValue(MaceteLessonPlanResource::TYPE_ADDITIONAL),
                                    ['class' => 't-field-tarea__input', 'rows' => 6, 'placeholder' => 'Flashcards, cartolina, som, imagens etc.']
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <?php foreach (MaceteLessonMaterial::typeLabels() as $type => $label): ?>
                        <h3><?php echo CHtml::encode($label); ?></h3>
                        <div class="columns">
                            <div class="column is-one-quarter">
                                <div class="t-field-text">
                                    <label class="t-field-text__label">Título</label>
                                    <?php echo CHtml::textField(
                                        'materials[' . $type . '][title]',
                                        $materialValue($type, 'title'),
                                        ['class' => 't-field-text__input', 'maxlength' => 150]
                                    ); ?>
                                </div>
                            </div>
                            <div class="column is-one-quarter">
                                <div class="t-field-text">
                                    <label class="t-field-text__label">Arquivo / link</label>
                                    <?php echo CHtml::textField(
                                        'materials[' . $type . '][file_path]',
                                        $materialValue($type, 'file_path'),
                                        ['class' => 't-field-text__input', 'maxlength' => 255]
                                    ); ?>
                                </div>
                            </div>
                            <div class="column is-two-fifths">
                                <div class="t-field-tarea">
                                    <label class="t-field-tarea__label">Descrição</label>
                                    <?php echo CHtml::textArea(
                                        'materials[' . $type . '][description]',
                                        $materialValue($type, 'description'),
                                        ['class' => 't-field-tarea__input', 'rows' => 3]
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <h2>Adaptações</h2>
                    <div class="columns">
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Crianças neurodivergentes</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_ADAPTATION_NEURODIVERGENT . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_ADAPTATION_NEURODIVERGENT),
                                    ['class' => 't-field-tarea__input', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Recomposição de aprendizagem</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_ADAPTATION_RECOVERY . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_ADAPTATION_RECOVERY),
                                    ['class' => 't-field-tarea__input', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Turma multisseriada</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_ADAPTATION_MULTIGRADE . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_ADAPTATION_MULTIGRADE),
                                    ['class' => 't-field-tarea__input', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                        <div class="column is-two-fifths">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Caso falte material</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_ADAPTATION_MISSING_MATERIAL . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_ADAPTATION_MISSING_MATERIAL),
                                    ['class' => 't-field-tarea__input', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-three-fifths">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'evaluation', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'evaluation', ['class' => 't-field-tarea__input', 'rows' => 5]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-three-fifths">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'references_text', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'references_text', ['class' => 't-field-tarea__input', 'rows' => 5]); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- .tag-inner -->
        </div><!-- .macete-form-layout__content -->

        <!-- Sidebar: Resumo do plano -->
        <aside class="macete-form-layout__sidebar">
            <div class="macete-summary">
                <h3 class="macete-summary__title">Resumo do plano</h3>

                <?php if ($schoolName !== ''): ?>
                    <div class="macete-summary__item">
                        <span class="macete-summary__label">Escola</span>
                        <span class="macete-summary__value"><?php echo CHtml::encode($schoolName); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($professorName !== ''): ?>
                    <div class="macete-summary__item">
                        <span class="macete-summary__label">Professor(a)</span>
                        <span class="macete-summary__value"><?php echo CHtml::encode($professorName); ?></span>
                    </div>
                <?php endif; ?>

                <div class="macete-summary__item">
                    <span class="macete-summary__label">Componente curricular</span>
                    <span class="macete-summary__value js-macete-summary-discipline">—</span>
                </div>

                <div class="macete-summary__item">
                    <span class="macete-summary__label">Etapa / Ano</span>
                    <span class="macete-summary__value js-macete-summary-stage">—</span>
                </div>

                <div class="macete-summary__item">
                    <span class="macete-summary__label">Unidade</span>
                    <span class="macete-summary__value js-macete-summary-unit">—</span>
                </div>

                <div class="macete-summary__item">
                    <span class="macete-summary__label">Status</span>
                    <span class="macete-summary__value js-macete-summary-status">
                        <span class="<?php echo $lessonPlan->getStatusBadgeClass(); ?>">
                            <?php echo CHtml::encode($lessonPlan->getStatusLabel()); ?>
                        </span>
                    </span>
                </div>

                <div class="macete-summary__item">
                    <span class="macete-summary__label">Habilidades selecionadas</span>
                    <span class="macete-summary__value js-macete-summary-abilities">
                        <?php echo $selectedAbilitiesCount; ?>
                        <?php echo $selectedAbilitiesCount === 1 ? 'habilidade' : 'habilidades'; ?>
                    </span>
                </div>

                <div class="macete-summary__info">
                    <i class="fa fa-info-circle"></i>
                    Seu plano será salvo como rascunho até a conclusão de todos os passos.
                </div>
            </div>
        </aside>

    </div><!-- .macete-form-layout -->

    <div class="row reverse show--tablet">
        <div class="t-buttons-container">
            <a class="t-button-secondary"
                href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONPLAN_INDEX); ?>">Voltar</a>
            <button class="t-button-primary" type="submit">Salvar</button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

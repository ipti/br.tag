<?php
/** @var $this LessonsplanController
 *  @var $lessonPlan MaceteLessonPlan
 *  @var $stages array
 *  @var $selectedStageIds array
 *  @var $stageComponents array
 *  @var $stageComponentDisciplines array
 *  @var $sectionValues array
 *  @var $resourceValues array
 *  @var $materialValues array
 *  @var $selectedAbilities CourseClassAbilities[]
 *  @var $schoolName string
 *  @var $professorName string
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/quill.snow.css?v=' . TAG_VERSION);
$cs->registerScriptFile($themeUrl . '/js/quill.min.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/macete.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/rich-text.js?v=' . TAG_VERSION, CClientScript::POS_END);
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
                href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_INDEX); ?>">Voltar</a>
            <?php if (!$lessonPlan->isNewRecord): ?>
                <a class="t-button-secondary"
                    href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_CREATE, ['lessonPlanId' => $lessonPlan->id]); ?>">
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
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                    </li>
                    <li class="t-tabs__item">
                        <a class="t-tabs__link js-macete-tab-link" href="#macete-methodology">
                            <span class="t-tabs__numeration">2</span> Metodologia
                        </a>
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                    </li>
                    <li class="t-tabs__item">
                        <a class="t-tabs__link js-macete-tab-link" href="#macete-complementary">
                            <span class="t-tabs__numeration">3</span> Complementar
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tag-inner t-margin-large--top">

                <!-- Tab 1: Identificação -->
                <div id="macete-identification" class="js-macete-tab-panel">

                    <div class="row">
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
                        <div class="column is-two-fifths">
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
                        <div class="column is-one-fifth">
                            <div class="t-field-select">
                                <?php echo $form->label($lessonPlan, 'status', ['class' => 't-field-select__label--required']); ?>
                                <?php echo $form->dropDownList(
                                    $lessonPlan,
                                    'status',
                                    MaceteLessonPlan::statusLabels(),
                                    [
                                        'class' => 'select-search-on t-field-select__input js-macete-status',
                                    ]
                                ); ?>
                                <?php echo $form->error($lessonPlan, 'status'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <div class="t-field-select macete-stage-repeater">
                                <label class="t-field-select__label--required">Etapas e componentes curriculares</label>
                                <div class="js-macete-stage-components">
                                    <?php foreach ($stageComponents as $index => $stageComponent): ?>
                                        <div class="row align-items--end js-macete-stage-component-row">
                                            <div class="column is-two-fifths clearleft">
                                                <div class="t-field-select">
                                                    <label class="t-field-select__label">Etapa</label>
                                                    <?php echo CHtml::dropDownList(
                                    'stage_components[' . $index . '][stage_id]',
                                    $stageComponent['stage_id'],
                                    CHtml::listData($stages, 'id', 'name'),
                                    [
                                        'class' => 'select-search-on t-field-select__input js-macete-stage-component-stage',
                                        'prompt' => 'Selecione a etapa',
                                    ]
                                ); ?>
                                                </div>
                                            </div>
                                            <div class="column is-two-fifths">
                                                <div class="t-field-select">
                                                    <label class="t-field-select__label">Componente curricular</label>
                                                    <?php echo CHtml::dropDownList(
                                                        'stage_components[' . $index . '][discipline_id]',
                                                        $stageComponent['discipline_id'],
                                                        CHtml::listData($stageComponentDisciplines[$index] ?? [], 'id', 'name'),
                                                        [
                                                            'class' => 'select-search-on t-field-select__input js-macete-stage-component-discipline',
                                                            'prompt' => 'Selecione o componente',
                                                        ]
                                                    ); ?>
                                                </div>
                                            </div>
                                            <div class="column is-one-fifth">
                                                <button type="button"
                                                    class="t-button-secondary js-macete-remove-stage-component">Remover</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="t-button-secondary js-macete-add-stage-component">Adicionar
                                    etapa</button>
                                <?php echo $form->error($lessonPlan, 'edcenso_stage_vs_modality_fk'); ?>
                            </div>
                        </div>
                    </div>

                    <script type="text/template" id="macete-stage-component-template">
                        <div class="row align-items--end js-macete-stage-component-row">
                            <div class="column is-two-fifths clearleft"><div class="t-field-select"><label class="t-field-select__label">Etapa</label><select class="select-search-on t-field-select__input js-macete-stage-component-stage" name="stage_components[__index__][stage_id]"><option value="">Selecione a etapa</option><?php foreach ($stages as $stage): ?><option value="<?php echo (int) $stage['id']; ?>"><?php echo CHtml::encode($stage['name']); ?></option><?php endforeach; ?></select></div></div>
                            <div class="column is-two-fifths"><div class="t-field-select"><label class="t-field-select__label">Componente curricular</label><select class="select-search-on t-field-select__input js-macete-stage-component-discipline" name="stage_components[__index__][discipline_id]"><option value="">Selecione a etapa primeiro</option></select></div></div>
                            <div class="column is-one-fifth"><button type="button" class="t-button-secondary js-macete-remove-stage-component">Remover</button></div>
                        </div>
                    </script>

                </div>

                <!-- Tab 2: Metodologia -->
                <div id="macete-methodology" class="js-macete-tab-panel hide">

                    <div class="row align-items--start">
                        <div class="column">
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
                        <div class="column">
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
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
                        <div class="column">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'territory_context', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'territory_context', [
                                    'class' => 't-field-tarea__input',
                                    'rows' => 4,
                                    'placeholder' => 'Contextualize a escola, comunidade e território.',
                                ]); ?>
                                <?php echo $form->error($lessonPlan, 'territory_context'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="column">
                            <div class="t-field-select">
                                <label class="t-field-select__label">Habilidades BNCC</label>
                                <input type="hidden" class="js-macete-ability-search t-field-select__input">
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
                        <div class="column"></div>
                    </div>
                    <div class="column">

                        <h2>Metodologia MACETE (Aprendizagem Baseada em Desafios)</h2>
                    </div>

                    <div class="column macete-phase-cards">
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
                                    <div
                                        class="js-macete-stage-empty <?php echo empty($selectedStageIds) ? '' : 'hide'; ?>">
                                        Selecione etapas na aba Identificação.
                                    </div>
                                    <?php foreach ($stages as $stage): ?>
                                        <?php
                                        $stageId = (int) $stage['id'];
                                        $target = $stageTarget($stageId);
                                        $selected = $isStageSelected($stageId);
                                        ?>
                                        <div class="js-macete-stage-field <?php echo $selected ? '' : 'hide'; ?>"
                                            data-stage-id="<?php echo $stageId; ?>">
                                            <label
                                                class="t-field-tarea__label"><?php echo CHtml::encode($stage['name']); ?></label>
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
                    <div class="column">
                        <h3>Contextualização por etapa</h3>
                    </div>
                    <div class="row">
                        <div
                            class="column is-full js-macete-stage-empty <?php echo empty($selectedStageIds) ? '' : 'hide'; ?>">
                            <p>Selecione etapas na aba Identificação para preencher os textos por etapa.</p>
                        </div>
                    </div>
                    <?php foreach ($stages as $stage): ?>
                        <?php
                        $stageId = (int) $stage['id'];
                        $target = $stageTarget($stageId);
                        $selected = $isStageSelected($stageId);
                        ?>
                        <div class="row js-macete-stage-field <?php echo $selected ? '' : 'hide'; ?>"
                            data-stage-id="<?php echo $stageId; ?>">
                            <div class="column is-full">
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
                        </div>
                    <?php endforeach; ?>

                    <div class="row">
                        <div class="column is-full">
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
                    <div class="column">
                        <h2>Recursos</h2>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Caixa MACETE</label>
                                <?php echo CHtml::textArea(
                                    'resources[' . MaceteLessonPlanResource::TYPE_MACETE_BOX . ']',
                                    $resourceValue(MaceteLessonPlanResource::TYPE_MACETE_BOX),
                                    ['class' => 't-field-tarea__input', 'rows' => 6, 'placeholder' => 'Liste os materiais da caixa MACETE usados na aula.']
                                ); ?>
                            </div>
                        </div>
                        <div class="column">
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
                    <div class="row">
                        <div class="column">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'evaluation', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'evaluation', ['class' => 't-field-tarea__input', 'rows' => 5]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-tarea">
                                <?php echo $form->labelEx($lessonPlan, 'references_text', ['class' => 't-field-tarea__label']); ?>
                                <?php echo $form->textArea($lessonPlan, 'references_text', ['class' => 't-field-tarea__input', 'rows' => 5]); ?>
                            </div>
                        </div>
                    </div>
                            <div class="column">
                        <h2>Adaptações</h2>
                    </div>
                    <div class="row">
                        <div class="column">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Crianças neurodivergentes</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_ADAPTATION_NEURODIVERGENT . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_ADAPTATION_NEURODIVERGENT),
                                    ['class' => 't-field-tarea__input', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                        <div class="column">
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
                    <div class="row">
                        <div class="column">
                            <div class="t-field-tarea">
                                <label class="t-field-tarea__label">Turma multisseriada</label>
                                <?php echo CHtml::textArea(
                                    'sections[' . MaceteLessonPlanSection::TYPE_ADAPTATION_MULTIGRADE . '][general]',
                                    $sectionValue(MaceteLessonPlanSection::TYPE_ADAPTATION_MULTIGRADE),
                                    ['class' => 't-field-tarea__input', 'rows' => 5]
                                ); ?>
                            </div>
                        </div>
                        <div class="column">
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
                    <?php foreach (MaceteLessonMaterial::typeLabels() as $type => $label): ?>
                        <div class="column">
                            <h3><?php echo CHtml::encode($label); ?></h3>
                        </div>
                        <div class="row">
                            <div class="column">
                                <div class="t-field-select macete-stage-repeater">
                                    <div class="js-macete-material-rows" data-material-type="<?php echo CHtml::encode($type); ?>">
                                        <?php $materialEntries = $materialValues[$type] ?? [['title' => '', 'file_path' => '', 'description' => '']]; ?>
                                        <?php foreach ($materialEntries as $materialIndex => $materialEntry): ?>
                                            <div class="js-macete-material-row">
                                                <div class="justify-content--end">
                                                    <button type="button" class="t-button-secondary js-macete-remove-material">Remover</button>
                                                </div>
                                                <div class="row">
                                                    <div class="column">
                                                        <div class="t-field-text">
                                                            <label class="t-field-text__label">Título</label>
                                                            <?php echo CHtml::textField(
                                    'materials[' . $type . '][' . $materialIndex . '][title]',
                                    $materialEntry['title'] ?? '',
                                    ['class' => 't-field-text__input', 'maxlength' => 150]
                                ); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="column">
                                                        <div class="t-field-text">
                                                            <label class="t-field-text__label">Arquivo / link</label>
                                                            <?php echo CHtml::textField(
                                                                'materials[' . $type . '][' . $materialIndex . '][file_path]',
                                                                $materialEntry['file_path'] ?? '',
                                                                ['class' => 't-field-text__input', 'maxlength' => 255]
                                                            ); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="column">
                                                        <div class="t-field-tarea">
                                                            <label class="t-field-tarea__label">Descrição</label>
                                                            <?php echo CHtml::textArea(
                                                                'materials[' . $type . '][' . $materialIndex . '][description]',
                                                                $materialEntry['description'] ?? '',
                                                                ['class' => 't-field-tarea__input', 'rows' => 3]
                                                            ); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="t-button-secondary js-macete-add-material"
                                        data-material-type="<?php echo CHtml::encode($type); ?>">Adicionar</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <script type="text/template" id="macete-material-template">
                        <div class="js-macete-material-row">
                            <div class="justify-content--end"><button type="button" class="t-button-secondary js-macete-remove-material">Remover</button></div>
                            <div class="row"><div class="column"><div class="t-field-text"><label class="t-field-text__label">Título</label><input type="text" class="t-field-text__input" maxlength="150" name="materials[__type__][__index__][title]"></div></div></div>
                            <div class="row"><div class="column"><div class="t-field-text"><label class="t-field-text__label">Arquivo / link</label><input type="text" class="t-field-text__input" maxlength="255" name="materials[__type__][__index__][file_path]"></div></div></div>
                            <div class="row"><div class="column"><div class="t-field-tarea"><label class="t-field-tarea__label">Descrição</label><textarea class="t-field-tarea__input" rows="3" name="materials[__type__][__index__][description]"></textarea></div></div></div>
                        </div>
                    </script>
                </div>

            </div><!-- .tag-inner -->
        </div><!-- .macete-form-layout__content -->

        <!-- Sidebar: Resumo do plano -->
        <aside class="macete-form-layout__sidebar">
            <div class="t-cards">
                <div class="t-cards-content">
                    <h2 class="t-cards-title">Resumo do plano</h2>

                    <?php if ($schoolName !== ''): ?>
                        <p><b>Escola:</b> <?php echo CHtml::encode($schoolName); ?></p>
                    <?php endif; ?>

                    <?php if ($professorName !== ''): ?>
                        <p><b>Professor(a):</b> <?php echo CHtml::encode($professorName); ?></p>
                    <?php endif; ?>

                    <p><b>Componente curricular:</b> <span class="js-macete-summary-discipline">—</span></p>

                    <p><b>Etapa / Ano:</b> <span class="js-macete-summary-stage">—</span></p>

                    <p><b>Unidade:</b> <span class="js-macete-summary-unit">—</span></p>

                    <p><b>Status:</b>
                        <span class="js-macete-summary-status">
                            <span class="<?php echo $lessonPlan->getStatusBadgeClass(); ?>">
                                <?php echo CHtml::encode($lessonPlan->getStatusLabel()); ?>
                            </span>
                        </span>
                    </p>

                    <p><b>Habilidades selecionadas:</b>
                        <span class="js-macete-summary-abilities">
                            <?php echo $selectedAbilitiesCount; ?>
                            <?php echo $selectedAbilitiesCount === 1 ? 'habilidade' : 'habilidades'; ?>
                        </span>
                    </p>

                    <p>
                        <i class="fa fa-info-circle t-margin-small--right"></i>Seu plano será salvo como rascunho até a
                        conclusão de todos os passos.
                    </p>
                </div>
            </div>
        </aside>

    </div><!-- .macete-form-layout -->

    <div class="row reverse show--tablet">
        <div class="t-buttons-container">
            <a class="t-button-secondary"
                href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_INDEX); ?>">Voltar</a>
            <button class="t-button-primary" type="submit">Salvar</button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

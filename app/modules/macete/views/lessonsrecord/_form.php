<?php
/* @var $this LessonsrecordController */
/* @var $lessonRecord MaceteLessonRecord */
/* @var $plans MaceteLessonPlan[] */
/* @var $classrooms Classroom[] */
/* @var $selectedAbilities CourseClassAbilities[] */
/* @var $territoryContext string */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/quill.snow.css?v=' . TAG_VERSION);
$cs->registerScriptFile($themeUrl . '/js/quill.min.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/macete.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/rich-text.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/lesson-record.js?v=' . TAG_VERSION, CClientScript::POS_END);

$form = $this->beginWidget('CActiveForm', [
    'id' => 'macete-lesson-record-form',
    'enableAjaxValidation' => false,
]);

$selectedPlan = $lessonRecord->lessonPlanFk;
?>

<div class="main">
    <div class="mobile-row">
        <div class="column">
            <h1><?php echo $lessonRecord->isNewRecord ? 'Registrar Aula MACETE' : 'Editar Registro MACETE'; ?></h1>
        </div>
        <div class="column  align-items--center justify-content--end show--desktop">
            <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_INDEX); ?>">Voltar</a>
            <button class="t-button-primary" type="submit">Salvar registro</button>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <?php echo $form->errorSummary($lessonRecord); ?>

    <div class="macete-form-layout">
        <div class="macete-form-layout__content">
            <div class="tag-inner">
                <div class="column">
                    <h2>Identificação</h2>
                </div>

                <div class="row">
                    <div class="column t-field-text">
                        <?php echo $form->label($lessonRecord, 'lesson_date', ['class' => 't-field-text__label--required']); ?>
                        <?php echo $form->textField($lessonRecord, 'lesson_date', ['class' => 't-field-text__input js-date js-macete-date', 'placeholder' => 'DD/MM/AAAA']); ?>
                        <?php echo $form->error($lessonRecord, 'lesson_date'); ?>
                    </div>
                    <div class="column t-field-select">
                        <?php echo $form->label($lessonRecord, 'classroom_fk', ['class' => 't-field-select__label--required']); ?>
                        <?php echo $form->dropDownList(
    $lessonRecord,
    'classroom_fk',
    CHtml::listData($classrooms, 'id', 'name'),
    [
        'class' => 'select-search-on t-field-select__input js-macete-record-classroom',
        'prompt' => 'Selecione a turma',
    ]
); ?>
                        <?php echo $form->error($lessonRecord, 'classroom_fk'); ?>
                    </div>

                </div>

                <div class="row">
                    <div class="column t-field-select">
                        <?php echo $form->label($lessonRecord, 'status', ['class' => 't-field-select__label--required']); ?>
                        <?php echo $form->dropDownList($lessonRecord, 'status', MaceteLessonRecord::statusLabels(), ['class' => 'select-search-on t-field-select__input']); ?>
                        <?php echo $form->error($lessonRecord, 'status'); ?>
                    </div>
                    <div class="column t-field-select">
                        <?php echo $form->label($lessonRecord, 'lesson_plan_fk', ['class' => 't-field-select__label--required']); ?>
                        <?php echo $form->dropDownList(
                            $lessonRecord,
                            'lesson_plan_fk',
                            CHtml::listData($plans, 'id', 'name'),
                            [
                                'class' => 'select-search-on t-field-select__input js-macete-plan-select',
                                'prompt' => 'Selecione o plano MACETE',
                            ]
                        ); ?>
                        <?php echo $form->error($lessonRecord, 'lesson_plan_fk'); ?>
                    </div>
                </div>
                <div class="column">
                    <h2>Registro da aula</h2>
                </div>

                <div class="row">
                    <div class="column t-field-tarea is-full">
                        <?php echo $form->label($lessonRecord, 'executed_content', ['class' => 't-field-tarea__label--required']); ?>
                        <?php echo $form->textArea($lessonRecord, 'executed_content', ['class' => 't-field-tarea__input large', 'rows' => 8, 'placeholder' => 'Registre o conteúdo efetivamente trabalhado.']); ?>
                        <?php echo $form->error($lessonRecord, 'executed_content'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="column t-field-select ">
                        <label class="t-field-select__label">Habilidades BNCC registradas</label>
                        <input type="hidden" class="js-macete-ability-search t-field-select__input">
                        <div class="courseplan-abilities-selected js-macete-abilities-selected">
                            <?php foreach ($selectedAbilities as $ability): ?>
                                <div class="ability-panel-option">
                                    <input type="hidden" class="ability-panel-option-id" name="abilities[]" value="<?php echo (int) $ability->id; ?>">
                                    <i class="fa fa-check-square"></i>
                                    <span>(<b><?php echo CHtml::encode($ability->code); ?></b>) <?php echo CHtml::encode($ability->description); ?></span>
                                    <i class="fa fa-remove remove-abilitie js-macete-remove-ability"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="column">

                    </div>
                </div>

            </div>
        </div>

        <aside class="macete-form-layout__sidebar">
            <div class="t-cards js-macete-plan-summary">
                <div class="t-cards-content">
                    <h2 class="t-cards-title">Plano selecionado</h2>
                        <p><b>Contextualização do território:</b> <?php echo nl2br(CHtml::encode($territoryContext)); ?></p>
                    <p><b>Tema:</b> <span data-summary-field="theme"><?php echo $selectedPlan !== null ? CHtml::encode($selectedPlan->theme) : ''; ?></span></p>
                    <p><b>Ano/Série:</b> <span data-summary-field="stage"><?php echo $selectedPlan !== null && $selectedPlan->stageFk !== null ? CHtml::encode($selectedPlan->stageFk->name) : ''; ?></span></p>
                    <p><b>Componentes:</b> <span data-summary-field="discipline"><?php echo $selectedPlan !== null ? CHtml::encode($selectedPlan->getDisciplineNames()) : ''; ?></span></p>
                    <p><b>Turma:</b> <span data-summary-field="classroom"><?php echo $lessonRecord->classroomFk !== null ? CHtml::encode($lessonRecord->classroomFk->name) : ''; ?></span></p>
                    <p><b>Habilidades:</b> <span data-summary-field="abilities"><?php echo $selectedPlan !== null ? CHtml::encode($selectedPlan->getAbilityCodes()) : ''; ?></span></p>
                </div>
            </div>
        </aside>
    </div>

    <div class="row reverse show--tablet">
        <div class="t-buttons-container">
            <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_INDEX); ?>">Voltar</a>
            <button class="t-button-primary" type="submit">Salvar registro</button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
/* @var $this LessonRecordController */
/* @var $lessonRecord MaceteLessonRecord */
/* @var $plans MaceteLessonPlan[] */
/* @var $classrooms Classroom[] */
/* @var $selectedAbilities CourseClassAbilities[] */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/macete.js?v=' . TAG_VERSION, CClientScript::POS_END);
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
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONRECORD_INDEX); ?>">Voltar</a>
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

    <div class="mobile-row">
        <div class="column is-three-fifths">
            <div class="row">
                <div class="column t-field-text clearfix is-one-quarter">
                    <?php echo $form->label($lessonRecord, 'lesson_date', ['class' => 't-field-text__label--required']); ?>
                    <?php echo $form->textField($lessonRecord, 'lesson_date', ['class' => 't-field-text__input js-date js-macete-date', 'placeholder' => 'DD/MM/AAAA']); ?>
                    <?php echo $form->error($lessonRecord, 'lesson_date'); ?>
                </div>
                <div class="column t-field-select clearfix is-one-quarter">
                    <?php echo $form->label($lessonRecord, 'classroom_fk', ['class' => 't-field-select__label--required']); ?>
                    <?php echo $form->dropDownList(
                        $lessonRecord,
                        'classroom_fk',
                        CHtml::listData($classrooms, 'id', 'name'),
                        [
                            'class' => 'select-search-on t-field-select__input',
                            'prompt' => 'Selecione a turma',
                        ]
                    ); ?>
                    <?php echo $form->error($lessonRecord, 'classroom_fk'); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-select clearfix is-three-fifths">
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
                <div class="column t-field-select clearfix is-one-quarter">
                    <?php echo $form->label($lessonRecord, 'status', ['class' => 't-field-select__label--required']); ?>
                    <?php echo $form->dropDownList($lessonRecord, 'status', MaceteLessonRecord::statusLabels(), ['class' => 'select-search-on t-field-select__input']); ?>
                    <?php echo $form->error($lessonRecord, 'status'); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-tarea clearfix is-three-fifths">
                    <?php echo $form->label($lessonRecord, 'executed_content', ['class' => 't-field-tarea__label--required']); ?>
                    <?php echo $form->textArea($lessonRecord, 'executed_content', ['class' => 't-field-tarea__input large', 'rows' => 6, 'placeholder' => 'Registre o conteúdo efetivamente trabalhado.']); ?>
                    <?php echo $form->error($lessonRecord, 'executed_content'); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-tarea clearfix is-three-fifths">
                    <?php echo $form->labelEx($lessonRecord, 'methodology_notes', ['class' => 't-field-tarea__label']); ?>
                    <?php echo $form->textArea($lessonRecord, 'methodology_notes', ['class' => 't-field-tarea__input large', 'rows' => 5]); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-tarea clearfix is-three-fifths">
                    <?php echo $form->labelEx($lessonRecord, 'evaluation_notes', ['class' => 't-field-tarea__label']); ?>
                    <?php echo $form->textArea($lessonRecord, 'evaluation_notes', ['class' => 't-field-tarea__input large', 'rows' => 5]); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-tarea clearfix is-three-fifths">
                    <?php echo $form->labelEx($lessonRecord, 'adaptation_notes', ['class' => 't-field-tarea__label']); ?>
                    <?php echo $form->textArea($lessonRecord, 'adaptation_notes', ['class' => 't-field-tarea__input large', 'rows' => 5]); ?>
                </div>
            </div>

            <div class="row">
                <div class="column t-field-select clearfix is-three-fifths">
                    <label class="t-field-select__label">Habilidades BNCC registradas</label>
                    <input type="hidden" class="js-macete-ability-search" style="width:100%;">
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
            </div>
        </div>

        <div class="column is-two-fifths">
            <div class="t-cards js-macete-plan-summary">
                <div class="t-cards-content">
                    <h2 class="t-cards-title">Plano selecionado</h2>
                    <p><b>Tema:</b> <span data-summary-field="theme"><?php echo $selectedPlan !== null ? CHtml::encode($selectedPlan->theme) : ''; ?></span></p>
                    <p><b>Etapa:</b> <span data-summary-field="stage"><?php echo $selectedPlan !== null && $selectedPlan->stageFk !== null ? CHtml::encode($selectedPlan->stageFk->name) : ''; ?></span></p>
                    <p><b>Componente:</b> <span data-summary-field="discipline"><?php echo $selectedPlan !== null && $selectedPlan->disciplineFk !== null ? CHtml::encode($selectedPlan->disciplineFk->name) : ''; ?></span></p>
                    <p><b>Turma:</b> <span data-summary-field="classroom"><?php echo $selectedPlan !== null && $selectedPlan->classroomFk !== null ? CHtml::encode($selectedPlan->classroomFk->name) : ''; ?></span></p>
                    <p><b>Habilidades:</b> <span data-summary-field="abilities"><?php echo $selectedPlan !== null ? CHtml::encode($selectedPlan->getAbilityCodes()) : ''; ?></span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row reverse show--tablet">
        <div class="t-buttons-container">
            <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONRECORD_INDEX); ?>">Voltar</a>
            <button class="t-button-primary" type="submit">Salvar registro</button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

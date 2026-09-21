<?php
/* @var $this LessonsplanController */
/* @var $lessonPlan MaceteLessonPlan */

$this->setPageTitle('TAG - Criar Plano MACETE');
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/macete.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/lesson-plan.js?v=' . TAG_VERSION, CClientScript::POS_END);
$form = $this->beginWidget('CActiveForm', [
    'id' => 'macete-lesson-plan-create-form',
    'enableAjaxValidation' => false,
]);
?>

<div id="mainPage" class="main">
    <div class="row">
        <div class="column is-full">
            <h1>Começar um plano MACETE</h1>
            <p>Defina o contexto curricular. Em seguida, você será direcionado para a edição completa, com o assistente pedagógico disponível.</p>
            <?php echo $form->errorSummary($lessonPlan); ?>
        </div>
    </div>

    <div class="t-cards macete-create-card">
        <div class="t-cards-content">
            <div class="row">
                <div class="column">
                    <div class="t-field-text">
                        <?php echo $form->labelEx($lessonPlan, 'theme', ['class' => 't-field-text__label']); ?>
                        <?php echo $form->textField($lessonPlan, 'theme', [
                            'class' => 't-field-text__input',
                            'maxlength' => 255,
                            'placeholder' => 'Ex.: Transformações químicas no cotidiano',
                            'required' => true,
                        ]); ?>
                        <?php echo $form->error($lessonPlan, 'theme'); ?>
                    </div>
                </div>
                <div class="column">
                    <div class="t-field-text">
                        <?php echo $form->labelEx($lessonPlan, 'name', ['class' => 't-field-text__label']); ?>
                        <?php echo $form->textField($lessonPlan, 'name', [
                            'class' => 't-field-text__input',
                            'maxlength' => 150,
                            'placeholder' => 'Opcional: será criado a partir do tema',
                        ]); ?>
                        <?php echo $form->error($lessonPlan, 'name'); ?>
                    </div>
                </div>
            </div>
            <div class="row js-macete-stage-component-row">
                <div class="column t-field-select">
                    <?php echo CHtml::label('Etapa', 'macete-create-stage', ['class' => 't-field-select__label']); ?>
                    <?php echo CHtml::dropDownList(
                        'stage_components[0][stage_id]',
                        '',
                        CHtml::listData($stages, 'id', 'name'),
                        [
                            'id' => 'macete-create-stage',
                            'class' => 't-field-select__input js-macete-stage-component-stage',
                            'prompt' => 'Selecione a etapa',
                            'required' => true,
                        ]
                    ); ?>
                </div>
                <div class="column t-field-select">
                    <?php echo CHtml::label('Componente curricular', 'macete-create-discipline', ['class' => 't-field-select__label']); ?>
                    <?php echo CHtml::dropDownList(
                        'stage_components[0][discipline_id]',
                        '',
                        [],
                        [
                            'id' => 'macete-create-discipline',
                            'class' => 't-field-select__input js-macete-stage-component-discipline',
                            'prompt' => 'Selecione a etapa primeiro',
                            'required' => true,
                        ]
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class="column is-full t-field-select">
                    <?php echo CHtml::label('Habilidades BNCC', 'macete-create-abilities', ['class' => 't-field-select__label']); ?>
                    <input id="macete-create-abilities" type="hidden" class="js-macete-ability-search t-field-select__input">
                    <div class="courseplan-abilities-selected js-macete-abilities-selected"></div>
                    <p class="t-field-text__help">Opcional, mas recomendada para que o assistente contextualize melhor as sugestões.</p>
                </div>
            </div>
            <div class="t-buttons-container">
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_INDEX); ?>">Cancelar</a>
                <button class="t-button-primary" type="submit">Criar rascunho e continuar</button>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
/* @var $this CoursePlanController */
/* @var $coursePlan CoursePlan */
/* @var $courseClasses CourseClass[] */
/* @var $form CActiveForm */
?>

<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v='.TAG_VERSION, CClientScript::POS_END);
// $cs->registerScriptFile($themeUrl . '/js/jquery/jquery.dataTables.min.js?v='.TAG_VERSION, CClientScript::POS_END);
// $cs->registerCssFile($themeUrl . '/css/jquery.dataTables.min.css');
// $cs->registerCssFile($themeUrl . '/css/dataTables.fontAwesome.css');




$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'course-plan-form',
    'enableAjaxValidation' => false,
));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="row main">
	<div class="column">
		<h1>Validar Plano de Aula</h1>
	</div>
</div>

<div class="main">
    <?php // echo $form->errorSummary($coursePlan); ?>
    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('error')) : ?>
            <div class="alert alert-danger">
                <?php echo Yii::app()->uSser->getFlash('error') ?>
            </div>
        <?php endif ?>
        <div class="widget border-bottom-none">

            <div class="t-tabs js-tab-control justify-content--space-between">
                <div>
                    <ul class="t-tabs__list tab-courseplan">
                        <li id="tab-create-plan" class="t-tabs__item active">
                            <a class="t-tabs__link" href="#create-plan" data-toggle="tab">
                                <span  class="t-tabs__numeration">1</span>
                                <?php echo Yii::t('default', 'Create Plan') ?></a>
                                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/seta-tabs.svg" alt="seta">
                        </li>
                        <li id="tab-class" class="t-tabs__item">
                            <a class="t-tabs__link" href="#class" data-toggle="tab">
                                <span  class="t-tabs__numeration">2</span>
                                <?php echo Yii::t('default', 'Class') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <a
                    data-toggle="tab" class='t-button-secondary prev' style="display:none;"><?php echo Yii::t('default', 'Previous') ?>
                    </a>
                    <a
                    data-toggle="tab" class='t-button-primary next'><?php echo Yii::t('default', 'Next') ?>
                    </a>
                    <a id="save"
                    class='t-button-primary last' style="display:none;"><?php echo Yii::t('default', 'Save') ?>
                    </a>
                </div>
            </div>
            <div class="widget-body form-horizontal">
                <input type="hidden" class="js-course-plan-id" value="<?= $coursePlan->id ?>">
                <div class="tab-content">
                    <div class="tab-pane active" id="create-plan">
                        <div class="row">
                            <div class="column flex is-two-fifths">
                                <div class="t-field-text">
                                    <?php echo CHtml::label(yii::t('default', 'Name'), 'name', array('class' => 'control-label t-field-text__label--required')); ?>
                                    <?php echo $form->textField($coursePlan, 'name', array('size' => 400, 'maxlength' => 500, 'class' => 't-field-text__input', 'placeholder' => 'Digite o Nome do Plano', 'readonly' => true)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column flex is-two-fifths">
                                <div class="t-field-select">
                                    <?php echo CHtml::label(yii::t('default', 'Stage'), 'modality_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                                    <?php
                                    echo $form->dropDownList($coursePlan, 'modality_fk', CHtml::listData($stages, 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on t-field-select__input',
                                        'prompt' => 'Selecione a etapa...',
                                    ));
                                    ?>
                                    <img class="js-course-plan-loading-disciplines"  style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column flex is-two-fifths">
                                <div class="t-field-select">
                                    <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                                    <?php echo $form->dropDownList($coursePlan, 'discipline_fk', array(), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on t-field-select__input',
                                        'initVal' => $coursePlan->discipline_fk,
                                        'prompt' => 'Selecione o componente curricular/eixo...',
                                    ));
                                    ?>
                                    <img class="js-course-plan-loading-abilities"  style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column flex is-two-fifths">
                            <div class="t-field-text">
                                <?php echo CHtml::label(yii::t('default', 'Start Date'), 'start_date', array('class' => 'control-label t-field-select__label--required')); ?>
                                <?php
                                $coursePlan->start_date = $this->dataConverter($coursePlan->start_date, 1);
                                echo $form->textField($coursePlan, 'start_date', array('size' => 400, 'maxlength' => 500, 'readonly' => true,
                                'class' => 't-field-text__input js-date date js-start-date', 'id' => 'courseplan_start_date')); ?>
                            </div>
                            </div>
                        </div>
                </div>
                <div class="tab-pane" id="class">
                    <table id="course-classes" class="t-accordion column display" cellspacing="0" width="100%">
                        <thead class="t-accordion__header">
                            <tr>
                                <th class="t-accordion__head" style="width: 10px;"></th>
                                <th class="t-accordion__head span1"><?= Yii::t('default', 'Class'); ?></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head span12"><?= Yii::t('default', 'Objective'); ?></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head" style="background-color: initial !important;"></th>
                            </tr>
                        </thead>
                        <tbody class="t-accordion__body js-change-idRows">
                        </tbody>
                    </table>

                    <div class="row">
                        <div>
                            <label class="t-field-tarea__label" for="observation">Observação*</label>
                        </div>
                        <textarea class="t-field-tarea__input validate-description" placeholder="Digite o Objetivo do Plano" name="observation"></textarea>
                    </div>
                    <div class="row">
                        <a href="" id="new-course-class" class="t-button-primary">
                            <img alt="Salvar Validação" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/buttonIcon/activeUser.svg">
                        </a>
                    </div>
                    <div class="js-all-types no-show">
                        <?php foreach ($types as $type) : ?>
                            <option value="<?= $type->id ?>"><?= $type->name ?></option>
                        <?php endforeach; ?>
                    </div>
                    <div class="js-type no-show">
                        <input type="text" value="<?$type?>">
                    </div>
                    <div class="js-all-resources no-show">
                        <?php foreach ($resources as $resource) : ?>
                            <option value="<?= $resource->id ?>"><?= $resource->name ?></option>
                        <?php endforeach; ?>
                    </div>
                    <div class="js-all-competences no-show">
                        <?php foreach ($competences as $stage => $competence) : ?>
                            <optgroup label="<?= $competence["stageName"]?>">
                                <?php foreach ($competence["data"] as $competenceData): ?>
                                    <option value="<?= $competenceData["id"] ?>"><?= $competenceData["code"] . "|" . $competenceData["description"] . "|" . $competence["stageName"] ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

<style>
.select2-container .select2-choice {
    margin: 10px 0;
}
</style>

<script>
    const allInputs = $(":input");
    // allInputs.attr('readonly', true);
</script>

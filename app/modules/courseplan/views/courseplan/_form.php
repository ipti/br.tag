<?php
/* @var $this CoursePlanController */
/* @var $coursePlan CoursePlan */
/* @var $courseClasses CourseClass[] */
/* @var $form CActiveForm */
?>

<?php

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v=' . TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v=' . TAG_VERSION, CClientScript::POS_END);
// $cs->registerScriptFile($themeUrl . '/js/jquery/jquery.dataTables.min.js?v='.TAG_VERSION, CClientScript::POS_END);
// $cs->registerCssFile($themeUrl . '/css/jquery.dataTables.min.css');
// $cs->registerCssFile($themeUrl . '/css/dataTables.fontAwesome.css');

$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));
$form = $this->beginWidget('CActiveForm', [
    'id' => 'course-plan-form',
    'enableAjaxValidation' => false,
]);
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<?php $readonly = Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id) || $coursePlan->situation == 'APROVADO' ? 'readonly' : '' ; ?>

<div class="main">
    <?php echo($coursePlan->situation == 'APROVADO') ||
    Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id) ||
    TagUtils::isManager()
    ? '<div id="validate-index"></div>' : '' ; ?>
    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('error')) : ?>
            <div class="alert alert-danger">
                <?php echo Yii::app()->user->getFlash('error') ?>
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
                <div id="js-submit-div" class="row">
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
                <div id="js-loading-div" class="row hide">
                    <img style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                </div>
            </div>
            <div class="widget-body form-horizontal">
                <input type="hidden" class="js-course-plan-id" value="<?= $coursePlan->id ?>">
                <div class="tab-content">
                    <div class="tab-pane active" id="create-plan">
                        <div class="row">
                            <div class="column flex is-two-fifths">
                                <div class="t-field-text">
                                    <?php echo CHtml::label(yii::t('default', 'Name'), 'name', ['class' => 'control-label t-field-text__label--required']); ?>
                                    <?php echo $form->textField(
        $coursePlan,
        'name',
        ['size' => 400, 'maxlength' => 500, 'class' => 't-field-text__input',
            'placeholder' => 'Digite o Nome do Plano', 'readonly' => $readonly]
    ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column flex is-two-fifths">
                                <div class="t-field-select">
                                    <?php echo CHtml::label(yii::t('default', 'Stage'), 'modality_fk', ['class' => 'control-label t-field-select__label--required']); ?>
                                    <?php
                                                                        echo $form->dropDownList($coursePlan, 'modality_fk', CHtml::listData($stages, 'id', 'name'), [
                                                                            'key' => 'id',
                                                                            'class' => 'select-search-on t-field-select__input',
                                                                            'prompt' => 'Selecione a etapa...',
                                                                        ]);
?>
                                    <img class="js-course-plan-loading-disciplines"  style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column flex is-two-fifths">
                                <div id="disciplinesContainer" class="t-field-select">
                                    <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline_fk', ['class' => 'control-label t-field-select__label--required']); ?>
                                    <?php echo $form->dropDownList($coursePlan, 'discipline_fk', [], [
                                        'key' => 'id',
                                        'class' => 'select-search-on t-field-select__input',
                                        'initVal' => $coursePlan->discipline_fk,
                                        'prompt' => 'Selecione o componente curricular/eixo...',
                                    ]);
?>
                                    <img class="js-course-plan-loading-abilities"  style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column flex is-two-fifths">
                            <div class="t-field-text">
                                <?php echo CHtml::label(yii::t('default', 'Start Date'), 'start_date', ['class' => 'control-label t-field-select__label--required']); ?>
                                <?php
                                $coursePlan->start_date = $this->dataConverter($coursePlan->start_date, 1);
echo $form->textField($coursePlan, 'start_date', ['size' => 400, 'maxlength' => 500,
    'class' => 't-field-text__input js-date date js-start-date', 'id' => 'courseplan_start_date', 'readonly' => $readonly]); ?>
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="column flex is-two-fifths">
                            <div class="t-field-text">
                                <?php echo CHtml::label(yii::t('default', 'Situation'), 'situation', ['class' => 'control-label t-field-select__label--required']); ?>
                                <?php
echo $form->textField($coursePlan, 'situation', ['size' => 400, 'maxlength' => 500, 'readonly' => true,
    'class' => 't-field-text__input']); ?>
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
                                <th class="t-accordion__head span12"><?= Yii::t('default', 'Content'); ?></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head"></th>
                                <th class="t-accordion__head" style="background-color: initial !important;"></th>
                            </tr>
                        </thead>
                        <tbody class="t-accordion__body js-change-idRows">
                        </tbody>
                    </table>

                    <div class="row <?= $coursePlan->situation == 'PENDENTE' || $coursePlan->isNewRecord ? '' : 'hide' ?>">
                        <a href="#new-course-class" id="new-course-class"
                            class="t-button-primary">
                            <img alt="Novo plano de aula" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/buttonIcon/start.svg">
                            <?= Yii::t('default', 'New'); ?>
                        </a>
                    </div>

                    <div class="control-group column clearleft">
                        <div>
                            <?php echo CHtml::label(yii::t('default', 'Observation'), 'observation', ['class' => 't-field-tarea__label--required']); ?>
                        </div>
                        <?= $form->textArea($coursePlan, 'observation', ['rows' => 6, 'cols' => 60,
                            'class' => 't-field-tarea__input validate-description', 'id' => 'courseplan_start_date',
                            'placeholder' => 'Digite a observação do Plano', 'style' => 'height: 120px; width: 700px;',
                            'readonly' => 'readonly']) ?>
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
                            <optgroup label="<?= $competence['stageName']?>">
                                <?php foreach ($competence['data'] as $competenceData): ?>
                                    <option value="<?= $competenceData['id'] ?>"><?= $competenceData['code'] . '|' . $competenceData['description'] . '|' . $competence['stageName'] ?></option>
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
<div class="modal fade modal-content" id="js-selectAbilities" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
                </button>
                <h4 class="modal-title"
                    id="myModalLabel">Adicionar Habilidades</h4>
            </div>
            <form method="post">
                <input type="hidden" class="course-class-index">
                <div class="modal-body">
                <div class="alert js-alert-ability-structure">Para adicionar habilidades, é necessário selecionar a etapa na aba criar plano, e informar o componente curricular/eixo.</div>
                    <div id="minorEducationContainer" class="column clearfix">
                        <div class="t-field-select">
                            <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline_fk', ['class' => 'control-label t-field-select__label--required']); ?>
                            <select class="select-search-on t-field-select__input select2-container" id="minorEducationDisciplines" name="minorEducationDisciplines">
                                <option value="">Selecione o componente curricular/eixo</option>
                            </select>
                            <img class="js-course-plan-loading-abilities"  style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                        </div>
                    </div>
                    <div class="js-abilities-parents">
                    </div>
                    <div class="js-abilities-panel">

                    </div>
                    <div class="js-abilities-selected">
                        <label>SELECIONADAS</label>
                    </div>
                    <img class="loading-abilities-select"  style="display:none;margin: 0px 5px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading"></img>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Cancelar
                        </button>
                        <button type="button" class="btn btn-primary js-add-selected-abilities"
                                data-dismiss="modal">Adicionar
                        </button>
                    </div>
                </div>
            </form>
    </div>
</div>


<div class="modal fade modal-content" id="js-createResource" tabindex="-1" role="dialog">
    <div modal="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="modalLabelResources">Adicionar Recurso</h4>
        </div>
        <div class="row-fluid">
            <div class="alert alert-error alert-resource hide">
                <p>É necessário inserir um recurso no campo</p>
            </div>
        </div>
        <div class="row-fluid">
            <div class="alert alert-info alert-resource-exist hide">
                <p>Recurso já está cadastrado</p>
            </div>
        </div>
        <form>
            <input type="hidden" class="course-class-index row">
            <div class="modal-body">
                <?php echo CHtml::label(yii::t('default', 'Add Resource'), ['class' => 'control-label t-field-text__label--required']); ?>
                <div class="row">
                    <?php
                        $newResource = new CourseClassResources();
echo $form->textField($newResource, 'name', ['size' => 400, 'maxlength' => 500,
    'class' => 't-field-text__input new-resource',
    'placeholder' => 'Digite o Nome do Recurso']);
?>
                    <button class="t-button-secondary confirm-new-resource" style="margin: 0px">Adicionar</button>
                </div>
                <div id="new-resources-table" class="t-accordeon-primary"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Cancelar
                    </button>
                    <button type="button" class="btn btn-primary js-add-selected-resources save-new-resources"
                            data-dismiss="modal">Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $this->endWidget(); ?>

<style>
.select2-container .select2-choice {
    margin: 10px 0;
}
</style>

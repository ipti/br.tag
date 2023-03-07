<?php
/* @var $this CoursePlanController */
/* @var $coursePlan CoursePlan */
/* @var $courseClasses CourseClass[] */
/* @var $form CActiveForm */
?>

<?php
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/courseplan/form/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/courseplan/form/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/courseplan/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/courseplan/form/pagination.js', CClientScript::POS_END);
$cs->registerScriptFile($themeUrl . '/js/jquery/jquery.dataTables.min.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/jquery.dataTables.min.css');
$cs->registerCssFile($themeUrl . '/css/dataTables.fontAwesome.css');
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile($baseUrl . 'sass/css/main.css');

$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'course-plan-form',
    'enableAjaxValidation' => false,
));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<?php echo $form->errorSummary($coursePlan); ?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Create Plan'); ?></h3>
        <div class="t-buttons-container">
            <a
                    data-toggle="tab" class='t-button-secondary prev'
                    style="display:none;"><?php echo Yii::t('default', 'Previous') ?>
            </a>
            <a
                    data-toggle="tab" class='t-button-primary next'><?php echo Yii::t('default', 'Next') ?>
            </a>
            <a id="save"
               class='t-button-primary last' style="display:none;"><?php echo Yii::t('default', 'Save') ?>
            </a>
        </div>
    </div>
</div>
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

        <div class="t-tabs js-tab-control">
            <ul class="t-tabs__contents tab-courseplan">
                <li id="tab-create-plan" class="t-tabs__itens active">
                    <a class="t-tabs__link" href="#create-plan"
                       data-toggle="tab"><?php echo Yii::t('default', 'Create Plan') ?></a>
                </li>
                <li id="tab-class" class="t-tabs__itens">
                    <a class="t-tabs__link" href="#class" data-toggle="tab"><?php echo Yii::t('default', 'Class') ?></a>
                </li>
            </ul>
        </div>
        <div class="widget-body form-horizontal">
            <input type="hidden" class="js-course-plan-id" value="<?= $coursePlan->id ?>">
            <div class="tab-content">
                <div class="tab-pane active" id="create-plan">
                    <div class="row">
                        <div class="column">
                            <div>
                                <?php echo CHtml::label(yii::t('default', 'Name') . "*", 'name', array(
                                    'class' => 'control-label required',

                                )); ?>
                                <?php
                                echo $form->textField($coursePlan, 'name', array('size' => 400, 'maxlength' => 500,));
                                ?>
                            </div>
                            <div>
                                <?php echo CHtml::label(yii::t('default', 'Stage') . "*", 'modality_fk', array('class' => 'control-label required')); ?>
                                <div>
                                    <?php
                                    echo $form->dropDownList($coursePlan, 'modality_fk', CHtml::listData($stages, 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on control-input',
                                        'prompt' => 'Selecione o estágio...',

                                    ));
                                    ?>
                                    <i class="js-course-plan-loading-disciplines fa fa-spin fa-spinner"></i>
                                </div>
                            </div>
                            <div>
                                <?php echo CHtml::label(yii::t('default', 'Discipline') . "*", 'discipline_fk', array('class' => 'control-label required')); ?>
                                <div class="coursePlan-input"><?php
                                    echo $form->dropDownList($coursePlan, 'discipline_fk', array(), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on control-input',
                                        'initVal' => $coursePlan->discipline_fk,
                                        'prompt' => 'Selecione a disciplina...',
                                    ));
                                    ?>
                                    <i class="js-course-plan-loading-abilities fa fa-spin fa-spinner"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="class">
                    <table id="course-classes" class="display" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th style="width: 10px;"></th>
                            <th class="span1"><?= Yii::t('default', 'Class'); ?></th>
                            <th></th>
                            <th class="span12"><?= Yii::t('default', 'Objective'); ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width: 20px;">
                                <a style="width: 53px;" href="#new-course-class" id="new-course-class"
                                   class="btn btn-success btn-small">
                                    <i style="margin-right: 6px;"
                                       class="fa fa-plus-square"></i><?= Yii::t('default', 'New'); ?>
                                </a>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                    <div class="js-all-types no-show">
                        <?php foreach ($types as $type) : ?>
                            <option value="<?= $type->id ?>"><?= $type->name ?></option>
                        <?php endforeach; ?>
                    </div>
                    <div class="js-all-resources no-show">
                        <?php foreach ($resources as $resource) : ?>
                            <option value="<?= $resource->id ?>"><?= $resource->name ?></option>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="js-selectAbilities" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel">Adicionar Habilidades</h4>
            </div>
            <form method="post">
                <input type="hidden" class="course-class-index">
                <div class="modal-body">
                    <div class="alert alert-error js-alert-ability-structure">Para adicionar habilidades, é preciso primeiro escolher a etapa e a disciplina do plano.</div>
                    <div class="js-abilities-parents">

                    </div>
                    <div class="js-abilities-panel">

                    </div>
                    <div class="js-abilities-selected">
                        <label>SELECIONADAS</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Cancelar
                    </button>
                    <button type="button" class="btn btn-primary js-add-selected-abilities"
                            data-dismiss="modal">Adicionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

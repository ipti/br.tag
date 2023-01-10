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
$cs->registerScriptFile($baseUrl . '/js/courseplan/form/dialogs.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/courseplan/form/validations.js', CClientScript::POS_END);
$cs->registerScriptFile($themeUrl . '/js/jquery/jquery.dataTables.min.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/jquery.dataTables.min.css');
$cs->registerCssFile($themeUrl . '/css/dataTables.fontAwesome.css');
$cs->registerCssFile($themeUrl . '/css/template2.css');

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
        <div class="buttons span9">
            <a id="add-content" class='btn btn-success hidden-print'><i class="fa fa-plus-square"></i> <?php echo Yii::t('default', 'Content') ?></a>
            <a id="add-resource" class='btn btn-success hidden-print'><i class="fa fa-plus-square"></i> <?php echo Yii::t('default', 'Resource') ?></a>
            <a id="add-type" class='btn btn-success hidden-print'><i class="fa fa-plus-square"></i> <?php echo Yii::t('default', 'Type') ?></a>
            <a id="save" class='btn btn-icon btn-primary glyphicons circle_ok hidden-print'><?php echo Yii::t('default', 'Save') ?><i></i></a>
        </div>
    </div>
</div>
<div class="tag-inner">

    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head  hidden-print">
            <ul class="tab-courseplan">
                <li id="tab-courseplan" class="active"><a href="#" data-toggle="tab"><?php echo Yii::t('default', 'Course Plan') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="courseplan">
                    <div class="row-fluid">
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::label(yii::t('default', 'Stage') . "*", 'modality_fk', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->dropDownList($coursePlan, 'modality_fk', CHtml::listData(EdcensoStageVsModality::model()->findAll(), 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on span12',
                                        'prompt' => 'Selecione o estágio'
                                    ));
                                    ?>
                                </div>

                            </div>
                        </div>
                        <div class=" span6">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::label(yii::t('default', 'Disciplines') . "*", 'discipline_fk', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls coursePlan-input"><?php
                                                        echo $form->dropDownList($coursePlan, 'discipline_fk', CHtml::listData(EdcensoDiscipline::model()->findAll(), 'id', 'name'), array(
                                                            'key' => 'id',
                                                            'class' => 'select-search-on  control-input',
                                                            'prompt' => 'Selecione a disciplina',
                                                        ));
                                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class=" span10">
                            <div class="control-group">
                                <div class="controls">
                                    <?php echo CHtml::label(yii::t('default', 'Name') . "*", 'name', array('class' => 'control-label')); ?>
                                </div>
                                <div class="controls">
                                    <?php
                                    echo $form->textField($coursePlan, 'name', ['class' => 'span12']);
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <table id="course-classes" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 10px;"></th>
                                <th class="span1"><?= Yii::t('default', 'Class'); ?></th>
                                <th class="span11"><?= Yii::t('default', 'Objective'); ?></th>
                                <th><?= Yii::t('default', 'Content'); ?></th>
                                <th><?= Yii::t('default', 'Resource'); ?></th>
                                <th><?= Yii::t('default', 'Type'); ?></th>
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
                                <th style="width: 20px;">
                                    <a style="width: 53px;" href="#new-course-class" id="new-course-class" class="btn btn-success btn-small">
                                        <i style="margin-right: 6px;" class="fa fa-plus-square" ></i><?= Yii::t('default', 'New'); ?>
                                    </a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php

$modals = [];
$modals[0] = ['id' => 'content', 'title' => Yii::t('default', 'Add Content')];
$modals[1] = ['id' => 'resource', 'title' => Yii::t('default', 'Add Resource')];
$modals[2] = ['id' => 'type', 'title' => Yii::t('default', 'Add Type')];

for ($i = 0; $i < 3; $i++) { ?>
    <div id="add-<?= $modals[$i]['id'] ?>-form" class="hide" title="<?= $modals[$i]['title'] ?>">
        <div class="row-fluid">
            <div class="span12">
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t('default', 'Name') . '*', 'add-' . $modals[$i]['id'] . '-name', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::textField('add-' . $modals[$i]['id'] . '-name', ''); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo CHtml::label(Yii::t('default', 'Description'), 'add-' . $modals[$i]['id'] . '-description', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <?php echo CHtml::textField('add-' . $modals[$i]['id'] . '-description', ''); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    var getClassesURL = "<?php echo Yii::app()->createUrl('classes/getClasses') ?>";
    var getContentsURL = "<?php echo Yii::app()->createUrl('classes/getContents') ?>";
    var saveContentURL = "<?php echo Yii::app()->createUrl('classes/saveContent') ?>";

    var btnCreate = "<?php echo Yii::t('default', 'Create'); ?>";
    var btnCancel = "<?php echo Yii::t('default', 'Cancel'); ?>";

    var labelClass = "<?= Yii::t('default', 'Class'); ?>";
    var labelObjective = "<?= Yii::t('default', 'Objective') . "*"; ?>";
    var labelContent = "<?= Yii::t('default', 'Content'); ?>";
    var labelResource = "<?= Yii::t('default', 'Resource'); ?>";
    var labelType = "<?= Yii::t('default', 'Type'); ?>";

    var myAddContentForm;

    var contents = '<?= $contents ?>';
    var resources = '<?= $resources ?>';
    var types = '<?= $types ?>';
    var courseClasses = '<?= json_encode($courseClasses) ?>';
</script>
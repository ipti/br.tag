<?php
/* @var $this ClassroomConfigurationControler */
/* @var $form ActiveForm */
/* @var $title String */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
        ));

$this->breadcrumbs = array(
    Yii::t('default', 'Reaproveitamento das Turmas'),
);
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic">
            <?php echo Yii::t('default', 'Configurarion'); ?>
        </h3>
    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">	
        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="student">
                    <div class="row-fluid">	
                        <div class=" span5"> 
                            <div class="row-fluid">
                                <div class="span3">
                                    <a href="<?php echo yii::app()->createUrl('wizard/Configuration/school')?>" class="widget-stats">
                                        <span class="glyphicons flag"><i></i></span>
                                        <span class="txt">School</span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="span3">
                                    <a href="<?php echo yii::app()->createUrl('wizard/Configuration/classroom')?>" class="widget-stats">
                                        <span class="glyphicons flag"><i></i></span>
                                        <span class="txt">Classrooms</span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="span3">
                                    <a href="<?php echo yii::app()->createUrl('wizard/Configuration/student')?>" class="widget-stats">
                                        <span class="glyphicons flag"><i></i></span>
                                        <span class="txt">Students</span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="span3">
                                </div>
                            </div>
                        </div>
                        <div class=" span6">
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<?php
/** @var $this ClassroomConfigurationControler */
/** @var $title String */
/** @var $form CActiveForm */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
        ));

$this->breadcrumbs = array(
    Yii::t('app', 'Classroom Configurarion'),
);

$lastYear = (Yii::app()->user->year - 1);
$school = Yii::app()->user->school;
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic">
            <?php echo $title; ?>
        </h3>
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Copy'), array('class' => 'btn btn-icon btn-primary last glyphicons roundabout', 'type' => 'submit')); ?>
        </div>
    </div>
</div>
<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">
        <div class="widget-head">
            <ul class="tab-sorcerer">
                <li id="tab-classroom" class="active">
                    <a class="glyphicons vcard" href="#classroom" data-toggle="tab"> 
                        <i></i> <?php echo Yii::t('default', 'Classroom') . ' ' . $lastYear ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <div class="tab-pane active" id="classroom">
                    <div class="row-fluid">	
                        <div class=" span12">
                            <div class="control-group">
                                <?php
                                echo chtml::dropDownList('Classrooms', "", CHtml::listData(Classroom::model()->findAllByAttributes(array('school_year' => $lastYear, 'school_inep_fk'=>$school)), 'id', 'name'), array(
                                    'class' => 'select-search-on span12',
                                    'multiple' => 'multiple',
                                    'placeholder' => Yii::t('default', 'Select Classrom'),
                                ));
                                ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
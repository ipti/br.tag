<?php
/** @var $this ClassroomConfigurationControler */
/** @var $title String */
/** @var $form CActiveForm */

$this->setPageTitle('TAG - ' .  Yii::t('default', 'Reaproveitamento das Turmas'));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'school-configuration-form',
    'enableAjaxValidation' => false
        ));

$this->breadcrumbs = array(
    Yii::t('default', 'Reaproveitamento das Turmas'),
);

$lastYear = (Yii::app()->user->year - 1);
$school = Yii::app()->user->school;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

?>
<div class="main">
    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1>
                <?php echo $title; ?>
            </h1>
            <div class="tag-buttons-container buttons">
                <?php echo CHtml::htmlButton('' . Yii::t('default', 'Copy'), array('class' => 't-button-primary  last ', 'type' => 'submit')); ?>
            </div>
        </div>
    </div>
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div>
        <div class="t-tabs">
            <ul class="t-tabs__list">
                <li id="tab-classroom" class="active t-tabs__item">
                    <a href="#classroom" data-toggle="tab" class="t-tabs__link"> 
                    <span  class="t-tabs__numeration">1</span>
                        <?php echo Yii::t('default', 'Classroom') . ' ' . $lastYear ?>
                    </a>
                </li>
            </ul>
        </div>

        <div class="widget-body form-horizontal"  style="margin-left: 20px;">
            <div class="tab-content">
                <div class="tab-pane active" id="classroom">
                    <div class="row-fluid">	
                        <div class=" span12">
                            <div class="control-group">
                                <?php
                                echo chtml::dropDownList('Classrooms', "", CHtml::listData(Classroom::model()->findAllByAttributes(array('school_year' => $lastYear, 'school_inep_fk'=>$school),array('order'=>'name ASC')), 'id', 'name'), array(
                                    'class' => 'select-search-on',
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
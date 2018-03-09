<?php
/* @var $this calendar.defaultController
 * @var $modelCalendar Calendar
 * @var $modelEvent CalendarEvent
 * @var $cs CClientScript
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/index.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('Quiz', 'Default'));


$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classroom-form',
    'enableAjaxValidation' => false,
        ));
?>

<div class="row-fluid  hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo $title; ?></h3>  
        <div class="buttons">
            <?php echo CHtml::htmlButton('<i></i>' . ($modelClassroom->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save')), array('id' => 'save', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button'));
            ?>
        </div>
    </div>
</div>

<div class="innerLR home">
	
</div>
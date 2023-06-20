<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs=array(
	$this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'StudentClassDiary',
    'enableAjaxValidation' => false,
)
);
?>

<div class="main">
    <h1>Diário de Classe</h1>
    
    <?php $this->endWidget(); ?>
</div>
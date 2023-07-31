<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs=array(
	$this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/index/functions.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/index/_initialization.js', CClientScript::POS_END);
?>
<div class="main">
	<h1>Diário de Classe</h1>
	<div class="row">
		<div class="column no-grow">
			<div class="t-field-select">
				<?php echo CHtml::dropDownList('discipline', "", $disciplines,  array('prompt' => 'Selecione a matéria', 'class' => 'select-search-on t-field-select__input js-select-disciplines',)); ?>
			</div> 
		</div>
	</div>
	<div class="row wrap js-add-classrooms-cards">
	</div>
</div>
<?php
/** @var DefaultController $this DefaultController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Diário de Classe'));
$this->breadcrumbs=array(
	$this->module->id,
);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/common/js/functions.js?v=1.1', CClientScript::POS_END);
?>
<div class="main">
<h1>Diário de Classe</h1>
<div class="row">
	<div class="column no-grow">
		<div class="t-field-select">
			<?php echo CHtml::dropDownList('discipline', null, $disciplines,  array('prompt' => 'Selecione a matéria', 'class' => 'select-search-on t-field-select__input js-select-disciplines',)); ?>
		</div> 
	</div>
</div>
<div class="row wrap js-add-classrooms-cards">
	
 <?php 
 /* foreach ($classrooms as $c) {
	echo '<div class="column">
			<a href="#" class="t-cards">
				<div class="t-cards-content">
					<div class="t-tag-primary">'.$c["discipline_name"].'</div>
					<div class="t-cards-title">'.$c["name"].'</div>
					<div class="t-cards-text clear-margin--left">'.$c["stage_name"].'</div>
				</div>
			</a>
		</div>';
	}; */
?>
</div>
</div>
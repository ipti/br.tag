<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<div class="main">
	<div class="row">
		<h1> Merenda </h1>
	</div>

	<div class="row margin-large--bottom">
		<a class="t-button-primary">Preparar Cardápios</a>
		<a class="t-button-secondary">Estoques</a>
	</div>

	<div class="row">
		<div class="t-field-select column is-one-third clearfix">
			<div class="t-field-select__label">
				Mostrar turnos
			</div>
			<div class="t-field-select__input">
			
			<?= CHtml::dropDownList('shift', '',
			 [
				'0' => 'Manhã',
				'1' => 'Tarde',
				'2' => 'Noite'
			 ], 
			 array('multiple' => 'multiple',
			 'class' => 'select-search-on t-multiselect multiselect')); ?>
			</div>
		</div>
	</div>
</div>

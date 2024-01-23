<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/menuComponents.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js', CClientScript::POS_END);
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'food-menu-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	)); ?>
	<div class="t-tabs row">
		<div class="column">
			<ul class="tab-instructor t-tabs__list ">
				<li class="active t-tabs__item"><a data-toggle="tab" class="t-tabs__link">
						<span class="t-tabs__numeration">1</span>
						<?= $model->isNewRecord ? 'Criar Cardápio' : 'Salvar Cardápio' ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="alert alert-error js-menu-error hide column"></div>
	</div>
	<div class="main form-content">
		<div class="row">
			<h3 class="column">
				Informações do Cardápio
			</h3>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<label for="menu_description" class="t-field-text__label--required">Nome</label>
				<input type="text" id="menu_description" name="Nome" class="t-field-text__input js-menu-name" required="required">

			</div>
			<div class="clear-margin--top column">
				<label for="public_target" class="t-field-select__label">Publico Alvo</label>
				<select id="public_target" name="Público Alvo" class="select-search-on t-field-select__input js-public-target js-initialize-select2" required="required" placeholder="Select Classrom">
					<option value="">Selecione o Público Alvo</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<label for="menu_start_date" class="t-field-text__label">Data Inicial</label>
				<input type="text" id="menu_start_date" name="Data Inicial"
						class="t-field-text__input js-date date js-start-date" required="required">
			</div>
			<div class="t-field-text column">
				<label for="menu_final_date" class="t-field-text__label">Data Final</label>
				<input type="text" id="menu_final_date" name="Data Final"
					class="t-field-text__input js-date date js-final-date" required="required" />
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<label for="menu_observation" class="t-field-select__label">Observação</label>
				<input type="text" id="menu_observation" name="Observação" class="t-field-select__input js-observation">
			</div>
			<div class="column"></div>
		</div>
		<div class="row">
			<div class="column t-buttons-container">
				<a class="t-button-primary js-add-meal">
					<span class="t-icon-start"></span>
					Adicionar Refeição
				</a>
			</div>
		</div>
		<div class="t-tabs-secondary js-days-of-week-component row">

		</div>
		<div class="row">
			<div class="column t-accordeon--header">
				<div class="row">
					<div class="column">
						Refeição
					</div>
					<div class="column">
						Nome
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="column">
				<div id="js-accordion" class="js-meals-component t-accordeon-secondary"></div>
			</div>
		</div>
	</div>
	<div class="row buttons">
		<a class="t-button-primary js-save-menu"><?= $model->isNewRecord ? 'Criar' : 'Salvar' ?></a>
	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<style>
	.date[readonly] {
		cursor: pointer;
		background-color: white;
	}
</style>

<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js', CClientScript::POS_END );
$cs->registerScriptFile($baseScriptUrl . '/functinos.js', CClientScript::POS_END );
$cs->registerScriptFile($baseScriptUrl . '/components.js', CClientScript::POS_END );
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-menu-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="main form-content">
		<div class="row">
			<h3 class="column">
				Informações do Cardápio
			</h3>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<?php echo $form->labelEx($model, 'description', array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'description', array('class'=>'t-field-text__input')); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
			<div class="t-multiselect clear-margin--top column">
				<?= chtml::label('Publico Alvo *', 'public_target', array('class'=> 't-field-select__label'));?>
					<?=  chtml::dropDownList('public_target', "",
					CHtml::listData(FoodPublicTarget::model()->findAll(), 'id', 'name'),
					array(
							'class' => 'select-search-on t-field-select__input',
							'placeholder' => Yii::t('default', 'Select Classrom'),
							'prompt' => 'Selecione o Público Alvo'
                        ));
						?>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<?php echo $form->labelEx($model,'start_date',  array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'start_date', array('class'=>'t-field-text__input js-date date', 'readonly' => 'readonly')); ?>
				<?php echo $form->error($model,'start_date'); ?>
			</div>
			<div class="t-field-text column">
				<?php echo $form->labelEx($model,'observation', array('class' => 't-field-select__label')); ?>
				<?php echo $form->textField($model,'observation', array('class'=>'t-field-select__input')); ?>
				<?php echo $form->error($model,'observation'); ?>
			</div>
		</div>
		<div class="row">
			<div class="column t-buttons-container">
				<a  class="t-button-primary js-add-meal hide">
					<span class="t-icon-start"></span>
					Adicionar Refeição
					</a>
			</div>
		</div>
		<div class="t-tabs-secondary js-days-of-week-component">
				
		</div>
		<div class="row js-show-meals-header hide">
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
				<div class="js-meals-component t-accordeon-secondary"></div>
			</div>
		</div>
		<!-- <div class="t-expansive-panel row">
			<div class="column">
				<div class="row">
					<div class="t-field-text column">
						<?= chtml::label('Hora da refeição *', 'mealTime', array('class'=> 't-field-text__label--required')); ?>
						<?= CHtml::textField('mealTime', '', array('class'=> 't-field-text__input js-mealTime', 'id' => 'mealTime'));?>
					</div>
					<div class="t-field-select column">
						<?= chtml::label('Refeição *', 'meal', array('class'=> 't-field-select__label--required'));?>
						<?=  CHtml::dropDownList('meal', '',  array(
										null => 'Selecione a refeição',
										'0' => 'Café da manhã',
										'1' => 'Almoço',
										'2' => 'Lanche',
										'3' => 'jantar',
									), array('class' => 'select-search-on t-field-select__input js-meal')); ?>
					</div>
				</div>
				<div class="row">
					
					<div class="t-field-select column">
						<?= chtml::label('Turno *', 'shift', array('class'=> 't-field-select__label--required'));?>
						<?=  CHtml::dropDownList('shift', '',  [
										null => 'Selecione o turno',
										'M' => 'Manhã',
										'T' => 'Tarde',
										'N' => 'Noite',
						], array('class' => 'select-search-on t-field-select__input js-shift')); ?>
					</div>
					<div class="column"></div>
				</div>
				<div class="row">
					<div class="column t-buttons-container">
						<a class="t-button-secondary js-add-plate">
							Adicionar Prato
						</a>
					</div>
				</div>
				<div class="row">
					<div class="column">
						<div id="js-accordion" class="t-accordeon-secondary">
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="row buttons">
		<a class="t-button-primary"><?= $model->isNewRecord ? 'Criar' : 'Salvar' ?></a>
	</div>
				
<?php $this->endWidget(); ?>
	
</div><!-- form -->
<style>
	.date[readonly] {
		cursor: pointer;
  		background-color: white;
	}
</style>
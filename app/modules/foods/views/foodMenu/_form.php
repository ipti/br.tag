<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */
/* @var $form CActiveForm */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js', CClientScript::POS_END );
$cs->registerScriptFile($baseScriptUrl . '/functinos.js', CClientScript::POS_END );
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
				<?php echo $form->labelEx($model,'stage_fk', array('class' => 't-field-select__label')); ?>
				<?php echo $form->dropDownList($model, 'stage_fk', 
				CHtml::listData(FoodPublicTarget::model()->findAll(), 'id', 'name'),
				array(
					'key' => 'id',
					'prompt' => 'Selecione a etapa...',
					'multiple' => 'multiple',
					'class' => 'select-search-on t-field-select__input')); ?>
				<?php echo $form->error($model,'stage_fk'); ?>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column">
				<?php echo $form->labelEx($model,'start_date',  array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'start_date', array('class'=>'t-field-text__input js-date')); ?>
				<?php echo $form->error($model,'start_date'); ?>
			</div>
			<div class="column">
				<?php echo $form->labelEx($model,'final_date', array('class' => 't-field-text__label')); ?>
				<?php echo $form->textField($model,'final_date', array('class'=>'t-field-text__input js-date')); ?>
				<?php echo $form->error($model,'final_date'); ?>
			</div>
		</div>
		<div class="row">
			<div class="t-field-text column is-half">
				<?php echo $form->labelEx($model,'observation', array('class' => 't-field-select__label')); ?>
				<?php echo $form->textField($model,'observation', array('class'=>'t-field-select__input')); ?>
				<?php echo $form->error($model,'observation'); ?>
			</div>
			<div class="column"></div>
		</div>
		<div class="row">
			<div class="column t-buttons-container">
				<a class="t-button-primary js-add-meal">
					Adicionar Refeição
				</a>
			</div>
		</div>
	</div>
	<?php echo $form->errorSummary($model); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Criar' : 'Salvar', array('class'=>'t-button-primary')); ?>
	</div>

	<div class="modal fade t-modal-container js-add-meal-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="t-modal__header">
				<h4 class="t-title">Adicionar Refeições</h4>
				<span class="t-icon-close close" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="t-modal__body">
				<div class="row">
					<div class="t-field-text column">
						<?= chtml::label('Hora da refeição *', 'mealTime', array('class'=> 't-field-text__label--required')); ?>
						<?= CHtml::textField('mealTime', '', array('class'=> 't-field-text__input', 'id' => 'mealTime'));?>
					</div>
					<div class="column clear-margin--right">
						<div class="t-field-checkbox-group row clear-margin--top">
							<div class="t-field-checkbox--vertical">
								<?= chtml::label('S', 'monday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('monday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>
							<div class="t-field-checkbox--vertical">
								<?= chtml::label('T', 'tuesday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('tuesday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>

							<div class="t-field-checkbox--vertical">
								<?= chtml::label('Q', 'wednesday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('wednesday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>

							<div class="t-field-checkbox--vertical">
								<?= chtml::label('Q', 'thursday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('thursday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>

							<div class="t-field-checkbox--vertical">
								<?= chtml::label('S', 'friday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('friday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>

							<div class="t-field-checkbox--vertical">
								<?= chtml::label('S', 'saturday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('saturday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>

							<div class="t-field-checkbox--vertical">
								<?= chtml::label('D', 'sunday', array('class'=> 't-field-checkbox__label'));?>
								<?= CHtml::checkBox('sunday', false, array('class' => 't-field-checkbox__input')) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="t-field-select column">
						<?= chtml::label('Publico *', 'stage_fk', array('class'=> 't-field-select__label--required'));?>
						<?=  CHtml::dropDownList('stage', '', [], array('class' => 'select-search-on t-field-select__input')); ?>
					</div>
					<div class="t-field-select column">
						<?= chtml::label('Turno *', 'stage_fk', array('class'=> 't-field-select__label--required'));?>
						<?=  CHtml::dropDownList('shift', '', [], array('class' => 'select-search-on t-field-select__input')); ?>
					</div>
				</div>
				<div class="row">
					<div class="column t-buttons-container">
						<a class="t-button-primary js-add-plate">
							Adicionar Prato
						</a>
					</div>
				</div>
				<div class="row">
					<div class="column">
						<div id="js-accordion" class="t-accordeon-primary">
						</div>
					</div>
				</div>
			</div>
			<div class="t-modal__footer row">
					<div class="t-buttons-container column">
						<button class="t-button-secondary clear-margin--right" data-dismiss="modal">Cancelar</button>
					</div>
					<div class="t-buttons-container column">
						<button class="t-button-primary clear-margin--right">Salvar</button>
					</div>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
	
</div><!-- form -->

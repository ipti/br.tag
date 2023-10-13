<?php
/* @var $this DefaultController */
/* @var $model EdcensoStageVsModality */
/* @var $form CActiveForm */

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'edcenso-stage-vs-modality-form',
	'enableAjaxValidation'=>false,
));
?>

<div class="form">
    <div class="mobile-row ">
        <div class="column clearleft">
            <h1><?php echo $model->isNewRecord ? 'Criar Etapa' : 'Atualizar Etapa ' . $model->id?></h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end">
            <a class="t-button-primary" type="button">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
            </a>
        </div>
    </div>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stage'); ?>
		<?php echo $form->textField($model,'stage'); ?>
		<?php echo $form->error($model,'stage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>


<?php $this->endWidget(); ?>

</div>

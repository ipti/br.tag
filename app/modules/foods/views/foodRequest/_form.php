<?php
/* @var $this FoodRequestController */
/* @var $model FoodRequest */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\foods\resources\request\_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile('app\modules\foods\resources\request\functions.js', CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-request-form',
	'enableAjaxValidation'=>false,
));
?>

<div class="form">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Solicitações' : '' ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="column clearleft">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>
    <div class="row">
        <div class="column clearleft">

        </div>
    </div>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'food_fk'); ?>
		<?php echo $form->textField($model,'food_fk'); ?>
		<?php echo $form->error($model,'food_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'measurementUnit'); ?>
		<?php echo $form->textField($model,'measurementUnit',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'measurementUnit'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div> -->

<?php $this->endWidget(); ?>

</div><!-- form -->

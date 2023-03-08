<?php
/* @var $this ProfessionalController */
/* @var $model Professional */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php
	$baseUrl = Yii::app()->baseUrl;
	$themeUrl = Yii::app()->theme->baseUrl;
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile($baseUrl . '/js/professional/form/pagination.js', CClientScript::POS_END);
	$cs->registerCssFile($themeUrl . '/css/template2.css');
	$cs->registerScript("VARS", "
    var GET_INSTITUTIONS = '" . $this->createUrl('instructor/getInstitutions') . "';
", CClientScript::POS_BEGIN);

	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'professional-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	));
	?>
	<div class="row-fluid hidden-print">
		<div class="span12">
			<h1><?php echo $title; ?></h1>
			<div class="tag-buttons-container buttons">
				<button class="t-button-primary pull-right save-professional" type="submit">
					<?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
				</button>
			</div>
		</div>
	</div>

	<div class="tag-inner">
		<?php if (Yii::app()->user->hasFlash('success') && (!$model->isNewRecord)) : ?>
			<div class="alert alert-success">
				<?php echo Yii::app()->user->getFlash('success') ?>
			</div>
		<?php endif ?>
		<div class="widget widget-tabs border-bottom-none">
			<?php echo $form->errorSummary($model); ?>
			<div class="alert alert-error classroom-error no-show"></div>
			<div class="widget-body form-horizontal">
				<div class="tab-content">
					<div class="tab-pane active" id="professional-identify">
						<div>
							<h3>Dados Básicos</h3>
						</div>
						<div class="row-fluid">
							<div class="span6">
								<div class="separator">
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($model, 'name'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($model, 'cpf', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($model, 'cpf', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($model, 'cpf'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($model, 'speciality', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($model, 'speciality', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($model, 'speciality'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($model, 'fundeb', array('class' => 'control-label', 'style' => 'width: 70px;')); ?>
											<?php echo $form->checkBox($model, 'fundeb', array('value' => 1, 'uncheckValue' => 0));?>
										</div>
										<div class="controls">
											<?php echo $form->error($model, 'fundeb'); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php $this->endWidget(); ?>
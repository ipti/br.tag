<?php
/* @var $this ProfessionalController */
/* @var $modelProfessional Professional */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php
	$baseUrl = Yii::app()->baseUrl;
	$themeUrl = Yii::app()->theme->baseUrl;
	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile($themeUrl . '/css/template2.css');

	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'professional-form',
		'enableAjaxValidation' => false,
	));
	?>
	<div class="row-fluid hidden-print">
		<div class="span12">
			<h1><?php echo $title; ?></h1>
			<div class="tag-buttons-container buttons">
				<button class="t-button-primary pull-right save-professional" type="submit">
					<?= $modelProfessional->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
				</button>
			</div>
		</div>
	</div>

	<div class="tag-inner">
		<?php if (Yii::app()->user->hasFlash('success') && (!$modelProfessional->isNewRecord)) : ?>
			<div class="alert alert-success">
				<?php echo Yii::app()->user->getFlash('success') ?>
			</div>
		<?php endif ?>
		<div class="widget widget-tabs border-bottom-none">
			<?php echo $form->errorSummary($modelProfessional); ?>
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
											<?php echo $form->labelEx($modelProfessional, 'name', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($modelProfessional, 'name', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($modelProfessional, 'name'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($modelProfessional, 'cpf', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($modelProfessional, 'cpf', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($modelProfessional, 'cpf'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($modelProfessional, 'speciality', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($modelProfessional, 'speciality', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($modelProfessional, 'speciality'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($modelProfessional, 'fundeb', array('class' => 'control-label', 'style' => 'width: 70px;')); ?>
											<?php echo $form->checkBox($modelProfessional, 'fundeb', array('value' => 1, 'uncheckValue' => 0));?>
										</div>
										<div class="controls">
											<?php echo $form->error($modelProfessional, 'fundeb'); ?>
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
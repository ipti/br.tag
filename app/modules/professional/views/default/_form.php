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

	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'id' => 'professional-form',
			'enableAjaxValidation' => false,
		)
	);
	?>
	<div class="row-fluid hidden-print">
		<div class="span12">
			<h1>
				<?php echo $title; ?>
			</h1>
			<div class="tag-buttons-container buttons">
				<button class="t-button-primary pull-right save-professional" type="submit">
					<?= $modelProfessional->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
				</button>
			</div>
		</div>
	</div>

	<div class="tag-inner">
		<?php if (Yii::app()->user->hasFlash('success') && (!$modelProfessional->isNewRecord)): ?>
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
											<?php echo $form->labelEx($modelProfessional, 'cpf_professional', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->textField($modelProfessional, 'cpf_professional', array('size' => 60, 'maxlength' => 100)); ?>
											<?php echo $form->error($modelProfessional, 'cpf_professional'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($modelProfessional, 'speciality_fk', array('class' => 'control-label')); ?>
										</div>
										<div class="controls">
											<?php echo $form->DropDownList(
												$modelProfessional,
												'speciality_fk',
												CHtml::listData(EdcensoProfessionalEducationCourse::model()->findAll(array('order' => 'name')), 'id', 'name'),
												array('prompt' => 'Selecione a especialidade', 'class' => 'select-search-on control-input')
											); ?>
											<?php echo $form->error($modelProfessional, 'speciality_fk'); ?>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<?php echo $form->labelEx($modelProfessional, 'fundeb', array('class' => 'control-label', 'style' => 'width: 70px;')); ?>
											<?php echo $form->checkBox($modelProfessional, 'fundeb', array('value' => 1, 'uncheckValue' => 0)); ?>
										</div>
										<div class="controls">
											<?php echo $form->error($modelProfessional, 'fundeb'); ?>
										</div>
									</div>
								</div>
							</div>
							<?php if(!$modelProfessional->isNewRecord) {?>
							<div class="span6">
								<div class="row">
									<a href="#" class="t-button-primary  " id="new-attendance-button">Adicionar Atendimento</a>
								</div>
								<div class="attendance-container">
									<div class="form-attendance">
										<div>
											<h3>Atendimento</h3>
										</div>
										<div class="control-group">
											<div class="controls">
												<?php echo $form->labelEx($modelAttendance, 'date', array('class' => 'control-label')); ?>
											</div>
											<div class="controls">
												<?php echo $form->dateField($modelAttendance, 'date', array('size' => 60, 'maxlength' => 100)); ?>
												<?php echo $form->error($modelAttendance, 'date'); ?>
											</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<?php echo $form->labelEx($modelAttendance, 'local', array('class' => 'control-label')); ?>
											</div>
											<div class="controls">
												<?php echo $form->textField($modelAttendance, 'local', array('size' => 60, 'maxlength' => 100)); ?>
												<?php echo $form->error($modelAttendance, 'local'); ?>
											</div>
										</div>
									</div>
									<div id="attendances" class="widget widget-scroll margin-bottom-none table-responsive">
										<h3>
											Atendimentos
										</h3>
										<table class="tag-table table-bordered table-striped"
											aria-describedby="tabela de atendimentos">
											<thead>
												<tr>
													<th style="text-align: center; min-width: 200px;">Data</th>
													<th style="text-align: center; min-width: 200px;">Local</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												foreach ($modelAttendances as $attendance) {
												?>
													<tr>
														<td style="text-align: center;"><?php echo date("d/m/Y", strtotime($attendance->date)) ?></td>
														<td style="text-align: center;"><?php echo $attendance->local?></td>
													</tr>
												<?php
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>				
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php $this->endWidget(); ?>
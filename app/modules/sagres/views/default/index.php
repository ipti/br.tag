<div id="mainPage" class="main" style="margin-top:40px; padding: 10px">
	<?php
	$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));
	?>

	<div class="clearfix"></div>
	<div class="widget widget-4 widget-tabs-icons-only widget-timeline margin-bottom-none">

		<div class="tab-pane active" id="Sagres_identify">
			<div class="row-fluid">
				<div class=" span6">
					
					<!-- nomeUnidGestora -->
					<div class="control-group">
						<div class="controls required">
							<?php echo $form->labelEx($SagresModel, 'name', array('class' => 'control-label')); ?>
						</div>
						<div class="controls">
							<?php echo $form->textField($SagresModel, 'name', array('size' => 60, 'maxlength' => 100)); ?>
							<?php echo Yii::t('help', 'Nome Completo Unidade Gestora'); ?>"><i></i></span> -->
							<?php echo $form->error($SagresModel, 'name'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="widget-body">
		</div>

		<div>
			<a href="<?= CHtml::normalizeUrl(array('sagres/export')) ?>" class="tag-button medium-button"> <?= Yii::t('default', 'Export Now') ?>
			</a>
		</div>
		<!-- Widget Heading END -->


	</div>
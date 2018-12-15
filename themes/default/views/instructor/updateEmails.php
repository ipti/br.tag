<?php
	/**
	 * Created by PhpStorm.
	 * User: IPTIPC100
	 * Date: 29/06/2016
	 * Time: 14:02
	 */
	$baseUrl = Yii::app()->baseUrl;
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile($baseUrl . '/js/instructor/form/updateEmails.js', CClientScript::POS_END);

	$this->setPageTitle('TAG - ' . Yii::t('default', 'Update Instructor e-mails'));

	$form = $this->beginWidget('CActiveForm', [
		'id' => 'updateEmails-form', 'enableAjaxValidation' => FALSE,
	]);
?>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic"><?= yii::t('default', 'Update Instructor e-mails') ?></h3>
		<div class="buttons">
			<?php echo CHtml::htmlButton('<i></i>' . Yii::t('default', 'Save'), array('id' => 'save-emails', 'class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'button')); ?>
		</div>
	</div>
</div>

<div class="innerLR instructor-emails">
	<div class="form-horizontal">
		<div class="alert alert-danger">Preencha os e-mails corretamente.</div>
		<?php foreach ($instructors as $instructor): ?>
			<div class="control-group">
				<label class="control-label" for="instructor"><?= $instructor->name ?></label>
				<div class="controls">
					<input name="<?= $instructor->id ?>" class="instructor" type="email">
				</div>
			</div>
		<?php endforeach ?>
		<div class="clear"></div>
	</div>
</div>

<?php $this->endWidget(); ?>

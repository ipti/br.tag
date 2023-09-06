<?php

/**
 * Created by PhpStorm.
 * User: IPTIPC100
 * Date: 29/06/2016
 * Time: 14:02
 */
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/instructor/form/updateEmails.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Update Instructor e-mails'));

$form = $this->beginWidget('CActiveForm', [
	'id' => 'updateEmails-form', 'enableAjaxValidation' => FALSE,
]);
?>
<div class="main">
	<div class="row-fluid">
		<div class="span12">
			<h1><?= yii::t('default', 'Update Instructor e-mails') ?></h1>
			<div class="tag-buttons-container buttons">
				<?php echo CHtml::htmlButton(Yii::t('default', 'Save'), array('id' => 'save-emails', 'class' => 't-button-primary  last', 'type' => 'button')); ?>
			</div>
		</div>
	</div>
	<!-- <style>
		.form-columns {
			column-count: 2;
			/* Divide em duas colunas */
		}

		.control-group {
			break-inside: avoid-column;
			/* Evita que um elemento seja dividido em duas colunas */
			margin-bottom: 20px;
			/* Espaçamento entre os elementos */
		}
	</style> -->

	<div class="innerLR instructor-emails">
		<div class="form-horizontal">
			<div class="alert alert-danger">Preencha os e-mails corretamente.</div>
			<?php foreach ($instructors as $key => $instructor) : ?>
				<?php
				if ($key % 2 == 0) {
					echo '<div class="row">';
				}
				?>
				<div class="column is-two-fifths">
					<label class="t-field-text" for="instructor"><?= $instructor->name ?></label>
					<input name="<?= $instructor->id ?>" class="instructor t-field-text__input" type="email">
				</div>
				<?php
				if ($key % 2 == 0) {
					echo '</div>';
				}
				?>
			<?php endforeach ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
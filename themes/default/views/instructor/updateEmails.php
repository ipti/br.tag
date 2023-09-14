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
	<div class="innerLR instructor-emails">
    <div class="alert alert-danger">Preencha os e-mails corretamente.</div>
    <div class="row wrap">
        <?php $half_count = ceil(count($instructors) / 2); ?>
        <div class="column is-one-third">
            <?php foreach (array_slice($instructors, 0, $half_count) as $instructor) : ?>
                <label class="t-field-text" for="instructor"><?= $instructor->name ?></label>
                <input name="<?= $instructor->id ?>" class="instructor t-field-text__input" type="email">
            <?php endforeach ?>
        </div>
        <div class="column is-one-third">
            <?php foreach (array_slice($instructors, $half_count) as $instructor) : ?>
                <label class="t-field-text" for="instructor"><?= $instructor->name ?></label>
                <input name="<?= $instructor->id ?>" class="instructor t-field-text__input" type="email">
            <?php endforeach ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<?php $this->endWidget(); ?>
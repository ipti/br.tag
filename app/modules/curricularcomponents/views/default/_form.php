<?php
/**
* @var DefaultController $this DefaultController
* @var EdcensoDiscipline $model EdcensoDiscipline
* @var EdcensoBaseDisciplines[] $edcensoBaseDisciplines EdcensoBaseDiscipline[]
* @var CActiveForm $form CActiveForm
*/

$form = $this->beginWidget('CActiveForm', [
    'id' => 'edcenso-discipline-_form-form',
    'enableAjaxValidation' => false,
]);
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile($baseScriptUrl . '/common/js/professional.js?v=1.1', CClientScript::POS_END);

if (!$model->isNewRecord) {
    $cantChangeCensoDiscipline = $model->edcenso_base_discipline_fk < 99;
}

?>

<div class="form">
    <div class="row-fluid hidden-print">
		<div class="span12">
			<h1>
				<?php echo $title; ?>
			</h1>
			<div class="tag-buttons-container buttons">
				<button class="t-button-primary pull-right" type="submit">
					<?= $model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save') ?>
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
            <div class="row">
                <div class="column">
                    <?php echo $form->errorSummary($model); ?>

                    <?php echo $form->hiddenField($model, 'id'); ?>

                    <div class="t-field-text">
                        <?php echo $form->label($model, 'name', ['class' => 'control-label t-field-text__label--required']); ?>
                        <?php echo $form->textField($model, 'name'); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>

                    <div class="t-field-text">
                        <?php echo $form->label($model, 'abbreviation', ['class' => 't-field-text__label control-label']); ?>
                        <?php echo $form->textField($model, 'abbreviation'); ?>
                        <?php echo $form->error($model, 'abbreviation'); ?>
                    </div>

                    <div class="t-field-text">
                        <?php echo $form->label($model, 'edcenso_base_discipline_fk', ['class' => 'control-label t-field-text__label--required']); ?>
                        <?php echo $form->dropDownList($model, 'edcenso_base_discipline_fk', CHtml::listData($edcensoBaseDisciplines, 'id', 'name'), ['class' => 't-field-text__input', 'disabled' => $cantChangeCensoDiscipline]); ?>
                        <?php echo $form->error($model, 'edcenso_base_discipline_fk'); ?>
                    </div>
                </div>
                <div class="column"></div>
            </div>
    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

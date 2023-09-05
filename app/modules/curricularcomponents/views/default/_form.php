<?php
/**
* @var DefaultController $this DefaultController
* @var EdcensoDiscipline $model EdcensoDiscipline
* @var EdcensoBaseDisciplines[] $edcenso_base_disciplines EdcensoBaseDiscipline[]
* @var CActiveForm $form CActiveForm
*/

$form = $this->beginWidget('CActiveForm', array(
    'id'=>'edcenso-discipline-_form-form',
    'enableAjaxValidation'=>false,
));
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile($baseScriptUrl . '/common/js/professional.js?v=1.1', CClientScript::POS_END);

if(!$model->isNewRecord){
    $cant_change_censo_discipline = $model->edcenso_base_discipline_fk < 99;
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
                        <?php echo $form->labelEx($model, 'name', array('class' => 'control-label t-field-text__label--required')); ?>
                        <?php echo $form->textField($model, 'name'); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>

                    <div class="t-field-text">
                        <?php echo $form->labelEx($model, 'abbreviation', array('class' => 't-field-text__label control-label')); ?>
                        <?php echo $form->textField($model, 'abbreviation'); ?>
                        <?php echo $form->error($model, 'abbreviation'); ?>
                    </div>

                    <div class="t-field-text">
                        <?php echo $form->labelEx($model, 'edcenso_base_discipline_fk', array('class' => 'control-label t-field-text__label--required')); ?>
                        <?php echo $form->dropDownList($model, 'edcenso_base_discipline_fk', CHtml::listData($edcenso_base_disciplines, "id", "name"), array( 'class' => 't-field-text__input', 'disabled' => $cant_change_censo_discipline)); ?>
                        <?php echo $form->error($model, 'edcenso_base_discipline_fk'); ?>
                    </div>
                </div>
                <div class="column"></div>
            </div>
    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form --> 
<?php
/* @var $this FoodAlertController */
/* @var $model FoodExpirationAlertGroup */
/* @var $assignedFoodIds int[] */
/* @var $foods Food[] */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Alerta de Vencimento'));

$form = $this->beginWidget('CActiveForm', [
    'id' => 'food-alert-group-form',
    'enableAjaxValidation' => false,
]);
?>

<div id="mainPage" class="main">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Novo Grupo de Alerta' : 'Editar Grupo de Alerta'; ?></h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/foodalert/index'); ?>">
                Voltar
            </a>
            <button class="t-button-primary" type="submit">Salvar</button>
        </div>
    </div>

    <?php echo $form->errorSummary($model); ?>

    <div class="tag-inner">
        <div class="row">
            <div class="column t-field-text is-one-third clearleft">
                <?php echo $form->label($model, 'name', ['class' => 't-field-text__label--required']); ?>
                <?php echo $form->textField($model, 'name', [
                    'class' => 't-field-text__input',
                    'maxlength' => 100,
                    'placeholder' => 'Ex.: Hortaliças',
                ]); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="column t-field-text is-one-third">
                <?php echo $form->label($model, 'alert_days', ['class' => 't-field-text__label--required']); ?>
                <?php echo $form->numberField($model, 'alert_days', [
                    'class' => 't-field-text__input',
                    'min' => 1,
                    'placeholder' => 'Ex.: 15',
                ]); ?>
                <?php echo $form->error($model, 'alert_days'); ?>
            </div>
            <div class="column t-field-select is-one-third">
                <label class="t-field-select__label--required">
                    Alimentos deste grupo
                    <span class="t-icon t-icon-info" data-toggle="tooltip" data-placement="right" title="Um alimento só pode estar em um grupo por vez. Se ele já estiver em outro grupo, será movido para este."></span>
                </label>
                <?php echo CHtml::dropDownList(
                    'foods',
                    $assignedFoodIds,
                    CHtml::listData($foods, 'id', 'description'),
                    [
                        'multiple' => 'multiple',
                        'class' => 'select-search-on t-multiselect t-field-select__input select2-container',
                        'placeholder' => 'Selecione os alimentos deste grupo',
                    ]
                ); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

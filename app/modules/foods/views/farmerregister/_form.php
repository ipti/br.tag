<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */
/* @var $modelFarmerFoods FarmerFoods */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\foods\resources\farmer\_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile('app\modules\foods\resources\farmer\functions.js', CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'farmer-register-form',
	'enableAjaxValidation'=>false,
));
?>

<div class="form">

    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Cadastro Agricultor' : '' ?></h1>
            <p></p>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <button id="saveStage" class="t-button-primary" type="submit">
                <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

    <div class="row">
        <h3>Dados Básicos</h3>
    </div>

    <div class="row t-margin-medium--bottom">
        <div class="column is-two-fifths t-field-select t-margin-none--bottom clearfix">
            <?php echo CHtml::label('Para preencher automaticamente, selecione o agricultor', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container">
                <option value="agricultor">Selecione o Agricultor</option>
            </select>
        </div>

        <div class="column is-one-fifth clearleft--on-mobile t-buttons-container t-padding-none--bottom t-margin-none--bottom clearfix">
            <button class="t-button-secondary mobile-margin-top clear-margin--all full--width align-self--end" type="button">Adicionar</button>
        </div>
    </div>

    <div class="row">
        <div class="column clearleft is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($model,'name', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'name', array('id' => 'stageName','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>
        </div>
        <div class="column clearleft--on-mobile is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($model,'cpf', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'cpf', array('id' => 'stageName','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model,'cpf'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column clearleft is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($model,'phone', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'phone', array('id' => 'stageName','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model,'phone'); ?>
            </div>
        </div>
        <div class="column clearleft--on-mobile is-two-fifths">
            <div class="t-field-select">
                <?php echo $form->label($model,'group_type', array('class' => 't-field-select__label--required')); ?>
                <select class="select-search-off t-field-select__input select2-container">
                    <option value="agricultor">Selecione o Grupo</option>
                    <option value="Fornecedor Individual">Fornecedor Individual</option>
                    <option value="Grupo Formal">Grupo Formal</option>
                    <option value="Grupo Informal">Grupo Informal</option>
                </select>
            </div>
        </div>
    </div>

    <hr class="t-separator">

    <div class="row">
        <h3>Relação de produtos</h3>
    </div>

    <div class="row">
        <div class="column clearleft is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($modelFarmerFoods,'amount', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($modelFarmerFoods,'amount', array('id' => 'stageName','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($modelFarmerFoods,'amount'); ?>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div>

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
            <?php echo CHtml::label('Para preencher os dados automaticamente, selecione o agricultor', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container">
                <option value="agricultor">Selecione o Agricultor</option>
            </select>
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
        <div class="column clearleft is-two-fifths">
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
        <div class="column clearleft is-two-fifths">
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

    <div class="row">
        <h3>Relação de produtos</h3>
    </div>

    <div class="row">
        <div class="column clearleft t-margin-none--bottom t-field-select is-two-fifths">
            <?php echo CHtml::label('Selecione o Alimento', 'food_fk', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="foodSelect">
                <option value="alimento">Selecione o Alimento</option>
            </select>
        </div>
        <div class="column clearleft t-margin-none--bottom t-field-text is-one-tenth">
            <?php echo $form->label($modelFarmerFoods,'amount', array('class' => 't-field-text__label--required')); ?>
            <?php echo $form->textField($modelFarmerFoods,'amount', array('id' => 'amount','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
            <?php echo $form->error($modelFarmerFoods,'amount'); ?>
        </div>
        <div class="column clearleft t-margin-none--bottom t-field-text is-one-tenth">
            <?php echo CHtml::label('Unidade', 'measurementUnit', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="measurementUnit" name="measurementUnit">
                <option value="selecione">Selecione</option>
            </select>
        </div>
        <div class="column is-one-tenth clearleft t-buttons-container t-padding-none--bottom t-margin-none--bottom clearfix">
            <button class="t-button-secondary t-margin-none--bottom full--width align-self--end" id="js-add-food" type="button">Adicionar</button>
        </div>
    </div>

    <div class="row">
        <div class="column is-four-fifths clearfix">
            <table id="foodsTable"  aria-describedby="FoodsTable" class="tag-table-secondary align-start">
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th></th>
                </tr>
            </table>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div>

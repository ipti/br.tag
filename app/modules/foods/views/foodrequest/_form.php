<?php
/* @var $this FoodRequestController */
/* @var $model FoodRequest */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\foods\resources\request\_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile('app\modules\foods\resources\request\functions.js', CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-request-form',
	'enableAjaxValidation'=>false,
));
?>

<div class="form">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="t-padding-none--bottom"><?php echo $model->isNewRecord ? 'Gerar solicitação' : '' ?></h1>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <button id="save-request" class="t-button-primary" type="button">
                <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>
    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

    <h3>Dados da solicitação</h3>

    <div class="row">
        <div class="column clearleft t-margin-none--bottom t-field-text is-two-fifths">
            <?php echo CHtml::label('Edital', 'notice', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="foodNotice" name="foodNotice">
                <option value="selecione">Selecione</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="column t-field-select t-multiselect clearfix is-two-fifths">
            <?php echo CHtml::label('Selecione as escolas', 'school_fk', array('class' => 't-field-select__label--required')); ?>
            <?php echo $form->dropDownList($requestSchoolModel,'school_fk',
                Chtml::listData(Yii::app()->user->usersSchools, 'inep_id', 'name'),
                array(
                    'id' => "requestSchools",
                    'multiple' => true,
                    'class' => 'js-select-schools t-field-select__input multiselect'
                )
            ); ?>
        </div>
        <div class="column t-field-select t-multiselect clearleft--on-mobile clearfix is-two-fifths">
            <?php echo CHtml::label('Selecione os agricultores', 'farmer_fk', array('class' => 't-field-select__label--required')); ?>
            <?php echo $form->dropDownList($requestFarmerModel,'farmer_fk',
                Chtml::listData(FarmerRegister::model()->findAll('status="Ativo"'), 'id', 'name'),
                array(
                    'id' => "requestFarmers",
                    'multiple' => true,
                    'class' => 'js-select-farmer t-field-select__input multiselect'
                )
            ); ?>
        </div>
    </div>

    <div class="row">
        <h3>Relação de produtos</h3>
    </div>

    <div class="row">
        <div class="column is-one-third t-margin-none--bottom t-field-select clearfix">
            <?php echo CHtml::label('Selecione o Alimento', 'food_fk', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container clear-margin--all" id="foodSelect">
                <option value="alimento">Selecione o Alimento</option>
            </select>
        </div>
        <div class="column is-one-tenth t-margin-none--bottom clearleft--on-mobile t-field-text clearfix">
            <?php echo $form->label($requestItemModel,'amount', array('class' => 't-field-text__label--required')); ?>
            <?php echo $form->textField($requestItemModel,'amount', array('id' => 'amount','class' => 't-field-text__input clear-margin--all js-amount', 'placeholder' => 'Valor')); ?>
            <?php echo $form->error($requestItemModel,'amount'); ?>
        </div>
        <div class="column is-one-tenth t-margin-none--bottom t-field-select clearfix">
            <?php echo $form->label($requestItemModel,'measurementUnit', array('class' => 't-field-text__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container clear-margin--all" id="measurementUnit" name="measurementUnit">
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

    <div class="row show--tablet">
        <div class="column clearfix">
            <div class="t-buttons-container">
                <a title="Cadastrar solicitacao" id="save-request" class="t-button-primary" type="button">
                    <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
                </a>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div>

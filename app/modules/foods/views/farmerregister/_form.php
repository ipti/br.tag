<?php
/* @var $this FarmerRegisterController */
/* @var $model FarmerRegister */
/* @var $modelFarmerFoods FarmerFoods */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/farmer/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/farmer/functions.js?v='.TAG_VERSION, CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'farmer-register-form',
	'enableAjaxValidation'=>false,
));
?>

<div class="form">

    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Cadastro Agricultor' : 'Atualizar Agricultor' ?></h1>
            <p></p>
        </div>
        <div class="column clearfix align-items--center justify-content--end show--desktop">
            <button id="save-farmer" class="t-button-primary" type="button">
                <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="column clearfix">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
                <br/>
            <?php endif ?>
        </div>
    </div>

    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

    <h3>Dados Básicos</h3>
    <p>Informe o CPF, caso o agricultor já possua cadastro na plataforma NHAM, os dados básicos serão preenchidos automaticamente.</p>

    <div class="row">
        <div class="column clearleft is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($model,'cpf', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'cpf', array('id' => 'farmerCpf','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model,'cpf'); ?>
            </div>
        </div>
        <div class="column clearleft is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($model,'name', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'name', array('id' => 'farmerName', 'disabled' => 'disabled','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model,'name'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column clearleft is-two-fifths">
            <div class="t-field-text">
                <?php echo $form->label($model,'phone', array('class' => 't-field-text__label--required')); ?>
                <?php echo $form->textField($model,'phone', array('id' => 'farmerPhone', 'disabled' => 'disabled','size'=>60,'maxlength'=>100, 'class' => 't-field-text__input')); ?>
                <?php echo $form->error($model,'phone'); ?>
            </div>
        </div>
        <div class="column clearleft is-two-fifths">
            <div class="t-field-select">
                <?php echo $form->label($model,'group_type', array('class' => 't-field-select__label--required')); ?>
                <?php
                echo $form->DropDownList($model, 'group_type', array(
                    "agricultor" => "Selecione o Grupo",
                    "Fornecedor Individual" => "Fornecedor Individual",
                    "Grupo Formal" => "Grupo Formal",
                    "Grupo Informal" => "Grupo Informal",
                ), array('id'=>'farmerGroupType', 'disabled' => 'disabled','class' => 'select-search-off t-field-select__input select2-container'));
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <h3>Relação de produtos</h3>
    </div>

    <div class="row">
        <div class="column clearleft t-margin-none--bottom t-field-select is-one-third">
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
        <div class="column clearleft t-margin-none--bottom t-field-text is-one-tenth">
            <?php echo CHtml::label('Edital', 'notice', array('class' => 't-field-select__label--required')); ?>
            <select class="select-search-on t-field-select__input select2-container" id="foodNotice" name="foodNotice">
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
                    <th>Edital</th>
                    <th></th>
                </tr>
            </table>
        </div>
    </div>

    <div class="row show--tablet">
        <div class="column clearfix">
            <div class="t-buttons-container">
                <a title="Salvar agricultor" id="save-farmer" class="t-button-primary" type="button">
                    <?= $model->isNewRecord ? Yii::t('default', 'Cadastrar') : Yii::t('default', 'Save') ?>
                </a>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div>

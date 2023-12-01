<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\foods\resources\inventory\_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile('app\modules\foods\resources\inventory\functions.js', CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-inventory-form',
	'enableAjaxValidation'=>false,
));

?>

<div class="form">
    <div class="mobile-row">
        <div class="column clearleft">
            <h1 class="clear-padding--bottom"><?php echo $model->isNewRecord ? 'Estoque' : '' ?></h1>
            <p>Cardápio semanal da sua escola</p>
        </div>
    </div>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <button class="t-button-primary" id="js-entry-stock-button" type="button">Lançamento de Estoque</button>
        <button class="t-button-primary">Solicitações</button>
        <a class="t-button-secondary"><span class="t-icon-printer"></span>Relatório de Estoque</a>
    </div>

    <div class="modal fade t-modal-container larger" id="js-entry-stock-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="t-modal__header">
                <h4 class="t-title" id="myModalLabel">Lançamento de Estoque</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <form method="post">
                <div class="t-modal__body">
                    <p>Selecione os itens e quantidades para adicionar ao estoque</p>
                    <div class="row">
                        <div class="column is-two-fifths t-field-select clearfix">
                            <label class="t-field-text__label--required">Selecione o Alimento</label>
                            <select class="select-search-off t-field-select__input select2-container">
                                <option value="">Buscar Alimento</option>
                            </select>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-field-text clearfix">
                            <label class="t-field-text__label--required">Quantidade</label>
                            <input type="text" class="t-field-text__input" placeholder="Valor"></input>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-field-text clearfix">
                            <label class="t-field-text__label--required">Validade</label>
                            <input type="date" class="t-field-text__input date" placeholder="Valor"></input>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-buttons-container clearfix justify-content--end">
                            <button class="t-button-secondary clear-margin--right full--width">Adicionar</button>
                        </div>
                    </div>
                    <div class="t-modal__footer row reverse">
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-primary clear-margin--right js-add-classroom-diary" data-dismiss="modal">Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

	<!-- <div class="row">
		<?php echo $form->labelEx($model,'school_fk'); ?>
		<?php echo $form->textField($model,'school_fk',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'school_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'food_fk'); ?>
		<?php echo $form->textField($model,'food_fk'); ?>
		<?php echo $form->error($model,'food_fk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'measurementUnit'); ?>
		<?php echo $form->textField($model,'measurementUnit',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'measurementUnit'); ?>
	</div>

    <div class="row ">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div> -->

<?php $this->endWidget(); ?>
</div>

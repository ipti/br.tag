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
                        <div class="column is-two-fifths t-field-select clear-margin--bottom clearfix">
                            <?php echo CHtml::label('Selecione o Alimento', 'food_fk', array('class' => 't-field-select__label--required')); ?>
                            <select class="select-search-on t-field-select__input select2-container" id="food" name="food">
                                <option>Selecione a turma</option>
                                <option>Arroz (KG)</option>
                                <option>Feijão (KG)</option>
                                <option>Macarrão (KG)</option>
                            </select>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-field-text clear-margin--bottom clearfix">
                            <?php echo $form->labelEx($model,'amount', array('class' => 't-field-text__label--required')); ?>
                            <?php echo $form->textField($model,'amount', array('class' => 't-field-text__input clear-margin--all js-amount', 'placeholder' => 'Valor')); ?>
                            <?php echo $form->error($model,'amount'); ?>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-field-text clear-margin--bottom clearfix">
                            <?php echo $form->labelEx($model,'expiration_date',  array('class' => 't-field-text__label')); ?>
                            <?php echo $form->textField($model,'expiration_date', array('class'=>'t-field-text__input js-date clear-margin--all js-expiration-date', 'placeholder' => 'Selecione')); ?>
                            <?php echo $form->error($model,'expiration_date'); ?>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-buttons-container clear-margin--bottom clearfix">
                            <button class="t-button-secondary clear-margin--all full--width" id="add-food" type="button">Adicionar</button>
                        </div>
                    </div>

                    <div id="foods_stock">
                    </div>

                    <div class="t-modal__footer row reverse">
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-primary clear-margin--right js-add-classroom-diary" data-dismiss="modal">Adicionar ao estoque</button>
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

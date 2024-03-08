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
            <h1 class="t-padding-none--bottom"><?php echo $model->isNewRecord ? 'Solicitações' : '' ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

	<div class="row  t-buttons-container">
        <a class="t-button-primary" id="js-entry-request-button" type="button">Gerar Solicitação</a>
    </div>

	<div class="row">
        <div class="t-field-select column is-two-fifths clearfix">
            <select class="select-search-on t-field-select__input select2-container" id="foodRequestSelect">
                <option value="total">Filtrar solicitações por alimento</option>
            </select>
        </div>
    </div>

	<div class="row">
        <div class="column is-four-fifths clearfix">
            <table id="foodRequestTable" class="tag-table-secondary align-start">

            </table>
        </div>
    </div>

	<div class="modal fade t-modal-container larger" id="js-entry-request-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            <div class="t-modal__header">
                <h4 class="t-title" id="myModalLabel">Gerar Solicitação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <form method="post">
                <div class="t-modal__body">
                    <p>Selecione os itens e quantidades para gerar solicitações</p>
                    <div class="row">
                        <div class="column clearfix">
                            <div id="request-modal-alert" class="alert hide"></div>
                        </div>
                    </div>
                    <div class="tablet-row bottom-margin">
						<div class="column is-third t-field-select t-margin-none--bottom clearfix">
							<?php echo CHtml::label('Selecione o Alimento', 'food_fk', array('class' => 't-field-select__label--required')); ?>
							<select class="select-search-on t-field-select__input select2-container clear-margin--all" id="food" name="food">
								<option value="alimento">Selecione o Alimento</option>
							</select>
						</div>
						<div class="column is-one-tenth clearleft--on-mobile t-field-text t-margin-none--bottom clearfix">
							<?php echo $form->label($model,'amount', array('class' => 't-field-text__label--required')); ?>
							<?php echo $form->textField($model,'amount', array('class' => 't-field-text__input clear-margin--all js-amount', 'placeholder' => 'Valor')); ?>
							<?php echo $form->error($model,'amount'); ?>
						</div>
						<div class="column is-one-tenth clearleft--on-mobile t-field-text t-margin-none--bottom clearfix">
							<?php echo CHtml::label('Unidade', 'measurementUnit', array('class' => 't-field-select__label--required')); ?>
							<select class="select-search-on t-field-select__input select2-container clear-margin--all" id="measurementUnit" name="measurementUnit">
                                <option value="selecione">Selecione</option>
							</select>
						</div>
						<div class="column t-field-text clearleft--on-mobile t-margin-none--bottom is-two-fifths clearfix">
							<?php echo $form->label($model,'description', array('class' => 't-field-text__label--required')); ?>
							<?php echo $form->textField($model,'description', array('class' => 't-field-text__input js-description clear-margin--all', 'placeholder' => 'Informe')); ?>
							<?php echo $form->error($model,'description'); ?>
						</div>
						<div class="column is-one-tenth clearleft--on-mobile t-buttons-container t-margin-none--bottom clearfix">
							<button class="t-button-secondary mobile-margin-top t-margin-none--bottom full--width align-self--end" id="add-request" type="button">Adicionar</button>
						</div>
                    </div>

                    <div id="food_request"></div>

                    <div class="t-modal__footer row reverse">
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-primary clear-margin--right" data-dismiss="modal" id="save-request">Enviar solicitação</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

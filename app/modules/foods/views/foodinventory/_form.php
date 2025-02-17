<?php
/* @var $this FoodInventoryController */
/* @var $model FoodInventory */
/* @var foodInventoryData[] $foodInventoryData foodInventoryData[] */
/* @var $form CActiveForm */

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\foods\resources\inventory\_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile('app\modules\foods\resources\inventory\functions.js?v='.TAG_VERSION, CClientScript::POS_END);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-inventory-form',
	'enableAjaxValidation'=>false,
));

$isNutritionist = Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id);
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
        <div class="column clearfix">
            <div id="info-alert" class="alert hide"></div>
        </div>
    </div>

    <div class="row t-buttons-container">
        <?php
            if(!$isNutritionist):
        ?>
        <a class="t-button-primary" id="js-entry-stock-button" type="button">Lançamento de Estoque</a>
        <?php endif; ?>
        <a class="t-button-secondary hide"><span class="t-icon-printer"></span>Relatório de Estoque</a>
    </div>
    <div class="row">
        <div class="t-field-select column is-one-fifth clearfix">
            <select class="select-search-on t-field-select__input select2-container" id="foodStockSelect">
                <option value="total">Filtrar por alimento</option>
            </select>
        </div>
        <div class="t-field-select column is-one-fifth clearfix">
            <select class="select-search-on t-field-select__input select2-container" id="foodStatusFilter">
                <option value="total">Filtrar por status</option>
                <option value="Disponivel">Disponível</option>
                <option value="Acabando">Acabando</option>
                <option value="Emfalta">Em falta</option>
            </select>
        </div>
    </div>
    <div class="row show--tabletDesktop">
        <div class="column is-four-fifths clearfix">
            <table id="foodStockTable"  aria-describedby="FoodStockTable" role="table" class="tag-table-secondary align-start">
             <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
             </tr>
            </table>
        </div>
    </div>
    <div id="foodStockList" class="row show--mobile">
    </div>

    <div class="modal fade t-modal-container" id="js-movements-modal" tabindex="-1">
        <div class="modal-dialog ">
            <div class="t-modal__header">
                <h4 class="t-title" id="myModalLabel">Movimentações</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <form method="post">
                <div class="t-modal__body">
                    <p>Entrada e saída de itens</p>
                    <table id="movementsTable" aria-describedby="MovementsTable" class="tag-table-secondary align-start">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade t-modal-container larger" id="js-entry-stock-modal" tabindex="-1">
        <div class="modal-dialog ">
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
                        <div class="column clearfix">
                            <div id="stock-modal-alert" class="alert hide"></div>
                        </div>
                    </div>
                    <div class="tablet-row bottom-margin">
                        <div class="column is-two-fifths t-field-select t-margin-none--bottom clearfix">
                            <?php echo CHtml::label('Selecione o Alimento', 'food_fk', array('class' => 't-field-select__label--required')); ?>
                            <select class="select-search-on t-field-select__input select2-container" id="food" name="food">
                                <option value="alimento">Selecione o Alimento</option>
                            </select>
                        </div>
                        <div class="column is-one-tenth clearleft--on-mobile t-field-text t-margin-none--bottom clearfix">
                            <?php echo $form->label($model,'amount', array('class' => 't-field-text__label--required')); ?>
                            <?php echo $form->textField($model,'amount', array('class' => 't-field-text__input t-margin-none js-amount', 'placeholder' => 'Valor')); ?>
                            <?php echo $form->error($model,'amount'); ?>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-field-text t-margin-none--bottom clearfix">
                            <?php echo CHtml::label('Unidade', 'measurementUnit', array('class' => 't-field-select__label--required')); ?>
                            <select class="select-search-on t-field-select__input select2-container" id="measurementUnit" name="measurementUnit">
                                <option value="selecione">Selecione</option>
                            </select>
                        </div>
                        <div class="column is-one-tenth clearleft--on-mobile t-field-text t-margin-none--bottom clearfix">
                            <?php echo $form->label($model,'expiration_date',  array('class' => 't-field-text__label')); ?>
                            <?php echo $form->textField($model,'expiration_date', array('class'=>'t-field-text__input js-date t-margin-none--top js-expiration-date', 'placeholder' => 'Selecione')); ?>
                            <?php echo $form->error($model,'expiration_date'); ?>
                        </div>
                        <div class="column is-one-fifth clearleft--on-mobile t-buttons-container t-padding-none--bottom t-margin-none--bottom clearfix">
                            <button class="t-button-secondary mobile-margin-top clear-margin--all full--width align-self--end" id="add-food" type="button">Adicionar</button>
                        </div>
                    </div>

                    <div id="foods_stock">
                    </div>

                    <div class="t-modal__footer row reverse">
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="t-buttons-container justify-content--center">
                            <button type="button" class="t-button-primary clear-margin--right" id="save-food">Adicionar ao estoque</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade t-modal-container" id="js-status-modal" tabindex="-1">
        <div class="modal-dialog ">
            <div class="t-modal__header">
                <div class="column clearfix">
                    <h4 class="t-title" id="myModalLabel">Definir Status</h4>
                    <p style="margin-bottom: 0px">Defina o status do produto</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <form method="post">
                <div class="t-modal__body">
                    <div class="row">
                        <div class="column t-field-select t-margin-none--bottom clearfix">
                            <?php echo CHtml::label('Status', array('class' => 't-field-select__label')); ?>
                            <select id="js-status-select" class="select-search-on t-field-select__input select2-container">
                                <option value="Disponivel">Disponível</option>
                                <option value="Acabando">Acabando</option>
                                <option value="Emfalta">Em falta</option>
                            </select>
                        </div>
                    </div>
                    <div class="row margin-large--top">
                        <div class="t-buttons-container">
                            <button type="button" id="js-saveFoodInventoryStatus" data-dismiss="modal" class="t-button-primary t-margin-none--right full--width">Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
</script>

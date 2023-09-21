<?php
/* @var $this StockController
 */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('lunchModule.stock', 'Stock');
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/stock.js', CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/lunch.css');
?>

<div class="main">

    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1><?= Yii::t('lunchModule.stock', 'Stock'); ?><br>
            <span><?= Yii::t('lunchModule.stock', 'Add or remove items from stock, have control of all transactions.'); ?></span>
            </h1>
        </div>
    </div>


    <div class="home stock-home">
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-success">
                        <?php echo Yii::app()->user->getFlash('success') ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('error')) : ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="alert alert-error">
                        <?php echo Yii::app()->user->getFlash('error') ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <div class="container-box" style="display: flex;">
                        <div class="stock-container">
                            <table class="stock-items-table js-tag-table js-disabled-table" aria-labelledby="Stock Table">
                                <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Armazenado</th>
                                        <th scope="col">Em falta</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->school->itemsAmount() as $item) : ?>
                                        <tr>
                                            <td><?= $item['name'] ?></td>
                                            <td><?= $item['amount'] * $item['measure'] . " " . $zitem['unity'] ?></td>
                                            <td class="<?= $item['amount'] * $item['measure'] != 0 ? "in" : "out" ?>">
                                                <?php echo $item['amount'] * $item['measure'] != 0 ? "Não" : "Sim" ?>
                                            </td>
                                            <td id="js-removeItem" data-id="<?= $item['id'] ?>">
                                                <span class="t-icon-trash t-button-icon-danger js-change-cursor"></span>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="transactions-container">
                            <div class="button-container">
                                <button  data-toggle="modal" href="#addItem" type="button" class="btn btn-primary add-item-button" data-dismiss="modal">Adicionar ao estoque</button>
                                <button  data-toggle="modal" href="#removeItem" type="button" class="btn btn-danger remove-item-button" data-dismiss="modal">Remover do estoque</button>
                            </div>
                            <div class="trasactions-header" style="margin-bottom: 20px;">
                                <h3>Movimentações</h3>
                                <h4>Entrada e saída de itens</h4>
                            </div>
                            <table class="transactions-table js-tag-table" aria-labelledby="Transactions Table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Quantidade</th>
                                        <th scope="col">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($this->school->transactions() as $transaction) {
                                        $isSpent = !is_null($transaction['motivation']);
                                        $amount = ($isSpent ? (-1) : (1)) * $transaction['amount'] * $transaction['measure'] . $transaction['acronym'];
                                        $name = $transaction['name'];
                                        $date = date("d/m/Y", strtotime($transaction['date']));
                                    ?>
                                    <tr>
                                        <td style="text-align: center;" class="<?= $isSpent ? "out" : "in" ?>"><?php echo $isSpent ? "Saída" : "Entrada" ?></td>
                                        <td><?= $name?></td>
                                        <td><?= $amount?></td>
                                        <td><?= $date?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal fade modal-content" id="addItem">
    <div class="modal-dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"><?= Yii::t('lunchModule.stock', 'Add Item'); ?></h4>
        </div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'add-item',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('lunch/stock/addItem')
        ));
        ?>
        <div class="modal-body">

            <div id="is-add-amount" class="widget widget-scroll margin-bottom-none">
                <div class="widget-head" style="background: none;">
                    <h4 class="heading">
                        <?= yii::t('lunchModule.stock', 'Add Amount') ?>
                    </h4>
                </div>
                <div class="widget-body">

                    <div class="row-fluid">
                        <div class=" span8">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Item'), 'Item', array('class' => 'control-label')); ?>
                            <div class="controls span12">
                                <?= CHtml::dropDownList(
                                    'Inventory[item]',
                                    '',
                                    CHtml::listData(Item::model()->findAll(), 'id', 'concatName'),
                                    ['class' => 'pull-left span12']
                                ); ?>

                            </div>
                        </div>
                        <div class="span2" style="width: 8%;">
                            <label class="control-label" style="margin: 0 0 10px 0; width: 10px;">&nbsp;</label>
                            <div class="controls span6" style="width: 100%;">
                                <a href="#" id="new-item" class="btn btn-success btn-small" style="background:none; border:none;">
                                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/addItem.svg" alt="Adicionar Novo Item">
                                </a>
                            </div>
                        </div>
                        <div class=" span2" style="width: 23%;">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'Amount', array('class' => 'control-label', 'style' => 'width: 100%')); ?>
                            <?= CHtml::numberField('Inventory[amount]', '1', ['min' => '0', 'step' => '1', 'style' => 'height:33px;width:90%;']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="is-new-item" class="widget widget-scroll margin-bottom-none">
                <div class="widget-head" style="background: none;">
                    <h4 class="heading">
                        <i></i><?= yii::t('lunchModule.stock', 'New Item') ?>
                    </h4>
                </div>
                <div class="widget-body" style="overflow: hidden;">
                    <div class="row-fluid">
                        <div class=" span6" style="margin-right:12px">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Name'), 'Name', array('class' => 'control-label')); ?>
                            <div class="controls span12">
                                <?= CHtml::textField('Item[name]', '', ['class' => ' span10', 'style' => 'height:44px;width:100%;']); ?>
                            </div>
                        </div>
                        <div class=" span2" style="margin-right:10px">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'Amount', array('class' => 'control-label')); ?>
                            <div class="controls span12">
                                <?= CHtml::numberField('Inventory[amount]', '1', ['min' => '0', 'step' => '1', 'class' => 'span10', 'style' => 'height:44px;width:100%;']); ?>
                            </div>
                        </div>
                        <div class=" span2" style="margin-right:10px">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Measure'), 'Measure', array('class' => 'control-label')); ?>
                            <div class="controls span12">
                                <?= CHtml::numberField('Item[measure]', '1', ['min' => '0', 'step' => '1', 'class' => 'span10', 'style' => 'height:44px;width:100%;']); ?>
                            </div>
                        </div>
                        <div class=" span2" style="width: 10%;">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Measure'), 'Measure', array('class' => 'control-label', 'style' => 'width:auto')); ?>
                            <div class="controls span12">
                                <?= CHtml::dropDownList(
                                    'Item[unity_fk]',
                                    '',
                                    CHtml::listData(Unity::model()->findAll(), 'id', 'acronym'),
                                    ['class' => ' span8', 'style' => 'width:100%']
                                ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class=" span12">
                            <?= CHtml::label(Yii::t('lunchModule.labels', 'Description'), 'Description', array('class' => 'control-label')); ?>
                            <div class="controls span12">
                                <?= CHtml::textField('Item[description]', '', ['class' => ' span11', 'style' => 'width:99%']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('lunchModule.stock', 'Close'); ?></button>
                <button type="submit" class="btn btn-primary"><?= Yii::t('lunchModule.stock', 'Add'); ?></button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<div class="modal fade modal-content" id="removeItem">
    <div class="modal-dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"><?= Yii::t('lunchModule.stock', 'Remove Item'); ?></h4>
        </div>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'remove-item',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('lunch/stock/removeItem')
        ));
        ?>
        <div class="modal-body" style="overflow: hidden;">
            <div class="row-fluid">
                <div class=" span6" style="width: 75%;">
                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Item'), 'Item', array('class' => 'control-label')); ?>
                    <div class="controls span12">
                        <?= CHtml::dropDownList(
                            'Item',
                            '',
                            CHtml::listData(Item::model()->findAll(), 'id', 'concatName'),
                            ['class' => 'span10', 'style' => 'width: 100%']
                        ); ?>
                    </div>
                </div>
                <div class=" span6" style="width: 21%; margin-left: 20px;">
                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'Amount', array('class' => 'control-label')); ?>
                    <?= CHtml::numberField('Amount', '1', ['min' => '0', 'step' => '0.1', 'class' => 'span10', 'style' => 'height:44px; width:99%;']); ?>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <?= CHtml::label(Yii::t('lunchModule.labels', 'Motivation'), 'Motivation', array('class' => 'control-label')); ?>
                    <div class="controls span12">
                        <?= CHtml::textField('Motivation', "", ['class' => 'span11', 'style' => 'width:99%']); ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('lunchModule.stock', 'Close'); ?></button>
                <button type="submit" class="btn btn-danger"><?= Yii::t('lunchModule.stock', 'Remove'); ?></button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<style>

</style>
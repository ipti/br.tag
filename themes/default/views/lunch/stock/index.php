<?php
/* @var $this StockController
 */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('lunchModule.stock', 'Stock');
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css');
$cs->registerScriptFile($baseScriptUrl . '/common/js/stock.js', CClientScript::POS_END);
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= Yii::t('lunchModule.stock', 'Stock'); ?>
            &nbsp;<span><?= Yii::t('lunchModule.stock', 'Add or remove items from stock, have control of all transactions.'); ?></span>
        </h3>

        <div class="buttons pull-right">
            <a data-toggle="modal" href="#addItem"
               class="btn btn-primary "><i class="fa fa-plus-circle"></i> <?= Yii::t('lunchModule.stock', 'Add'); ?></a>
            <a data-toggle="modal" href="#removeItem"
               class="btn btn-danger"><i class="fa fa-minus-circle"></i> <?= Yii::t('lunchModule.stock', 'Remove'); ?>
            </a>
        </div>
    </div>
</div>


<div class="innerLR home">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
            </div>
        </div>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="alert alert-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            </div>
        </div>
    <?php endif ?>
    <div class="row-fluid">
        <div class="widget widget-scroll margin-bottom-none">
            <div class="widget-head"><h4 class="heading glyphicons notes">
                    <i></i>Estoque</h4>
            </div>
            <div class="widget-body in" style="height: auto;">
                <div class="row-fluid">
                    <div class="span12">
                        <table
                            class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white dataTable">
                            <thead>
                            <tr role="row">
                                <th class="span1">Código</th>
                                <th class="span3">Nome</th>
                                <th>Descrição</th>
                                <th class="span1">Quantidade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tbody>
                            <?php foreach ($this->school->itemsAmount() as $item): ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['description'] ?></td>
                                    <td class="text-left"><?= $item['amount'] * $item['measure'] . " " . $item['unity'] ?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row-fluid">
        <div class="span12">
            <div class="widget widget-scroll margin-bottom-none">
                <div class="widget-head"><h4 class="heading glyphicons notes">
                        <i></i>Movimentações</h4>
                </div>
                <div class="widget-body in" style="height: auto;">
                    <table class="dynamicTable tableTools table table-striped table-condensed table-white dataTable">
                        <thead>
                        <tr>
                            <th class="span1"></th>
                            <th>Ação</th>
                            <th class="span8">Motivo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->school->transactions() as $transaction) {
                            $isSpent = !is_null($transaction['motivation']);
                            $amount = ($isSpent ? (-1) : (1)) * $transaction['amount'] * $transaction['measure'] . $transaction['acronym'];
                            $name = $transaction['name'];
                            $date = date("d/m/Y, h:i ", strtotime($transaction['date']));
                            $text = $amount . " de <strong>" . $name . "</strong> em " . $date;
                            $motivation = $isSpent ? $transaction['motivation'] : " - ";
                            ?>
                            <tr>
                                <td class="center">
                                    <span class="fa fa-<?= $isSpent ? "minus red" : "plus green" ?>"></span>
                                </td>
                                <td><?= $text; ?></td>
                                <td><span><?= $motivation; ?></span></td>

                            </tr>
                        <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addItem">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
                    <div class="widget-head">
                        <h4 class="heading">
                            <i></i><?= yii::t('lunchModule.stock', 'Add Amount') ?>
                        </h4>
                    </div>
                    <div class="widget-body">

                        <div class="row-fluid">
                            <div class=" span4">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Item'), 'Item', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::dropDownList('Inventory[item]', '',
                                        CHtml::listData(Item::model()->findAll(), 'id', 'concatName'), ['class' => 'pull-left span12']); ?>

                                </div>
                            </div>
                            <div class="span2">
                                <label class="control-label">&nbsp;</label>

                                <div class="controls span6">
                                    <a href="#" id="new-item" class="btn btn-success btn-small">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                </div>
                            </div>
                            <div class=" span6">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'Amount', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::numberField('Inventory[amount]', '1', ['min' => '0', 'step' => '1', 'class' => 'span10']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="is-new-item" class="widget widget-scroll margin-bottom-none">
                    <div class="widget-head">
                        <h4 class="heading">
                            <i></i><?= yii::t('lunchModule.stock', 'New Item') ?>
                        </h4>
                    </div>
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class=" span6">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Name'), 'Name', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::textField('Item[name]', '', ['class' => ' span10']); ?>
                                </div>
                            </div>
                            <div class=" span2">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'Amount', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::numberField('Inventory[amount]', '1', ['min' => '0', 'step' => '1', 'class' => 'span10']); ?>
                                </div>
                            </div>
                            <div class=" span2">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Measure'), 'Measure', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::numberField('Item[measure]', '1', ['min' => '0', 'step' => '1', 'class' => 'span10']); ?>
                                </div>
                            </div>
                            <div class=" span2">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Measure'), 'Measure', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::dropDownList('Item[unity_fk]', '',
                                        CHtml::listData(Unity::model()->findAll(), 'id', 'acronym'), ['class' => ' span8']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class=" span12">
                                <?= CHtml::label(Yii::t('lunchModule.labels', 'Description'), 'Description', array('class' => 'control-label')); ?>
                                <div class="controls span12">
                                    <?= CHtml::textField('Item[description]', '', ['class' => ' span11']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= Yii::t('lunchModule.stock', 'Close'); ?></button>
                <button type="submit" class="btn btn-primary"><?= Yii::t('lunchModule.stock', 'Add'); ?></button>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="removeItem">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><?= Yii::t('lunchModule.stock', 'Remove Item'); ?></h4>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'remove-item',
                'enableAjaxValidation' => false,
                'action' => Yii::app()->createUrl('lunch/stock/removeItem')
            ));
            ?>
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span6">
                        <?= CHtml::label(Yii::t('lunchModule.labels', 'Item'), 'Item', array('class' => 'control-label')); ?>
                        <div class="controls span12">
                            <?= CHtml::dropDownList('Item', '',
                                CHtml::listData(Item::model()->findAll(), 'id', 'concatName'), ['class' => 'span10']); ?>
                        </div>
                    </div>
                    <div class=" span6">
                        <?= CHtml::label(Yii::t('lunchModule.labels', 'Amount'), 'Amount', array('class' => 'control-label')); ?>
                        <div class="controls span12">
                            <?= CHtml::numberField('Amount', '1', ['min' => '0', 'step' => '0.1', 'class' => 'span10']); ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?= CHtml::label(Yii::t('lunchModule.labels', 'Motivation'), 'Motivation', array('class' => 'control-label')); ?>
                        <div class="controls span12">
                            <?= CHtml::textField('Motivation', "", ['class' => 'span11']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= Yii::t('lunchModule.stock', 'Close'); ?></button>
                <button type="submit" class="btn btn-danger"><?= Yii::t('lunchModule.stock', 'Remove'); ?></button>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<?php
/* @var $this StockController
 * @var $school School
 *
 */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('lunchModule.lunch', 'Menu');
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/stock.js', CClientScript::POS_END);
?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= Yii::t('lunchModule.stock', 'Menu'); ?>
            &nbsp;<span><?= Yii::t('lunchModule.lunch', 'Make a nutritious menu to manage the school lunch.'); ?></span>
        </h3>

        <div style="width:250px" class="buttons pull-right">
            <a data-toggle="modal" href="<?= yii::app()->createUrl("/lunch/lunch/create")?>" class="btn btn-primary ">
                <i class="fa fa-plus-circle"></i> <?= Yii::t('lunchModule.lunch', 'New Menu'); ?>
            </a>
			<a data-toggle="modal" href="<?= yii::app()->createUrl("/lunch/stock")?>" class="btn btn-primary ">
                <i class="fa"></i> <?= Yii::t('lunchModule.lunch', 'Stock'); ?>
            </a>
        </div>
    </div>
</div>


<div class="innerLR home">
    <div class="row-fluid">
        <div class="span12">
            <?php if (count($this->school->menus()) > 0): ?>
                <table class="dynamicTable tableTools table table-striped table-condensed table-white dataTable">
                    <thead>
                    <tr>
                        <th><?= yii::t('lunchModule.labels', 'Menu') ?></th>
                        <th class="span4"><?= yii::t('lunchModule.labels', 'Date') ?></th>
                        <th class="center span2"><?= yii::t('lunchModule.labels', 'Actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->school->menus() as $menu): ?>
                        <tr>
                            <td><a href="<?= yii::app()->createUrl('/lunch/lunch/update',['id'=>$menu->id])?>"><?= $menu->name; ?></a></td>
                            <td><?= date('d/m/Y h:i',strtotime($menu->date)); ?></td>
                            <td class="center">
                                <a href="<?= yii::app()->createUrl('/lunch/lunch/update',['id'=>$menu->id])?>"><span class="fa fa-cutlery"></span></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <td class="center">
                    <span class="fa fa-cutlery"></span>
                </td>
            <?php endif ?>
        </div>
    </div>
</div>

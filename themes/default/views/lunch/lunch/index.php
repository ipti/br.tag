<?php
/* @var $this StockController
 * @var $school School
 *
 */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('lunchModule.lunch', 'Menu');
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseScriptUrl . '/common/css/layout.css?v=1.0');
$cs->registerScriptFile($baseScriptUrl . '/common/js/stock.js', CClientScript::POS_END);
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile($baseUrl . '/css/lunch.css');
?>
<div class="main">
    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1 style="padding: 0;margin-top:0.875em;"><?= Yii::t('lunchModule.stock', 'Menu'); ?><br>
            <span><?= Yii::t('lunchModule.lunch', 'Make a nutritious menu to manage the school lunch.'); ?></span>
            </h1>
            <div class="t-buttons-container">
                <a data-toggle="modal" href="<?= yii::app()->createUrl("/lunch/lunch/create")?>" class="t-button-primary">
                    <?= Yii::t('lunchModule.lunch', 'New Menu'); ?>
                </a>
            </div>
        </div>
    </div>


    <div class="home lunch-home">
        <div class="row-fluid">
            <div class="span12">
                <div class="container-box lunch-container">
                    <?php if (count($this->school->menus()) > 0): ?>
                        <table class="js-tag-table" aria-labelledby="Menu Table">
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
                                    <td><?= date('d/m/Y',strtotime($menu->date)); ?></td>
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
    </div>
</div>
<?php
/* @var $this LunchController */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/lunch.css');

$this->pageTitle = 'TAG - ' . Yii::t('lunchModule.index', 'Lunch');
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1><?php echo Yii::t('lunchModule.index','Lunch'); ?></h1>
        </div>
    </div>

    <div class="home">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <div class="container-box">
                        <a href="<?= Yii::app()->createUrl('lunch/lunch/index')?>">
                            <button type="button" class="lunch-box-container" style="margin-left: 0px;">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/lunchIcon/lunch.svg" alt="lunch"/>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title"><?= Yii::t('lunchModule.index','Menu')?></span><br>
                                    <span class="subtitle">Gerenciar Cardápios</span>
                                </div>
                            </button>
                        </a>
                        <a href="<?= Yii::app()->createUrl('lunch/stock/index')?>">
                            <button type="button" class="lunch-box-container">
                                <div class="pull-left" style="margin-right: 20px;">
                                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/lunchIcon/stock.svg" alt="stock"/>
                                    <!-- <div class="t-icon-schedule report-icon"></div> -->
                                </div>
                                <div class="pull-left">
                                    <span class="title"><?= yii::t('lunchModule.index','Stock')?></span><br>
                                    <span class="subtitle">Gerenciar Estoque de Alimentos</span>
                                </div>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
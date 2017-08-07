<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?= yii::t('lunchModule.index','Lunch')?></h3>
    </div>
</div>
<div class="innerLR home">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="span3">
                    <a href="<?= yii::app()->createUrl('lunch/stock/index')?>" class="widget-stats">
                        <span class="glyphicons shopping_bag"><i></i></span>
                        <span class="txt"><?= yii::t('lunchModule.index','Stock')?></span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?= yii::app()->createUrl('lunch/lunch/index')?>" class="widget-stats">
                        <span class="glyphicons notes"><i></i></span>
                        <span class="txt"><?= yii::t('lunchModule.index','Menu')?></span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
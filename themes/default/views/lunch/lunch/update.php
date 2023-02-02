
<?php
$this->setPageTitle('TAG - ' . Yii::t('lunchModule.lunch', 'Update Menu'));
$title = Yii::t('lunchModule.lunch', 'Update Menu');

?>
<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?= Yii::t('lunchModule.lunch', 'Update Menu'); ?>
            &nbsp;<span><?= Yii::t('lunchModule.lunch', 'Fill the form and add the portions to the meal.'); ?></span>
        </h3>

        <div class="buttons pull-right span4">
            <a onclick="window.print()" class="btn btn-default ">
                <i class="fa fa-print"></i> <?= Yii::t('lunchModule.lunch', 'Print'); ?>
            </a>
        </div>
    </div>
</div>


<div class="innerLR home">
    <?= $this->renderPartial('_form', [
        'menuModel' => $menu,
        'title' => $title,
        'isUpdate' => true
    ]);
    ?>
</div>
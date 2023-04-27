
<?php
$this->setPageTitle('TAG - ' . Yii::t('lunchModule.lunch', 'Update Menu'));
$title = Yii::t('lunchModule.lunch', 'Update Menu');

?>

<div class="main">
    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1 style="padding: 0;margin-top:0.875em;"><?=  Yii::t('lunchModule.lunch', 'Update Menu'); ?><br>
            <span><?= Yii::t('lunchModule.lunch', 'Fill the form and add the portions to the meal.'); ?></span></h1>

            <div class="buttons pull-right span4" style="margin-right: 15px;">
                <a onclick="window.print()" class="btn btn-default ">
                    <i class="fa fa-print"></i> <?= Yii::t('lunchModule.lunch', 'Print'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="home">
        <?= $this->renderPartial('_form', array(
            'menuModel' => $menu,
            'title' => $title,
            'isUpdate'=>true
        ));
        ?>
    </div>

</div>
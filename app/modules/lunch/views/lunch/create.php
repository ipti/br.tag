<?php
$this->setPageTitle('TAG - ' . Yii::t('lunchModule.lunch', 'New Menu'));
$title = Yii::t('lunchModule.lunch', 'New Menu');
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic"><?= Yii::t('lunchModule.lunch', 'New Menu'); ?>
            &nbsp;<span><?= Yii::t('lunchModule.lunch', 'Fill the form and add the portions to the meal.'); ?></span>
        </h3>
        <div class="buttons pull-right span4">
            <a href="#newMenu" class="btn btn-primary ">
                <i class="fa fa-save"></i> <?= Yii::t('lunchModule.lunch', 'Save'); ?>
            </a>
            <a href="<?= yii::app()->createUrl("/lunch/lunch")?>" class="btn btn-danger ">
                <i class="fa fa-times"></i> <?= Yii::t('lunchModule.lunch', 'Cancel'); ?>
            </a>
        </div>
    </div>
</div>


<div class="innerLR home">
    <?= $this->renderPartial('_form', array(
        'menuModel' => $menu,
        'title' => $title,
        'isUpdate'=>false
    ));
    ?>        
</div>
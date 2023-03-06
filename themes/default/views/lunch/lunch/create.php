<?php
$this->setPageTitle('TAG - ' . Yii::t('lunchModule.lunch', 'New Menu'));
$title = Yii::t('lunchModule.lunch', 'New Menu');
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h1><?= Yii::t('lunchModule.lunch', 'New Menu'); ?>
            &nbsp;<span><?= Yii::t('lunchModule.lunch', 'Fill the form and add the portions to the meal.'); ?></span>
        </h1>
        <div class="buttons pull-right span4">
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
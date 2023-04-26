<?php
$this->setPageTitle('TAG - ' . Yii::t('lunchModule.lunch', 'New Menu'));
$title = Yii::t('lunchModule.lunch', 'New Menu');
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12" style="margin-left: 20px;">
            <h1 style="padding: 0;margin-top:0.875em;"><?= Yii::t('lunchModule.lunch', 'New Menu'); ?><br>
            <span><?= Yii::t('lunchModule.lunch', 'Fill the form and add the portions to the meal.'); ?></span></h1>
        </div>
    </div>

    <div class="home">
        <?= $this->renderPartial('_form', array(
            'menuModel' => $menu,
            'title' => $title,
            'isUpdate'=>false
        ));
        ?>        
    </div>
    
</div>
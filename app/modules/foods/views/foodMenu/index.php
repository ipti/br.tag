<?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Food Menus'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on FoodMenu.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new FoodMenu'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new FoodMenu')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();

?>

<div class="row-fluid">
    <div class="span12">
        <h1><?php echo Yii::t('default', 'Food Menus') ?></h1>
        <div class="t-buttons-container">
            <a class="t-button-primary"
                href="<?= Yii::app()->createUrl('foods/foodMenu/create') ?>"><?= Yii::t('default', 'Add') ?></a>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('error')) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
        <?php
        if (isset($buttons))
            echo $buttons;
        ?>
        <br/>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <?php
        if (isset($buttons))
            echo $buttons;
        ?>
    <?php endif ?>
</div>

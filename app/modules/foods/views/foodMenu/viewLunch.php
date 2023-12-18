<?php

// $baseUrl = Yii::app()->baseUrl;
// $cs = Yii::app()->getClientScript();
// $cs->registerScriptFile($baseUrl . "/");

$this->setPageTitle('TAG - ' . Yii::t('default', 'Merenda'));

$this->menu = array(
    array('label' => 'List FoodMenu', 'url' => array('index')),
    array('label' => 'Inventory', 'url' => array('inventory')),
);

?>

<div id="viewMealsPage" class="main container-instructor">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Lunch') ?></h1>
        </div>
    </div>
</div>

<div id="mainPage" class="main container-instructor">
    
</div>

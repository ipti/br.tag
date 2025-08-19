<?php
/* @var $this ProvisionAcountsController */
/* @var $model ProvisionAcounts */

$this->breadcrumbs=array(
	'Professionals'=>array('index'),
	'Create',
);

?>

<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Add New Management Unit'));
    $title = Yii::t('default', 'Add New Management Unit');
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        'model' => $model,
        'title' => $title
    ));
    ?> 
</div>
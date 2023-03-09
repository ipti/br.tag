<?php
/* @var $this ProfessionalController */
/* @var $model Professional */

$this->breadcrumbs=array(
	'Professionals'=>array('index'),
	'Create',
);

?>

<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Add New Professional'));
    $title = Yii::t('default', 'Add New Professional');
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        'modelProfessional' => $modelProfessional,
        'modelAttendance' => $modelAttendance,
        'title' => $title
    ));
    ?> 
</div>
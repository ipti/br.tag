<?php
/* @var $this EdcensoDisciplineController */
/* @var $model EdcensoDiscipline */

$this->breadcrumbs=array(
    'Edcenso Disciplines'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List EdcensoDiscipline', 'url'=>array('index')),
    array('label'=>'Create EdcensoDiscipline', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#edcenso-discipline-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Edcenso Disciplines</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'edcenso-discipline-grid',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'id',
        'name',
        array(
            'class'=>'CButtonColumn',
        ),
    ),
)); ?>
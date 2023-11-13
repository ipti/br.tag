<div id="mainPage" class="main">
    <?php
    /* @var $this InstanceConfigController */
    /* @var $model InstanceConfig */

    $this->breadcrumbs = array(
        'Instance Configs' => array('index'),
        'Manage',
    );

    $this->menu = array(
        array('label' => 'List InstanceConfig', 'url' => array('index')),
        array('label' => 'Create InstanceConfig', 'url' => array('create')),
    );

    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#instance-config-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
    ?>

    <h1>Manage Instance Configs</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'instance-config-grid',
    'enablePagination' => false,
    'enableSorting' => false,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
    'dataProvider' => $model->search(),
    'columns' => array(
        'id',
        'launch_grades',
        'sedsp_sync',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
)
); ?>


</div>

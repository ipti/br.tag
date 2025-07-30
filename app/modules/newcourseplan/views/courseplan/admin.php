<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */

$this->breadcrumbs = array(
    'Course Plans' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List CoursePlan', 'url' => array('index')),
    array('label' => 'Create CoursePlan', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#course-plan-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<div id="mainPage" class="main">

    <h1>Manage Course Plans</h1>

    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'course-plan-grid',
        'dataProvider' => $model->search(),
        'enablePagination' => false,
        'enableSorting' => false,
        'ajaxUpdate' => false,
        'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
        'columns' => array(
            'id',
            array(
                'name' => 'name',
                'header' => 'Título',
                'value' => '$data->name',
            ),
            array(
                'name' => 'modalityFk',
                'header' => 'Modalidade',
                'value' => '$data->modalityFk->name',
            ),
            'disciplineFk.name',
            array(
                'name' => 'situation',
                'header' => 'Situação',
            ),
            'start_date',
            array(
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'buttons' => array(
                    'update' => array(
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                    ),
                    'delete' => array(
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                        'options' => array('class' => 'delete-button'),
                    )
                ),
                'updateButtonOptions' => array('style' => 'margin-right: 20px;', 'class' => ""),
                'deleteButtonOptions' => array('style' => 'cursor: pointer;', 'class' => ""),
                'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
            ),
        ),
    )); ?>
</div>

<?php
/* @var $this CourseclassController */
/* @var $model CourseClass */

$this->breadcrumbs = array(
    'Course Classes' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List CourseClass', 'url' => array('index')),
    array('label' => 'Create CourseClass', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#course-class-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div id="mainPage" class="main">
<h1>Manage Course Classes</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>,
    <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php $this->renderPartial('_search', array(
        'model' => $model,
    )); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'course-class-grid',
    'dataProvider' => $model->search(),
    'enablePagination' => false,
    'enableSorting' => false,
    'ajaxUpdate' => false,
    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',

    'columns' => array(
        'order',
        'content',
        'methodology',
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

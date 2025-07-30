<?php
/* @var $this CoursePlanController */
/* @var $model CoursePlan */

$this->breadcrumbs = array(
    'Course Plans' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => 'List CoursePlan', 'url' => array('index')),
    array('label' => 'Create CoursePlan', 'url' => array('create')),
    array('label' => 'View CoursePlan', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Manage CoursePlan', 'url' => array('admin')),
);
?>

<div id="mainPage" class="main">

    <h1>Update CoursePlan <?php echo $model->id; ?></h1>

    <?php $this->renderPartial('_form', array('model' => $model, 'stages' => $stages,)); ?>


    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'course-class-grid',
        'dataProvider' => new CArrayDataProvider($model->courseClasses, array(
            'keyField' => 'id', // ou a chave primária correta da tabela course_class
        )),
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
                        'url' => 'Yii::app()->createUrl("newcourseplan/courseclass/update", array("id"=>$data["id"]))',
                    ),
                    'delete' => array(
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                        'url' => 'Yii::app()->createUrl("newcourseplan/courseclass/delete", array("id"=>$data["id"]))',
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

<?php
/* @var $this GradeConceptController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Grade Concepts',
);

$this->menu=array(
	array('label'=>'Create GradeConcept', 'url'=>array('create')),
	array('label'=>'Manage GradeConcept', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
    <div class="row">
        <h1>Gerenciar conceitos</h1>
    </div>

    <div class="row">
        <div class="t-buttons-container">
            <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('gradeconcept/default/create')?>">Adicionar conceito</a>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br/>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
        <br/>
    <?php endif ?>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns'=>array(
                        'id',
                        'name',
                        'acronym',
                        'value',
                        array(
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{update}',
                            'buttons' => array(
                                'update' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                )
                            ),
                            'updateButtonOptions' => array('style' => 'margin-right: 20px;', 'class'=>""),
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                        ),
                    ),
                )); ?>
            </div>
        </div>
    </div>
</div>

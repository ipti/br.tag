<?php
/* @var $this CourseClassAbilitiesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Course Class Abilities',
);

$this->menu=array(
	array('label'=>'Create CourseClassAbilities', 'url'=>array('create')),
	array('label'=>'Manage CourseClassAbilities', 'url'=>array('admin')),
);
?>

<div class="main">

    <div class="row-fluid">
        <div class="span12">
            <h1>Habilidades</h1>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl("abilities/courseclassabilities/create") ?>" class="t-button-primary  "> Adicionar Habilidade</a>
            </div>
        </div>
    </div>
    <div class="tag-inner">
    <div class="widget clearmargin">
            <div class="widget-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary table  table-striped table-hover table-primary table-vertical-center',
                    'columns' => array(
                        array(
                            'name' => 'Código',
                            'type' => 'raw',
                            'value' => '$data->code',
                        ),
                        array(
                            'name' => 'Descrição',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->description,Yii::app()->createUrl("abilities/courseclassabilities/update",array("id"=>$data->id)))',
                        ),
                        array(
                            'name' => 'Disciplina',
                            'type' => 'raw',
                            'value' => '$data->edcensoDisciplineFk->name',
                        ),
                        array(
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{delete}',
                            'buttons' => array(
                                'update' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                    'url' => 'Yii::app()->createUrl("abilities/courseclassabilities/update",array("id"=>$data->id))',
                                ),
                                'delete' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    'url' => 'Yii::app()->createUrl("abilities/courseclassabilities/delete",array("id"=>$data->id))'
                                )
                            ),
                            'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
                            'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
                            'htmlOptions' => array('width' => '80px', 'style' => 'text-align: center'),
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>

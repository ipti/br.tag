<div id="mainPage" class="main">
<?php
$this->setPageTitle('TAG - ' . Yii::t('default','Classrooms'));
$this->breadcrumbs=array(
	Yii::t('default', 'Classrooms'),
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new Classroom'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new Classroom')),
); 

?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Classrooms')?></h3>  
        <div class="buttons">
                <a href="?r=classroom/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar turma</a>
        </div>
    </div>
</div>
    
<div class="innerLR">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget">  
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $filter->search(),
                        'enablePagination' => true,
                        'filter'=>$filter,
                        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            'inep_id',
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,"?r=classroom/update&id=".$data->id)',
                                'htmlOptions' => array('width'=> '70%')
                            ),
                            array('class' => 'CButtonColumn','template'=>'{delete}'),),
                    ));
                    ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>

</div>

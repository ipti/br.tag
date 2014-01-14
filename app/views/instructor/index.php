<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	Yii::t('default', 'Instructor Identifications'),
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorIdentification')),
); 

?>
    
<div class="heading-buttons">
	<h3><?php echo Yii::t('default', 'Instructor Identifications')?></h3>
	<div class="buttons pull-right">
		<a href="?r=instructor/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar professor</a>
	</div>
	<div class="clearfix"></div>
</div>
    
<div class="innerLR">
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
                        'filter' => $filter,
                        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,"?r=instructor/update&id=".$data->id)'
                            ),
                     array('class' => 'CButtonColumn','template'=>' {delete}',),),
                    )); ?>
                </div>   
            </div>
        </div>

</div>

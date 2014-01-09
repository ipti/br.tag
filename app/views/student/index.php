<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	Yii::t('default', 'Student Identifications'),
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new StudentIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new StudentIdentification')),
); 

?>
    
<div class="heading-buttons">
	<h3><?php echo Yii::t('default', 'Student Identifications')?></h3>
	<div class="buttons pull-right">
		<a href="?r=student/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar aluno</a>
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
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'itemsCssClass' => 'table table-bordered table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
//                            array(
//                                'class' => 'CLinkColumn',
//                                'header'=>'Escola',
//                                'labelExpression'=>'SchoolIdentification::model()->findByPk($data->school_inep_id_fk)->name',
//                                'urlExpression'=>'"?r=school/update&id=".$data->school_inep_id_fk',
//                                ),
                            'name',
                     array('class' => 'CButtonColumn','template'=>'{update} {delete}',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>

</div>

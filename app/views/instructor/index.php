<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	'Instructor Identifications',
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
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'itemsCssClass' => 'table table-bordered table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
//                    		'register_type',
//		'school_inep_id_fk',
		'inep_id',
		'id',
		'name',
//		'email',
//		'nis',
//		'birthday_date',
//		'sex',
//		'color_race',
//		'mother_name',
//		'nationality',
//		'edcenso_nation_fk',
//		'edcenso_uf_fk',
//		'edcenso_city_fk',
//		'deficiency',
//		'deficiency_type_blindness',
//		'deficiency_type_low_vision',
//		'deficiency_type_deafness',
//		'deficiency_type_disability_hearing',
//		'deficiency_type_deafblindness',
//		'deficiency_type_phisical_disability',
//		'deficiency_type_intelectual_disability',
//		'deficiency_type_multiple_disabilities',
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>

</div>

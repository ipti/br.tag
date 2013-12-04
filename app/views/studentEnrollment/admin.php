<?php
$this->breadcrumbs=array(
	'Student Enrollments'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List StudentEnrollment', 'url'=>array('index')),
	array('label'=>'Create StudentEnrollment', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('student-enrollment-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Student Enrollments</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'student-enrollment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'student_inep_id',
		'student_fk',
		'classroom_inep_id',
		'classroom_fk',
		/*
		'enrollment_id',
		'unified_class',
		'edcenso_stage_vs_modality_fk',
		'another_scholarization_place',
		'public_transport',
		'transport_responsable_government',
		'vehicle_type_van',
		'vehicle_type_microbus',
		'vehicle_type_bus',
		'vehicle_type_bike',
		'vehicle_type_animal_vehicle',
		'vehicle_type_other_vehicle',
		'vehicle_type_waterway_boat_5',
		'vehicle_type_waterway_boat_5_15',
		'vehicle_type_waterway_boat_15_35',
		'vehicle_type_waterway_boat_35',
		'vehicle_type_metro_or_train',
		'student_entry_form',
		'id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

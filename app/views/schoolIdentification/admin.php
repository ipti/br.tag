<?php
$this->breadcrumbs=array(
	'School Identifications'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SchoolIdentification', 'url'=>array('index')),
	array('label'=>'Create SchoolIdentification', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('school-identification-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage School Identifications</h1>

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
	'id'=>'school-identification-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'inep_id',
		'situation',
		'initial_date',
		'final_date',
		'name',
		/*
		'latitude',
		'longitude',
		'cep',
		'address',
		'address_number',
		'address_complement',
		'address_neighborhood',
		'edcenso_uf_fk',
		'edcenso_city_fk',
		'edcenso_district_fk',
		'ddd',
		'phone_number',
		'public_phone_number',
		'other_phone_number',
		'fax_number',
		'email',
		'edcenso_regional_education_organ_fk',
		'administrative_dependence',
		'location',
		'private_school_category',
		'public_contract',
		'private_school_maintainer_fk',
		'private_school_maintainer_cnpj',
		'private_school_cnpj',
		'regulation',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

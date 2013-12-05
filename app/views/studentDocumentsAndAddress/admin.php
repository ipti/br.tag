<?php
$this->breadcrumbs=array(
	'Student Documents And Addresses'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List StudentDocumentsAndAddress', 'url'=>array('index')),
	array('label'=>'Create StudentDocumentsAndAddress', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('student-documents-and-address-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Student Documents And Addresses</h1>

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
	'id'=>'student-documents-and-address-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'register_type',
		'school_inep_id_fk',
		'student_fk',
		'id',
		'rg_number',
		'rg_number_complement',
		/*
		'rg_number_edcenso_organ_id_emitter_fk',
		'rg_number_edcenso_uf_fk',
		'rg_number_expediction_date',
		'civil_certification',
		'civil_certification_type',
		'civil_certification_term_number',
		'civil_certification_sheet',
		'civil_certification_book',
		'civil_certification_date',
		'notary_office_uf_fk',
		'notary_office_city_fk',
		'edcenso_notary_office_fk',
		'civil_register_enrollment_number',
		'cpf',
		'foreign_document_or_passport',
		'nis',
		'document_failure_lack',
		'residence_zone',
		'cep',
		'address',
		'number',
		'complement',
		'neighborhood',
		'edcenso_uf_fk',
		'edcenso_city_fk',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

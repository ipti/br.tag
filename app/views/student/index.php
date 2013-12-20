<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	'Student Identifications',
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
//                    		'register_type',
//		'school_inep_id_fk',
		'inep_id',
//		'id',
		'name',
//		'nis',
//		'birthday',
//		'sex',
//		'color_race',
//		'filiation',
//		'mother_name',
//		'father_name',
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
//		'deficiency_type_autism',
//		'deficiency_type_aspenger_syndrome',
//		'deficiency_type_rett_syndrome',
//		'deficiency_type_childhood_disintegrative_disorder',
//		'deficiency_type_gifted',
//		'resource_aid_lector',
//		'resource_aid_transcription',
//		'resource_interpreter_guide',
//		'resource_interpreter_libras',
//		'resource_lip_reading',
//		'resource_zoomed_test_16',
//		'resource_zoomed_test_20',
//		'resource_zoomed_test_24',
//		'resource_braille_test',
//		'resource_none',
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>

</div>

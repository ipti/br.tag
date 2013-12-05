<?php
$this->breadcrumbs=array(
	'Student Identifications'=>array('index'),
	$model->name,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new StudentIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new StudentIdentification')),
array('label'=> Yii::t('default', 'List StudentIdentification'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Student Identifications, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View StudentIdentification # '.$model->id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                    		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'name',
		'nis',
		'birthday',
		'sex',
		'color_race',
		'filiation',
		'mother_name',
		'father_name',
		'nationality',
		'edcenso_nation_fk',
		'edcenso_uf_fk',
		'edcenso_city_fk',
		'deficiency',
		'deficiency_type_blindness',
		'deficiency_type_low_vision',
		'deficiency_type_deafness',
		'deficiency_type_disability_hearing',
		'deficiency_type_deafblindness',
		'deficiency_type_phisical_disability',
		'deficiency_type_intelectual_disability',
		'deficiency_type_multiple_disabilities',
		'deficiency_type_autism',
		'deficiency_type_aspenger_syndrome',
		'deficiency_type_rett_syndrome',
		'deficiency_type_childhood_disintegrative_disorder',
		'deficiency_type_gifted',
		'resource_aid_lector',
		'resource_aid_transcription',
		'resource_interpreter_guide',
		'resource_interpreter_libras',
		'resource_lip_reading',
		'resource_zoomed_test_16',
		'resource_zoomed_test_20',
		'resource_zoomed_test_24',
		'resource_braille_test',
		'resource_none',
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
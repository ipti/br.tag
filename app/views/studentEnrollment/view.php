<?php
$this->breadcrumbs=array(
	'Student Enrollments'=>array('index'),
	$model->id,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on StudentEnrollment.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new StudentEnrollment'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new StudentEnrollment')),
array('label'=> Yii::t('default', 'List StudentEnrollment'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Student Enrollments, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View StudentEnrollment # '.$model->id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                    		'register_type',
		'school_inep_id_fk',
		'student_inep_id',
		'student_fk',
		'classroom_inep_id',
		'classroom_fk',
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
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
<?php
$this->breadcrumbs=array(
	'Instructor Identifications'=>array('index'),
	$model->name,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorIdentification')),
array('label'=> Yii::t('default', 'List InstructorIdentification'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Instructor Identifications, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View InstructorIdentification # '.$model->id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                    		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'name',
		'email',
		'nis',
		'birthday_date',
		'sex',
		'color_race',
		'mother_name',
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
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
<!--        <div class="columntwo">
            <?php // echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>-->
</div>
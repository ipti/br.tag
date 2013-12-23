<?php
$this->breadcrumbs=array(
	'Instructor Teaching Datas'=>array('index'),
	$model->id,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorTeachingData.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorTeachingData'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorTeachingData')),
array('label'=> Yii::t('default', 'List InstructorTeachingData'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Instructor Teaching Datas, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View InstructorTeachingData # '.$model->id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                    		'register_type',
		'school_inep_id_fk',
		'instructor_inep_id',
		'id',
		'classroom_inep_id',
		'classroom_id_fk',
		'role',
		'contract_type',
		'discipline_1_fk',
		'discipline_2_fk',
		'discipline_3_fk',
		'discipline_4_fk',
		'discipline_5_fk',
		'discipline_6_fk',
		'discipline_7_fk',
		'discipline_8_fk',
		'discipline_9_fk',
		'discipline_10_fk',
		'discipline_11_fk',
		'discipline_12_fk',
		'discipline_13_fk',
		'instructor_fk',
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
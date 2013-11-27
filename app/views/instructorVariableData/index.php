<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	'Instructor Variable Datas',
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorVariableData.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorVariableData'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorVariableData')),
); 

?>
<div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'Instructor Variable Datas')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'baseScriptUrl' => Yii::app()->theme->baseUrl . '/plugins/gridview/',
                        'columns' => array(
                    		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'scholarity',
		'high_education_situation_1',
		'high_education_formation_1',
		'high_education_course_code_1_fk',
		'high_education_initial_year_1',
		'high_education_final_year_1',
		'high_education_institution_type_1',
		'high_education_institution_code_1_fk',
		'high_education_situation_2',
		'high_education_formation_2',
		'high_education_course_code_2_fk',
		'high_education_initial_year_2',
		'high_education_final_year_2',
		'high_education_institution_type_2',
		'high_education_institution_code_2_fk',
		'high_education_situation_3',
		'high_education_formation_3',
		'high_education_course_code_3_fk',
		'high_education_initial_year_3',
		'high_education_final_year_3',
		'high_education_institution_type_3',
		'high_education_institution_code_3_fk',
		'post_graduation_specialization',
		'post_graduation_master',
		'post_graduation_doctorate',
		'post_graduation_none',
		'other_courses_nursery',
		'other_courses_pre_school',
		'other_courses_basic_education_initial_years',
		'other_courses_basic_education_final_years',
		'other_courses_high_school',
		'other_courses_education_of_youth_and_adults',
		'other_courses_special_education',
		'other_courses_native_education',
		'other_courses_field_education',
		'other_courses_environment_education',
		'other_courses_human_rights_education',
		'other_courses_sexual_education',
		'other_courses_child_and_teenage_rights',
		'other_courses_ethnic_education',
		'other_courses_other',
		'other_courses_none',
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
           <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>

</div>

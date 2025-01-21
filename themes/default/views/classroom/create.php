<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new Classroom'));
    $title = Yii::t('default', 'Create a new Classroom');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List Classroom'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')),
    );
    ?>
    <?php
    echo $this->renderPartial('_form', array(
        "stages" => $stages,
        "gradeRules" => $gradeRules,
        'gradeRulesStages' => $gradeRulesStages,
        'modelClassroom' => $modelClassroom,
        'complementaryActivities' => $complementary_activities,
        'modelTeachingData' => $modelTeachingData,
        'edcensoStageVsModalities' => $edcensoStageVsModalities,
        'modelEnrollments' => $modelEnrollments,
        'disabledFields' => false,
        'title' => $title));
    ?>
</div>

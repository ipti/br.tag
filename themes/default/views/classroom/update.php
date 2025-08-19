<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Classroom'));

    $title = Yii::t('default', 'Update Classroom') . ': ' . $modelClassroom->name;
    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new Classroom'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
        array('label' => Yii::t('default', 'List Classroom'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')),
    );
    ?>

    <div class="twoColumn">
        <div class="columnone">
            <?php
            echo $this->renderPartial('_form', array(
                "stages" => $stages,
                "gradeRules" => $gradeRules,
                'gradeRulesStages' => $gradeRulesStages,
                'modelClassroom' => $modelClassroom,
                'modelTeachingData' => $modelTeachingData,
                'edcensoStageVsModalities' => $edcensoStageVsModalities,
                'modelEnrollments' => $modelEnrollments,
                'disabledFields' => $disabledFields,
                'title' => $title,
                'complementaryActivities' => $complementaryActivities));
            ?>
        </div>
        <div class="columntwo"></div>
    </div>
</div>

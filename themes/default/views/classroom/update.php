<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Classroom'));

    $title = Yii::t('default', 'Update Classroom') . ': ' . $modelClassroom->name;
    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = [
        ['label' => Yii::t('default', 'Create a new Classroom'), 'url' => ['create'], 'description' => Yii::t('default', 'This action create a new Classroom')],
        ['label' => Yii::t('default', 'List Classroom'), 'url' => ['index'], 'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')],
    ];
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php
            echo $this->renderPartial('_form', ['modelClassroom' => $modelClassroom,
                'modelTeachingData' => $modelTeachingData,
                'title' => $title,
                'complementaryActivities' => $complementaryActivities]);
            ?>        </div>
        <div class="columntwo">
        </div>
    </div>
</div>
<div id="mainPage" class="main">
    <?php
    $this->breadcrumbs = array(
        Yii::t('default', 'Classrooms') => array('index'),
        $modelClassroom->name => array('view', 'id' => $modelClassroom->id),
        Yii::t('default', 'Update'),
    );

    $title = Yii::t('default', 'Update Classroom');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new Classroom'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
        array('label' => Yii::t('default', 'List Classroom'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')),
    );
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo $this->renderPartial('_form', array('modelClassroom' => $modelClassroom, 'modelTeachingData'=> $modelTeachingData, 'title' => $title, 'complementary_activities' => $complementary_activities)); ?>        </div>
        <div class="columntwo">
        </div>
    </div>
</div>
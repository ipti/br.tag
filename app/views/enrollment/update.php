<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Update StudentEnrollment'));
    $this->breadcrumbs = array(
        Yii::t('default', 'Student Enrollments')=> array('index'),
        $model->studentFk->name.' - '.$model->classroomFk->name,
    );

    $title = Yii::t('default', 'Update StudentEnrollment');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentEnrollment.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentEnrollment'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentEnrollment')),
        array('label' => Yii::t('default', 'List StudentEnrollment'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all Student Enrollments, you can search, delete and update')),
    );
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo $this->renderPartial('_form', array('model' => $model, 'title' => $title)); ?>        </div>
        <div class="columntwo">
        </div>
    </div>
</div>
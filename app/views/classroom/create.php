<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Create a new Classroom'));
    $this->breadcrumbs=array(
            Yii::t('default', 'Classrooms')=>array('index'),
            Yii::t('default', 'Create'),
    );
    $title=Yii::t('default', 'Create a new Classroom');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu=array(
        array('label'=> Yii::t('default', 'List Classroom'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Classrooms, you can search, delete and update')),
    );
    ?>
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo $this->renderPartial('_form', array('modelClassroom'=>$modelClassroom, 
                'complementary_activities'=>$complementary_activities,
                'modelTeachingData' => $modelTeachingData,
                'error' => $error,
                'instructor_id'=> $instructor_id,
                'title'=>$title)); ?>        </div>
        <div class="columntwo">
            <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>

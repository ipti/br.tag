<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Create a new StudentEnrollment'));
    $this->breadcrumbs=array(
            Yii::t('default', 'Student Enrollments')=>array('index'),
            Yii::t('default', 'Create'),
    );
    $title=Yii::t('default', 'Create a new StudentEnrollment');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentEnrollment.');
    $this->menu=array(
        array('label'=> Yii::t('default', 'List StudentEnrollment'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Student Enrollments, you can search, delete and update')),
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
            <?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>$title)); ?>        </div>
        <div class="columntwo">
            <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
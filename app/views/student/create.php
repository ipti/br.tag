<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Add New Student'));
    $this->breadcrumbs=array(
            Yii::t('default', 'Student Identifications')=>array('index'),
            Yii::t('default', 'Create'),
    );
    $title=Yii::t('default', 'Add New Student');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu=array(
        array('label'=> Yii::t('default', 'List StudentIdentification'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Student Identifications, you can search, delete and update')),
    );
    ?>
    <?php echo $this->renderPartial('_form', array('modelStudentIdentification'=>$modelStudentIdentification,'modelStudentDocumentsAndAddress'=>$modelStudentDocumentsAndAddress,'title'=>$title)); ?>        </div>

    <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>
</div>
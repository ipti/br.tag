<div id="mainPage" class="main">
    <?php
$this->breadcrumbs=array(
	'School Structures'=>array('index'),
	$model->school_inep_id_fk=>array('view','id'=>$model->school_inep_id_fk),
	'Update',
);

    $title=Yii::t('default', 'Update SchoolStructure: ');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolStructure.');
    $this->menu=array(
    array('label'=> Yii::t('default', 'Create a new SchoolStructure'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new SchoolStructure')),
    array('label'=> Yii::t('default', 'List SchoolStructure'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all School Structures, you can search, delete and update')),
    );  
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>$title)); ?>        </div>
        <div class="columntwo">
            <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
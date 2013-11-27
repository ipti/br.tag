<div id="mainPage" class="main">
    <?php
$this->breadcrumbs=array(
	'School Identifications'=>array('index'),
	$model->name=>array('view','id'=>$model->inep_id),
	'Update',
);

    $title=Yii::t('default', 'Update SchoolIdentification: ');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu=array(
    array('label'=> Yii::t('default', 'Create a new SchoolIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new SchoolIdentification')),
    array('label'=> Yii::t('default', 'List SchoolIdentification'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all School Identifications, you can search, delete and update')),
    );  
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo $this->renderPartial('_form', array('model'=>$model,'title'=>$title)); ?>        </div>
        <div class="columntwo">
            <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
<div id="mainPage" class="main">
    <?php
$this->breadcrumbs=array(
	Yii::t('default', 'School Identifications')=>array('index'),
	Yii::t('default', 'Create'),
);
    $title=Yii::t('default', 'Create a new SchoolIdentification');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu=array(
        array('label'=> Yii::t('default', 'List SchoolIdentification'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all School Identifications, you can search, delete and update')),
    );
    ?>
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
           
            <?php echo $this->renderPartial('_form', array('modelSchoolIdentification'=>$modelSchoolIdentification,'modelSchoolStructure'=>$modelSchoolStructure,'title'=>$title,)); ?>        </div>
        <div class="columntwo">
            <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	'Instructor Teaching Datas',
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorTeachingData.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorTeachingData'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorTeachingData')),
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
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'Instructor Teaching Datas')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'baseScriptUrl' => Yii::app()->theme->baseUrl . '/plugins/gridview/',
                        'columns' => array(
                    		'register_type',
		'school_inep_id_fk',
		'inep_id',
		'id',
		'classroom_inep_id',
		'classroom_id_fk',
		'role',
		'contract_type',
		'discipline_1_fk',
		'discipline_2_fk',
		'discipline_3_fk',
		'discipline_4_fk',
		'discipline_5_fk',
		'discipline_6_fk',
		'discipline_7_fk',
		'discipline_8_fk',
		'discipline_9_fk',
		'discipline_10_fk',
		'discipline_11_fk',
		'discipline_12_fk',
		'discipline_13_fk',
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
           <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>

</div>

<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	'Instructor Documents And Addresses',
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorDocumentsAndAddress.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorDocumentsAndAddress'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorDocumentsAndAddress')),
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
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'Instructor Documents And Addresses')?></div></div>
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
		'cpf',
		'area_of_residence',
		'cep',
		'address',
		'address_number',
		'complement',
		'neighborhood',
		'edcenso_uf_fk',
		'edcenso_city_fk',
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
           <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>

</div>

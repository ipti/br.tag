<?php
$this->breadcrumbs=array(
	'Instructor Documents And Addresses'=>array('index'),
	$model->id,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorDocumentsAndAddress.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new InstructorDocumentsAndAddress'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new InstructorDocumentsAndAddress')),
array('label'=> Yii::t('default', 'List InstructorDocumentsAndAddress'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Instructor Documents And Addresses, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View InstructorDocumentsAndAddress # '.$model->id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
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
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <?php echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>
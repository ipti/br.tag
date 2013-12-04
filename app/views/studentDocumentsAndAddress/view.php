<?php
$this->breadcrumbs=array(
	'Student Documents And Addresses'=>array('index'),
	$model->id,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on StudentDocumentsAndAddress.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new StudentDocumentsAndAddress'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new StudentDocumentsAndAddress')),
array('label'=> Yii::t('default', 'List StudentDocumentsAndAddress'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all Student Documents And Addresses, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View StudentDocumentsAndAddress # '.$model->id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                    		'register_type',
		'school_inep_id_fk',
		'student_fk',
		'id',
		'rg_number',
		'rg_number_complement',
		'rg_number_edcenso_organ_id_emitter_fk',
		'rg_number_edcenso_uf_fk',
		'rg_number_expediction_date',
		'civil_certification',
		'civil_certification_type',
		'civil_certification_term_number',
		'civil_certification_sheet',
		'civil_certification_book',
		'civil_certification_date',
		'notary_office_uf_fk',
		'notary_office_city_fk',
		'edcenso_notary_office_fk',
		'civil_register_enrollment_number',
		'cpf',
		'foreign_document_or_passport',
		'nis',
		'document_failure_lack',
		'residence_zone',
		'cep',
		'address',
		'number',
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
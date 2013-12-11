<?php
$this->breadcrumbs=array(
	'School Identifications'=>array('index'),
	$modelSchoolIdentification->name,
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new SchoolIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new SchoolIdentification')),
array('label'=> Yii::t('default', 'List SchoolIdentification'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all School Identifications, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'View SchoolIdentification # '.$modelSchoolIdentification->inep_id.' :')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$modelSchoolIdentification,
                    'attributes'=>array(
                    		'register_type',
		'inep_id',
		'situation',
		'initial_date',
		'final_date',
		'name',
		'latitude',
		'longitude',
		'cep',
		'address',
		'address_number',
		'address_complement',
		'address_neighborhood',
		'edcenso_uf_fk',
		'edcenso_city_fk',
		'edcenso_district_fk',
		'ddd',
		'phone_number',
		'public_phone_number',
		'other_phone_number',
		'fax_number',
		'email',
		'edcenso_regional_education_organ_fk',
		'administrative_dependence',
		'location',
		'private_school_category',
		'public_contract',
		'private_school_maintainer_fk',
		'private_school_maintainer_cnpj',
		'private_school_cnpj',
		'regulation',
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>
</div>

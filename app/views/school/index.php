<div id="mainPage" class="main">
<?php
$this->breadcrumbs=array(
	'School Identifications',
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new SchoolIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new SchoolIdentification')),
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
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'School Identifications')?></div></div>
                <div class="panelGroupBody">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'baseScriptUrl' => Yii::app()->theme->baseUrl . '/plugins/gridview/',
                        'columns' => array(
		//'register_type',
		'inep_id',
		//'situation',
		//'initial_date',
		//'final_date',
		'name',
/*		'latitude',
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
		'regulation',*/
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
           <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>

</div>

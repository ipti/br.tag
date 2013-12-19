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
    
<div class="heading-buttons">
	<h3><?php echo Yii::t('default', 'School Identifications')?></h3>
	<div class="buttons pull-right">
		<a href="" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar escola</a>
	</div>
	<div class="clearfix"></div>
</div>
    
<div class="innerLR">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget">
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        //'htmlOptions' => array('class' => 'example'),
                        'itemsCssClass' => 'table table-bordered table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => true,
                        'columns' => array(
		//'register_type',
		'inep_id',
		//'situation',
		//'initial_date',
		//'final_date',
		'name',
//		'latitude',
//		'longitude',
//		'cep',
//		'address',
//		'address_number',
//		'address_complement',
//		'address_neighborhood',
//		'edcenso_uf_fk',
//		'edcenso_city_fk',
//		'edcenso_district_fk',
//		'ddd',
//		'phone_number',
//		'public_phone_number',
//		'other_phone_number',
//		'fax_number',
//		'email',
//		'edcenso_regional_education_organ_fk',
//		'administrative_dependence',
//		'location',
//		'private_school_category',
//		'public_contract',
//		'private_school_maintainer_fk',
//		'private_school_maintainer_cnpj',
//		'private_school_cnpj',
//		'regulation',
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
           <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?>        </div>
    </div>

</div>

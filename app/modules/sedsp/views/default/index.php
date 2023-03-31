<?php
/* @var $this DefaultController */	

$this->breadcrumbs=array(
	$this->module->id,
);
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/template2.css');
//$query = StudentIdentification::model()->findAllByAttributes(["school_inep_id_fk" => $school_id],"'gov_id' IS NULL");
//$criteria->compare('school_inep_id_fk', "$school_id");
$criteria = new CDbCriteria;
//$criteria->addInCondition('gov_id', 'null');
//$criteria->compare('gov_id','IS NULL');
//$dataProvider = new CActiveDataProvider('StudentIdentification', array(
//    'criteria' => $criteria
//));

$dataProvider=new CActiveDataProvider('StudentIdentification', array(
    'criteria'=>array(
        'condition'=>'gov_id is null',
        'order'=>'name ASC'
    ),
    'countCriteria'=>array(
        'condition'=>'gov_id is null',
    ),
    'pagination'=>array('PageSize'=>100),
))



?>

<?php

?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
           <?php $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'enablePagination' => true,
            'enableSorting' => false,
            'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
            'columns' => array(
            array(
            'name' => 'name'
            ),
                array
                (
                    'class'=>'CButtonColumn',
                    'template'=>'{email}',
                    'buttons'=>array
                    (
                        'email' => array
                        (
                            'label'=>'Gerar RA',
                            'options'=>array("id"=>'data->primaryKey'),
                            'click'=>"function(){
                                    var th=this;
                                    $.fn.yiiGridView.update('yw0', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              //$('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
                                              //$.fn.yiiGridView.update('yw0');
                                              $(th).html(data);
                                              //$(th).removeAttr('href');
                                        }
                                    })
                                    return false;
                              }
                     ",
                            'url'=>'Yii::app()->controller->createUrl("GenRA",array("id"=>$data->primaryKey))',
                        ),
                    )


                    )
            ),
            ));?>
        </div>
    </div>
</div>
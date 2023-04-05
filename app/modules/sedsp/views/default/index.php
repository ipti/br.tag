<?php
/* @var $this DefaultController */	

$this->breadcrumbs=array(
	$this->module->id,
);
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/sass/css/main.css');
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
           <?php $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'enablePagination' => false,
            'enableSorting' => false,
            'itemsCssClass' => 'js-tag-table tag-table table table-condensed
            table-striped table-hover table-primary table-vertical-center checkboxs',
            'columns' => array(
            array(
            'name' => 'name'
            ),
                array
                (
                    'header' => 'Ações',
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
                                              //$(th).removeAttr('  href');
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
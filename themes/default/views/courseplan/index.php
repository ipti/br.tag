<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.13.2/r-2.4.0/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.13.2/r-2.4.0/datatables.min.js"></script>
<?php
/* @var $this CoursePlanController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/template2.css');
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Course Plan') ?></h3>
            <div class="buttons">
                <a href="<?php echo Yii::app()->createUrl('courseplan/create') ?>"
                   class="tag-button medium-button"><?= Yii::t('default', 'Create Plan'); ?> </a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="columnone" style="padding-right: 1em">
            <div class="widget">
                <div class="alert courseplan-alert <?= Yii::app()->user->hasFlash('success') ? "alert-success" : "no-show" ?>"><?php echo Yii::app()->user->getFlash('success') ?></div>
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => false,
                        'itemsCssClass' => 'js-tag-table tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'header' => Yii::t('default', 'Name'),
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("courseplan/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '25%')
                            ),
                            array(
                                'header' => Yii::t('default', 'Stage'),
                                'name' => 'modality_fk',
                                'type' => 'raw',
                                'value' => '$data->modalityFk->name',
                                'htmlOptions' => array('width' => '25%'),
                            ),
                            array(
                                'header' => Yii::t('default', 'Discipline'),
                                'name' => 'discipline_fk',
                                'value' => '$data->disciplineFk->name',
                                'htmlOptions' => array('width' => '20%'),
                                'filter' => false
                            ),
                            array(
                                'header' => Yii::t('default', 'Autor'),
                                'name' => 'users_fk',
                                'value' => '$data->usersFk->name',
                                'htmlOptions' => array('width' => '25%'),
                                'filter' => false
                            ),
                            array(
                                'class' => 'CButtonColumn',
                                'template' => '{delete}',
                                'buttons' => array(
                                    'delete' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    )
                                ),
                                'afterDelete' => 'function(link, success, data){
                                    data = JSON.parse(data);
                                    if (data.valid) {
                                        $(".alert").text(data.message).addClass("alert-success").removeClass("alert-error");
                                    } else {
                                        $(".alert").text(data.message).addClass("alert-error").removeClass("alert-success");
                                    }
                                    $(".courseplan-alert").show();
                                }',

                            ),
                        ),
                    )); ?>


                </div>
            </div>
        </div>
        <div class="columntwo">
        </div>

    </div>
</div>


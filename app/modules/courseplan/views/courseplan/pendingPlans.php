<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

$this->menu=array(
	array('label'=>'Create CoursePlan', 'url'=>array('index')),
	array('label'=>'List ClassPlan', 'url'=>array('admin')),
);

?>

<?php
/* @var $this CoursePlanController */
/* @var $dataProvider CActiveDataProvider */


$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));

$this->menu = array(
    array('label' => 'List Courseplan', 'url' => array('index')),
    array('label' => '')
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v='.TAG_VERSION, CClientScript::POS_END);

?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Pending Course Plan') ?></h1>
            <div class="t-buttons-container">
                <a  href="<?php echo Yii::app()->createUrl('courseplan/courseplan/index') ?>"
                class="t-button-primary"><?= Yii::t('default', 'Course Plan') ?></a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="columnone" style="padding-right: 1em">
            <div class="widget clearmargin">
                <div class="alert courseplan-alert <?= Yii::app()->user->hasFlash('success') ? "alert-success" : "no-show" ?>"><?php echo Yii::app()->user->getFlash('success') ?></div>
                <div class="widget-body">
                    <?php $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => false,
                        'enableSorting' => false,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'header' => Yii::t('default', 'Name'),
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("courseplan/courseplan/validatePlan",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '25%', 'class' => 'link-update-grid-view')
                            ),
                            array(
                                'header' => Yii::t('default', 'Autor'),
                                'name' => 'users_fk',
                                'value' => '$data->usersFk->name',
                                'htmlOptions' => array('width' => '25%'),
                                'filter' => false
                            ),
                            array(
                                'header' => Yii::t('default', 'Situation'),
                                'name' => 'situation',
                                'value' => '$data->situation',
                                'htmlOptions' => array('width' => '25%'),
                            ),
                            array(
                                'header' => 'Ações',
                                'class' => 'CButtonColumn',
                                'template' => '{validate}',
                                'buttons' => array(
                                    'validate' => array(
                                        'label' => 'Validar',
                                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/validatePlan",array("id"=>$data->id))',
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/activeUser.svg'
                                    ),
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
                            )
                        )
                    )); ?>
                </div>
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>
</div>


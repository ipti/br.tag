<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

$this->menu=array(
	array('label'=>'Create CoursePlan', 'url'=>array('index')),
	array('label'=>'List Pending ClassPlan', 'url'=>array('admin')),
);

?>

<?php
/* @var $this CoursePlanController */
/* @var $dataProvider CActiveDataProvider */


$this->setPageTitle('TAG - ' . Yii::t('default', 'Course Plan'));

$this->menu = array(
    array('label' => 'List Pending Courseplan', 'url' => array('index')),
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
// $cs->registerScriptFile($baseScriptUrl . '/_initialization.js');

// $baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
// $themeUrl = Yii::app()->theme->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/functions.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/validations.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl . '/pagination.js?v='.TAG_VERSION, CClientScript::POS_END);

?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Course Plan'); ?></h1>
            <div class="t-buttons-container">
                <?php if (!Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)): ?>
                    <a href="<?php echo Yii::app()->createUrl('courseplan/courseplan/create') ?>"
                    class="t-button-primary"><?= Yii::t('default', 'Create Plan'); ?> </a>
                    <br/>
                <?php endif ?>
                <?php if (!Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)): ?>
                    <a  href="<?php echo Yii::app()->createUrl('courseplan/courseplan/pendingPlans') ?>"
                    class="t-button-primary"><?= Yii::t('default', 'Pendent Plan') ?></a>
                    <br/>
                <?php endif ?>
            </div>
        </div>
    </div>

        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <div id="select-container" class="tablet-row align-items--center-on-desktop">
            <div class="mobile-row">
                <div class="column clearleft">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Stage'), 'stage', array('class' => 't-field-select__label--required'));?>
                        <select class="select-search-on t-field-select__input" id="stage" style="min-width: 185px;">
                            <option value="">Selecione a etapa</option>
                            <?php
                                $stages = array_map("unserialize", array_unique(array_map("serialize", $stages)));
                                foreach ($stages as $stage) :
                            ?>
                                <option value="<?=$stage['id']?>"><?=$stage['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- diciplina -->
                </div>
                <div class="column discipline-container hide">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Discipline'),
                            'month', array('class' => 't-field-select__label--required')); ?>
                        <select class="select-search-on t-field-select__input" id="discipline"
                                style="min-width: 185px;"></select>
                    </div>
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
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("courseplan/courseplan/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '25%', 'class' => 'link-update-grid-view')
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
                                'header' => 'Ações',
                                'class' => 'CButtonColumn',
                                'template' => '{approved} {canceled} {delete}',
                                'buttons' => array(
                                    'delete' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    ),
                                    'approved' => array(
                                        'label' => '',
                                        'options' => [
                                            'class' => 't-course-plan-canceled',
                                            'style' => 'font-size: 20px; padding-right: 3 3 0 0;'
                                        ],
                                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/enableCoursePlanEdition",array("id"=>$data->id))',
                                        'visible' => '!Yii::app()->getAuthManager()->checkAccess("instructor", Yii::app()->user->loginInfos->id) && $data->situation !== CoursePlan::STATUS_PENNDING',
                                    ),
                                    'canceled' => array(
                                        'label' => '',
                                        'options' => [
                                            'class' => 't-course-plan-approved',
                                            'style' => 'font-size: 20px; padding: 3 3 0 0;'
                                        ],
                                        'url' => 'Yii::app()->createUrl("courseplan/courseplan/enableCoursePlanEdition",array("id"=>$data->id))',
                                        'visible' => '!Yii::app()->getAuthManager()->checkAccess("instructor", Yii::app()->user->loginInfos->id) && $data->situation === CoursePlan::STATUS_PENNDING',
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


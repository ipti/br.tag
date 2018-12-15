<?php
/* @var $this SchoolController
 * @var $school SchoolIdentification
 */
$this->headerDescription = CHtml::tag("span", [],$school->name);
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScript("variables",
//    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
//$cs->registerScriptFile('/themes/default/common/js/loadMap.js', CClientScript::POS_END);
$cs->registerCssFile($baseScriptUrl.'/common/css/resultsmanagement.css');
?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            <??>
            <a href="<?=yii::app()->createUrl("resultsmanagement/ManagementSchool/frequency",["sid" => $school->inep_id]);?>"
               class="widget-stats margin-bottom-none">
                <span class="glyphicons check"><i></i></span>
                <span class="txt"><?= yii::t('resultsmanagementModule.managementSchool', 'Frequency') ?></span>
                <div class="clearfix"></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?=yii::app()->createUrl("resultsmanagement/ManagementSchool/performance",["sid" => $school->inep_id]);?>" class="widget-stats margin-bottom-none">
                <span class="glyphicons list"><i></i></span>
                <span class="txt"><?= yii::t('resultsmanagementModule.managementSchool', 'Performance') ?></span>
                <div class="clearfix"></div>
            </a>
        </div>
    </div>
</div>
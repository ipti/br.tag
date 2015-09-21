<?php
/* @var $this ManagementSchoolController
 * @var $school SchoolIdentification
 */

$this->headerDescription = CHtml::tag("span", [],$school->name);

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScript("variables",
//    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
//$cs->registerScriptFile('/themes/default/common/js/loadMap.js', CClientScript::POS_END);
$cs->registerCssFile('/themes/default/common/css/resultsmanagement.css');
?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            <a href="<?=$this->createUrl("frequency",["sid"=>$school->inep_id])?>" class="widget-stats margin-bottom-none">
                <span class="glyphicons check"><i></i></span>
                <span class="txt"><?= yii::t('resultsmanagementModule.managementSchool', 'Frequency') ?></span>
                <div class="clearfix"></div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?=$this->createUrl("performance",["sid"=>$school->inep_id])?>" class="widget-stats margin-bottom-none">
                <span class="glyphicons list"><i></i></span>
                <span class="txt"><?= yii::t('resultsmanagementModule.managementSchool', 'Performance') ?></span>
                <div class="clearfix"></div>
            </a>
        </div>
    </div>
</div>
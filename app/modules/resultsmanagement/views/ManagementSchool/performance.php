<?php
/* @var $this ManagementSchoolController
 * @var $school SchoolIdentification
 */

$this->headerDescription = CHtml::tag("span", [],$school->name.CHtml::tag("span", []," | ".yii::t('resultsmanagementModule.managementSchool', 'Performance')));


$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScript("variables",
//    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
//$cs->registerScriptFile('/themes/default/common/js/managementschool.js', CClientScript::POS_END);
$cs->registerCssFile('/themes/default/common/css/resultsmanagement.css');
?>
<div class="row">
    <div class="col-md-12">
    </div>
</div>
<?php
/* @var $this ManagementSchoolController
 * @var $school SchoolIdentification
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$this->headerDescription = CHtml::tag("span", [],$school->name.CHtml::tag("span", []," | ".yii::t('resultsmanagementModule.managementSchool', 'Frequency')));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript("variables", '
    var chartDataUrl = "'.$this->createUrl("loadChartData").'";
    var $sid = "'.$school->inep_id.'";
',CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/common/js/managementschool.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/lib/js/plugins/charts/flot/jquery.flot.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/lib/js/plugins/charts/flot/jquery.flot.pie.min.js', CClientScript::POS_END);
$cs->registerCssFile($baseScriptUrl.'/common/css/resultsmanagement.css');
?>
<div class="row">
    <div class="col-md-4">
        <?= Chtml::dropDownList("classroom","",$classrooms,[
            "class" => "filter-select",
            "ajax" => [
                "type"=>"get",
                "url"=>$this->createUrl("loadClassroomInfos"),
                "success"=>"loadClassroomInfos",
                "data"=>["sid"=>$school->inep_id, "cid"=>"js:this.value"],
            ]
        ]); ?>
    </div>
    <div class="col-md-4">
        <?= Chtml::dropDownList("month","",[],[
            "class" => "filter-select",
        ]); ?>
    </div>
    <div class="col-md-4">
        <?= Chtml::dropDownList("discipline","",[],[
            "class" => "filter-select",
        ]); ?>
    </div>
</div>
<div class="separator bottom"></div>
<div class="row">
    <div class="col-md-12">
        <div id="chart_pie" style="height: 400px; padding: 0px;"></div>
    </div>
</div>
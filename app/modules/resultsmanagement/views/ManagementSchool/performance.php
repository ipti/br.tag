<?php
/* @var $this ManagementSchoolController
 * @var $school SchoolIdentification
 * @var $efficiencies array
 */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$this->headerDescription = CHtml::tag("span", [],$school->name.CHtml::tag("span", []," | ".yii::t('resultsmanagementModule.managementSchool', 'Performance')));

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript("variables", '
    var chartDataUrl = "'.$this->createUrl("loadPerformanceChartData").'";
    var $sid = "'.$school->inep_id.'";
    var proficiencyDataUrl = "'.$this->createUrl("loadDataForProficiency").'";
',CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/lib/js/plugins/charts/flot/jquery.flot.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/common/js/performance/iniPerformanceChart.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/common/js/performance/filterPerformance.js', CClientScript::POS_END);
$cs->registerScriptFile($baseScriptUrl.'/common/js/performance/filterProficiency.js', CClientScript::POS_END);
$cs->registerCssFile($baseScriptUrl.'/common/css/resultsmanagement.css');
?>
<div class="row">
    <div class="col-md-12">
        <div class="widget widget-tabs">
            <div class="widget-head">
                <ul>
                    <li class="active"><a href="#tab-1" data-toggle="tab"><i></i> <?=yii::t('resultsmanagementModule.managementSchool', 'Efficiency')?></li>
                    <li><a href="#tab-2" data-toggle="tab"><i></i>  <?=yii::t('resultsmanagementModule.managementSchool', 'Performance')?></a></li>
                    <li><a href="#tab-3" data-toggle="tab"><i></i>  <?=yii::t('resultsmanagementModule.managementSchool', 'Proficiency')?></a></li>
                    <li><a href="#tab-4" data-toggle="tab"><i></i>  <?=yii::t('resultsmanagementModule.managementSchool', 'Evolution')?></a></li>
                </ul>
            </div>
            <div class="widget-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-1">
                        <?= $this->renderPartial('partialViews/_efficiency', array('data' => $efficiencies)); ?>
                    </div>
                    <div class="tab-pane" id="tab-2">
                        <?= $this->renderPartial('partialViews/_performance', array('school' => $school,'classrooms' => $classrooms)); ?>
                    </div>
                    <div class="tab-pane" id="tab-3">
                        <?= $this->renderPartial('partialViews/_proficiency', array('school' => $school,'classrooms' => $classrooms)); ?>
                    </div>
                    <div class="tab-pane" id="tab-4">
                        <?= $this->renderPartial('partialViews/_evolution', array('school' => $school)); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
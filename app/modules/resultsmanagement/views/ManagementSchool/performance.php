<?php
/* @var $this ManagementSchoolController
 * @var $school SchoolIdentification
 * @var $efficiencies array
 */

$this->headerDescription = CHtml::tag("span", [],$school->name.CHtml::tag("span", []," | ".yii::t('resultsmanagementModule.managementSchool', 'Performance')));

$data = $efficiencies;

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScript("variables",
//    "var URLGetMapInfos = '".$this->createUrl("GetGMapInfo")."';", CClientScript::POS_BEGIN);
//$cs->registerScriptFile('/themes/default/common/js/managementschool.js', CClientScript::POS_END);
$cs->registerCssFile('/themes/default/common/css/resultsmanagement.css');
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
                        <?= $this->renderPartial('_efficiency', array('data' => $data)); ?>
                    </div>
                    <div class="tab-pane" id="tab-2">
                        <?= $this->renderPartial('_performance', array('school' => $school)); ?>
                    </div>
                    <div class="tab-pane" id="tab-3">
                        <?= $this->renderPartial('_proficiency', array('school' => $school)); ?>
                    </div>
                    <div class="tab-pane" id="tab-4">
                        <?= $this->renderPartial('_evolution', array('school' => $school)); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
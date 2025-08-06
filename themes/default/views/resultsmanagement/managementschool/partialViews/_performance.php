<?php
/**
 *
 * @var \SchoolIdentification $school
 */
?>
<div class="separator bottom"></div>
<h5><?=Yii::t('resultsmanagementModule.managementSchool', 'Performance')?></h5>
<p class="tab-description"><?= Yii::t('resultsmanagementModule.managementSchool', 'Based on the notes and the number of students it is possible to calculate the proportion of students with learning appropriate to your school stage')?></p>

<div class="separator bottom"></div>
<div class="container-fluid">
<div class="row">
    <div class="col-md-4">
        <?= CHtml::dropDownList('classroom', '', $classrooms, [
            'class' => 'filter-select',
            'ajax' => [
                'type' => 'get',
                'url' => $this->createUrl('loadPerformanceClassroomInfos'),
                'success' => 'loadClassroomInfos',
                'data' => ['sid' => $school->inep_id, 'cid' => 'js:this.value'],
            ]
        ]); ?>
    </div>
    <div class="col-md-4">
        <?= CHtml::dropDownList('unit', '', [], [
            'class' => 'filter-select',
        ]); ?>
    </div>
    <div class="col-md-4">
        <?= CHtml::dropDownList('discipline', '', [], [
            'class' => 'filter-select',
        ]); ?>
    </div>
</div>

<div class="separator bottom"></div>
<div class="row">
    <div class="col-md-12">
        <div id="chart" style="height: 400px; padding: 0px;"></div>
    </div>
</div>
</div>

<?php
/**
 *
 * @var \SchoolIdentification $school
 */
?>
<div class="separator bottom"></div>
<h5><?=Yii::t('resultsmanagementModule.managementSchool', 'Evolution of learning in school')?></h5>

<p class="tab-description"><?= Yii::t('resultsmanagementModule.managementSchool', 'We can verify that the results have improved over the years.')?></p>

<div class="separator bottom"></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <?= CHtml::dropDownList('evolution-classroom', '', $classrooms, [
                'class' => 'filter-select',
                'ajax' => [
                    'type' => 'get',
                    'url' => $this->createUrl('LoadDisciplineInfo'),
                    'success' => 'loadDisciplineInfo',
                    'data' => ['sid' => $school->inep_id, 'cid' => 'js:this.value'],
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= CHtml::dropDownList('evolution-discipline', '', [], [
                'class' => 'filter-select',
            ]); ?>
        </div>
    </div>

    <div class="separator bottom"></div>


    <div class="row" id="evolutions">

    </div>

</div>

<?php
/* @var $schools SchoolIdentification[]
 * @var $classrooms Classroom[]
 * @var $enrollments StudentEnrollment[]
 */


$schoolsCount = count($schools);
$schoolsActive = 0;
$schoolsInactive = 0;
$schoolsExtinct = 0;
foreach($schools as $school){
    if($school->situation == 1) $schoolsActive++;
    else if($school->situation == 2) $schoolsInactive++;
    else if($school->situation == 3) $schoolsExtinct++;
}

$enrollmentsCount = count($enrollments);
$enrollmentsUrban = 0;
$enrollmentsRural = 0;
$enrollmentsRegular = 0;
$enrollmentsAditional = 0;
#if AssistanceType = 4

?>


<div class="widget">
    <div class="widget-head">
        <h4 class="heading">
            <?= yii::t('resultsmanagementModule.layout', 'City Informations') ?>
        </h4>
    </div>
    <div class="widget-body">
        <div class="panel-group" id="accordion">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse"
                   data-parent="#accordion" href="#collapseEnrollment">
                    <?= yii::t('resultsmanagementModule.layout', 'Enrollments') ?>
                </a>
            </h4>
            <div id="collapseEnrollment"  class="collapse panel-content">
                <hr/>
                <h5><span><?= yii::t('resultsmanagementModule.layout', 'TOTAL SCHOOLS')?>: </span><?=$schoolsCount?></h5>
                                        <span><?= yii::t('resultsmanagementModule.layout', 'Working : {active} | Not working: {inactive} | Extinct: {extinct}',
                                                ['{active}' => $schoolsActive, "{inactive}"=>$schoolsInactive, "{extinct}"=>$schoolsExtinct])?></span>
                <h5><span><?= yii::t('resultsmanagementModule.layout', 'TOTAL ENROLLMENTS')?>: </span><?=$enrollmentsCount?></h5>
                <div class="panel-box">
                    <div class="row">
                        <div class="col-md-5">
                            <span><?=yii::t('resultsmanagementModule.layout',"URBAN AREA")?>: <?=$enrollmentsUrban?></span>
                            <br>
                            <span><?=yii::t('resultsmanagementModule.layout',"RURAL AREA")?>: <?=$enrollmentsRural?></span>
                        </div>
                        <div class="col-md-7">
                            <span><?=yii::t('resultsmanagementModule.layout',"REGULAR EDUCATION")?>: <?=$enrollmentsRegular?></span>
                            <br>
                            <span><?=yii::t('resultsmanagementModule.layout',"ADITIONAL ACTV.")?>:  <?=$enrollmentsAditional?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator bottom"></div>
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse"
                   data-parent="#accordion" href="#collapseService">
                    <?= yii::t('resultsmanagementModule.layout', 'Services') ?>
                </a>
            </h4>

            <div id="collapseService" class="collapse panel-content">
                <hr/>
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
                brunch.
            </div>
        </div>
    </div>
</div>
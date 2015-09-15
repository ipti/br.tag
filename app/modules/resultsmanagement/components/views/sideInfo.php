<?php
/* @var $schools SchoolIdentification[]
 */

$year = Yii::app()->user->year;

$schoolsCount = count($schools);
$enrollmentsCount = count($enrollments);

$schoolsActive = 0;
$schoolsInactive = 0;
$schoolsExtinct = 0;

$enrollmentsLocation = [];
$enrollmentsLocation[1] = 0;
$enrollmentsLocation[2] = 0;
$enrollmentsRegular = 0;
$enrollmentsAditional = 0;

$earlyEducationCount = 0;
$earlyEducationDayCare = 0;
$earlyEducationKindergarten = 0;


$primarySchoolCount = 0;
$primarySchoolLower = 0;
$primarySchoolHigher = 0;
$primarySchoolMulti = 0;

$ejaCount = 0;
# 43 ... 48 || 51 || 58 || 60 ... 63 || 65 || 66

$specialCount = 0;

foreach($schools as $school){
    if($school->situation == 1) $schoolsActive++;
    else if($school->situation == 2) $schoolsInactive++;
    else if($school->situation == 3) $schoolsExtinct++;

    foreach($school->classrooms as $classroom){
        if($classroom->school_year == $year) {
            $count = count($classroom->studentEnrollments);

            $enrollmentsLocation[$school->location] += $count;
            $enrollmentsAditional += $classroom->assistance_type == 4 ? $count : 0;
            $enrollmentsRegular += $classroom->assistance_type != 4 ? $count : 0;

            $stage = $classroom->edcenso_stage_vs_modality_fk;

            $earlyEducationDayCare += $stage == 1 ? $count : 0;
            $earlyEducationKindergarten += $stage == 2 || $stage == 3 ? $count : 0;

            $primarySchoolLower += ($stage >= 4 && $stage <= 7) || ($stage >= 14 && $stage <= 18) ? $count : 0;
            $primarySchoolHigher += ($stage >= 8 && $stage <= 11) || ($stage >= 19 && $stage <= 21) || $stage == 41 ? $count : 0;
            $primarySchoolMulti += $stage == 12 || $stage == 13 || $stage == 22 || $stage == 23 || $stage == 24 ? $count : 0;

            $ejaCount += $classroom->modality == 3 ? $count : 0;

            $specialCount += $classroom->modality == 2 ? $count : 0;
        }
    }
}
$earlyEducationCount = $earlyEducationDayCare + $earlyEducationKindergarten;
$primarySchoolCount = $primarySchoolLower + $primarySchoolHigher + $primarySchoolMulti;

?>


<div class="widget">
    <div class="widget-head">
        <h4 class="heading">
            <?= yii::t('resultsmanagementModule.sideInfo', 'City Information') ?>
        </h4>
    </div>

    <div class="widget-body">
    <div class="panel-group" id="accordion">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse"
                       data-parent="#accordion" href="#collapseEnrollment">
                        <?= yii::t('resultsmanagementModule.sideInfo', 'Enrollments') ?>
                    </a>
                </h4>
            </div>
            <div id="collapseEnrollment"  class="collapse panel-content">
                <hr/>
                <h5><span><?= yii::t('resultsmanagementModule.sideInfo', 'TOTAL SCHOOLS')?>: </span><?=$schoolsCount?></h5>
                                        <span><?= yii::t('resultsmanagementModule.sideInfo', 'Working : {active} | Not working: {inactive} | Extinct: {extinct}',
                                                ['{active}' => $schoolsActive, "{inactive}"=>$schoolsInactive, "{extinct}"=>$schoolsExtinct])?></span>
                <h5><span><?= yii::t('resultsmanagementModule.sideInfo', 'TOTAL ENROLLMENTS')?>: </span><?=$enrollmentsCount?></h5>
                <div class="panel-box">
                    <div class="row">
                        <div class="col-md-5">
                            <span><?=yii::t('resultsmanagementModule.sideInfo',"URBAN AREA")?>: <?=$enrollmentsLocation[1]?></span>
                            <br>
                            <span><?=yii::t('resultsmanagementModule.sideInfo',"RURAL AREA")?>: <?=$enrollmentsLocation[2]?></span>
                        </div>
                        <div class="col-md-7">
                            <span><?=yii::t('resultsmanagementModule.sideInfo',"REGULAR EDUCATION")?>: <?=$enrollmentsRegular?></span>
                            <br>
                            <span><?=yii::t('resultsmanagementModule.sideInfo',"ADITIONAL ACTV.")?>:  <?=$enrollmentsAditional?></span>
                        </div>
                    </div>
                </div>
                <div class="separator bottom"></div>
                <div class="row">
                    <p class="panel-bar"><?= yii::t('resultsmanagementModule.sideInfo', "REGULAR EDUCATION")?></p>
                    <div class="col-md-6">
                        <h6><?=yii::t('resultsmanagementModule.sideInfo',"EARLY EDUCATION")?></h6>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"TOTAL")?>: <?=$earlyEducationCount?></span>
                        <br>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"DAY CARE")?>: <?=$earlyEducationDayCare?></span>
                        <br>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"KINDERGARTEN")?>: <?=$earlyEducationKindergarten?></span>
                    </div>
                    <div class="col-md-6">
                        <h6><?=yii::t('resultsmanagementModule.sideInfo',"PRIMARY SCHOOL")?></h6>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"TOTAL")?>: <?=$primarySchoolCount?></span>
                        <br>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"LOWER")?>:  <?=$primarySchoolLower?></span>
                        <br>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"HIGHER")?>:  <?=$primarySchoolHigher?></span>
                        <br>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"MULTI")?>:  <?=$primarySchoolMulti?></span>
                    </div>
                </div>
                <div class="separator bottom"></div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="panel-bar"><?= yii::t('resultsmanagementModule.sideInfo', "ADULT EDUCATION")?></p>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"TOTAL")?>: <?=$ejaCount?></span>
                    </div>
                    <div class="col-md-6">
                        <p class="panel-bar"><?=yii::t('resultsmanagementModule.sideInfo',"SPECIAL EDUCATION")?></p>
                        <span><?=yii::t('resultsmanagementModule.sideInfo',"TOTAL")?>:  <?=$specialCount?></span>
                    </div>
                </div>
                <div class="separator bottom"></div>
            </div>
        </div>

        <div class="separator bottom"></div>

        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse"
                       data-parent="#accordion" href="#collapseService">
                        <?= yii::t('resultsmanagementModule.sideInfo', 'Services') ?>
                    </a>
                </h4>
            </div>
            <div id="collapseService" class="collapse panel-content" style="height: 0px">
                <hr/>
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
                brunch.
            </div>
        </div>
    </div>
    </div>
</div>
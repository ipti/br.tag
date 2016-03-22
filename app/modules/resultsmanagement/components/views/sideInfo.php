<?php
/* @var $schools SchoolIdentification[] */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

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
$specialCount = 0;

$structureCount = 0;

$waterPublic = 0;
$waterArtWell = 0;
$waterCistern = 0;
$waterRiver = 0;
$waterInexistent = 0;

$waterFiltrated = 0;
$waterNotFiltrated = 0;

$electricityPublic = 0;
$electricityGenerator = 0;
$electricityOther = 0;
$electricityInexistent = 0;

$sewagePublic = 0;
$sewageFossa = 0;
$sewageInexistent = 0;

$garbageCollect = 0;
$garbageBurn = 0;
$garbageThrow = 0;
$garbageRecycle = 0;
$garbageBury = 0;
$garbageOther = 0;

$schoolsHtml = "";

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

    $structure = $school->structure;
    if (isset($structure)) {
        $structure->consumed_water_type == 2 ? $waterFiltrated++ : $waterNotFiltrated++;
        
        $structure->water_supply_public == 1 ? $waterPublic++ :  
            $structure->water_supply_artesian_well == 1 ? $waterArtWell++ :
                $structure->water_supply_well == 1 ? $waterCistern++ :
                    $structure->water_supply_river == 1 ? $waterRiver++ : $waterInexistent++;

        $structure->energy_supply_public == 1 ? $electricityPublic++ :
            $structure->energy_supply_generator == 1 ? $electricityGenerator++ :
                $structure->energy_supply_other == 1 ? $electricityOther++ : $electricityInexistent++;

        $structure->sewage_public == 1 ? $sewagePublic++ :
            $structure->sewage_fossa == 1 ? $sewageFossa++ : $sewageInexistent++;

        $structure->garbage_destination_collect == 1 ? $garbageCollect++ :
            $structure->garbage_destination_burn == 1 ? $garbageBurn++ :
                $structure->garbage_destination_throw_away == 1 ? $garbageThrow++ :
                    $structure->garbage_destination_recycle == 1 ? $garbageRecycle++ :
                        $structure->garbage_destination_bury == 1 ? $garbageBury++ : $garbageOther++;

        $structureCount++;
    }
    $schoolsHtml .= CHtml::tag("span",["class"=>"map-label"],CHtml::link($school->name,yii::app()->createUrl("resultsmanagement/ManagementSchool/index")."/{$school->inep_id}"));
}
$earlyEducationCount = $earlyEducationDayCare + $earlyEducationKindergarten;
$primarySchoolCount = $primarySchoolLower + $primarySchoolHigher + $primarySchoolMulti;

$decimalPoint = 1;
$waterPublicPercent = number_format(($waterPublic/$structureCount)*100,$decimalPoint)."%";
$waterArtWellPercent = number_format(($waterArtWell/$structureCount)*100,$decimalPoint)."%";
$waterCisternPercent = number_format(($waterCistern/$structureCount)*100,$decimalPoint)."%";
$waterRiverPercent = number_format(($waterRiver/$structureCount)*100,$decimalPoint)."%";
$waterInexistentPercent = number_format(($waterInexistent/$structureCount)*100,$decimalPoint)."%";

$waterFiltratedPercent = number_format(($waterFiltrated/$structureCount)*100,$decimalPoint)."%";
$waterNotFiltratedPercent = number_format(($waterNotFiltrated/$structureCount)*100,$decimalPoint)."%";

$electricityPublicPercent = number_format(($electricityPublic/$structureCount)*100,$decimalPoint)."%";
$electricityGeneratorPercent = number_format(($electricityGenerator/$structureCount)*100,$decimalPoint)."%";
$electricityOtherPercent = number_format(($electricityOther/$structureCount)*100,$decimalPoint)."%";
$electricityInexistentPercent =number_format(($electricityInexistent/$structureCount)*100,$decimalPoint)."%";

$sewagePublicPercent = number_format(($sewagePublic/$structureCount)*100,$decimalPoint)."%";
$sewageFossaPercent = number_format(($sewageFossa/$structureCount)*100,$decimalPoint)."%";
$sewageInexistentPercent= number_format(($sewageInexistent/$structureCount)*100,$decimalPoint)."%";

$garbageCollectPercent = number_format(($garbageCollect/$structureCount)*100,$decimalPoint)."%";
$garbageBurnPercent = number_format(($garbageBurn/$structureCount)*100,$decimalPoint)."%";
$garbageThrowPercent = number_format(($garbageThrow/$structureCount)*100,$decimalPoint)."%";
$garbageRecyclePercent = number_format(($garbageRecycle/$structureCount)*100,$decimalPoint)."%";
$garbageBuryPercent = number_format(($garbageBury/$structureCount)*100,$decimalPoint)."%";
$garbageOtherPercent = number_format(($garbageOther/$structureCount)*100,$decimalPoint)."%";
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
                <h5><span><?= yii::t('resultsmanagementModule.sideInfo', 'TOTAL SCHOOLS')?>: </span><?=$schoolsCount?></h5>
                <span>
                    <?= yii::t('resultsmanagementModule.sideInfo', 'Working : {active} | Not working: {inactive} | Extinct: {extinct}',
                      ['{active}' => $schoolsActive, "{inactive}"=>$schoolsInactive, "{extinct}"=>$schoolsExtinct])?>
                </span>

                <div class="separator bottom"></div>
                <div class="row row-info water">
                    <div class="col-md-12"><h6><img src="<?=$baseScriptUrl?>/common/img/water.png"/><?= yii::t('resultsmanagementModule.sideInfo', 'Water')?></h6></div>
                    <div class="col-md-6">
                        <h6><?= yii::t('resultsmanagementModule.sideInfo', 'Supply')?></h6>
                        <div class="box"><?=$waterPublicPercent ?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterPublic ])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Public')?></span>
                        <div class="box"><?=$waterArtWellPercent   ?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterArtWell   ])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Artesian Well')?></span>
                        <div class="box"><?=$waterCisternPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterCistern])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Cistern/Well')?></span>
                        <div class="box"><?=$waterRiverPercent  ?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterRiver  ])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Fountain/River')?></span>
                        <div class="box"><?=$waterInexistentPercent  ?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterInexistent  ])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Inexistent')?></span>

                    </div>
                    <div class="col-md-6">
                        <h6><?= yii::t('resultsmanagementModule.sideInfo', 'Consumed by students')?></h6>
                        <div class="box"><?=$waterFiltratedPercent    ?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterFiltrated   ])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Filtrated')?></span>
                        <div class="box"><?=$waterNotFiltratedPercent ?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$waterNotFiltrated])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Not Filtrated')?></span>
                    </div>
                </div>
                <div class="separator bottom"></div>
                <div class="row row-info electricity">
                    <div class="col-md-12">
                        <h6><img src="<?=$baseScriptUrl?>/common/img/electricity.png"/><?= yii::t('resultsmanagementModule.sideInfo', 'Electricity')?></h6>
                    </div>
                    <div class="col-md-6">
                        <div class="box"><?=$electricityPublicPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$electricityPublic])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Public')?></span>
                        <div class="box"><?=$electricityGeneratorPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$electricityGenerator])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Generator')?></span>
                    </div>
                    <div class="col-md-6">
                        <div class="box"><?=$electricityOtherPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$electricityOther])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Other')?></span>
                        <div class="box"><?=$electricityInexistentPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$electricityInexistent])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Inexistent')?></span>
                    </div>
                </div>
                <div class="separator bottom"></div>
                <div class="row row-info sewage">
                    <div class="col-md-12"><h6><img src="<?=$baseScriptUrl?>/common/img/sanitary-sewage.png"/><?= yii::t('resultsmanagementModule.sideInfo', 'Sanitary sewage')?></h6></div>
                    <div class="col-md-6">
                        <div class="box"><?=$sewagePublicPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$sewagePublic])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Public')?></span>
                        <div class="box"><?=$sewageFossaPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$sewageFossa])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Fossa')?></span>
                        </div>
                    <div class="col-md-6">
                        <div class="box"><?=$sewageInexistentPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$sewageInexistent])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Inexistent')?></span>
                    </div>
                </div>
                <div class="separator bottom"></div>
                <div class="row row-info garbage">
                    <div class="col-md-12"><h6><img src="<?=$baseScriptUrl?>/common/img/garbage.png"/><?= yii::t('resultsmanagementModule.sideInfo', 'Garbage')?></h6></div>
                    <div class="col-md-6">
                        <div class="box"><?=$garbageCollectPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$garbageCollect])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Collect')?></span>
                        <div class="box"><?=$garbageBurnPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$garbageBurn])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Burn')?></span>
                        <div class="box"><?=$garbageThrowPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$garbageThrow])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Throw Away')?></span>
                    </div>
                    <div class="col-md-6">
                        <div class="box"><?=$garbageRecyclePercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$garbageRecycle])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Recycle')?></span>
                        <div class="box"><?=$garbageBuryPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$garbageBury])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Bury')?></span>
                        <div class="box"><?=$garbageOtherPercent?><p><?= yii::t('resultsmanagementModule.sideInfo',"({count} schools)", ['{count}'=>$garbageOther])?></p></div> <span><?= yii::t('resultsmanagementModule.sideInfo', 'Other')?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="separator bottom"></div>

        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse"
                       data-parent="#accordion" href="#collapseSchools">
                        <?= yii::t('resultsmanagementModule.sideInfo', 'Schools') ?>
                    </a>
                </h4>
            </div>
            <div id="collapseSchools"  class="collapse panel-content">
                <hr/>
                    <div class="row">
                        <div class="col-md-12">
                            <?=$schoolsHtml?>
                        </div>
                    </div>
                <div class="separator bottom"></div>
            </div>
        </div>
    </div>
    </div>
</div>
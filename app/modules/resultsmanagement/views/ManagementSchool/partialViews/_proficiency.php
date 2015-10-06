<?php
/**
 *
 * @var \SchoolIdentification $school
 */
?>
<div class="separator bottom"></div>
<h5><?=yii::t("resultsmanagementModule.managementSchool", "Proficiency")?></h5>

<p class="tab-description"><?= yii::t("resultsmanagementModule.managementSchool", "We can place the student learning in 4 quality levels of proficiency. Proper learning encompasses profiente and advanced levels.")?></p>

<div class="separator bottom"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <?= Chtml::dropDownList("proficiency-classroom","",$classrooms,[
                "class" => "filter-select",
                "ajax" => [
                    "type"=>"get",
                    "url"=>$this->createUrl("loadClassroomInfoForProficiency"),
                    "success"=>"loadClassroomInfos",
                    "data"=>["sid"=>$school->inep_id, "cid"=>"js:this.value"],
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= Chtml::dropDownList("proficiency-discipline","",[],[
                "class" => "filter-select",
            ]); ?>
        </div>

    </div>

    <div class="separator bottom"></div>


    <div class="row" id="proficiencies">

    </div>
</div>



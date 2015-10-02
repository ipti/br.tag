<?php
/**
 *
 * @var \SchoolIdentification $school
 * @var array $data
 *
 */
?>


<div class="separator bottom"></div>
<h5><?=yii::t("resultsmanagementModule.managementSchool", "Efficiency")?></h5>
<p class="tab-description"><?= yii::t("resultsmanagementModule.managementSchool", "Results obtained by the student during the school year about the tests and final exams when adopted")?></p>

<div class="separator bottom"></div>
<div class="container-fluid">
    <div class="row">
        <?php foreach($data as $unity => $d) {
            $total = $d["good"]+$d["regular"]+$d["bad"];
            $good = number_format($d["good"]/$total,2);
            $regular = number_format($d["regular"]/$total,2);
            $bad = number_format($d["bad"]/$total,2);

            $goodColor = "box-blue-".ceil(($good == 0 ? 1 : $good) / 25);
            $regularColor = "box-gray-".ceil(($regular == 0 ? 1 : $regular) / 25);
            $badColor = "box-red-".ceil(($bad == 0 ? 1 : $bad) / 25);
            ?>
            <div class="col-md-2 efficiency-block">
                <h5><?= $unity ?></h5>
                <?php $this->widget('resultsmanagement.components.boxWidget', [
                    'color' => $goodColor,
                    'percent' => $good,
                    'label' => yii::t("resultsmanagementModule.managementSchool", "Good"),
                    'sideLabel' => "",
                ]);
                ?>
                <div class="separator bottom"></div>

                <?php $this->widget('resultsmanagement.components.boxWidget', [
                    'color' => $regularColor,
                    'percent' => $regular,
                    'label' => yii::t("resultsmanagementModule.managementSchool", "Regular"),
                    'sideLabel' => ""
                ]);
                ?>
                <div class="separator bottom"></div>

                <?php $this->widget('resultsmanagement.components.boxWidget', [
                    'color' => $badColor,
                    'percent' => $bad,
                    'label' => yii::t("resultsmanagementModule.managementSchool", "Bad"),
                    'sideLabel' => ""
                ]);
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>


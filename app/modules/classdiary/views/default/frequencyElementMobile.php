<table class="column tag-table-secondary js-table-frequency">
    <thead>
        <tr>
            <th class="text-align--left">
                Nome
            </th>
            <th class="text-align--right">
                Faltas
            </th>
        </tr>
    </thead>
    <tbody>
        
    <?php foreach ($frequency["students"] as $f): 
        $url_link = yii::app()->createUrl('classdiary/default/StudentClassDiary', array('student_id' => $f["studentId"], 'stage_fk' => $_GET["stage_fk"], 'classrom_id' => $_GET["classrom_fk"], 'schedule' => $f["schedule"]["schedule"], 'date' => $date, 'discipline_fk' => $discipline_fk, "justification" => $f["schedule"]["justification"])) ; 
        $is_disabled = (!$f["schedule"]["available"] ? "disabled" : "");
    ?>
            <tr>
                <td> <a class='js-justification' href='<?= $url_link ?>'><span class="t-icon-attendance-note t-icon "></span><?php echo $f["studentName"]; ?></a></td>
                <td class='justify-content--end' <?= $is_disabled ?>><input class='js-frequency-checkbox' id="<?php echo $f["studentId"] ?>" type='checkbox' <?php echo (!$f["schedule"]["available"] ? "disabled" : "") ?> <?php echo ( $f["schedule"]["fault"] ? "checked" : "") ?> 
                studentId='<?php echo $f["studentId"] ?>' classrom_id='<?php echo $_GET["classrom_fk"] ?>' stage_fk='<?php echo $_GET["stage_fk"] ?>' schedule='<?php echo $f["schedule"]["schedule"]?>'/></td>
            </tr>
    <?php endforeach ?>
    </tbody>
</table>
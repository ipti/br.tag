<?php if($frequency["valid"] == true): ?>
<div class="t-badge-info">
    <span class="t-info_positive t-badge-info__icon"></span>
    Para justificar falta e avaliação de Aluno clique no ícone
</div>

<?php
$command = Yii::app()->db->createCommand("
    SELECT s.day
    FROM instructor_faults if2 
    JOIN schedule s ON if2.schedule_fk = s.id 
    WHERE if2.instructor_fk = (SELECT id from instructor_identification ii WHERE ii.users_fk = :instructor_id);"
);

$command->bindValue(':instructor_id', Yii::app()->user->loginInfos->id);
$daysFaults = $command->queryColumn();
$dateParts = explode('/', $date); 
$usFormattedDate = $dateParts[1] . '/' . $dateParts[0] . '/' . $dateParts[2];
$dayToCheck = date('j', strtotime($usFormattedDate));

if (!in_array($dayToCheck, $daysFaults)) {
?>
<table aria-label="Tabela de alunos" class="column clearfix tag-table-secondary js-table-frequency">
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
            $url_link = yii::app()->createUrl('classdiary/default/StudentClassDiary', array('student_id' => $f["studentId"], 'stage_fk' => $stage_fk, 'classroom_id' => $classroom_fk, 'schedule' => $f["schedule"]["schedule"], 'date' => $date, 'discipline_fk' => $discipline_fk, "justification" => $f["schedule"]["justification"])) ; 
            $is_disabled = (!$f["schedule"]["available"] ? "disabled" : "");
        ?>
        <tr>
            <td> <a class='js-justification'  href='<?= $url_link ?>'><span class="t-icon-annotation t-icon "></span><?php echo $f["studentName"]; ?></a></td>
            <td class='justify-content--end' <?= $is_disabled ?>><input class='js-frequency-checkbox' id="<?php echo $f["studentId"] ?>" type='checkbox' <?= $is_disabled ?> <?php echo ( $f["schedule"]["fault"] ? "checked" : "") ?> 
            data-studentId='<?php echo $f["studentId"] ?>' data-classroom_id='<?php echo $classroom_fk ?>' data-stage_fk='<?php echo $stage_fk ?>' data-schedule='<?php echo $f["schedule"]["schedule"]?>'/></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php 
} else {
    echo '<div class="msg" style="padding: 10px;background: #eaeaf8;font-weight: bold;">Professor ausente para este dia</div>';
}
endif;
?>

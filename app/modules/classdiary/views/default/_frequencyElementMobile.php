<?php if ($frequency['valid'] == true): ?>
    <div class="t-badge-info t-margin-none--left"><span class="t-info_positive t-badge-info__icon"></span>Para justificar
        falta e avaliação de Aluno clique no ícone "<span class="t-icon-annotation t-icon"></span>" </div>
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

            <?php foreach ($frequency['students'] as $f):
            ?>
                <<<<<<< HEAD
                    <tr>
                    <td>
                        <label class="t-badge-info t-margin-none--left <?= $f['status'] == '1' || $f['status'] == '' ? 'hide' : ''; ?>">
                            <?= $f['statusLabel'] ?>
                        </label>
                        <?php echo $f['studentName']; ?>
                    </td>
                    <td class='justify-content--end'>
                        <?php foreach ($f['schedule'] as $s):
                            $isDisabled = ((!$s['available'] || !$s['valid']) ? 'disabled' : '');
                            $urlLink = yii::app()->createUrl('classdiary/default/StudentClassDiary', ['student_id' => $f['studentId'], 'stage_fk' => $stageFk, 'classroom_id' => $classroomFk, 'schedule' => $s['schedule'], 'date' => $date, 'discipline_fk' => $disciplineFk, 'justification' => $s['justification']]);
                        ?>
                            <a class="<?= 'js-justification ' . $isDisabled ?>" href='<?= $urlLink ?>'><span class="t-icon-annotation t-icon" style="margin:0px 5px 0px 10px;"></span></a><?php echo  $frequency['isMinorEducation'] ? '' : $s['schedule'] . '°' ?> <input class='js-frequency-checkbox' id="<?php echo $f['studentId'] ?>" type='checkbox' <?= $isDisabled ?> <?php echo ($s['fault'] ? 'checked' : '') ?>
                                data-studentId='<?php echo $f['studentId'] ?>' data-classroom_id='<?php echo $classroomFk ?>' data-stage_fk='<?php echo $stageFk ?>' data-schedule='<?php echo $s['schedule'] ?>' />
                        <?php endforeach ?>
                    </td>
                    </tr>
                    =======
                    <tr>
                        <td>
                            <label class="t-badge-info t-margin-none--left <?= $f['status'] == '1' || $f['status'] == '' ? 'hide' : ''; ?>">
                                <?= $f['statusLabel'] ?>
                            </label>
                            <?php echo $f['studentName']; ?>
                        </td>
                        <td class='justify-content--end'>
                            <?php foreach ($f['schedule'] as $s):
                                $isDisabled = ((!$s['available'] || !$s['valid']) ? 'disabled' : '');
                                $urlLink = yii::app()->createUrl('classdiary/default/StudentClassDiary', ['studentId' => $f['studentId'], 'stageFk' => $stageFk, 'classroomId' => $classroomFk, 'schedule' => $s['schedule'], 'date' => $date, 'disciplineFk' => $disciplineFk, 'justification' => $s['justification']]);
                            ?>
                                <a class="<?= 'js-justification ' . $isDisabled ?>" href='<?= $urlLink ?>'><span class="t-icon-annotation t-icon"
                                        style="margin:0px 5px 0px 10px;"></span></a><?php echo  $frequency['isMinorEducation'] ? '' : $s['schedule'] . '°' ?>
                                <input class='js-frequency-checkbox' id="<?php echo $f['studentId'] ?>" type='checkbox' <?= $isDisabled ?>
                                    <?php echo ($s['fault'] ? 'checked' : '') ?> data-studentId='<?php echo $f['studentId'] ?>'
                                    data-classroom_id='<?php echo $classroomFk ?>' data-stage_fk='<?php echo $stageFk ?>'
                                    data-schedule='<?php echo $s['schedule'] ?>' />
                            <?php endforeach ?>
                        </td>
                    </tr>
                    >>>>>>> main
                <?php endforeach ?>
        </tbody>
    </table>
<?php else : ?>
    <<<<<<< HEAD
        <div class="t-badge-critical"><span class="t-info_positive t-badge-critical__icon"></span><?= $frequency['error']; ?> </div>
        =======
        <div class="t-badge-critical"><span class="t-info_positive t-badge-critical__icon"></span><?= $frequency['error']; ?>
        </div>
        >>>>>>> main
    <?php endif ?>
    <style>
        .disabled {
            pointer-events: none;
            cursor: not-allowed;
        }
    </style>
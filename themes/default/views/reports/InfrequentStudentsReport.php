<?php
/* @var $this ReportsController */
/* @var $report array */
/* @var $title string */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$formatCpf = static function ($value) {
    $digits = preg_replace('/\D/', '', (string) $value);

    if (strlen($digits) !== 11) {
        return $value;
    }

    return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
};

$formatTelephone = static function ($value) {
    $digits = preg_replace('/\D/', '', (string) $value);

    if (strlen($digits) === 11) {
        return '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 5) . '-' . substr($digits, 7, 4);
    }

    if (strlen($digits) === 10) {
        return '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 4) . '-' . substr($digits, 6, 4);
    }

    return $value;
};

$calculateAge = static function ($birthday) {
    foreach (['d/m/Y', 'Y-m-d'] as $format) {
        $date = DateTime::createFromFormat($format, (string) $birthday);
        $errors = DateTime::getLastErrors();

        if ($date !== false && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
            return $date->diff(new DateTime())->y;
        }
    }

    return '';
};
?>

<div class="pageA4H" style="width: 1080px;">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo CHtml::encode($title); ?> - <?php echo Yii::app()->user->year; ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="window.print()" class="btn btn-icon glyphicons print hidden-print"
                    style="padding: 10px;">
                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg"
                        class="img_cards" />
                    <?php echo Yii::t('default', 'Print'); ?><i></i>
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($report)): ?>
        <p class="alert alert-info">Não há alunos ativos com infrequência superior a 30% no ano letivo selecionado.</p>
    <?php else: ?>
        <table class="table table-bordered table-striped" aria-label="Alunos infrequentes">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Escola</th>
                    <th>Aluno</th>
                    <th>Idade</th>
                    <th>Turma</th>
                    <th>Série/Ano</th>
                    <th>Turno</th>
                    <th>Aulas/Dias letivos</th>
                    <th>Faltas</th>
                    <th>Infrequência</th>
                    <th>CPF</th>
                    <th>Endereço residencial</th>
                    <th>Responsável legal</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report as $index => $student): ?>
                    <?php
                    $address = array_filter([
                        $student['address'],
                        $student['address_number'],
                        $student['address_complement'],
                        $student['neighborhood'],
                    ], static function ($value) {
                        return $value !== null && $value !== '';
                    });
                    ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo CHtml::encode($student['school_name']); ?></td>
                        <td><?php echo CHtml::encode($student['student_name']); ?></td>
                        <td><?php echo CHtml::encode($calculateAge($student['birthday'])); ?></td>
                        <td><?php echo CHtml::encode($student['classroom_name']); ?></td>
                        <td><?php echo CHtml::encode($student['stage_name']); ?></td>
                        <td><?php echo CHtml::encode($student['classroom_turn']); ?></td>
                        <td><?php echo CHtml::encode($student['total_classes']); ?></td>
                        <td><?php echo CHtml::encode($student['total_faults']); ?></td>
                        <td><?php echo number_format((float) $student['absence_percentage'], 2, ',', '.'); ?>%</td>
                        <td><?php echo CHtml::encode($formatCpf($student['cpf'])); ?></td>
                        <td><?php echo CHtml::encode(implode(', ', $address)); ?></td>
                        <td><?php echo CHtml::encode($student['responsable_name']); ?></td>
                        <td><?php echo CHtml::encode($formatTelephone($student['responsable_telephone'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="13"><strong>Total de alunos infrequentes</strong></td>
                    <td><strong><?php echo count($report); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>

    <div class="report-criteria">
        <strong>Critérios de filtragem:</strong>
        foram consideradas as matrículas do ano letivo selecionado em toda a rede municipal, com status
        <em>MATRICULADO</em> ou sem status informado. A infrequência é calculada somente sobre horários válidos da turma
        e faltas sem justificativa. Nas etapas menores, aulas e faltas são contabilizadas por dia letivo; nas demais
        etapas, por horário/aula. Integram a relação somente os alunos com percentual de faltas acumuladas superior a
        30%.
    </div>
</div>

<style>
    table {
        font-size: 10px;
    }

    table th,
    table td {
        vertical-align: middle !important;
    }

    table th {
        text-align: center !important;
    }

    .report-criteria {
        font-size: 11px;
        line-height: 1.4;
        margin-top: 16px;
    }

    @media print {
        .hidden-print {
            display: none;
        }

        @page {
            size: landscape;
        }
    }
</style>

<?php
/**
 * Partial view: Histórico de Vínculos do Professor
 *
 * @var $instructor  InstructorIdentification
 * @var $teachingHistory array  Passed from InstructorController::actionUpdate
 */

$roles = [
    1 => 'Docente',
    2 => 'Auxiliar/Assistente Educacional',
    3 => 'Profissional/Monitor Ed. Especial',
    4 => 'Coordenador Pedagógico',
    5 => 'Diretor',
    6 => 'Vice-Diretor',
    7 => 'Secretaria',
    8 => 'Orientador Educacional',
    9 => 'Outro',
];

$periods = [
    1 => 'Matutino',
    2 => 'Vespertino',
    3 => 'Noturno',
    4 => 'Integral',
];

$contracts = [
    1 => 'Concursado/Efetivo',
    2 => 'Seleção Pública',
    3 => 'Temporário',
    4 => 'Terceirizado',
    5 => 'Sem vínculo',
    6 => 'Aposentado',
];

// Agrupa por ano
$byYear = [];
foreach ($teachingHistory as $row) {
    $year = $row['classroom_year'] ?: 'Sem ano';
    $byYear[$year][] = $row;
}
krsort($byYear);
?>

<div style="padding: 20px 12px;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
        <span class="t-icon-schedule" style="font-size:18px; color:#3f45ea;"></span>
        <h3 style="margin:0; font-size:15px; font-weight:700; color:#252A31; font-family:'Inter',sans-serif;">
            Histórico de Vínculos
        </h3>
        <span class="t-badge-info" style="font-size:12px; font-family:'Inter',sans-serif;">
            <?= count($teachingHistory) ?> registro(s)
        </span>
        <?php if (!empty($teachingHistory)): ?>
        <a href="<?= Yii::app()->createUrl('instructor/printHistory', ['id' => $instructor->id]) ?>"
           target="_blank"
           class="t-button-secondary"
           style="margin-left:auto; font-size:12px; display:inline-flex; align-items:center; gap:6px; padding:6px 14px; text-decoration:none;">
            <span class="t-icon-print"></span>
            Imprimir Ficha
        </a>
        <?php endif; ?>
    </div>

    <?php if (empty($teachingHistory)): ?>
        <div style="text-align:center; padding:40px 20px; color:#5F738C; font-size:13px; font-family:'Inter',sans-serif;">
            <span class="t-icon-book" style="font-size:28px; display:block; margin-bottom:10px; opacity:.4;"></span>
            Nenhum vínculo encontrado para este professor.
        </div>
    <?php else: ?>

    <!-- Uma única tabela para manter alinhamento consistente entre todos os anos -->
    <table style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif; font-size:13px; table-layout:fixed;">
        <colgroup>
            <col style="width:28%"><!-- Escola -->
            <col style="width:21%"><!-- Turma -->
            <col style="width:9%"> <!-- Período -->
            <col style="width:12%"><!-- Função -->
            <col style="width:18%"><!-- Tipo de Contrato -->
            <col style="width:8%"> <!-- INEP -->
            <col style="width:4%"> <!-- Imprimir -->
        </colgroup>
        <thead>
            <tr style="background:#f5f7f9; font-size:11px; color:#5F738C; text-transform:uppercase; letter-spacing:.04em;">
                <th style="padding:8px 10px; text-align:left; font-weight:600; border-bottom:2px solid #e8edf1;">Escola</th>
                <th style="padding:8px 10px; text-align:left; font-weight:600; border-bottom:2px solid #e8edf1;">Turma</th>
                <th style="padding:8px 10px; text-align:left; font-weight:600; border-bottom:2px solid #e8edf1;">Período</th>
                <th style="padding:8px 10px; text-align:left; font-weight:600; border-bottom:2px solid #e8edf1;">Função</th>
                <th style="padding:8px 10px; text-align:left; font-weight:600; border-bottom:2px solid #e8edf1;">Contrato</th>
                <th style="padding:8px 10px; text-align:center; font-weight:600; border-bottom:2px solid #e8edf1;">INEP</th>
                <th style="padding:8px 10px; text-align:center; font-weight:600; border-bottom:2px solid #e8edf1;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($byYear as $year => $rows): ?>
            <!-- Cabeçalho de grupo por ano -->
            <tr>
                <td colspan="6" style="
                    padding:6px 10px;
                    background:#eaeaf8;
                    font-size:11px;
                    font-weight:700;
                    color:#3f45ea;
                    letter-spacing:.06em;
                    text-transform:uppercase;
                    border-top:2px solid #d5d5f5;
                ">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span><?= CHtml::encode($year) ?></span>
                    </div>
                </td>
                <td style="
                    padding:6px 10px;
                    background:#eaeaf8;
                    border-top:2px solid #d5d5f5;
                    text-align:center;
                ">
                    <a href="<?= Yii::app()->createUrl('instructor/printYearHistory', ['id' => $instructor->id, 'year' => $year]) ?>"
                       target="_blank"
                       title="Imprimir resumo deste ano letivo"
                       style="display:inline-flex; align-items:center; justify-content:center; opacity:.90;">
                        <img src="<?= Yii::app()->theme->baseUrl ?>/img/Impressora.svg"
                             alt="Imprimir Resumo do Ano"
                             style="width:16px; height:16px;">
                    </a>
                </td>
            </tr>
            <?php foreach ($rows as $i => $row): ?>
            <tr style="<?= $i % 2 === 0 ? 'background:#fff;' : 'background:#fafafe;' ?> color:#252A31;">
                <td style="padding:8px 10px; border-bottom:1px solid #eff2f5; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= CHtml::encode($row['school_name']) ?>">
                    <?= CHtml::encode($row['school_name'] ?: '—') ?>
                </td>
                <td style="padding:8px 10px; border-bottom:1px solid #eff2f5; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= CHtml::encode($row['classroom_name']) ?>">
                    <?= CHtml::encode($row['classroom_name'] ?: '—') ?>
                </td>
                <td style="padding:8px 10px; border-bottom:1px solid #eff2f5; color:#465567;">
                    <?= CHtml::encode($periods[$row['classroom_period']] ?? '—') ?>
                </td>
                <td style="padding:8px 10px; border-bottom:1px solid #eff2f5; color:#465567; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= CHtml::encode($roles[$row['role']] ?? '') ?>">
                    <?= CHtml::encode($roles[$row['role']] ?? '—') ?>
                </td>
                <td style="padding:8px 10px; border-bottom:1px solid #eff2f5; color:#465567; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= CHtml::encode($contracts[$row['contract_type']] ?? '') ?>">
                    <?= CHtml::encode($contracts[$row['contract_type']] ?? '—') ?>
                </td>
                <td style="padding:8px 10px; border-bottom:1px solid #eff2f5; text-align:center; font-family:monospace; font-size:11px; color:#5F738C;">
                    <?= CHtml::encode($row['school_inep_id_fk'] ?: '—') ?>
                </td>
                <td style="padding:6px 10px; border-bottom:1px solid #eff2f5; text-align:center;">
                    <a href="<?= Yii::app()->createUrl('instructor/printHistory', ['id' => $instructor->id, 'teaching_id' => $row['id']]) ?>"
                       target="_blank"
                       title="Imprimir ficha deste vínculo"
                       style="display:inline-flex; align-items:center; justify-content:center; opacity:.75;">
                        <img src="<?= Yii::app()->theme->baseUrl ?>/img/Impressora.svg"
                             alt="Imprimir"
                             style="width:16px; height:16px;">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>

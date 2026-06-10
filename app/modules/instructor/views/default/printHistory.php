<?php
/**
 * Ficha Imprimível — Vínculos agrupados por Ano Letivo
 * Uma página por ano por vínculo, com aulas ministradas por disciplina e faltas
 *
 * @var $this          InstructorController
 * @var $instructor    InstructorIdentification
 * @var $instructorDoc InstructorDocumentsAndAddress
 * @var $teachingHistory array  enriched with classes_by_discipline[], faults[]
 */

$this->pageTitle = 'TAG - Ficha de Vínculos — ' . $instructor->name;

$roles = [
    1 => 'Docente',
    2 => 'Auxiliar/Assistente Educacional',
    3 => 'Profissional/Monitor de Educação Especial',
    4 => 'Coordenador Pedagógico',
    5 => 'Diretor',
    6 => 'Vice-Diretor',
    7 => 'Secretaria',
    8 => 'Orientador Educacional',
    9 => 'Outro',
];

$contracts = [
    1 => 'Concursado/Efetivo/Estável',
    2 => 'Procedimento de Seleção Pública',
    3 => 'Contrato Temporário',
    4 => 'Terceirizado',
    5 => 'Sem vínculo',
    6 => 'Aposentado',
];

$periods = [
    1 => 'Matutino',
    2 => 'Vespertino',
    3 => 'Noturno',
    4 => 'Integral',
];

$months = [
    1=>'Jan',2=>'Fev',3=>'Mar',4=>'Abr',5=>'Mai',6=>'Jun',
    7=>'Jul',8=>'Ago',9=>'Set',10=>'Out',11=>'Nov',12=>'Dez',
];

// Agrupa vínculos por ano letivo
$byYear = [];
foreach ($teachingHistory as $link) {
    $year = $link['classroom_year'] ?: 'Sem ano';
    $byYear[$year][] = $link;
}
krsort($byYear);
?>

<!-- Botões de ação (ocultos na impressão) -->
<div class="row-fluid hidden-print" style="padding:12px 20px; border-bottom:1px solid #eee; margin-bottom:0;">
    <a onclick="window.print()" class="btn btn-icon glyphicons print" style="padding:10px;">
        <img alt="impressora" src="<?= Yii::app()->theme->baseUrl ?>/img/Impressora.svg" class="img_cards" />
        Imprimir todas as fichas <i></i>
    </a>
    <a href="javascript:history.back()" class="btn btn-icon glyphicons circle_arrow_left" style="padding:10px; margin-left:8px;">
        Voltar <i></i>
    </a>
    <span style="margin-left:16px; font-size:12px; color:#888; vertical-align:middle;">
        <?= count($teachingHistory) ?> vínculo(s) · <?= count($byYear) ?> ano(s)
    </span>
</div>

<?php if (empty($teachingHistory)): ?>
<div class="pageA4H" style="text-align:center; padding:60px;">
    <p>Nenhum vínculo encontrado para este professor.</p>
</div>
<?php endif; ?>

<?php foreach ($byYear as $year => $links): ?>

<?php foreach ($links as $linkIndex => $link):
    $absence_rate = $link['classes_given'] > 0
        ? round(($link['faults_count'] / $link['classes_given']) * 100, 1)
        : 0;
?>

<div class="pageA4H" style="page-break-after: always;">

    <?php $this->renderPartial('//reports/head'); ?>

    <!-- Título do Ano Letivo -->
    <div style="background:#3f45ea; color:#fff; padding:7px 14px; margin:10px 0 8px; border-radius:3px; display:flex; align-items:center; justify-content:space-between;">
        <strong style="font-size:14px; letter-spacing:.03em;">Ano Letivo: <?= CHtml::encode($year) ?></strong>
        <span style="font-size:11px; opacity:.85;">Vínculo <?= $linkIndex + 1 ?>/<?= count($links) ?> deste ano</span>
    </div>

    <!-- Dados do Professor -->
    <table class="table table-bordered" style="font-size:11.5px; margin-bottom:8px;">
        <tbody>
            <tr style="background:#eaeaf8;">
                <th colspan="4" style="font-size:10px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea;">Dados do Professor</th>
            </tr>
            <tr>
                <th style="width:14%; background:#fafafa;">Nome</th>
                <td style="width:36%;"><?= CHtml::encode($instructor->name) ?></td>
                <th style="width:14%; background:#fafafa;">CPF</th>
                <td style="width:36%;"><?= CHtml::encode($instructorDoc ? $instructorDoc->cpf : '—') ?></td>
            </tr>
            <tr>
                <th style="background:#fafafa;">INEP</th>
                <td><?= CHtml::encode($instructor->inep_id ?: '—') ?></td>
                <th style="background:#fafafa;">NIS</th>
                <td><?= CHtml::encode($instructor->nis ?: '—') ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Dados do Vínculo -->
    <table class="table table-bordered" style="font-size:11.5px; margin-bottom:8px;">
        <tbody>
            <tr style="background:#eaeaf8;">
                <th colspan="4" style="font-size:10px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea;">Dados do Vínculo</th>
            </tr>
            <tr>
                <th style="width:14%; background:#fafafa;">Escola</th>
                <td colspan="3">
                    <?= CHtml::encode($link['school_name'] ?: '—') ?>
                    <span style="color:#888; font-size:10px;">(INEP: <?= CHtml::encode($link['school_inep_id_fk']) ?>)</span>
                </td>
            </tr>
            <tr>
                <th style="background:#fafafa;">Turma</th>
                <td><?= CHtml::encode($link['classroom_name'] ?: '—') ?></td>
                <th style="background:#fafafa;">Etapa</th>
                <td><?= CHtml::encode($link['stage_name'] ?: '—') ?></td>
            </tr>
            <tr>
                <th style="background:#fafafa;">Período</th>
                <td><?= CHtml::encode($periods[$link['classroom_period']] ?? '—') ?></td>
                <th style="background:#fafafa;">Função</th>
                <td><?= CHtml::encode($roles[$link['role']] ?? '—') ?></td>
            </tr>
            <tr>
                <th style="background:#fafafa;">Contrato</th>
                <td colspan="3"><?= CHtml::encode($contracts[$link['contract_type']] ?? '—') ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Aulas ministradas por disciplina ou dias trabalhados -->
    <?php if (!empty($link['is_minor_stage'])): ?>
    <table class="table table-bordered" style="font-size:11.5px; margin-bottom:8px;">
        <thead>
            <tr style="background:#eaeaf8;">
                <th colspan="2" style="font-size:10px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea;">
                    Aulas Ministradas (Via Diário de Classe) - Turma de Regência
                </th>
            </tr>
            <tr style="background:#f5f7f9; font-size:10px; color:#5F738C;">
                <th style="width:75%;">Descrição</th>
                <th style="width:25%; text-align:center;">Dias Ministrados</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total de Dias Distintos Registrados pelo Professor</td>
                <td style="text-align:center; font-weight:700; font-size:14px; color:#3f45ea;"><?= (int)$link['classes_given'] ?></td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
    <!-- Aulas ministradas por disciplina -->
    <table class="table table-bordered" style="font-size:11.5px; margin-bottom:8px;">
        <thead>
            <tr style="background:#eaeaf8;">
                <th colspan="3" style="font-size:10px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea;">
                    Aulas Ministradas por Disciplina
                    <span style="font-weight:400; font-size:10px; color:#5F738C;">(via Diário de Classe)</span>
                </th>
            </tr>
            <tr style="background:#f5f7f9; font-size:10px; color:#5F738C;">
                <th style="width:55%;">Disciplina</th>
                <th style="width:20%; text-align:center;">Carga Prevista</th>
                <th style="width:25%; text-align:center;">Aulas Ministradas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($link['classes_by_discipline'])): ?>
            <tr>
                <td colspan="3" style="text-align:center; color:#888; font-size:11px; padding:10px;">
                    Nenhuma aula registrada em class_contents
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($link['classes_by_discipline'] as $disc): ?>
            <tr>
                <td><?= CHtml::encode($disc['discipline_name']) ?></td>
                <td style="text-align:center;"><?= CHtml::encode($disc['workload_planned']) ?: '—' ?></td>
                <td style="text-align:center; font-weight:600;"><?= (int)$disc['classes_given'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="background:#f0f0f0; font-weight:700;">
                <td colspan="2" style="text-align:right; padding-right:12px;">Total Ministrado</td>
                <td style="text-align:center; color:#3f45ea; font-size:12px;"><?= $link['classes_given'] ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- Resumo de Frequência -->
    <table class="table table-bordered" style="font-size:11.5px; margin-bottom:4px;">
        <tbody>
            <tr style="background:#eaeaf8;">
                <th colspan="4" style="font-size:10px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea;">
                    Frequência
                    <span style="font-weight:400; font-size:9.5px; color:#5F738C; margin-left:6px;">* cálculo por dia letivo</span>
                </th>
            </tr>
            <tr>
                <th style="width:25%; background:#fafafa;">Dias Letivos Previstos</th>
                <td style="width:25%; font-size:13px; font-weight:700;"><?= $link['total_distinct_days'] ?></td>
                <th style="width:25%; background:#fafafa;">Dias de Falta</th>
                <td style="font-size:13px; font-weight:700; color:<?= $link['faults_count'] > 0 ? '#c0392b' : '#27ae60' ?>;">
                    <?= $link['faults_count'] ?>
                    <?php if ($link['faults_count'] > 0): ?>
                    <span style="font-size:10px; font-weight:400; color:#c0392b;">(<?= $absence_rate ?>%)</span>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="font-size:9px; color:#888; margin:0 0 8px;">* Cada falta corresponde a um dia letivo completo, independente do número de horários.</p>

    <!-- Registro de Faltas -->
    <?php if (!empty($link['faults'])): ?>
    <table class="table table-bordered table-striped" style="font-size:11px; margin-bottom:14px;">
        <thead>
            <tr style="background:#fdf3f0;">
                <th colspan="2" style="font-size:10px; text-transform:uppercase; letter-spacing:.05em; color:#c0392b;">
                    Registro de Faltas (<?= $link['faults_count'] ?>)
                </th>
            </tr>
            <tr style="font-size:10px;">
                <th style="width:20%;">Data</th>
                <th>Justificativa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($link['faults'] as $fault): ?>
            <tr>
                <td><?= sprintf('%02d/%s/%d', $fault['day'], $months[$fault['month']] ?? $fault['month'], $fault['year']) ?></td>
                <td><?= CHtml::encode($fault['justification'] ?: '—') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="text-align:center; padding:8px; color:#27ae60; font-size:11px; border:1px solid #d5f5e3; background:#eafaf1; border-radius:3px; margin-bottom:14px;">
        Nenhuma falta registrada.
    </div>
    <?php endif; ?>

    <!-- Assinaturas -->
    <div style="margin-top:30px; page-break-inside:avoid;">
        <table style="width:100%; border-collapse:collapse; font-size:11px;">
            <tr>
                <td style="width:35%; text-align:center; padding-top:30px; border-top:1px solid #555;">
                    Professor(a)<br>
                    <strong><?= CHtml::encode($instructor->name) ?></strong>
                </td>
                <td style="width:30%;"></td>
                <td style="width:35%; text-align:center; padding-top:30px; border-top:1px solid #555;">
                    Responsável / Direção
                </td>
            </tr>
        </table>
    </div>

    <p style="text-align:right; font-size:9px; color:#bbb; margin-top:10px;">
        Emitido em: <?= date('d/m/Y H:i') ?>
    </p>

    <?php $this->renderPartial('//reports/footer'); ?>
</div>

<?php endforeach; // links do ano ?>
<?php endforeach; // anos ?>

<style>
    @media print {
        .hidden-print { display: none !important; }
        @page { size: A4 portrait; margin: 1cm; }
        .pageA4H { page-break-after: always; }
        th, td, div[style*="background:#3f45ea"] {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
    .pageA4H { padding: 14px 18px; }
</style>

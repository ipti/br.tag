<?php
/**
 * Ficha Imprimível Sumarizada — Todos os Vínculos de um Ano Letivo
 *
 * @var $this          InstructorController
 * @var $instructor    InstructorIdentification
 * @var $instructorDoc InstructorDocumentsAndAddress
 * @var $teachingHistory array  enriched with classes_by_discipline[], faults[]
 * @var $year          int      The academic year
 */

$this->pageTitle = 'TAG - Resumo do Ano Letivo ' . $year . ' — ' . $instructor->name;

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

$grandTotalGiven = 0;
$grandTotalSchedules = 0;
$grandTotalFaults = 0;

foreach ($teachingHistory as $link) {
    $grandTotalGiven += $link['classes_given'];
    $grandTotalSchedules += $link['total_schedules'];
    $grandTotalFaults += $link['faults_count'];
}

$grandAbsenceRate = $grandTotalGiven > 0 
    ? round(($grandTotalFaults / $grandTotalGiven) * 100, 1) 
    : 0;
?>

<!-- Botões de ação (ocultos na impressão) -->
<div class="row-fluid hidden-print" style="padding:12px 20px; border-bottom:1px solid #eee; margin-bottom:0;">
    <a onclick="window.print()" class="btn btn-icon glyphicons print" style="padding:10px;">
        <img alt="impressora" src="<?= Yii::app()->theme->baseUrl ?>/img/Impressora.svg" class="img_cards" />
        Imprimir Resumo do Ano <i></i>
    </a>
    <a href="javascript:history.back()" class="btn btn-icon glyphicons circle_arrow_left" style="padding:10px; margin-left:8px;">
        Voltar <i></i>
    </a>
    <span style="margin-left:16px; font-size:12px; color:#888; vertical-align:middle;">
        <?= count($teachingHistory) ?> vínculo(s) no ano letivo de <?= CHtml::encode($year) ?>
    </span>
</div>

<div class="pageA4H">

    <?php $this->renderPartial('//reports/head'); ?>

    <!-- Título do Resumo do Ano -->
    <div style="background:#3f45ea; color:#fff; padding:7px 14px; margin:10px 0 8px; border-radius:3px; display:flex; align-items:center; justify-content:space-between;">
        <strong style="font-size:14px; letter-spacing:.03em;">Resumo de Vínculos — Ano Letivo: <?= CHtml::encode($year) ?></strong>
        <span style="font-size:11px; opacity:.85;"><?= count($teachingHistory) ?> Vínculo(s)</span>
    </div>

    <!-- Dados do Professor -->
    <table class="table table-bordered" style="font-size:11.5px; margin-bottom:14px;">
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

    <!-- Vínculos do Ano -->
    <?php if (empty($teachingHistory)): ?>
        <p style="text-align:center; padding:20px; color:#666;">Nenhum vínculo encontrado neste ano letivo.</p>
    <?php else: ?>
        <h4 style="font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea; margin-bottom:6px; margin-top:16px;">
            Todos os Vínculos (<?= count($teachingHistory) ?>)
        </h4>

        <table class="table table-bordered table-striped" style="font-size:10.5px; margin-bottom:14px;">
            <thead>
                <tr style="background:#f5f7f9; font-size:10px; color:#5F738C;">
                    <th style="width:22%;">Escola</th>
                    <th style="width:18%;">Turma (Etapa)</th>
                    <th style="width:15%;">Período / Função</th>
                    <th style="width:15%;">Contrato</th>
                    <th style="width:10%; text-align:center;">Faltas</th>
                    <th style="width:20%; text-align:center;">Aulas Ministradas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachingHistory as $link): ?>
                <tr>
                    <td style="word-wrap:break-word;">
                        <strong><?= CHtml::encode($link['school_name'] ?: '—') ?></strong><br>
                        <span style="color:#777; font-size:9.5px;">INEP: <?= CHtml::encode($link['school_inep_id_fk']) ?></span>
                    </td>
                    <td style="word-wrap:break-word;">
                        <?= CHtml::encode($link['classroom_name'] ?: '—') ?><br>
                        <span style="color:#777; font-size:9.5px;"><?= CHtml::encode($link['stage_name'] ?: '—') ?></span>
                    </td>
                    <td>
                        <?= CHtml::encode($periods[$link['classroom_period']] ?? '—') ?><br>
                        <span style="color:#777; font-size:9.5px;"><?= CHtml::encode($roles[$link['role']] ?? '—') ?></span>
                    </td>
                    <td>
                        <?= CHtml::encode($contracts[$link['contract_type']] ?? '—') ?>
                    </td>
                    <td style="text-align:center;">
                        <span style="color:<?= $link['faults_count'] > 0 ? '#c0392b' : '#27ae60' ?>; font-weight:700;">
                            <?= $link['faults_count'] ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:700; color:#3f45ea; font-size:12px;"><?= $link['classes_given'] ?></span>
                        <span style="color:#888; font-size:10px;">/ <?= $link['total_schedules'] ?> prev.</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Resumo Geral de Frequência do Ano -->
        <table class="table table-bordered" style="font-size:11.5px; margin-bottom:20px; border-left:3px solid #3f45ea;">
            <tbody>
                <tr style="background:#eaeaf8;">
                    <th colspan="6" style="font-size:10.5px; text-transform:uppercase; letter-spacing:.05em; color:#3f45ea;">
                        Resumo Consolidado do Ano Letivo (Todos os Vínculos)
                    </th>
                </tr>
                <tr style="background:#fafafa; text-align:center;">
                    <th style="width:16%;">Total de Vínculos</th>
                    <td style="width:16%; font-size:14px; font-weight:700; color:#555;"><?= count($teachingHistory) ?></td>

                    <th style="width:18%;">Aulas Ministradas</th>
                    <td style="width:16%; font-size:14px; font-weight:700; color:#3f45ea;"><?= $grandTotalGiven ?></td>

                    <th style="width:16%;">Total de Faltas</th>
                    <td style="width:18%; font-size:14px; font-weight:700; color:<?= $grandTotalFaults > 0 ? '#c0392b' : '#27ae60' ?>;">
                        <?= $grandTotalFaults ?>
                        <?php if ($grandTotalFaults > 0): ?>
                        <span style="font-size:10.5px; font-weight:400; color:#c0392b;">(<?= $grandAbsenceRate ?>%)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Detalhamento de Faltas (se houver e para todos os vínculos, sumarizado) -->
        <?php 
           $allFaults = [];
           foreach ($teachingHistory as $link) {
               if(!empty($link['faults'])){
                   foreach($link['faults'] as $f){
                       $f['school_name'] = $link['school_name'];
                       $f['classroom_name'] = $link['classroom_name'];
                       $allFaults[] = $f;
                   }
               }
           }
           
           // Ordenando todas as faltas cronologicamente
           usort($allFaults, function($a, $b) {
               if ($a['year'] != $b['year']) return $a['year'] <=> $b['year'];
               if ($a['month'] != $b['month']) return $a['month'] <=> $b['month'];
               return $a['day'] <=> $b['day'];
           });
        ?>

        <?php if (!empty($allFaults)): ?>
        <table class="table table-bordered table-striped" style="font-size:10px; margin-bottom:14px; width:70%;">
            <thead>
                <tr style="background:#fdf3f0;">
                    <th colspan="3" style="font-size:9.5px; text-transform:uppercase; letter-spacing:.05em; color:#c0392b;">
                        Detalhamento de Faltas do Ano (<?= count($allFaults) ?>)
                    </th>
                </tr>
                <tr style="font-size:9.5px; color:#5F738C;">
                    <th style="width:15%;">Data</th>
                    <th style="width:40%;">Vínculo (Escola / Turma)</th>
                    <th style="width:45%;">Justificativa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allFaults as $fault): ?>
                <tr>
                    <td><?= sprintf('%02d/%s/%d', $fault['day'], $months[$fault['month']] ?? $fault['month'], $fault['year']) ?></td>
                    <td>
                        <?= CHtml::encode($fault['school_name']) ?> <br>
                        <span style="color:#888;"><?= CHtml::encode($fault['classroom_name']) ?></span>
                    </td>
                    <td style="color:#555;"><?= CHtml::encode($fault['justification'] ?: '—') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

    <?php endif; ?>

    <!-- Assinaturas -->
    <div style="margin-top:40px; page-break-inside:avoid;">
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

    <p style="text-align:right; font-size:9px; color:#bbb; margin-top:20px;">
        Emitido em: <?= date('d/m/Y H:i') ?>
    </p>

    <?php $this->renderPartial('//reports/footer'); ?>
</div>

<style>
    @media print {
        .hidden-print { display: none !important; }
        .pageA4H { margin: 0; border: none; padding: 0; box-shadow: none; }
        body { background: #fff !important; }
        /* Força a cor de fundo em table-striped e th em alguns navegadores (Chrome) */
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>

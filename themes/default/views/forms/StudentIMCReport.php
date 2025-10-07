<?php
/* @var $this ReportsController */
/* @var $report Mixed */
/* @var $school SchoolIdentification */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile("$baseUrl/sass/css/main.css?v=" . TAG_VERSION);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

/**
 * Função auxiliar para converter o código da série/etapa
 */
function getStageName($stageCode)
{
    $map = [
        '1' => 'CRECHE', '2' => 'PRÉ-ESCOLA', '3' => 'INFANTIL - UNIFICADA',
        '4' => '1ª SÉRIE', '5' => '2ª SÉRIE', '6' => '3ª SÉRIE', '7' => '4ª SÉRIE',
        '8' => '5ª SÉRIE', '9' => '6ª SÉRIE', '10' => '7ª SÉRIE', '11' => '8ª SÉRIE',
        '14' => '1º ANO', '15' => '2º ANO', '16' => '3º ANO', '17' => '4º ANO', '18' => '5º ANO',
        '19' => '6º ANO', '20' => '7º ANO', '21' => '8º ANO', '41' => '9º ANO',
        '25' => '1ª SÉRIE', '30' => '1ª SÉRIE', '35' => '1ª SÉRIE',
        '26' => '2ª SÉRIE', '31' => '2ª SÉRIE', '36' => '2ª SÉRIE',
        '27' => '3ª SÉRIE', '32' => '3ª SÉRIE', '37' => '3ª SÉRIE',
        '28' => '4ª SÉRIE', '33' => '4ª SÉRIE', '38' => '4ª SÉRIE'
    ];
    return $map[$stageCode] ?? '';
}

function getTurnName($turn)
{
    return [
        'M' => 'MANHÃ',
        'T' => 'TARDE',
        'N' => 'NOITE'
    ][$turn] ?? '';
}
?>

<div class="row-fluid hidden-print">
    <div class="span6">
        <div class="buttons" style="width: auto;">
            <a id="print" onclick="window.print()" class="t-button-secondary hidden-print" style="padding: 10px;">
                <span class="t-icon-printer"></span>
                <?= Yii::t('default', 'Print') ?>
            </a>
        </div>
    </div>
</div>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>

    <h3>RELATÓRIO ACOMPANHAMENTO DE SAÚDE</h3>

    <!-- Cabeçalho da escola -->
    <table class="table table-studentimc">
        <tr>
            <td colspan="3">ESCOLA: <?= CHtml::encode($response["school"]->name) ?></td>
            <td>CÓDIGO: <?= CHtml::encode($response["classroom"]->school_inep_fk) ?></td>
        </tr>
        <tr>
            <td>ENDEREÇO:
                <?= CHtml::encode($response["school"]->address) ?>
                <?= !empty($response["school"]->address_number) ? ', ' . CHtml::encode($response["school"]->address_number) : '' ?>
            </td>
            <td>TURNO: <?= getTurnName($response["classroom"]->turn) ?></td>
            <td>ANO: <?= getStageName($response["classroom"]->edcenso_stage_vs_modality_fk) ?></td>
            <td>TURMA: <?= CHtml::encode($response["classroom"]->name) ?></td>
        </tr>
    </table>

    <!-- Lista de alunos -->
    <?php foreach ($response["students"] as $student): ?>
        <div class="student-list">
            <div class="student-info">
                <div>ALUNO: <?= CHtml::encode($student["studentIdentification"]->name) ?></div>
                <div>IDADE: <?= CHtml::encode($student["age"]) ?></div>
                <div>MATRÍCULA: <?= CHtml::encode($student["studentEnrollment"]->id) ?></div>
            </div>

            <?php if (!empty($student["variationRate"])): ?>
                <div class="student-info">
                    TAXA DE VARIAÇÃO: <?= CHtml::encode($student["variationRate"]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tabela de IMC -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>IMC</th>
                    <th>ALTURA (m)</th>
                    <th>PESO (kg)</th>
                    <th>DATA DE COLETA</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($student["studentIMC"] as $imc): ?>
                    <tr>
                        <td><?= CHtml::encode($imc->IMC) ?></td>
                        <td><?= CHtml::encode($imc->height) ?></td>
                        <td><?= CHtml::encode($imc->weight) ?></td>
                        <td><?= date('d/m/Y', strtotime($imc->created_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>

<!-- Estilos -->
<style>
    .table-striped, .table-striped td, .table-striped th {
        border-color: #000 !important;
        font-size: 9px !important;
    }

    .table-studentimc {
        font-size: 10px;
        width: 100%;
        table-layout: fixed;
    }

    .student-list {
        margin-top: 30px;
        padding: 4px;
    }

    .student-info {
        display: flex;
        gap: 20px;
        font-size: 10px;
        flex-wrap: wrap;
    }

    h3 {
        text-align: center;
    }

    table {
        page-break-after: auto;
        border-collapse: collapse;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    @media print {
        .hidden-print {
            display: none !important;
        }
    }
</style>

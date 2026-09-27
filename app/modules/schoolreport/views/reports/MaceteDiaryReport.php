<?php
/**
 * @var ReportsController $this ReportsController
 * @var array $days
 * @var int $totalScheduled
 * @var int $totalRegistered
 * @var string $instructorName
 * @var string $classroomName
 * @var string $month
 * @var int $year
 */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<style>
    th, td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    @page {
        size: landscape;
    }
    @media print {
        #print {
            display: none;
        }
    }
</style>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
    <h3 id="report-title">Diário do MACETE</h3>
    <div class="row-fluid hidden-print">
        <div class="buttons">
            <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;">
                <img alt="impressora"
                     src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg"
                     class="print hidden-print"/>
                <?php echo Yii::t('default', 'Print') ?>
                <i></i>
            </a>
        </div>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="tableHead"><b>Turma: </b><?= $classroomName ?></th>
                <th><b>Mês/Ano: </b><?= $month ?>/<?= $year ?></th>
            </tr>
            <tr>
                <th class="tableHead" colspan="2"><b>Professor: </b><?= $instructorName ?></th>
            </tr>
            <tr>
                <th class="tableHead"><b>Total de dias letivos (Calendário Escolar): </b><?= $totalScheduled ?></th>
                <th><b>Total de aulas registradas no MACETE: </b><?= $totalRegistered ?></th>
            </tr>
        </thead>
    </table>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Dia</th>
                <th>Situação</th>
                <th>Plano usado</th>
                <th>Conteúdo executado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($days)): ?>
                <tr>
                    <td colspan="4">Nenhuma aula prevista no quadro de horário para o período selecionado.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($days as $day): ?>
                <?php if (empty($day['records'])): ?>
                    <tr>
                        <td><?= $day['day'] ?>/<?= $month ?></td>
                        <td><?= $day['registered'] ? 'Registrada' : 'Pendente' ?></td>
                        <td>—</td>
                        <td>—</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($day['records'] as $record): ?>
                        <tr>
                            <td><?= $day['day'] ?>/<?= $month ?></td>
                            <td><?= $day['registered'] ? 'Registrada' : 'Pendente' ?></td>
                            <td><?= CHtml::encode($record['plan']) ?></td>
                            <td style="text-align: left !important;"><?= CHtml::encode(mb_substr(strip_tags((string) $record['content']), 0, 300)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="signaturesContainer">
        <div class="signature">PROFESSOR(A)</div>
        <div class="signature">COORDENADOR(A)</div>
    </div>
    <div id="rodape"><?php $this->renderPartial('footer'); ?></div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
<style>
    td {
        padding: 8px !important;
    }
    h3 {
        text-align: center;
    }
    .tableHead {
        width: 50%;
    }
</style>

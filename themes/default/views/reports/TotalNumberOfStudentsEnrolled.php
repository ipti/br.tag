<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H" style="width: 1080px;">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'TOTAL DE ALUNOS MATRICULADOS POR ESCOLA'); ?></h3>
    <h3><?php echo $header ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-striped" aria-labelledby="total enrollments">
        <thead>
            <th scope="col">UNIDADE ESCOLAR</th>
            <th scope="col">TURMAS</th>
            <th scope="col">ALUNOS</th>
        </thead>
        <tbody>
            <?php 
            $countTotalClass = 0;
            $countTotalEnrollments = 0;
            foreach($report as $r) { ?>
                <tr>
                    <td><?= $r['school_name'] ?></td>
                    <td><?= $r['count_class'] ?></td>
                    <td><?= $r['count_enrollments'] ?></td>
                </tr>
            <?php
            $countTotalClass += $r['count_class'];
            $countTotalEnrollments += $r['count_enrollments'];
            }
            ?>
            <tr>
                <td style="text-align: center;"><b>TOTAL</b></td>
                <td><b><?= $countTotalClass ?></b></td>
                <td><b><?= $countTotalEnrollments?></b></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>

<style>
    table thead th {
        vertical-align: middle !important;
        text-align: center !important;
    }

    @media print {
        .hidden-print {
            display: none;
        }
        @page {
            size: landscape;
        }
        table {
            page-break-inside: auto;
        }
    }

</style>
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
    <h3><?php echo Yii::t('default', $title); ?></h3>
    <h3><?php echo $header ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-striped" aria-labelledby="cns students">
        <thead>
            <th>ORDEM</th>
            <?php if($allSchools) {echo "<th scope='col'>ESCOLA</th>";}else if($allClassrooms) {echo "<th scope='col'>TURMA</th>";}else {echo "";}?>
            <th>NOME</th>
            <th>D.N</th>
            <th>CPF</th>
            <th>RG</th>
            <th>NIS</th>
            <th>RESPONSÁVEL</th>
            <th>TEL. RESPONSÁVEL</th>
        </thead>
        <tbody>
            <?php
            $ordem = 1;
            foreach($report as $r) { 
            $ordemStr = $ordem < 10 ? "0".$ordem : $ordem;
            ?>
                <tr>
                    <td><?= $ordemStr ?></td>
                    <?php if($allSchools) {echo "<td>".$r['school_name']."</td>";}else if($allClassrooms) {echo "<td>".$r['classroom_name']."</td>";}else {echo "";}?>
                    <td><?= $r['name'] ?></td>
                    <td><?= $r['birthday'] ?></td>
                    <td><?= $r['cpf'] ?></td>
                    <td><?= $r['rg_number'] ?></td>
                    <td><?= $r['nis'] ?></td>
                    <td><?= $r['responsable_name'] ?></td>
                    <td><?= $r['responsable_telephone'] ?></td>
                </tr>
            <?php 
            $ordem++;
            }
            ?>
            <tr>
                <td></td>
                <?php if($allSchools) {echo "<td></td>";}else if($allClassrooms) {echo "<td></td>";}else {echo "";}?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>TOTAL:<?php echo " ".count($report)?></b></td>
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
<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ReportOfStudentsBenefitingFromTheBF/_initialization.js', CClientScript::POS_END);

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
            <?php echo $allSchools ? "<th scope='col'>ESCOLA</th>" : "<th scope='col'>TURMA</th>"?>
            <th>NOME</th>
            <th>D.N</th>
            <th>CNS</th>
            <th>RESPONSÁVEL</th>
            <th>TEL. RESPONSÁVEL</th>
        </thead>
        <tbody>
            <?php foreach($report as $r) { ?>
                <tr>
                    <?php echo $allSchools ? "<td>".$r['school_name']."</td>" : "<td>".$r['classroom_name']."</td>"?>
                    <td><?= $r['name'] ?></td>
                    <td><?= date('d/m/Y', strtotime($r['birthday'])); ?></td>
                    <td><?= $r['cns'] ?></td>
                    <td><?= $r['responsable_name'] ?></td>
                    <td><?= $r['responsable_telephone'] ?></td>
                </tr>
            <?php } 
            if($countTotal) {
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php echo $allSchools ? "<td></td>" : ""?>
                <td><b>TOTAL:<?php echo " ".count($report)?></b></td>
            </tr>
            <?php }?>
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
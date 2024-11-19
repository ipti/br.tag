<?php
/**
 * @var ReportsController $this ReportsController
 * @var EdcensoStageVsModality[] $stages List Of stages
 *
*/
Yii::app()->clientScript->registerCoreScript('jquery');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ClassContentsReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/reports/ClassContentsReport/functions.js?v='.TAG_VERSION, CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<style>
    th, td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    /* Landscape orientation */
    @page{
        size: landscape;
    }
    /* Hidden the print button */
    @media print {
        #print {
            display: none;
    }
}
</style>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3 id="report-title">Aulas Ministradas</h3>
    <div class="row-fluid hidden-print">
        <div class="buttons">
            <a  id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;">
                <img    alt="impressora"
                        src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg"
                        class="print hidden-print"/>
                <?php echo Yii::t('default', 'Print') ?>
                <i></i>
            </a>
        </div>
    </div>
    <table class="tag-table-report table-bordered">
        <tr>
            <th><b>Turma: </b><?= $classroomName ?></th>
            <th><b>Mês/Ano: </b><?= $month ?>/<?= $year ?></th>
        </tr>
        <tr>
            <th><b>Professor: </b><?= $instructorName ?></th>
            <th><b>Eixo/Disciplina: </b></th>
        </tr>
        <tr>
            <th><b>Total de aulas ministradas do mês: </b><?= $totalClassContents ?></th>
            <th><b>Total de carga horária do mês: </b><?= $totalClasses ?></th>
        </tr>
    </table>

    <?php
    $html = "";
    foreach ($classContents as $day) {
        $html += "<table class='tag-table-report table-bordered'>";
    }
    ?>
    <div id="rodape"><?php $this->renderPartial('footer'); ?></div>
</div>
<script>
    function imprimirPagina() {
        // printButton = document.getElementsByClassName('span12');
        // printButton.style.visibility = 'hidden';
        window.print();
        // printButton.style.visibility = 'visible';
    }
</script>

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

<div class="pageA4V">
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
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th id="classroom" class="tableHead"><b>Turma: </b><?= $classroomName ?></th>
                <th id="date"><b>Mês/Ano: </b><?= $month ?>/<?= $year ?></th>
            </tr>
            <tr>
                <th id="instructor" class="tableHead"><b>Professor: </b><?= $instructorName ?></th>
                <th id="discipline"><b>Eixo/Disciplina: </b></th>
            </tr>
            <tr>
                <th id="totalClassContents" class="tableHead"><b>Total de aulas ministradas do mês: </b><?= $totalClassContents ?></th>
                <th id="totalClasses"><b>Total de carga horária do mês: </b><?= $totalClasses ?></th>
            </tr>
        </thead>
    </table>

    <?php
    $html = "";
    foreach ($classContents as $id => $day) {
        $html .= "<table class='table table-bordered table-striped'>";
        $html .= "
        <thead>
            <tr>
                <th colspan='2' style='padding: 4px 10px'>{$id}/{$month}</th>
            </tr>
        </thead>";
        $html .= "<tbody>";
        foreach ($day["contents"] as $content) {
            $html .= "
            <tr>
                <td colspan='2'>
                    <b>Aula: </b>{$content['order']}
                    <b> Plano de aula: </b>{$content['name']}
                    <b> Conteúdo: </b>{$content['content']} </br>";
                    foreach ($content["abilities"] as $ability) {
                        $html .= "
                        <b>{$ability['code']}: </b>{$ability['description']} </br>
                        ";
                    }
            $html .= "
                </td>
            </tr>
            ";
        }
        $html .= "</tbody>";
        $html .= "
        <thead>
            <tr>
                <th colspan='2'>OBSERVAÇÕES DE AULA</th>
            </tr>
        </thead>";
        $html .= "
        <tbody>
            <tr>
                <td colspan='2'>{$day["diary"]}</td>
            </tr>
        </tbody>
        ";
        $html .= "
        <thead>
            <tr>
                <th colspan='2'>OBSERVAÇÕES DE AULA POR ALUNO</th>
            </tr>
        </thead>";
        $html .= "<tbody>";
        foreach ($day["students"][0] as $student) {
            if($student["diary"]) {
                $html .= "
                <tr>
                    <td class='studentName'>
                        <b>{$student['name']}</b>
                    </td>
                    <td class='studentDiary'>
                        {$student['diary']}
                    </td>
                </tr>
                ";
            }
        }
        $html .= "</tbody>";
        $html .= "</table>";
    }
    echo $html;
    ?>
    <div class="signaturesContainer">
        <div class="signature">PROFESSOR</div>
        <div class="signature">COORDENADOR</div>
    </div>
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
<style>
    td {
        text-align: left !important;
        padding: 8px !important;
    }
    th {
        text-align: left !important;
    }
    h3 {
        text-align: center;
    }
    .studentName {
        width: 30%;
    }
    .tableHead {
        width: 50%;
    }
    .signaturesContainer {
        display: flex;
        justify-content: space-between;
        text-align: center;
        margin-top: 60px;
        padding: 0px 50px;
    }
    .signature {
        border-top: 2px solid #000000;
        font-weight: bold;
        width: 40%;
    }

    @media print {
        @page {
            size: portrait;
        }
    }
</style>

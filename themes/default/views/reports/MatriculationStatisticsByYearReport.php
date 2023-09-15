<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/NumberStudentsPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Matrículas Atuais'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <div>
        <table class="table table-bordered table-striped">

            <tr><th>Ordem</th><th>Cód.&nbsp;da Turma</th><th>Nome da Turma</th><th>Horário de Funcionamento</th><th>Tipo de Atendimento</th><th>Modalidade</th><th>Etapa</th><th>Nº&nbsp;de Alunos</th></tr>
            <?php
            $html = "";
            $count_students = 0;
            $i = 0;
            foreach ($report as $r) {
                $i++;
                $html .= "<tr>"
                    . "<td>" . $i . "</td>"
                    . "<td>" . $r["id"] . "</td>"
                    . "<td>" . $r["name"] . "</td>"
                    . "<td>" . $r["time"] . "</td>"
                    . "<td>" . $r["assistance_type"] . "</td>"
                    . "<td>" . $r["modality"] . "</td>"
                    . "<td>" . $r["stage"] . "</td>"
                    . "<td>" . $r["students"] . "</td>"
                    . "</tr>";
                $count_students += intval($r["students"]);
            }
            echo $html;
            echo
                "<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style='font-weight:bold;text-align: right;'>TOTAL:</td>
                <td style='font-weight:bold;'>".$count_students."</td>
            </tr>"
            ?>
            </tbody>
        </table>
    </div>
    <div id="rodape"><?php $this->renderPartial('footer'); ?></div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
<style>
    @media print {
        .hidden-print {
            display: none;
        }
        table { page-break-inside:auto;}
        tr    { page-break-inside:avoid; page-break-after:auto;}
        td    { page-break-inside:avoid; page-break-after:auto;}
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }
    @page {
        size: landscape;
    }
</style>
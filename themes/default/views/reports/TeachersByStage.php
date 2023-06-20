<?php
/* @var $this ReportsController */
/* @var $professor mixed */
/* @var $classroom mixed */


$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/TeachersBySchool/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Teachers By School'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>

    <?php
    $ordem = 1;


    if (count($report) == 0) {
        echo "<br><span class='alert alert-primary'>Não há etapas cadastradas.</span>";
    } else {
        foreach ($report as $r) {

            echo "<h5><b>Nome da etapa: </b>" . $r['stage']->name . "</h5><br>";

            $html = "";
            $html .= "<table class= 'table table-bordered table-striped' >";
            $html .= "<tr>"
                . "<th> <b>Ordem </b> </th>"
                . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
                . "<th> <b>Data de Nascimento </b></th>"
                . "<th> <b>Nome Completo </b></th>"
                . "<th> <b>Escolaridade </b></th>"
                . "</tr>";

            if (count($r["instructors"]) == 0) {
                echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; professores nessa etapa.</span>";
            } else {
                foreach ($r["instructors"] as $p) {
                    $html .= "<tr>"
                        . "<td>" . $ordem . "</td>"
                        . "<td>" . $p['inep_id'] . "</td>"
                        . "<td>" . $p['birthday_date'] . "</td>"
                        . "<td>" . $p['name'] . "</td>"
                        . "<td>" . ($p['scholarity'] == 1 ? "Fundamental Incompleto" : $p['scholarity'] == 2 ? "Fundamental Completo" :
                            $p['scholarity'] == 3 ? "Ensino M&eacute;dio � Normal/Magist&eacute;rio" : $p['scholarity'] == 4 ? "Ensino M&eacute;dio � Normal/Magist&eacute;rio Ind&iacute;gena" :
                                $p['scholarity'] == 5 ? "Ensino M&eacute;dio" : "Superior") . "</td>"

                        . "</tr>";

                    $ordem++;
                }
            }
            $html .= "<tr>"
                . "<td colspan= 5>" . " <b> Total de professores nessa etapa: </b>" . count($r["instructors"]) . "</td>"
                . "</tr>";

            $html .= "</table>" . "<br><br>";
            echo $html;
            $ordem = 1;
            $html = "";
        }
    }

    ?>
    <?php $this->renderPartial('footer'); ?>
</div>

<style>
    @media print {
        .hidden-print {
            display: none;
        }
        @page {
            size: landscape;
        }
    }
</style>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>

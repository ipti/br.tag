<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/DisciplineAndIntructorRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Discipline And Instructor Relation'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>

    <?php

    function isNotEmpty($var){
        return $var != NULL;
    }

    $ordem = 1;

    foreach($classroom as $c) {
        $html = "";

        echo "<b>Nome da turma: </b>" . $c['name'] . "<br>" ;
        echo "<b>C&oacute;digo da Turma: </b>" . $c['inep_id'] . "<br>";
        echo "<b>Etapa: </b>" . $c['stage'].  "<br>";
        echo "<b>Modalidade: </b>" . $c['modality'] .  "<br>";
        echo "<b>Hor&aacute;rio de Funcionamento: </b>" . $c['initial_hour'].":".$c['initial_minute'] . "-" . $c['final_hour'] .":" . $c['final_minute'] .  "<br>";
        echo "<b>Dias da Semana: </b>" . ($c['week_days_sunday'] == 1 ? "Domingo - " :"") . ($c['week_days_monday'] == 1? "Segunda - ":"") .
        ($c['week_days_tuesday'] == 1? "Terca - ":"") . ($c['week_days_wednesday'] == 1? "Quarta - ":"") .
        ($c['week_days_thursday'] == 1? "Quinta - ":"") . ($c['week_days_friday'] == 1? "Sexta - ":"" )  .
        ($c['week_days_saturday'] == 1? "Sabado ":"" ) .  "<br>";

        $html .= "<table class= 'table table-bordered table-striped' >";
            $html .= "<tr>"
                . "<th> <b>Ordem </b></th>"
                . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
                . "<th> <b>Data de Nascimento  </b></th>"
                . "<th> <b>Nome Completo  </b></th>"
                . "<th> <b>Escolaridade  </b></th>"
                . "<th> <b>Componente(s) curricular(es)/eixo(s) que leciona  </b></th>"
                . "</tr>";

            foreach($c["instructors"] as $instructor) {
                $html .= "<tr>"
                    . "<td rowspan= $contador>" . $ordem . "</td>"
                    . "<td rowspan= $contador>" . $instructor['inep_id']. "</td>"
                    . "<td rowspan= $contador>" . $instructor['birthday_date'] . "</td>"
                    . "<td rowspan= $contador>" . $instructor['name'] . "</td>"
                    . "<td rowspan= $contador>" . ($instructor['scholarity'] == 1 ? "Fundamental Incompleto" : $instructor['scholarity'] == 2 ? "Fundamental Completo" :
                        $instructor['scholarity'] == 3 ? "Ensino M&eacute;dio � Normal/Magist&eacute;rio" : $instructor['scholarity'] == 4 ? "Ensino M&eacute;dio � Normal/Magist&eacute;rio Ind�gena" :
                            $instructor['scholarity'] == 5 ? "Ensino M&eacute;dio" : "Superior") . "</td>"
                    . "<td>";
                foreach($instructor["disciplines"] as $discipline) {
                    $html .= $discipline["name"] . "<br>";
                }
                $html .= "</td></tr>";
            }


        $html .= "<tr>"
                . "<td colspan= 6>" . " <b> Total de docentes nessa turma: </b>" . count($c["instructors"]).
                    "</td>"
        . "</tr>";

        $html .= "</table>" . "<br>";
        echo $html;
        $ordem = 1;
        $html = "";
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
<?php
/* @var $this ReportsController */
/* @var $professor mixed*/
/* @var $classroom mixed*/


$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EducationalAssistantPerClassroomReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Educational Assistant Per Classroom'); ?></h3>


        <?php
        $ordem = 1;


        if (count($classroom) == 0) {
            echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
        } else {
            foreach ($classroom as $c) {
                @$stage = EdcensoStageVsModality::model()->findByPk($c->edcenso_stage_vs_modality_fk)->name;
                $total_docentes = 0;

                echo "<b>Nome da turma: </b>" . $c['name'] . "<br>";
                echo "<b>C&oacute;digo da Turma: </b>" . $c['inep_id'] . "<br>";
                echo "<b>Etapa: </b>" . $c['stage'] . "<br>";
                echo "<b>Modalidade: </b>" . $c['modality'] . "<br>";
                echo "<b>Hor&aacute;rio de Funcionamento: </b>" . $c['initial_hour'] . ":" . $c['initial_minute'] . "-" . $c['final_hour'] . ":" . $c['final_minute'] . "<br>";
                echo "<b>Dias da Semana: </b>" . ($c['week_days_sunday'] == 1 ? "Domingo - " : "") . ($c['week_days_monday'] == 1 ? "Segunda - " : "") .
                    ($c['week_days_tuesday'] == 1 ? "Terca - " : "") . ($c['week_days_wednesday'] == 1 ? "Quarta - " : "") .
                    ($c['week_days_thursday'] == 1 ? "Quinta - " : "") . ($c['week_days_friday'] == 1 ? "Sexta - " : "") .
                    ($c['week_days_saturday'] == 1 ? "Sabado " : "") . "<br>";

                $html = "";
                $html .= "<table class= 'table table-bordered table-striped' >";
                $html .= "<tr>"
                    . "<th> <b>Ordem </b> </th>"
                    . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
                    . "<th> <b>Data de Nascimento </b></th>"
                    . "<th> <b>Nome Completo </b></th>"
                    . "<th> <b>Escolaridade </b></th>"
                    . "</tr>";

                if(count($professor) ==  0){
                    echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; professores auxiliares nas turmas.</span>";
                }
                else {

                    foreach ($professor as $p) {
                       if ($p['classroomID'] == $c['id']) {
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
                            $total_docentes++;
                       }
                    }
                }
                $html .= "<tr>"
                    . "<td colspan= 4>" . " <b> Total de assistentes educacionais nessa turma: </b>" . $total_docentes .
                    "</td>"
                    . "</tr>";

                $html .= "</table>" . "<br>";
                echo $html;
                $ordem = 1;
                $html = "";
            }
        }

        ?>
</div>

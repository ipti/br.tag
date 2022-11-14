<?php

/* @var $this ReportsController */
/* @var $students mixed */
/* @var $classrooms mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsUsingSchoolTransportationRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

    <div class="pageA4H">

    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Students Using School Transportation Relation'); ?></h3>


<?php
        if(count($classrooms) == 0){
        echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";

        }else {
            foreach ($classrooms as $c) {
                echo "<div class='classroom-container'>";
                echo "<b><br> Nome da turma: </b>" . $c['name'] . "<br>";
                echo "<b>C&oacute;digo da Turma: </b>" . $c['inep_id'] . "<br>";
                echo "<b>Etapa: </b>" . $c['stage'] . "<br>";
                echo "<b>Modalidade: </b>" . $c['modality'] . "<br>";
                echo "<b>Hor&aacute;rio de Funcionamento: </b>" . $c['initial_hour'] . ":" . $c['initial_minute'] . "-" . $c['final_hour'] . ":" . $c['final_minute'] . "<br>";
                echo "<b>Dias da Semana: </b>" . ($c['week_days_sunday'] == 1 ? "Domingo - " : "") . ($c['week_days_monday'] == 1 ? "Segunda - " : "") .
                    ($c['week_days_tuesday'] == 1 ? "Terca - " : "") . ($c['week_days_wednesday'] == 1 ? "Quarta - " : "") .
                    ($c['week_days_thursday'] == 1 ? "Quinta - " : "") . ($c['week_days_friday'] == 1 ? "Sexta - " : "") .
                    ($c['week_days_saturday'] == 1 ? "Sabado " : "") . "<br>";

                $student_disabilities = array();
                $student_aid = array();
                $i = 0;
                $j = 0;
                $ordem = 1;

                $html = "";
                $html .= "<table class= table table-bordered table-striped >";
                $html .= "<br> <tr>"
                    . "<th> <b>Ordem </b> </th>"
                    . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
                    . "<th> <b>Data de Nascimento </b></th>"
                    . "<th> <b>Nome Completo </b></th>"
                    . "<th> <b> Zona de Residencia do Aluno</b></th>"
                    . "<th> <b> Poder Publico Responsavel pelo Transporte </b></th>"
                    . "<th> <b> Tipo do Veiculo </b></th>"
                    . "<th> <b> Endereço </b></th>"
                    . "<th> <b> Bairro </b></th>"
                    . "</tr>";
                echo $html;
                $html = "";
                foreach($students as $s) {
                    if ($s['classroom_fk'] == $c['id']) {
                        $transportes = "";

                        if ($s['vehicle_type_van'] == 1) {
                            $transportes .= "Rodovi&aacute;rio - Van/Kombi";
                        } else if ($s['vehicle_type_microbus'] == 1) {
                            $transportes .= "Rodovi&aacute;rio - Micro&ocirc;nibus";
                        } else if ($s['vehicle_type_bus'] == 1 || $s['public_transport'] == 1) {
                            $transportes .= "Rodovi&aacute;rio - &Ocirc;nibus";
                        } else if ($s['vehicle_type_bike'] == 1) {
                            $transportes .= "Rodovi&aacute;rio - Bicicleta";
                        } else if ($s['vehicle_type_animal_vehicle'] == 1) {
                            $transportes .= "Rodovi&aacute;rio - Tra&ccedil;&atilde;o Animal";
                        } else if ($s['vehicle_type_other_vehicle'] == 1) {
                            $transportes .= "Rodovi&aacute;rio - Outro ";
                        } else if ($s['vehicle_type_waterway_boat_5'] == 1) {
                            $transportes .= "Embarca&ccedil;&atilde;o - Capacidade de at&eacute; 5 Alunos";
                        } else if ($s['vehicle_type_waterway_boat_5_15'] == 1) {
                            $transportes .= "Embarca&ccedil;&atilde;o - Capacidade de 5 at&eacute; 15 Alunos";
                        } else if ($s['vehicle_type_waterway_boat_15_35'] == 1) {
                            $transportes .= "Embarca&ccedil;&atilde;o - Capacidade de 15 at&eacute; 35 Alunos";
                        } else if ($s['vehicle_type_waterway_boat_35'] == 1) {
                            $transportes .= "Embarca&ccedil;&atilde;o - Capacidade de at&eacute; 35 Alunos";
                        } else if ($s['vehicle_type_metro_or_train'] == 1) {
                            $transportes .= "Metr&ocirc;/Trem";
                        } else {
                            $transportes .= "N&atilde;o foi Informado";
                        }

                        $transportes .= ".";

                        $html .= "<tr>"
                            . "<td>" . $ordem . "</td>"
                            . "<td>" . $s['inep_id'] . "</td>"
                            . "<td>" . $s['birthday'] . "</td>"
                            . "<td>" . $s['name'] . "</td>"
                            . "<td>" . ($s['residence_zone'] == 1 ? "Urbana" : "Rural") . "</td>"
                            . "<td>" . ($s['transport_responsable_government'] == 1 ? "Estadual" : "Municipal") . "</td>"
                            . "<td>" . $transportes . "</td>"
                            . "<td>" . $s["address"] . "</td>"
                            . "<td>" . $s["neighborhood"] . "</td>";

                        $html .= "</tr>";
                        echo $html;
                        $ordem++;
                        $html = "";
                    }
                }
                $html .= "</table></div>";
                echo $html;
            }
        }

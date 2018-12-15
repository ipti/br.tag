<?php

/* @var $this ReportsController */
/* @var $students mixed */
/* @var $classrooms mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsWithDisabilitiesRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>


<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Educational Assistant Per Classroom'); ?></h3>

    <?php

if(count($classrooms) == 0){
    echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";

}else {
    foreach ($classrooms as $c) {
        echo "<div class='classroom-container'>";

        echo "<b>Nome da turma: </b>" . $c['name'] . "<br>";
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
        $html .= "<table class= 'table table-bordered table-striped'>";
        $html .= "<tr>"
            . "<th> <b>Ordem </b> </th>"
            . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
            . "<th> <b>Data de Nascimento </b></th>"
            . "<th> <b>Nome Completo </b></th>"
            . "<th> <b> Tipo de Defici&ecirc;ncia </b></th>"
            . "<th> <b> Recursos/aux&iacute;lios </b></th>"
            . "</tr>";
        echo $html;
        $html = "";

        foreach ($students as $s) {
            if ($s['classroom_fk'] == $c['id']) {

                if ($s['deficiency_type_blindness'] == 1) {
                    $student_disabilities[$i++] = "Cegueira";
                } else if ($s['deficiency_type_low_vision'] == 1) {
                    $student_disabilities[$i++] = "Baixa Vis&atilde;o";
                } else if ($s['deficiency_type_deafness'] == 1) {
                    $student_disabilities[$i++] = "Surdez";
                } else if ($s['deficiency_type_disability_hearing'] == 1) {
                    $student_disabilities[$i++] = "Defici&ecirc;ncia Auditiva";
                } else if ($s['deficiency_type_deafblindness'] == 1) {
                    $student_disabilities[$i++] = "Surdocegueira";
                } else if ($s['deficiency_type_phisical_disability'] == 1) {
                    $student_disabilities[$i++] = "Defici&ecirc;ncia F&iacute;sica";
                } else if ($s['deficiency_type_intelectual_disability'] == 1) {
                    $student_disabilities[$i++] = "Defici&ecirc;ncia Intelectual";
                } else if ($s['deficiency_type_multiple_disabilities'] == 1) {
                    $student_disabilities[$i++] = "Defici&ecirc;ncia M&uacute;ltipla";
                } else if ($s['deficiency_type_autism'] == 1) {
                    $student_disabilities[$i++] = "Autismo Infantil";
                } else if ($s['deficiency_type_aspenger_syndrome'] == 1) {
                    $student_disabilities[$i++] = " S&iacute;ndrome de Asperger";
                } else if ($s['deficiency_type_rett_syndrome'] == 1) {
                    $student_disabilities[$i++] = "S&iacute;ndrome de Rett";
                } else if ($s['deficiency_type_childhood_disintegrative_disorder'] == 1) {
                    $student_disabilities[$i++] = "Transtorno desintegrativo da inf&acirc;ncia";
                } else if ($s['deficiency_type_gifted'] == 1) {
                    $student_disabilities[$i++] = "Altas habilidades/Superdota&ccedil;&atilde;o";
                }

                if ($s['resource_none'] == 1) {
                    $student_aid[0] = "N&atilde;o h&aacute; aux&iacute;lio";
                }else if ($s['resource_none'] == null){
                        $student_aid[0] = "N&atilde;o informado";
                } else {
                    if ($s['resource_aid_lector'] == 1) {
                        $student_aid[$j++] = 'Aux�lio ledor';
                    } else if ($s['resource_aid_transcription'] == 1) {
                        $student_aid[$j++] = 'Aux�lio transcri��o';
                    } else if ($s['resource_interpreter_guide'] == 1) {
                        $student_aid[$j++] = 'Guia-Int�rprete';
                    } else if ($s['resource_interpreter_libras'] == 1) {
                        $student_aid[$j++] = 'Int�rprete de Libras';
                    } else if ($s['resource_lip_reading'] == 1) {
                        $student_aid[$j++] = 'Leitura Labial';
                    } else if ($s['resource_zoomed_test_16'] == 1) {
                        $student_aid[$j++] = 'Prova Ampliada (Fonte Tamanho 16)';
                    } else if ($s['resource_zoomed_test_20'] == 1) {
                        $student_aid[$j++] = 'Prova Ampliada (Fonte Tamanho 20)';
                    } else if ($s['resource_zoomed_test_24'] == 1) {
                        $student_aid[$j++] = 'Prova Ampliada (Fonte Tamanho 24)';
                    }
                    else if ($s['resource_braille_test'] == 1) {
                        $student_aid[$j++] = 'Prova em Braille';
                    }
                }

                $html .= "<tr>"
                    . "<td>" . $ordem . "</td>"
                    . "<td>" . $s['inep_id'] . "</td>"
                    . "<td>" . $s['birthday'] . "</td>"
                    . "<td>" . $s['name'] . "</td>";

                if (count($student_disabilities) == 1) {
                    $html .= "<td>" . $student_disabilities[0] . "</td>";
                }
                else{
                    $html .= "<td>";
                    for($a = 0 ; $a < count($student_disabilities); $a++ ){
                        $html .= $student_disabilities[$a] . " ,";
                    }
                    $html .= "</td>";
                }

                if (count($student_aid) == 1) {
                    $html .= "<td>" . $student_aid[0] . "</td>";
                }
                else{
                    $html .= "<td>";
                    for($a = 0 ; $a < count($student_aid); $a++ ){
                        $html .= $student_aid[$a ] . " ,";
                    }
                    $html .= "</td>";
                }
                $html .= "</tr>";

                echo $html;

                $i = 0;
                $j = 0;
                $ordem++;
            }
            $html = "";
        }
        $html .= "</table></div>";
        echo $html;
    }
}

    ?>
</div>
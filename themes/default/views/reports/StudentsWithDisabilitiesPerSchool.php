<?php

    /* @var $this ReportsController */
    /* @var $students mixed */
    /* @var $schools mixed */

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/reports/StudentsWithDisabilitiesPerSchool/_initialization.js', CClientScript::POS_END);
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Students With Disabilities Per School Relation'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <?php
    if (count($report) == 0) {
        echo "<br><span class='alert alert-primary'>Não há escolas cadastradas.</span>";
    } else {
        foreach ($report as $r) {
            echo "<div class='classroom-container'>";

            echo "<b>Nome da escola: </b>" . $r['school']->name . "<br>";
            echo "<b>ID INEP: </b>" . $r['school']->inep_id . "<br>";
            echo "<b>Endereço: </b>" . $r['school']->address . ', ' . $r['school']->address_number . ', ' . $r['school']->address_neighborhood . "<br>";
            echo "<b>Cidade: </b>" . $r['school']->edcensoCityFk->name . "<br>";
            echo "<b>Estado: </b>" . strtoupper($r['school']->edcensoUfFk->name) . "<br>";

            $student_disabilities = array();
            $student_aid = array();
            $i = 0;
            $j = 0;
            $ordem = 1;
            $total = 0;
    
    
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
            
            if (count($r["students"]) == 0) {
                echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; alunos com deficiência nessa escola.</span><br>";
            } else {
                foreach ($r["students"] as $s) {
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
                            $student_aid[$j++] = 'Auxílio ledor';
                        } else if ($s['resource_aid_transcription'] == 1) {
                            $student_aid[$j++] = 'Auxílio transcrição';
                        } else if ($s['resource_interpreter_guide'] == 1) {
                            $student_aid[$j++] = 'Guia-Intérprete';
                        } else if ($s['resource_interpreter_libras'] == 1) {
                            $student_aid[$j++] = 'Intérprete de Libras';
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
                    $total ++;
                    $ordem++;
                    $html = "";
                }
                $html .= "<tr>"
                . "<th><b>Total</b></th>" 
                . "<td colspan='5'>$total</td>"
                . "</tr>";
                
                $html .= "</table></div>";
                echo $html;
                $html = "";
            }
        }
    }
    ?>
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
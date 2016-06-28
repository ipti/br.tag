<?php

/* @var $this ReportsController */
/* @var $professor mixed*/
/* @var $classroom mixed*/


$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/DisciplineAndIntructorRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>


<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Discipline And Instructor Relation'); ?></h3>

        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?>
                <i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">


    <p> <b>Escola: </b><?= $school->inep_id ?>-<?= $school->name ?></p>
    <p> <b>Estado: </b><? echo $school->edcensoUfFk->name; ?></p>
    <p> <b>Munic&iacute;pio:</b> <? echo $school->edcensoCityFk->name; ?> </p>
    <p> <b>Localiza&ccedil;&atilde;o:</b> <? echo $school->location == 0? "Rural" : "Urbana" ?> </p>
    <p> <b>Depend&ecirc;ncia Administrativa:</b> <? echo $school->administrative_dependence == 1 ? "Federal" :
            $school->administrative_dependence == 2? "Estadual" :
                $school->administrative_dependence == 3? "Municipal": "Estadual"
        ?> </p>

    <?

    function isNotEmpty($var){
        return $var != NULL;
    }

    $ordem = 1;

    foreach($classroom as $c) {
        $html = "";

        $total_docentes = 0;

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
                . "<th> <b>Disciplina que leciona  </b></th>"
                . "</tr>";

            foreach($professor as $p) {
                if($p['classroom_inep_id'] == $c['inep_id']) {
                    $instructor = InstructorTeachingData::model()->findByPk($p['id']);

                    $disciplinas = array(
                        ($instructor->discipline1Fk->name),
                        ($instructor->discipline2Fk->name),
                        ($instructor->discipline3Fk->name),
                        ($instructor->discipline4Fk->name),
                        ($instructor->discipline5Fk->name),
                        ($instructor->discipline6Fk->name),
                        ($instructor->discipline7Fk->name),
                        ($instructor->discipline8Fk->name),
                        ($instructor->discipline9Fk->name),
                        ($instructor->discipline10Fk->name),
                        ($instructor->discipline11Fk->name),
                        ($instructor->discipline12Fk->name),
                        ($instructor->discipline13Fk->name)
                );
                $contador = count(array_filter($disciplinas,"isNotEmpty"));
                $disciplinas = array_filter($disciplinas,"isNotEmpty");

                   $html .= "<tr>"
                        . "<td rowspan= $contador>" . $ordem . "</td>"
                        . "<td rowspan= $contador>" . $p['inep_id']. "</td>"
                        . "<td rowspan= $contador>" . $p['birthday_date'] . "</td>"
                        . "<td rowspan= $contador>" . $p['name'] . "</td>"
                        . "<td rowspan= $contador>" . ($p['scholarity'] == 1 ? "Fundamental Incompleto" : $p['scholarity'] == 2 ? "Fundamental Completo" :
                           $p['scholarity'] == 3 ? "Ensino M&eacute;dio – Normal/Magist&eacute;rio" : $p['scholarity'] == 4 ? "Ensino M&eacute;dio – Normal/Magist&eacute;rio Indígena" :
                               $p['scholarity'] == 5 ? "Ensino M&eacute;dio" : "Superior") . "</td>"

                        . "<td>" . $disciplinas[0] . "</td>"
                        . "</tr>";

                    $html .= "<tr>"
                         ."<td>" . $disciplinas[1] . "</td>"
                        ."<td>" . $disciplinas[2] . "</td>"
                        ."<td>" . $disciplinas[3] . "</td>"
                        ."<td>" . $disciplinas[4] . "</td>"
                        ."<td>" . $disciplinas[5] . "</td>"
                        ."<td>" . $disciplinas[6] . "</td>"
                        ."<td>" . $disciplinas[7] . "</td>"
                        ."<td>" . $disciplinas[8] . "</td>"
                        ."<td>" . $disciplinas[9] . "</td>"
                        ."<td>" . $disciplinas[10] . "</td>"
                        ."<td>" . $disciplinas[11] . "</td>"
                        ."<td>" . $disciplinas[12] . "</td>"

                        . "</tr>";

                    $ordem++;
                    $total_docentes++;

               }
            }


        $html .= "<tr>"
                . "<td colspan= 4>" . " <b> Total de docentes nessa turma: </b>" . $total_docentes.
                    "</td>"
        . "</tr>";

        $html .= "</table>" . "<br>";
        echo $html;
        $ordem = 1;
        $html = "";
    }

    ?>
</div>
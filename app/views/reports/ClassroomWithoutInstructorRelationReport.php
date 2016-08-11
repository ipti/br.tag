<?php


/* @var $this ReportsController */
/* @var $professor mixed */
/* @var $classroom mixed */
/* @var &disciplina mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ClassroomWithoutInstructorRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Classroom Without Instructor Relation'); ?></h3>
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?>
                <i></i></a>
        </div>
    </div>
</div>

<div class="innerLR">
    <p> <b>Escola: </b><?php echo $school->inep_id ?>-<?php echo $school->name ?></p>
    <p> <b>Estado: </b><?php echo $school->edcensoUfFk->name; ?></p>
    <p> <b>Munic&iacute;pio:</b> <?php echo $school->edcensoCityFk->name; ?> </p>
    <p> <b>Localiza&ccedil;&atilde;o:</b> <?php echo $school->location == 0? "Rural" : "Urbana" ?> </p>
    <p> <b>Depend&ecirc;ncia Administrativa:</b> <?php echo $school->administrative_dependence == 1 ? "Federal" :
            $school->administrative_dependence == 2? "Estadual" :
                $school->administrative_dependence == 3? "Municipal": "Estadual"
        ?> </p>

    <?php


    if (count($classroom) == 0) {
        echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
    } else { ?>
        <table class="table-no-instructors table table-bordered table-striped">
            <tr>
                <th> Ordem</th>
                <th> Nome da Turma</th>
                <th> Etapa</th>
                <th> Disciplina(s)</th>
            </tr>

            <?php

            $ordem = 1;

            foreach ($classroom as $c) {

                $disciplinas = array();

                $count = 0;
                foreach ($disciplina as $d) {
                    if ($d[id] == $c[id]) {
                        $disciplinas[$count++] = $d['discipline_name'];
                    }
                }

                if (count($disciplinas) > 0) {
                    $html = "";
                    $html .= "<tr>"
                        . "<td>" . $ordem . "</td>"
                        . "<td>" . $c['name'] . "</td>"
                        . "<td>" . $c['stage'] . "</td>"
                        . "<td>";
                    $hasDiscipline = false;
                    for ($i = 0; $i < 13; $i++) {
                        if ($disciplinas[$i] != null) {
                            $hasDiscipline = true;
                            $html .= $disciplinas[$i] . ", ";
                        }
                    }
                    if ($hasDiscipline) {
                        $html = substr($html, 0, -2);
                    }
                    $html .= "</td></tr>";
                    $ordem++;
                    echo $html;
                }
            }
            ?>
        </table>
    <?php } ?>


</div>
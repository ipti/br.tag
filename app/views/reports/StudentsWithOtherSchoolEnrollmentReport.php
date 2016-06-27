<?php

/* @var $this ReportsController */
/* @var $students mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsWithOtherSchoolEnrollmentReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Students With Other School Enrollment'); ?></h3>

        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?>
                <i></i></a>
        </div>
    </div>
</div>

<div class="innerLR">
    <p> <b>Escola: </b> <?= $school->inep_id ?>-<?= $school->name ?></p>
    <p> <b>Estado: </b> <? echo $school->edcensoUfFk->name; ?></p>
    <p> <b>Munic&iacute;pio:</b> <? echo $school->edcensoCityFk->name; ?> </p>
    <p> <b>Localiza&ccedil;&atilde;o:</b> <? echo $school->location == 0? "Rural" : "Urbana" ?> </p>
    <p> <b>Depend&ecirc;ncia Administrativa:</b> <? echo $school->administrative_dependence == 1 ? "Federal" :
            $school->administrative_dependence == 2? "Estadual" :
                $school->administrative_dependence == 3? "Municipal": "Estadual"
        ?> </p>

    <table class= table table-bordered table-striped>
        <?
        $ordem = 1;
        $html = "";
        $html .= "<table class= table table-bordered table-striped>";
        $html .= "<tr>"
            . "<th> <b>Ordem </b> </th>"
            . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
            . "<th> <b>Data de Nascimento </b></th>"
            . "<th> <b>Nome Completo </b></th>"
            . "<th> <b> Id da Escola 1 </b></th>"
            . "<th> <b> Id da Turma 1 </b></th>"
            . "<th> <b> Nome da Turma 1 </b></th>"
            . "<th> <b> Horario da Turma 1  </b></th>"
            . "<th> <b> Dias da Semana da Turma 1  </b></th>"
            . "<th> <b> Id da Escola 2 </b></th>"
            . "<th> <b> Id da Turma 2 </b></th>"
            . "<th> <b> Nome da Turma 2 </b></th>"
            . "<th> <b> Horario da Turma 2  </b></th>"
            . "<th> <b> Dias da Semana da Turma 2  </b></th>"
            . "</tr>";
        echo $html;
        $html = "";

        foreach($students as $s){
            $html .= "<tr>"
                    . "<td>". $ordem ."</td>"
                    . "<td>". $s['student_id'] ."</td>"
                    . "<td>". $s['student_birthday'] ."</td>"
                    . "<td>". $s['student_name'] ."</td>"
                    . "<td>". $s['school1'] ."</td>"
                    . "<td>".  " " ."</td>"
                    . "<td>". " " ."</td>"

                    . "<td>". $s['classroom_id1'] ."</td>"
                    . "<td>". $s['class1Name'] ."</td>"
//                    . "<td>". $s['initial_hour'] .":".$s['initial_minute'] ." - ".$s['final_minute'] .":". $s['final_minute']   ."</td>"
//                    ."<td>" . ($s['week_days_sunday'] == 1 ? "Domingo - " :"") . ($s['week_days_monday'] == 1? "Segunda - ":"") .
//                ($s['week_days_tuesday'] == 1? "Terca - ":"") . ($s['week_days_wednesday'] == 1? "Quarta - ":"") .
//                ($s['week_days_thursday'] == 1? "Quinta - ":"") . ($s['week_days_friday'] == 1? "Sexta - ":"" )  .
//                ($s['week_days_saturday'] == 1? "Sabado ":"" )."</td>"

                    . "<td>". $s['school2'] ."</td>"
//                    . "<td>". $s['classroom_id2'] ."</td>"
//                    . "<td>". $s['classroom_id2'] ."</td>"
//                    . "<td>". $s['classroom_id2'] ."</td>"

                    . "</tr>";
            $ordem++;
        }
        echo $html;
        ?>
    </table>
</div>

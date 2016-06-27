<?php
/* @var $this ReportsController */
/* @var $role mixed */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/SchoolProfessionalNumberByClassroomReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'School Professional Number By Classroom'); ?></h3>

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

    <table class="table table-bordered table-striped">
        <tr>
            <th> <b>Ordem </b></th>
            <th> <b>Modalidade</b></th>
            <th> <b>Etapa </b></th>
            <th> <b>C&oacute;digo da Turma </b></th>
            <th> <b>Nome da Turma </b></th>
            <th> <b>Tipo de Atendimento </b></th>
            <th> <b>N&uacute;mero de Docentes </b></th>
            <th> <b>N&uacute;mero de Auxiliares/Assistentes Educacionais </b> </th>
            <th> <b>N&uacute;mero de Profissionais/Monitores </b></th>
            <th> <b>N&uacute;mero de Int&eacute;rprete de Libras </b></th>
        </tr>

        <?php

        $cargos = array(
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
        );

       $ordem = 1;

       foreach($classroom as $c){
           $cargos[0] = 0;
           $cargos[1] = 0;
           $cargos[2] = 0;
           $cargos[3] = 0;

           $classroom_inep_id = "";

            foreach($role as $r) {

              if($r['name'] == $c['name']) {
                    if ($r['role'] == '1') {
                        $cargos[0]++;
                    } else if ($r['role'] == '2') {
                        $cargos[1]++;
                    } else if ($r['role'] == '3') {
                        $cargos[2]++;
                    } else if ($r['role'] == '4') {
                        $cargos[3]++;
                    }
                    $classroom_inep_id = $r['classroom_inep_id'];
                }
            }

           $html = "";
           $html .= "<tr>"
               ."<td>" . $ordem . "</td>"
               ."<td>" . $c['modality']. "</td>"
               ."<td>" . $c['stage'] . "</td>"
               ."<td>" . $c['inep_id']. "</td>"
               ."<td>" . $c['name'] . "</td>"
               ."<td>" . $c['assistance_type'] . "</td>"
               ."<td>" . $cargos[0] . "</td>"
               ."<td>" . $cargos[1] . "</td>"
               ."<td>" . $cargos[2] . "</td>"
               ."<td>" . $cargos[3] . "</td>"

               . "</tr>";
           echo $html;
           $ordem++;
       }
        ?>

    </table>

</div>

<p class="info-issued">Gerado pelo Sistema de Informa&ccedil;&atilde;o TAG em: <?php echo date('d/m/Y H:i'); ?></p>

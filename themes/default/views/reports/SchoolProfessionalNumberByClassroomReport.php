<?php
/* @var $this ReportsController */
/* @var $role mixed */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/SchoolProfessionalNumberByClassroomReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'School Professional Number By Classroom'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <tr>
            <th> <b>Ordem </b></th>
            <th> <b>Modalidade</b></th>
            <th> <b>Etapa </b></th>
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


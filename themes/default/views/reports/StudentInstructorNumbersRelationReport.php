<?php
/* @var $this ReportsController */
/* @var $instructor mixed */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentInstructorNumbersRelationReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>


<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Student Instructor Numbers Relation') . ' - ' . Yii::app()->user->year; ?></h3>
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
            <th> <b>C&oacute;digo da Turma </b></th>
            <th> <b>Nome da Turma </b></th>
            <th> <b>Tipo de Atendimento </b></th>
            <th> <b> Hor&aacute;rio de Atendimento </b></th>
            <th> <b> Turma participante do Programa Mais Educa&ccedil;&atilde;o </b></th>
            <th> <b> N&uacute;mero de Alunos da turma </b></th>
            <th> <b>N&uacute;mero de Docentes da turma </b></th>
            <th> <b>N&uacute;mero de Auxiliares/Assistentes Educacionais </b> </th>
            <th> <b>N&uacute;mero de Profissionais/Monitores </b></th>
            <th> <b>N&uacute;mero de Int&eacute;rprete de Libras </b></th>
        </tr>

        <?php
        $ordem = 1;
        $html = "";

        if(count($classroom) == 0){
            echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";

        }else {

            foreach ($classroom as $c) {
                $cargos = array(
                    0 => 0,
                    1 => 0,
                    2 => 0,
                    3 => 0,
                );

                foreach ($instructor as $i) {
                    if ($i['classroomId'] == $c['id']) {
                        if ($i['role'] == '1') {
                            $cargos[0]++;
                        } else if ($i['role'] == '2') {
                            $cargos[1]++;
                        } else if ($i['role'] == '3') {
                            $cargos[2]++;
                        } else if ($i['role'] == '4') {
                            $cargos[3]++;
                        }
                    }
                }

                $html .= "<tr>"
                    . "<td>" . $ordem . "</td>"
                    . "<td>" . $c['modality'] . "</td>"
                    . "<td>" . $c['stage'] . "</td>"
                    . "<td>" . $c['inep_id'] . "</td>"
                    . "<td>" . $c['name'] . "</td>"
                    . "<td>" . $c['assistance_type'] . "</td>"
                    . "<td>" . $c['time'] . "</td>"
                    . "<td>" . ($c['mais_educacao_participator'] == null ? "Nao se Aplica" : "Outros") . "</td>"
                    . "<td>" . $c['students'] . "</td>"
                    . "<td>" . $cargos[0] . "</td>"
                    . "<td>" . $cargos[1] . "</td>"
                    . "<td>" . $cargos[2] . "</td>"
                    . "<td>" . $cargos[3] . "</td>"
                    . "</tr>";


                    $ordem++;
                }
                echo $html;
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

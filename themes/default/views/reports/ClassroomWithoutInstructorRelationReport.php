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


<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Classroom Without Instructor Relation'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>

    <?php


    if (count($classroom) == 0) {
        echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
    } else { ?>
        <table class="table-no-instructors table table-bordered table-striped">
            <tr>
                <th> Ordem</th>
                <th> Nome da Turma</th>
                <th> Etapa</th>
                <th> Componente(s) curricular(es)/eixo(s)</th>
            </tr>

            <?php

            $ordem = 1;

            foreach ($classroom as $c) {

                $disciplinas = array();

                $count = 0;
                foreach ($disciplina as $d) {
                    if ($d['id'] == $c['id']) {
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
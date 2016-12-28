<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/NumberStudentsPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Alunos por Turma'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>
        <?php $this->renderPartial('head'); ?>
        <table class="table table-bordered table-striped">
            <tr><th>Ordem</th><th>Cód.&nbsp;da Turma</th><th>Nome da Turma</th><th>Horário de Funcionamento</th><th>Tipo de Atendimento</th><th>Modalidade</th><th>Etapa</th><th>Nº&nbsp;de Alunos</th></tr>
            <?php
            $html = "";
            $i = 0;
            foreach ($report as $r) {
                $i++;
                $html .= "<tr>"
                        . "<td>" . $i . "</td>"
                        . "<td>" . $r["id"] . "</td>"
                        . "<td>" . $r["name"] . "</td>"
                        . "<td>" . $r["time"] . "</td>"
                        . "<td>" . $r["assistance_type"] . "</td>"
                        . "<td>" . $r["modality"] . "</td>"
                        . "<td>" . $r["stage"] . "</td>"
                        . "<td>" . $r["students"] . "</td>"
                        . "</tr>";
            }
            echo $html;
            ?>
            </tbody>
        </table>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>
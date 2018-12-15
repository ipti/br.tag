<?php

/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>



<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Alunos Participantes do Bola Familia'); ?></h3>
    <div>
        <table class="table table-bordered table-striped">
            <?php
            $html = "";
            foreach ($report as $name => $r) {
            }
            echo $html;
            ?>
        </table>
    </div>
<table class="table table-bordered table-striped" style="font-size: 11px">
    <tr>
        <th rowspan="" style="text-align: center;">Nº</th>
        <th rowspan="">ALUNO</th>
        <th rowspan="" style="text-align: center;">TURMA</th>
        <th rowspan="" style="text-align: center;">DATA DE NASCIMENTO</th>

        <?php
        //        for ($i = 1; $i <= 31; $i++) {
        //            echo '<td>'.$i.'</td>';
        //        }
        ?>
    </tr>
    <?php
    $rows = "";
    foreach ($report as $key=>$r){
        $rows .= "<tr>"
            . "<td style='text-align: center;'>" . ($key + 1) . "</td>"
            . "<td>" . $r['name'] . "</td>"
            . "<td>" . $r['turma'] . "</td>"
            . "<td style='text-align: center;'>" . $r['birthday'] . "</td>";
//        for ($i = 1; $i <= 31; $i++) {
//            $rows.= '<td>&nbsp;</td>';
//        }
        $rows .= "</tr>";
    }
    echo $rows;
    ?>
</table>
    <?php $this->renderPartial('footer'); ?>

</div>

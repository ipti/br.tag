<?php

/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/OutOfTownStudentsReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Out Of Town Students'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-striped" style="font-size: 11px">
        <tr>
            <th rowspan="" style="text-align: center;">Nº</th>
            <th rowspan="" style="text-align: center;">ID INEP</th>
            <th rowspan="">ALUNO</th>
            <th rowspan="" style="text-align: center;">DATA DE NASCIMENTO</th>
            <th rowspan="" style="text-align: center;">ENDEREÇO</th>
            <th rowspan="" style="text-align: center;">ESCOLA</th>
            <th rowspan="" style="text-align: center;">CIDADE DO ESTUDANTE</th>
            <th rowspan="" style="text-align: center;">CIDADE DA ESCOLA</th>
        </tr>
        <?php
        $rows = "";
        foreach ($report as $key=>$r){
            $rows .= "<tr>"
                . "<td style='text-align: center;'>" . ($key + 1) . "</td>"
                . "<td>" . $r['inep_id'] . "</td>"
                . "<td>" . $r['name'] . "</td>"
                . "<td style='text-align: center;'>" . $r['birthday'] . "</td>"
                . "<td>" . $r['address'] . "</td>"
                . "<td>" . $r['school'] . "</td>"
                . "<td>" . $r['city_student'] . "</td>"
                . "<td>" . $r['city_school'] . "</td>";
            $rows .= "</tr>";
        }
        echo $rows;
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

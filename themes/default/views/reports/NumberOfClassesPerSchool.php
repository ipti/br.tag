<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/BFReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H" style="width: 1080px;">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', $title); ?></h3>
    <h3><?php echo $header ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <?php foreach ($report as $r) {?>
        <div class="header-container">
            <h6><?= "ESCOLA: ".$r["school"]->name?></h6>
            <h6><?= "ID INEP: ".$r["school"]->inep_id?></h6>
            <h6><?= "GESTOR: ".$r["school"]->manager_name?></h6>
            <h6><?= "ENDEREÇO: ".$r["school"]->address.", ".$r["school"]->address_number.", "
            .$r["school"]->address_neighborhood.", ".$r["school"]->edcensoCityFk->name."/".$r["school"]->edcensoUfFk->acronym?></h6>
        </div>
        <table class="table table-bordered table-striped" aria-labelledby="classes list">
            <thead>
                <th>ORDEM</th>
                <th>TURMA</th>
                <th>ETAPA</th>
                <th>TURNO</th>
            </thead>
            <tbody>
                <?php 
                $ordem = 1;
                foreach($r["classrooms"] as $classroom) { 
                    $ordemStr = $ordem < 10 ? "0".$ordem : $ordem;
                    if($classroom->turn == "M") $turn = "Matutino";
                    else if ($classroom->turn == "T") $turn = "Vespertino";
                    else if ($classroom->turn == "N") $turn = "Noturno";
                    else $turn = "Não informado";
                    ?>
                    <tr>
                        <td><?= $ordemStr ?></td>
                        <td><?= $classroom->name ?></td>
                        <td><?= $classroom->edcensoStageVsModalityFk->name ?></td>
                        <td><?= $turn ?></td>
                    </tr>
                <?php 
                    $ordem++;
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b><?= "TOTAL: ".count($r["classrooms"])?></b></td>
                </tr>
            </tbody>
        </table>
    <?php }?>
</div>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>

<style>
    table thead th {
        vertical-align: middle !important;
        text-align: center !important;
    }

    .header-container {
        border: 1px solid #ccc;
        padding: 20px;
        margin-top: 50px;
    }

    @media print {
        .hidden-print {
            display: none;
        }
        table {
            page-break-inside: auto;
        }
    }

</style>
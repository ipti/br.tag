<?php
/* @var $this ReportsController */
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="pageA4H" style="width:1080px;">
    <?php $this->renderPartial('head'); ?>
    <h3>
        <?php echo Yii::t('default', 'Statistical Data'); ?>
    </h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print'
                    style="padding: 10px;"><img alt="impressora"
                        src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>

    <?php
    $ordem = 1;

    if (count($report) == 0) {
        echo "<br><span class='alert alert-primary'>Não há etapas cadastradas.</span>";
    } else {
        $ordem = 1;
        $html = "";
        $html .= "<table class='table table-bordered table-striped' >"
                ."<tr>"
                ."<th style ='width:10%;'> <b>Ordem </b> </th>"
                ."<th style ='width:70%;'> <b>Nome da etapa </b></th>"
                ."<th style ='width:20%;'> <b>Quantidade de Alunos </b></th>"
                ."</tr>";
        foreach ($report as $r) {
            $ordemStr = $ordem < 10 ? "0".$ordem : $ordem;
            
            
            $html .= "<tr>"
                . "<td>" . $ordemStr . "</td>"
                . "<td>" . $r['stage']->name . "</td>"
                . "<td>" . count($r["students"]) . "</td>"
                . "</tr>";

            $ordem++;
            

            /* código a ser descomentado de acordo com a demanda
            echo "<h5><b>Nome da etapa: </b>" . $r['stage']->name . "</h5><br>";

            $html = "";
            $html .= "<table class='table table-bordered table-striped' >";
            $html .= "<tr>"
                . "<th> <b>Ordem </b> </th>"
                . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
                . "<th> <b>RG </b></th>"
                . "<th> <b>CPF </b></th>"
                . "<th> <b>Data de Nascimento </b></th>"
                . "<th> <b>Nome Completo </b></th>"
                . "</tr>";

            if (count($r["students"]) == 0) {
                //echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; aluno nessa etapa.</span>";
            } else {
                foreach ($r["students"] as $s) {
                    $html .= "<tr>"
                        . "<td>" . $ordem . "</td>"
                        . "<td>" . $s['inep_id'] . "</td>"
                        . "<td>" . $s['rg_number'] . "</td>"
                        . "<td>" . $s['cpf'] . "</td>"
                        . "<td>" . date('d/m/Y', strtotime($s['birthday'])) . "</td>"
                        . "<td>" . $s['name'] . "</td>"
                        . "</tr>";

                    $ordem++;
                }
            }
            $html .= "<tr>"
                . "<td colspan= 5>" . " <b> Total de alunos nessa etapa: </b>" . count($r["students"]) . "</td>"
                . "</tr>";

            $html .= "</table>" . "<br><br>";
            //echo $html;
            $ordem = 1;
            $html = "";*/
        }
        $html .= "</table>";
        echo $html;
    }

    ?>
    <?php $this->renderPartial('footer'); ?>
</div>

<style>
    @media print {
        .hidden-print {
            display: none;
        }

        @page {
            size: portrait;
        }
    }
</style>

<script>
    function imprimirPagina() {
        window.print();
    }
</script>
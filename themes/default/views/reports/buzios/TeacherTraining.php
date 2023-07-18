<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/TeacherTraining/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$turno =  $classroom[0]['turno'];
if ($turno == 'M') {
    $turno = "Matutino";
}else if ($turno == 'T') {
    $turno = "Vesperitino";
}else if ($turno == 'N') {
    $turno = "Noturno";
}else if ($turno == '' || $turno == null) {
    $turno = "___________";
}
?>

<div class="pageA4H">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosI'); ?>
    </div>
    <h3><?php echo Yii::t('default', 'Teacher Training'); ?></h3>
    <h3><?php echo $title?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <p style="font-size: 19px;">
        Aos <?php echo $count_days?> dias do mês de <?php echo $mounth?> de 
        <?php echo $year?>, às <?php echo $hour?>, realizou-se a 
        reunião de Conselho de Classe referente ao <br> <?php echo $quarterly?> Trimestre,
        <?php echo $classroom[0]['school_name']?>, do(a) <?php echo $classroom[0]['classroom_name']?>, do turno <?php echo $turno?>, presidido por _____________________________________________&nbsp,&nbsp_____________________
        desta Unidade Escolar
    </p>
    <table>
        <thead>
            <tr>
                <th scope="col" rowspan="2">Nº</th>
                <th scope="col" rowspan="2">Nome do Aluno</th>
                <th scope="col" colspan="<?php echo count($disciplines)?>">Baixo Rendimento</th>
                <th scope="col" rowspan="2">Movimentação do Aluno</th>
            </tr>
            <tr>
                <?php
                foreach ($disciplines as $d) {
                    echo "<th scope='col' class='vertical-head'>".$d."</th>";
                }?>
            </tr>
        </thead>
        <tbody>
            <?php
            $count_std = 1;
            $array_students = [];
            foreach ($classroom as $c) { 
                if(!in_array($c['name'] ,$array_students)) {
            ?>
                <tr>
                    <td style="text-align: center;"><?= $count_std < 10 ? "0" . $count_std : $count_std ?></td>
                    <td><?= $c['name'] ?></td>
                    <?php 
                    for ($i=0; $i < count($disciplines); $i++) { 
                        echo "<td></td>";
                    }
                    ?>
                    <td style="text-align: center;">
                    <?php
                        $create_date =  date('d/m/y', strtotime($c['create_date']));
                        $date_cancellation = date('d/m/y', strtotime($c['date_cancellation']));
                        if ($c['status'] == 1) {
                            echo '';
                        } else if ($c['status'] == 2) {
                            if ($c['date_cancellation'] != null) {
                                echo 'Trans. ' . $date_cancellation;
                            }else {
                                echo 'Trans. ____/____/______';
                            }
                        } else if ($c['status'] == 3) {
                            if ($c['date_cancellation'] != null) {
                                echo 'Cancel. ' . $date_cancellation;
                            }else {
                                echo 'Cancel. ____/____/______';
                            }
                        } else if ($c['status'] == 4) {
                            if ($c['date_cancellation'] != null) {
                                echo 'Evad. ' . $date_cancellation;
                            }else {
                                echo 'Evad. ____/____/______';
                            }
                        }else {
                            echo '';
                        }
                    ?>
                    </td>
                    </tr>
                <?php
                    $count_std++;
                    }
                    array_push($array_students, $c['name']);
                }
                ?>
        </tbody>
    </table>
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
    .vertical-head {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }

    table th {
        text-align: center !important;
        vertical-align: inherit !important;
    }

    table {
        width: 96%;
        margin-top: 10px;
        page-break-inside: auto;
    }

    table th,
    table td {
        border: 2px solid #C0C0C0;
        padding: 10px;
    }
</style>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>
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
        <?php $this->renderPartial('buzios/headers/headBuziosVI'); ?>
    </div>
    <h3><?php echo Yii::t('default', 'Quarterly Class Council Report'); ?></h3>
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
        reunião de Conselho de Classe referente ao <br> <?php echo $quarterly?> Trimestre do Curso de Formação de Professores na Modalidade Normal, em Nível Médio,
        <?php echo $classroom[0]['school_name']?>, Turma <?php echo $classroom[0]['classroom_name']?>, do turno <?php echo $turno?>, presidido por _____________________________________________&nbsp,&nbsp_____________________
        deste Estabelecimento de Ensino
    </p>
    <p style="font-weight:bold;font-size: 16px;margin-top:30px;">1. ALUNOS COM BAIXO RENDIMENTO, FALTOSOS E COM PROBLEMAS DE ATITUDE:</p>
    <table aria-labelledby="Students List">
        <thead>
            <tr>
                <th scope="col" rowspan="2">Nº</th>
                <th scope="col" rowspan="2">Nome do Aluno</th>
                <th scope="col" colspan="<?php echo count($disciplines)?>">Baixo Rendimento</th>
                <th scope="col" rowspan="2" class="vertical-head">PCD</th>
                <th scope="col" rowspan="2" class="vertical-head">Faltoso(a)</th>
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
                    <td></td>
                    <td></td>
                    <td style="text-align: center;">
                    <?php
                        $create_date =  TagUtils::convertDateFormat($c['create_date']);
                        $date_cancellation = TagUtils::convertDateFormat($c['date_cancellation']);
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
    <p style="margin-top: 10px;">Legenda: PCD - Pessoa com Defeciência. </p>
    <div class="container-box line-box individual-observations">

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">2. Visão geral da turma:</p>

        <p style="font-weight:bold;font-size: 16px;">2.1 Frequência e Pontualidade:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">2.2 Relacionamento:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">2.3 Atitude:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">2.4 Desempenho:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">2.5 Justificativas:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">3. Alunos Destaques:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">4. Alunos Encaminhados:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">5. Deliberações finais (diagnóstico dos problemas, suas causas e soluções):</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p style="font-weight:bold;font-size: 16px;margin-top:30px;">6. Observações:</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________</p>

        <p class="container-box" style="font-weight:bold;margin-top:30px;">Sem mais para o momento, lavro a presente ata que vai assinada por todos envolvidos nesta reunião:</p>

        <table class="instructors-list-table container-box" aria-labelledby="Instructors List">
            <thead>
                <tr>
                    <th scope="col">Componente curricular/eixo</th>
                    <th scope="col">Nome do Professor</th>
                    <th scope="col" style="width: 40%;">Assinatura</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $validate_array = array();
                foreach ($classroom as $c) {
                    $json = json_encode(array(
                        "prof_name" => $c['prof_name'],
                        "discipline" => $c['discipline']
                    ));
                    if(!in_array($json, $validate_array)) {
            ?>
                        <tr>
                            <td><?= $c['discipline'] ?></td>
                            <td><?= $c['prof_name'] ?></td>
                            <td></td>
                        </tr>
            <?php
                    }
                    array_push($validate_array, $json);
                }
            ?>
            </tbody>
        </table>
        <div class="container-box signatures-container">
            <p>
                <span>_______________________________________</span>
                <span>_______________________________________</span>
                <span>_______________________________________</span>
            </p>
            <p>
                <span>Prof. Supervisor(a) Escolar</span>
                <span>Prof. Inspetor(a) Escolar</span>
                <span>Prof. Orientador(a) Educacional </span>
            </p>
            <p style="margin-top: 50px;">
                <span>_______________________________________</span>
                <span>_______________________________________</span>
            </p>
            <p>
                <span>Secretário(a) escolar</span>
                <span>Direção</span>
            </p>
        </div>
    </div>    
</div>

<style>
    @media print {
        .signatures-container {
            page-break-before: always;
        }
        .hidden-print {
            display: none;
        }
        @page {
            size: portrait;
        }
	}
    .vertical-head {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
    }

    .line-box p:not(p:first-child) {
        margin: 10px 0;
    }

    .container-box {
        font-size: 14px;
        margin: 40px 0;
    }

    table th {
        text-align: center !important;
        vertical-align: inherit !important;
        max-width: 10px;
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

    .signatures-container {
        margin-top: 80px !important;
        width: 96%;
    }

    .signatures-container p {
        margin: 10px 0px;
        width: 100%;
        align-items: center;
        display: flex;
    }

    .signatures-container p span {
        margin: 0px 50px;
        width: 100%;
        align-items: center;
        display: flex;
        justify-content: center;
    }

</style>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>
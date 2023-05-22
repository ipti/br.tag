<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuartelyClassCouncil/_initialization.js', CClientScript::POS_END);

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
<?php if (!empty($classroom)) { ?>
<div class="pageA4H page" style="height: auto;">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosI'); ?>
    </div>
    <h3><?php echo Yii::t('default', 'Quarterly Class Council Report')." - "; ?> <?php echo $title == '' ? mb_strtoupper($classroom[0]['class_stage'], 'UTF-8') : $title?></h3>
    <p style="font-size: 19px;">Aos <?php echo $count_days?> dias do mês de <?php echo $mounth?> de 
    <?php echo $year?> às <?php echo $hour?>hs, realizou-se a 
    reunião de Conselho de Classe referente ao <br> <?php echo $quarterly?> Trimestre,
        <?php echo $classroom[0]['school_name']?>, na turma <?php echo $classroom[0]['classroom_name']?>, do turno <?php echo $turno?>, presidido por _________________________________________
        desta Unidade Escolar</p>

    <div class="container-box global-analysis">
        <p style="margin: 20px 0;">1 - Análise Global da turma:</p>
        <p>
            <span style="margin-right: 350px;">1.1 Frequência</span>
            <span>(<span style="color: white;">___</span>) Excelente</span>
            <span>(<span style="color: white;">___</span>) Boa</span>
            <span>(<span style="color: white;">___</span>) Regular</span>
            <span>(<span style="color: white;">___</span>) Ruim</span>
        </p>
        <p>
            <span style="margin-right: 334px;">1.2 Pontualidade</span>
            <span>(<span style="color: white;">___</span>) Excelente</span>
            <span>(<span style="color: white;">___</span>) Boa</span>
            <span>(<span style="color: white;">___</span>) Regular</span>
            <span>(<span style="color: white;">___</span>) Ruim</span>
        </p>
    </div>
    <div class="container-box line-box box-aspects cognitive-aspects">
        <p>1.3 Aspectos cognitivos (Conhecimento de mundo):</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
    </div>
    <div class="container-box line-box box-aspects socio-affective-aspects">
        <p>1.3 Aspectos socioafetivos (Formação pessoal e social):</p>
        <p>_________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
        <p>
            _________________________________________________________________________________________________________________________________________________________________________________
        </p>
    </div>
    <div class="container-box individual-aspects">
        <p>1.5 Aspectos individuais</p>
        <table class="students-list-table" aria-labelledby="Students List">
            <thead style="height: 150px;">
                <tr>
                    <th rowspan="2" scope="col">Nº</th>
                    <th rowspan="2" scope="col">NOME DO ALUNO</th>
                    <th class="vertical-head" rowspan="2" scope="col">PCD</th>
                    <th class="vertical-head" rowspan="2" scope="col">INFREQ.</th>
                    <th colspan="2" scope="col">Área Cognitiva (AC)</th>
                    <th colspan="2" scope="col">Área Socioafetiva (AS)</th>
                    <th rowspan="2" scope="col">Observações</th>
                </tr>
                <tr>
                    <th scope="col"><span class="vertical-head">Destaque</span></th>
                    <th scope="col"><span class="vertical-head">Dificuldade</span></th>
                    <th scope="col"><span class="vertical-head">Destaque</span></th>
                    <th scope="col"><span class="vertical-head">Dificuldade</span></th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
        <p style="margin-top: 10px;">Legenda: PCD - Pessoa com Defeciência. </p>
        <div class="container-box line-box individual-observations">
            <p>1. Observações individuais</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box line-box difficulties-found">
            <p>2. Dificuldades encontradas para o desenvolvimento das atividades pedagógicas:</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box line-box developed-actions">
            <p>3. Ações desenvolvidas para minimizar as dificuldades encontradas (professores, equipe pedagógica e diretiva):</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box line-box final-deliberations">
            <p>4. Deliberações Finais (ações e/ou propostas para o próximo Trimestre):</p>
            <p>_________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
            <p>
                _________________________________________________________________________________________________________________________________________________________________________________
            </p>
        </div>
        <div class="container-box">
            <p>Nada mais havendo a declarar, lavramos a presente Ata</p>
        </div>
        <table class="instructors-list-table"  aria-labelledby="Instructors List">
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
                <span>Prof. Supervisor (a) Escolar</span>
                <span>Prof. Orientador (a) Educacional </span>
                <span>Prof. Inspetor (a) Escolar</span>
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
<?php }else {?>
<div class="pageA4H page" style="height: auto;">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosI'); ?>
    </div>
    <h3><?php echo Yii::t('default', 'Quarterly Class Council Report')." - "; ?> <?php echo $title == '' ? mb_strtoupper($classroom[0]['class_stage'], 'UTF-8') : $title?></h3>
    <div class='no-enrollments'>Não há alunos matriculados na turma.</div>
</div>
<?php }?>

<style>
    @media print {
        .signatures-container {
            page-break-before: always;
        }
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

    .global-analysis p:not(p:first-child) {
        margin: 10px 0;
    }

    .global-analysis p span:not(span:first-child) {
        margin-inline: 30px;
    }

    .line-box p:not(p:first-child) {
        margin: 10px 0;
    }

    .container-box {
        font-size: 14px;
        margin: 40px 0;
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
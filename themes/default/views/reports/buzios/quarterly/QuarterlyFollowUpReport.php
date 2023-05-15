<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuartelyClassCouncil/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$stage = '';
if ($stage_id == 1) {
    $stage = "1º, 2º E 3º ANOS";
}else if ($stage_id == 2) {
    $stage = "4º E 5º ANOS";
}

?>
<div class="pageA4H page" style="height: auto;">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosI'); ?>
    </div>
    <!-- TAB RELATÓRIO -->
    <h3 style="margin-bottom: 20px;"><?php echo "RELATÓRIO TRIMESTRAL DE ACOMPANHAMENTO DOS ".$stage." <br>COMPONENTE CURRICULAR: ".mb_strtoupper($report[0]['discipline_name'], 'UTF-8');?></h3>
    <div class="container-section" style="border-top: 3px solid black;"><?php echo "Escola: ".$school->name?></div>
    <div class="container-section"><?php echo "Professor(a): ".$report[0]['instructor_name']?></div>
    <div class="container-section container">
        <span><?php echo "Turma: ".$report[0]['classroom_name']?></span>
        <span><?php echo "Ano de escolaridade: ".$stage?></span>
        <span style="margin-right: 100px;"><?php echo "Turno: ".$turno?></span>
    </div>
    <div class="container-section container">
        <span><?php echo "Trimestre: ".$trimestre?></span>
        <span style="margin-right: 480px;"><?php echo "Ano em curso: ".Yii::app()->user->year?></span>
    </div>
    <div class="container-section" style="padding: 0;width: 95.6%;">
        <div class="container-info-title">
            1- Principais ações do professor desenvolvidas no Ambiente Escolar 
            (Participação nos encontros pedagógicos, 
            construção de currículo coletivo, em reuniões de responsáveis e nos COC).
        </div>
        <div class="container-info-text"></div>
    </div>
    <div class="container-section" style="padding: 0;width: 95.6%;">
        <div class="container-info-title">
            2- Unidades Temáticas desenvolvidas no trimestre:
        </div>
        <div class="container-info-text"></div>
    </div>
    <div class="container-section" style="padding: 0;width: 95.6%;">
        <div class="container-info-title">
            3- ESPAÇOS PEDAGÓGICOS:
            Quais os contextos de aprendizagens desenvolvidos e
            intervenções realizadas para consolidar os objetivos de aprendizagem e desenvolvimento
            inerentes às unidades temáticas.
        </div>
        <div class="container-info-text"></div>
    </div>
    <div class="container-section" style="padding: 0;width: 95.6%;">
        <div class="container-info-title">
            4 - Dificuldades encontradas
        </div>
        <div class="container-info-text"></div>
    </div>
    <div class="container-section" style="padding: 0;width: 95.6%;">
        <div class="container-info-title">
            5– Sobre o aluno incluso
        </div>
        <div class="container-info-text"></div>
    </div>

    <!-- TAB RELATÓRIO AVALIATIVO -->
    <div class="container-evaluative-title">
        <div class="cabecalho" style="margin: 30px 0;">
            <?php $this->renderPartial('buzios/headers/headBuziosII'); ?>
        </div>
        <p>RELATÓRIO AVALIATIVO TRIMESTRAL</p>
        <p><?php echo "Unidade Escolar: ".$school->name?></p>
        <p>
            <span><?php echo "Professor(a): ".$report[0]['instructor_name']?></span>
            <span><?php echo "Componenete Curricular: ".$report[0]['discipline_name']?></span>
        </p>
        <p>
            <span>Ano de Escolaridade:</span><span>(__)4º</span><span>(__)5º</span>
            <span><?php echo "Turno: ".$turno?></span>
            <span><?php echo "Turma: ".$report[0]['classroom_name']?></span>
        </p>
    </div>
    <div class="container-evaluative-content">
        <table aria-labelledby="Evaluative Content Table">
            <thead>
                <tr>
                    <th scope="col" rowspan="2" style="width: 30%;font-size:20px;">ALUNO(A)</th>
                    <th scope="col" colspan="7" style="font-size: 12px;">HABILIDADES GERAIS DAS UNIDADES TEMÁTICAS (Brincadeiras e Jogos, Ginásticas, Esportes e Danças)</th>
                </tr>
                <tr>
                    <th scope="col" class="vertical-head">1- Aprende os conhecimentos<br>necessários para a execução <br>das atividades referentes à <br>prática realizada?</th>
                    <th scope="col" class="vertical-head">2- Planeja e utiliza estratégias para<br> resolver desafios peculiares à<br> prática realizada?</th>
                    <th scope="col" class="vertical-head">3- Experimenta, frui e recria<br> diferentes práticas corporais<br> presente no contexto comunitário<br> e regional, reconhecendo,<br> respeitando e valorando as<br> manifestações de diferentes<br> culturas?</th>
                    <th scope="col" class="vertical-head">4- Diferencia os conceitos de<br> ludicidade e competividade e<br> suas características?</th>
                    <th scope="col" class="vertical-head">5- Respeita as diferenças<br> individuais de desempenho dos <br>colegas?</th>
                    <th scope="col" class="vertical-head">6- Demostra autonomia ao realizar as<br> atividades, assim como bom nível de <br>concentração?</th>
                    <th scope="col" class="vertical-head">7- Age com respeito mútuo,<br> reconhecendo e exercendo as <br>regras de convivência?</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) { ?>
                    <tr>
                        <td><?= $student['student_name']?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .container-section {
        width: 93%;
        border-bottom: 3px solid black;
        border-left: 3px solid black;
        border-right: 3px solid black;
        padding: 10px 15px;
        font-size: 16px;
        font-weight: bold;
    }

    .container-evaluative-title {
        width: 70%;
        font-size: 14px;
    }
    .container-evaluative-content {
        width: 96%;
        font-size: 14px;
    }
    .container {
        display: flex;
        justify-content: space-between;
        margin-left: 0;
        margin-right: 0;
    }
    .container span:nth-child(2) {
        margin-left: auto;
        margin-right: auto;
    }
    .container-info-title {
        width: 30%;
        background-color: #ccc;
        padding: 20px;
        border-right: 3px solid black;
        height: 180px;
    }
    .container-info-text {
        width: 70%;
    }
    .vertical-head {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-size: 10px !important;
    }

    table th {
        text-align: center !important;
        vertical-align: inherit !important;
    }

    table {
        width: 100%;
        margin-top: 10px;
        page-break-inside: auto;
    }

    table th,
    table td {
        border: 2px solid #C0C0C0;
        padding: 10px;
    }
</style>
<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuartelyClassCouncil/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>
<div class="pageA4H page" style="height: auto;">
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('buzios/headers/headBuziosI'); ?>
    </div>
    <h3 style="margin-bottom: 20px;"><?php echo "RELATÓRIO TRIMESTRAL DE ACOMPANHAMENTO DOS 4º E 5º ANOS COMPONENTE CURRICULAR: ".mb_strtoupper($report[0]['discipline_name'], 'UTF-8');?></h3>
    <div class="container-section" style="border-top: 3px solid black;"><?php echo "Escola: ".$school->name?></div>
    <div class="container-section"><?php echo "Professor(a): ".$report[0]['instructor_name']?></div>
    <div class="container-section container">
        <span><?php echo "Turma: ".$report[0]['classroom_name']?></span>
        <span><?php echo "Ano de escolaridade: ".Yii::app()->user->year?></span>
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
</style>
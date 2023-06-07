<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentGradesReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

// Tratamento do cabeçalho das disciplinas
function classroomDisciplineLabelResumeArray($discipline_id)
{
    $label = '';
    if ($discipline_id == 1)  $label = "Química";
    if ($discipline_id == 2)  $label = "Física";
    if ($discipline_id == 3)  $label = "Matemática";
    if ($discipline_id == 4)  $label = "Biologia";
    if ($discipline_id == 5)  $label = "Ciências";
    if ($discipline_id == 6)  $label = "Português";
    if ($discipline_id == 7)  $label = "Inglês";
    if ($discipline_id == 8)  $label = "Espanhol";
    if ($discipline_id == 9)  $label = "Outro Idioma";
    if ($discipline_id == 10)  $label = "Artes";
    if ($discipline_id == 11)  $label = "Edicação Física";
    if ($discipline_id == 12)  $label = "História";
    if ($discipline_id == 13)  $label = "Geografia";
    if ($discipline_id == 14)  $label = "Filosofia";
    if ($discipline_id == 16)  $label = "Informática";
    if ($discipline_id == 17)  $label = "Disc. Profissionalizante";
    if ($discipline_id == 20)  $label = "Educação Especial";
    if ($discipline_id == 21)  $label = "Sociedade&nbspe Cultura";
    if ($discipline_id == 23)  $label = "Libras";
    if ($discipline_id == 25)  $label = "Pedogogia";
    if ($discipline_id == 26)  $label = "Ensino Religioso";
    if ($discipline_id == 27)  $label = "Língua Nativa";
    if ($discipline_id == 28)  $label = "Estudo Social";
    if ($discipline_id == 29)  $label = "Sociologia";
    if ($discipline_id == 30)  $label = "Francês";
    if ($discipline_id == 99)  $label = "Outras";
    if ($discipline_id == 10001)  $label = "Redação";
    if ($discipline_id == 10002)  $label = "Linguagem oral e escrita";
    if ($discipline_id == 10003)  $label = "Natureza e sociedade";
    if ($discipline_id == 10004)  $label = "Movimento";
    if ($discipline_id == 10005)  $label = "Música";
    if ($discipline_id == 10006)  $label = "Artes visuais";
    if ($discipline_id == 10007)  $label = "Escuta, Fala, Pensamento e Imaginação";
    if ($discipline_id == 10008)  $label = "Espaço, Tempo, Quantidade, Relações e Transformações";
    if ($discipline_id == 10009)  $label = "Corpo, Gesto e Movimento";
    if ($discipline_id == 10010)  $label = "Traços, Sons, Cores e Formas";
    if ($discipline_id == 10011)  $label = "O Eu, O Outro e o Nós";
    if ($discipline_id == 10012)  $label = "Manifestações Culturais e Artísticas Globais e Regionais";
    if ($discipline_id == 10013)  $label = "Gestão Sustentável de Detinos Turísticos";
    if ($discipline_id == 10014)  $label = "Lazer, Esporte e Trabalho";
    if ($discipline_id == 10015)  $label = "Inglês Instrumental";
    if ($discipline_id == 10016)  $label = "Espanhol Instrumental";
    if ($discipline_id == 10017)  $label = "Georgrafia Turística";
    if ($discipline_id == 10018)  $label = "Desafios contemporâneos: Do Global ao Local";
    if ($discipline_id == 10019)  $label = "História Regional";
    if ($discipline_id == 10020)  $label = "Sociedade Buziana";
    if ($discipline_id == 10021)  $label = "Antropologia Sociocultural";
    if ($discipline_id == 10022)  $label = "Patrimônios Culturais";
    if ($discipline_id == 10023)  $label = "Literatura na Era Digital";
    if ($discipline_id == 10024)  $label = "Educação Financeira";
    if ($discipline_id == 10025)  $label = "Expressão Oral e Escrita";
    if ($discipline_id == 10026)  $label = "Projeto de Vida II";
    if ($discipline_id == 10027)  $label = "Língua Espanhola";
    if ($discipline_id == 10028)  $label = "Análise e Experimentação científica";
    if ($discipline_id == 10029)  $label = "Consciência Ecológica e Educação Ambiental";
    if ($discipline_id == 10030)  $label = "Ciências, Tecnologia e Sociedade";
    if ($discipline_id == 10031)  $label = "Pensamento Lógico-Matemático";
    if ($discipline_id == 10032)  $label = "Cálculo";
    if ($discipline_id == 10033)  $label = "Sustentabilidade";
    if ($discipline_id == 10034)  $label = "Eficiência Energética";
    if ($discipline_id == 10035)  $label = "Corpo e Movimento";
    if ($discipline_id == 10036)  $label = "Geometria";
    if ($discipline_id == 10037)  $label = "Corpo e Saúde";
    if ($discipline_id == 10038)  $label = "Ciências Biológicas, Agrárias e da Saúde";
    if ($discipline_id == 10039)  $label = "Projeto de vida I";
    if ($discipline_id == 10040)  $label = "Projeto de Leitura";
    if ($discipline_id == 10041)  $label = "Pluralidade Cultural";
    if ($discipline_id == 10042)  $label = "Estudo Dirigido: Atividade de Lingua Portuguesa e de Matmática";
    if ($discipline_id == 10043)  $label = "Atividades Esportivas e Motoras";
    
    return $label;
}
$rows = count($baseDisciplines)+count($diversifiedDisciplines); // contador com a soma do total de disciplinas da matriz
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" onclick="imprimirPagina()" class='btn btn-icon hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<div class="pageA4H">
    <div>
        <div id="report">
            <?php $this->renderPartial('head'); ?>
            <br>
            <div style="margin: 0 0 0 50px; width: calc(100% - 51px); text-align:center">
                <div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span9"><b>ALUNO: </b><?= $enrollment->studentFk->name ?></div>
                    <div class="span9"><b>TURMA: </b><?= $enrollment->classroomFk->name ?></div>
                    <div class="span9"><b>ANO LETIVO: </b><?= $enrollment->classroomFk->school_year ?></div>
                </div>
            </div>
            <br>
            <table style="margin: 0 0 0 50px; font-size: 8px; width: calc(100% - 51px);"
                   class="table table-bordered report-table-empty">
                <thead>
                    <tr>
                        <th colspan="<?= $rows+4 ?>" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
                    </tr>
                    <tr>
                        <td style="text-align: center; max-width: 90px !important;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                        <td colspan="<?= count($baseDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:150px;">BASE
                            NACIONAL
                            COMUM
                        </td>
                        <td colspan="<?= count($diversifiedDisciplines) ?>" style="text-align: center; font-weight: bold; min-width:100px;">PARTE
                            DIVERSIFICADA
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>DIAS&nbsp;LETIVOS</div>
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>CARGA&nbsp;HORÁRIA</div>
                        </td>
                        <td rowspan="2" class="vertical-text">
                            <div>Nº&nbsp;DE&nbsp;FALTAS</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="vertical-text">
                            <div>BIMESTRES</div>
                        </td>
                        <?php foreach ($baseDisciplines as $name): ?>
                            <td class="vertical-text">
                                <div><?= classroomDisciplineLabelResumeArray($name) ?></div>
                            </td>
                        <?php endforeach; ?>
                        <?php foreach ($diversifiedDisciplines as $name): ?>
                            <td class="vertical-text">
                                <div><?= classroomDisciplineLabelResumeArray($name) ?></div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for($i=1;$i<=count($unities);$i++) {?>
                        <tr>
                            <td><?= strtoupper($unities[$i-1]->name) ?></td>
                            <?php
                            $school_days = 0;
                            $workload = 0;
                            $faults = 0;
                            for($j=0; $j < $rows; $j++) { 
                                $school_days += $result[$j]['school_days'];
                                $workload += $result[$j]['workload'];
                                $faults += $result[$j]['faults'];
                                ?>
                                <td style="text-align: center;"><?= $result[$j]['grades'][$i-1]->grade ?></td>
                            <?php }?>
                            <td style="text-align: center;"><?= $school_days?></td>
                            <td style="text-align: center;"><?= $workload?></td>
                            <td style="text-align: center;"><?= $faults?></td>
                        </tr>
                    <?php }?>
                </tbody>

                <tr>
                    <td colspan="1">MÉDIA ANUAL</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['final_media']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="1">NOTA DA PROVA FINAL</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= end($result[$i]['grades'])->grade?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="1">MÉDIA FINAL</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['final_media']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE AULAS DADAS</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['total_number_of_classes']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">TOTAL DE FALTAS</td>
                    <?php for ($i=0; $i < $rows; $i++) { ?>
                        <td style="text-align: center;"><?= $result[$i]['faults']?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align:right;" colspan="1">FREQUÊNCIAS %</td>
                    <?php for ($i=0; $i < $rows; $i++) { 
                        $totalDiasAula = $result[$i]['school_days'];
                        $quantidadeFaltas = $result[$i]['faults'];
                        $frequencia = (($totalDiasAula - $quantidadeFaltas) / $totalDiasAula) * 100;
                        $verifyCalc = is_nan($frequencia);
                        ?>
                        <td style="text-align: center;"><?= !$verifyCalc ? strval(number_format($frequencia, 2))."%" : "" ?></td>
                    <?php }?>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br/>

            <div style="text-align:right;">Resultado Final _____________________________</div>
            <div style="text-align:center">APTO PARA CURSAR O _____________ ANO DO ENSINO FUNDAMENTAL
                <div>
                    <div style="text-align: center;line-height: 15px;margin-top:20px;">
                        _________________________________________________________<br>Local e data
                        <div>
                            <div style="float: left;line-height: 15px; width:50%">
                                _________________________________________________________<br>Assinatura do(a) Secretário
                                (a)
                            </div>
                            <div style="float: right;line-height: 15px;width:50%">
                                _________________________________________________________<br>Assinatura do(a) Diretor(a)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .vertical-text {
        height: 100px;
        vertical-align: bottom !IMPORTANT;
        max-width: 20px;
        width: 35px;
        padding-bottom: 10px !important;
    }

    .vertical-text div {
        transform: translate(25px, 0px) rotate(270deg);
        width: 100px;
        line-height: 13px;
        margin: 0 10px 0 0;
        transform-origin: bottom left;
    }

    td {
        max-width: 35px;
    }

    @media print {
        #container-header {
            width: 425px !important;
        }

        table, td, tr, th {
            border-color: black !important;
        }

        table tbody tr td {
            padding: 10px !important;
        }

        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%, auto;
        } 

        .hidden-print {
            display: none;
        }
    }

    @page {
      size: landscape;
    }
</style>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>   
<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentDeclarationReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
/**
 * @var $school SchoolIdentification;
 */
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<br/>
<div class="innerLR district">
    <div>
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                jQuery.ajax({'type': 'GET',
                    'data': {'enrollment_id':<?php echo $enrollment_id;?>},
                    'url': '<?php echo Yii::app()->createUrl('reports/getEnrollmentDeclarationInformation') ?>',
                    'success': function (data) {
                        gerarRelatorio(data);
                    }, 'error': function () {
                        limpar();
                    }, 'cache': false});
                return false;
            }
            );
            /*]]>*/
        </script>
        <br>
        <div id="report" style="font-size: 14px">

            <div id="container-header" style="text-align: center; width: 100%; margin: 0 auto;margin-top: -30px;">
                <div>
                    <img src="data:<?=$school->logo_file_type?>;base64,<?=base64_encode($school->logo_file_content)?>" width="40px" style="margin-bottom:10px">
                </div>
                <span style="font-size: 14px">
                    ESTADO DE SERGIPE<br>

                    PREFEITURA MUNICIPAL DE <?=strtoupper($school->edcensoCityFk->name)?><br>
                    <?=strtoupper($school->report_header)?></span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/><br/>
            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <br>
                <div id="report_type_container" style="text-align: center">
                    <span id="report_type_label" style="font-size: 16px">DECLARAÇÃO</span>
                </div>
                <br><br>
                <span style="clear:both;display:block"></span>
                Declaramos para os devidos fins que 
                <span class="name" style="font-weight: bold"></span>,
                <?php
                    if ($gender == '1'){
                        echo "filho de ";
                    } else {
                        echo "filha de ";
                    }
                ?>
                <span class="mother"></span>
                e 
                <span class="father"></span>,
                <?php
                    if ($gender == '1'){
                        echo "nascido em ";
                    } else {
                        echo "nascida em ";
                    }
                ?>
                <span class="birthday"></span>
                na cidade de 
                <span class="city"></span>,
                matriculou-se no(a) <?php echo $school->name ?> no ano de 
                <span class="enrollment_date"></span>, 
                <?php
                    $c;
                    //$stage = '7';
                    //$class = '41';
                    switch ($class) {
                        case '4':
                            $c = '1º';
                            break;
                        case '5':
                            $c = '2º';
                            break;
                        case '6':
                            $c = '3º';
                            break;
                        case '7':
                            $c = '4º';
                            break;
                        case '8':
                            $c = '5º';
                            break;
                        case '9':
                            $c = '6º';
                            break;
                        case '10':
                            $c = '7º';
                            break;
                        case '11':
                            $c = '8º';
                            break;
                        case '14':
                            $c = '1º';
                            break;
                        case '15':
                            $c = '2º';
                            break;
                        case '16':
                            $c = '3º';
                            break;
                        case '17':
                            $c = '4º';
                            break;
                        case '18':
                            $c = '5º';
                            break;
                        case '19':
                            $c = '6º';
                            break;
                        case '20':
                            $c = '7º';
                            break;
                        case '21':
                            $c = '8º';
                            break;
                        case '41':
                            $c = '9º';
                            break;
                        case '25':
                        case '30':
                        case '35':
                            $c = '1º';
                            break;
                        case '26':
                        case '31':
                        case '36':
                            $c = '2º';
                            break;
                        case '27':
                        case '32':
                        case '37':
                            $c = '3º';
                            break;
                        case '28':
                        case '33':
                        case '38':
                            $c = '4º';
                            break;
                    }
                    switch ($stage) {
                        case '1':
                            echo "na Educação Infantil e encontra-se frequentando regularmente as aulas.";
                            break;
                        case '2':
                        case '3':
                            echo "no " . $c . " Ano do Ensino Fundamental, onde ";
                            if ($gender == '1'){
                                echo "o mesmo:";
                            } else {
                                echo "a mesma:";
                            }
                            break;
                        case '4':
                            echo "no " . $c . " Ano do Ensino Médio, onde ";
                            if ($gender == '1'){
                                echo "o mesmo:";
                            } else {
                                echo "a mesma:";
                            }
                            break;
                        case '6':
                            echo "na _____ série da Educação de Jovens e Adultos, onde ";
                            if ($gender == '1'){
                                echo "o mesmo:";
                            } else {
                                echo "a mesma:";
                            }
                            break;
                        case '7':
                            if ($class == '56') {
                                echo "no _____ Ano do __________________________________________, onde ";
                                if ($gender == '1'){
                                    echo "o mesmo:";
                                } else {
                                    echo "a mesma:";
                                }
                            } else if ($class == '41') {
                                echo "no " . $c . " Ano do Ensino Fundamental, onde ";
                                if ($gender == '1'){
                                    echo "o mesmo:";
                                } else {
                                    echo "a mesma:";
                                }
                            } else {
                                echo "no " . $c . " Ano do Ensino Fundamental, onde ";
                                if ($gender == '1'){
                                    echo "o mesmo:";
                                } else {
                                    echo "a mesma:";
                                }
                            }
                            break; 
                            
                    }
                ?>
                <br/><br/>
                <div class="pull-left" style="text-align:center">
                    <span>IDENTIFICAÇÃO ÚNICA</span>
                    <table class="table_inep_id">
                        <tr>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>

                        </tr>
                    </table>
                </div>
                <div class="pull-right" style="text-align:center">
                    <span>NIS</span>
                    <table class="table_nis">
                        <tr>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                            <td class="cell">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <?php
                    switch ($stage) {
                        case '1':
                            echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
                            break;
                        case '2':
                        case '3':
                        case '7':
                            echo "<br><br><br><br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) está frequentando normalmente as aulas<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) cancelou a matrícula<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) abandonou os estudos<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            if ($gender == '1') {
                                echo "aprovado";
                            } else {
                                echo "aprovada";
                            }
                            if ($class == '56'){
                                echo " para o _____ Ano do __________________________________________<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            } else {
                                echo " para o " . $c . " Ano do Ensino Fundamental<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            }
                            if ($gender == '1') {
                                echo "reprovado<br>";
                            } else {
                                echo "reprovada<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) solicitou transferência<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) prazo de expedição do documento ____ dias<br><br><br>";
                            break;
                        case '4':
                            echo "<br><br><br><br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) está frequentando normalmente as aulas<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) cancelou a matrícula<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) abandonou os estudos<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            if ($gender == '1') {
                                echo "aprovado";
                            } else {
                                echo "aprovada";
                            }
                            echo " para o " . $c . " Ano do Ensino Médio<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            if ($gender == '1') {
                                echo "reprovado<br>";
                            } else {
                                echo "reprovada<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) solicitou transferência<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) prazo de expedição do documento ____<br><br><br>";
                            break;
                        case '6':
                            echo "<br><br><br><br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) está frequentando normalmente as aulas<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) cancelou a matrícula<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) abandonou os estudos<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            if ($gender == '1') {
                                echo "aprovado<br>";
                            } else {
                                echo "aprovada<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) foi ";
                            if ($gender == '1') {
                                echo "reprovado<br>";
                            } else {
                                echo "reprovada<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) concluiu a ____ Série do Ensino Fundamental<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) solicitou transferência<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) prazo de expedição do documento ____ dias<br><br><br>";
                            break;
                    }
                ?>
                <span>
                    OBS: _________________________________________________________________________________<br/>
                    _______________________________________________________________________________________<br/>
                    _______________________________________________________________________________________
                </span>
                <br/><br/><br/><br/>
                <span class="pull-right">
                    <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
                </span>
                <br/><br/><br/><br/>
                <div style="text-align: center">
                    <span style="font-size: 14px">
                        SIMONE MOURA DE SOUZA ALMEIDA
                    </span>
                    <br/>
                    <span>
                        Chefe de Divisão de Inspeção Escolar
                    </span>
                    <br/>
                    <span>
                        Decreto de 03/05/2013
                    </span>
                </div>
            </div>
        </div>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>

<style>
    .cell {
        border: 1px solid;
        line-height: 16px;
        width: 16px
    }
    @media print {
        #report {
            margin: 0 50px 0 100px;
        }
        #report_type_container{            
            border-color: white !important;
        }
        #report_type_label{
            border-bottom: 1px solid black !important;
            font-size: 22px !important;
            font-weight: 500;
            font-family: serif;            
        }
        table, td, tr, th {
            border-color: black !important;
        }
        .report-table-empty td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .vertical-text {
            height: 110px;
            vertical-align: bottom !IMPORTANT;
        }
        .vertical-text div {
            transform: translate(5px, 0px) rotate(270deg);
            width: 5px;
            line-height: 13px;
            margin: 0px 10px 0px 0px;
        }
    }
</style>
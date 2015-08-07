<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentDeclarationReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<br/>
<div class="innerLR boquim">
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
                    <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="margin-bottom:10px">
                </div>
                <span>
                    ESTADO DE SERGIPE<br>
                    PREFEITURA MUNICIPAL DE BOQUIM<br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/><br/>
            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <br>
                <div id="report_type_container">
                    <span id="report_type_label">DECLARAÇÃO</span>
                </div>
                <br><br>
                <span style="clear:both;display:block"></span>
                DECLARAMOS PARA OS DEVIDOS FINS QUE 
                <span class="name" style="font-weight: bold"></span>,
                <?php
                    if ($gender == '1'){
                        echo "FILHO DE ";
                    } else {
                        echo "FILHA DE ";
                    }
                ?>
                <span class="mother"></span>
                E 
                <span class="father"></span>,
                <?php
                    if ($gender == '1'){
                        echo "NASCIDO EM ";
                    } else {
                        echo "NASCIDA EM ";
                    }
                ?>
                <span class="birthday"></span>
                NA CIDADE DE 
                <span class="city"></span>,
                MATRICULOU-SE NO(A) <?php echo $school->name ?> NO ANO DE 
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
                            echo "NA EDUCAÇÃO INFANTIL E ENCONTRA-SE FREQUENTANDO REGULARMENTE AS AULAS.";
                            break;
                        case '2':
                        case '3':
                            echo "NO " . $c . " ANO DO ENSINO FUNDAMENTAL, ONDE ";
                            if ($gender == '1'){
                                echo "O MESMO:";
                            } else {
                                echo "A MESMA:";
                            }
                            break;
                        case '4':
                            echo "NO " . $c . " ANO DO ENSINO MÉDIO, ONDE ";
                            if ($gender == '1'){
                                echo "O MESMO:";
                            } else {
                                echo "A MESMA:";
                            }
                            break;
                        case '6':
                            echo "NA _____ SÉRIE DA EDUCAÇÃO DE JOVENS E ADULTOS, ONDE ";
                            if ($gender == '1'){
                                echo "O MESMO:";
                            } else {
                                echo "A MESMA:";
                            }
                            break;
                        case '7':
                            if ($class == '56') {
                                echo "NO _____ ANO DO __________________________________________, ONDE ";
                                if ($gender == '1'){
                                    echo "O MESMO:";
                                } else {
                                    echo "A MESMA:";
                                }
                            } else if ($class == '41') {
                                echo "NO " . $c . " ANO DO ENSINO FUNDAMENTAL, ONDE ";
                                if ($gender == '1'){
                                    echo "O MESMO:";
                                } else {
                                    echo "A MESMA:";
                                }
                            } else {
                                echo "NO " . $c . " ANO DO ENSINO FUNDAMENTAL, ONDE ";
                                if ($gender == '1'){
                                    echo "O MESMO:";
                                } else {
                                    echo "A MESMA:";
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
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) ESTÁ FREQUENTANDO NORMALMENTE<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) CANCELOU A MATRÍCULA<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) ABANDONOU OS ESTUDOS<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            if ($gender == '1') {
                                echo "APROVADO";
                            } else {
                                echo "APROVADA";
                            }
                            if ($class == '56'){
                                echo " PARA O _____ ANO DO __________________________________________<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            } else {
                                echo " PARA O " . $c . " ANO DO ENSINO FUNDAMENTAL<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            }
                            if ($gender == '1') {
                                echo "REPROVADO<br>";
                            } else {
                                echo "REPROVADA<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) SOLICITOU TRANSFERÊNCIA<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) PRAZO DE EXPEDIÇÃO DO DOCUMENTO ____ DIAS<br><br><br>";
                            break;
                        case '4':
                            echo "<br><br><br><br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) ESTÁ FREQUENTANDO NORMALMENTE<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) CANCELOU A MATRÍCULA<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) ABANDONOU OS ESTUDOS<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            if ($gender == '1') {
                                echo "APROVADO";
                            } else {
                                echo "APROVADA";
                            }
                            echo " PARA O " . $c . " ANO DO ENSINO MÉDIO<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            if ($gender == '1') {
                                echo "REPROVADO<br>";
                            } else {
                                echo "REPROVADA<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) SOLICITOU TRANSFERÊNCIA<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) PRAZO DE EXPEDIÇÃO DO DOCUMENTO ____ DIAS<br><br><br>";
                            break;
                        case '6':
                            echo "<br><br><br><br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) ESTÁ FREQUENTANDO NORMALMENTE<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) CANCELOU A MATRÍCULA<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) ABANDONOU OS ESTUDOS<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            if ($gender == '1') {
                                echo "APROVADO<br>";
                            } else {
                                echo "APROVADA<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) FOI ";
                            if ($gender == '1') {
                                echo "REPROVADO<br>";
                            } else {
                                echo "REPROVADA<br>";
                            }
                            echo "(&nbsp;&nbsp;&nbsp;&nbsp;) CONCLUIU A ____ SÉRIE DO ENSINO FUNDAMENTAL<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) SOLICITOU TRANSFERÊNCIA<br>"
                                . "(&nbsp;&nbsp;&nbsp;&nbsp;) PRAZO DE EXPEDIÇÃO DO DOCUMENTO ____ DIAS<br><br><br>";
                            break;
                    }
                ?>
                <span>
                    OBS:_______________________________________________________________________________________________________________________________________________________________________________<br/>
                    ____________________________________________________________________________________________________________________________________________________________________________________<br/>
                    ____________________________________________________________________________________________________________________________________________________________________________________
                </span>
                <br/><br/><br/><br/><br/><br/>
                <span class="pull-right">
                    Boquim(SE), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
                </span>
                <br/><br/><br/><br/><br/><br/>
                <div style="text-align: center">
                    <span>
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
    </div>
</div>

<style>
    .cell {
        border: 1px solid;
        line-height: 20px;
        width: 20px
    }
    #report_type_container{
        height:100%;
        border: 1px solid black;
        text-align: center;
        background-color: lightgray;
        margin-bottom: 5px;
    }
    @media print {        
        #report_type_container{            
            border-color: white !important;
        }
        #report_type_label{
            border-bottom: 1px solid black !important;
            font-size: 24px;            
            font-weight: 400;
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
        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat:no-repeat;
            background-position:center center;
            background-size: 100% 100%, auto;
        }
    }
</style>
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
        <div id="report">

            <div id="container-header" style="width: 600px; margin: 0 auto;margin-top: -30px;">
                <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="float: left; margin-right: 5px;">
                <span style="text-align: center; float: left; margin-top: 5px;">PREFEITURA MUNICIPAL DE BOQUIM<br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/><br/>
            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                
                <?php $gender = "";?>
                
                <div style=" height:100%;  border: 1px solid black; text-align: center; background-color: lightgray; margin-bottom: 5px;">DECLARAÇÃO</div>
                <br/>
                <span style="clear:both;display:block"></span>
                DECLARAMOS PARA OS DEVIDOS FINS QUE 
                <span class="name" style="font-weight: bold"></span>,
                FILHO(A) DE 
                <span class="mother"></span>
                E 
                <span class="father"></span>,
                NASCIDO(A) EM 
                <span class="birthday"></span>
                NA CIDADE DE 
                <span class="city"></span>,
                MATRICULOU-SE NESTA UNIDADE ESCOLAR NO ANO DE 
                <span class="enrollment_date"></span>
                NA EDUCAÇÃO INFANTIL E ENCONTRA-SE FREQUENTANDO REGULARMENTE AS AULAS.
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
                <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
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
    
    @media print {
        #container-header {
            width: 425px !important;
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
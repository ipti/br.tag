<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/TransferForm/_initialization.js', CClientScript::POS_END);

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
        <br>
        <div id="report">

            <div id="container-header" style="display:table; margin: 0 auto;margin-top: -30px;text-align: center;">                
                <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="margin: 0 auto; display: block">
                <span style="margin-top: 5px;">PREFEITURA MUNICIPAL DE BOQUIM<br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO<!--, CULTURA, ESPORTE, LAZER E TURISMO--><br>
                    BASE LEGAL: LEI FEDERAL - 9394/96 - LEI MUNICIPAL: 523/2006<br>
                    PARECER: 10/2007/CMEB, RESOLUÇÕES: 45/2010/CMEB, 44/2010/CMEB, 35/2009/CMEB, 23/2008/CMEB
                </span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/><br/>

            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <div style=" height:100%;  border: 1px solid black; text-align: center; background-color: lightgray; margin-bottom: 5px;">GUIA DE TRANSFERÊNCIA</div>
            </div>

            <table>
                <tr>
                    <td colspan="8">
                        UNIDADE ESCOLAR: <?php echo $school->name ?><br>
                        ENDEREÇO: <?php echo $school->address ?><br>
                        ATO DE CRIAÇÃO: ______________________________________________________________________________________________________________________________________________<br>
                        ATO DE AUTORIZAÇÃO: _______________________________________________________________________________________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center">ALUNO(A)</td>
                    <td colspan="5"></td>
                    <td style="text-align: center">ID. ALUNO</td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2" style="text-align: center">
                        LOCAL DE<br>
                        NASCIMENTO
                    </td>
                    <td colspan="4" style="text-align: center">CIDADE</td>
                    <td style="text-align: center">ESTADO</td>
                    <td style="text-align: center">NACIONALIDADE</td>
                    <td style="text-align: center">DATA DE NASCIMENTO</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center">DOCUMENTO DE IDENTIDADE - RG</td>
                    <td colspan="2" style="text-align: center">DATA DE EXPEDIÇÃO</td>
                    <td colspan="2" style="text-align: center">ORGÃO EXPEDIDOR</td>
                    <td style="text-align: center">ESTADO</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center">Nº DA CERTIDÃO DE NASCIMENTO</td>
                    <td style="text-align: center">FLS</td>
                    <td style="text-align: center">LIVRO</td>
                    <td colspan="2" style="text-align: center">DISTRITO/MUNICÍPIO</td>
                    <td style="text-align: center">ESTADO</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8">NOME DE PAI/MÃE: </td>
                </tr>
            
            
                <tr>
                    <td colspan="8" style="text-align: center">RESULTADO DE ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL
                        <br>LEI FEDERAL 9394/96 - LEI MUNICIPAL: 523/2006
                    </td>                    
                </tr>
                <tr>
                    <td rowspan="10" style="text-align: center">
                        BASE<br>
                        NACIONAL<br>
                        COMUM</td>
                    <td rowspan="2" colspan="3" style="text-align: center">ÁREA DE CONHECIMENTO</td>
                    <td colspan="4" style="text-align: center">RESULTADO FINAL</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">
                        1º CICLO PEDAGÓGICO<br>
                        1º ao 3º ESCOLAR
                    </td>
                    <td colspan="2" style="text-align: center">CORREÇÃO DE FLUXO</td>
                </tr>
                <tr>
                    <td colspan="3">LÍNGUA PORTUGUESA</td>                
                    <td rowspan="11" colspan="2">
                        (&nbsp;&nbsp;&nbsp;&nbsp;) PROMOVIDO<br><br>
                        (&nbsp;&nbsp;&nbsp;&nbsp;) EVADIDO<br><br>
                        (&nbsp;&nbsp;&nbsp;&nbsp;) TRANSFERIDO NO<br>
                        DECORRER DO ANO LETIVO
                    </td>                                
                    <td rowspan="11" colspan="2">
                        (&nbsp;&nbsp;&nbsp;&nbsp;) PROMOVIDO PARA<br>
                        ____________________________<br><br><br>
                        (&nbsp;&nbsp;&nbsp;&nbsp;) RETIDO NA<br>
                        ____________________________
                    </td>
                </tr>
                <tr><td colspan="3">ARTE</td></tr>
                <tr><td colspan="3">EDUCAÇÃO FÍSICA</td></tr>
                <tr><td colspan="3">HISTÓRIA</td></tr>
                <tr><td colspan="3">GEOGRAFIA</td></tr>
                <tr><td colspan="3">CIÊNCIAS NATURAIS</td></tr>
                <tr><td colspan="3">MATEMÁTICA</td></tr>
                <tr><td colspan="3">ENSINO RELIGIOSO</td></tr>
                <tr>
                    <td rowspan="3" style="text-align: center">
                        PARTE<br>
                        DIVERSIDADE
                    </td>
                </tr>
                <tr><td colspan="3">REDAÇÃO</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>


                <tr><td colspan="8" style="text-align: center">ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL</td></tr>
                <tr>
                    <td style="text-align: center">FORMA</td>
                    <td style="text-align: center">ANO</td>
                    <td style="text-align: center">
                        ANO DE<br>
                        CONCLUSÃO
                    </td>
                    <td style="text-align: center">
                        CARGA<br>
                        HORÁRIA
                    </td>
                    <td style="text-align: center">FREQUÊNCIA</td>
                    <td style="text-align: center">UNIDADE ESCOLAR</td>
                    <td style="text-align: center">MUNICÍPIO</td>
                    <td style="text-align: center">U.F.</td>
                </tr>
                <tr>
                    <td rowspan="3" style="text-align: center; transform: translate(5px, 0px) rotate(270deg);">1º CICLO</td>
                    <td style="text-align: center">
                        1º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        2º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        3º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="4" style="text-align: center; transform: translate(5px, 0px) rotate(270deg);">
                        CORREÇÃO<br>
                        DE FLUXO
                    </td>
                    <td style="text-align: center">
                        1º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        2º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        3º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center">
                        4º<br>
                        ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<style>
    label {
        font-weight: bold;
    }
    header-label {

    }
    table, td, tr, th {
        border: 1px solid;
        border-color: black;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    @media print {
        table, td, tr, th {
            border: 1px solid;
            border-color: black !important;
        }
        table {
            border-collapse: collapse;
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
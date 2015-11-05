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
        
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                jQuery.ajax({'type': 'GET',
                    'data': {'enrollment_id':<?php echo $enrollment_id;?>},
                    'url': '<?php echo Yii::app()->createUrl('reports/getTransferFormInformation') ?>',
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
        <div id="report" style="font-size: 10px">

            <div id="container-header" style="display:table; margin: 0 auto;margin-top: -30px;text-align: center;">                
                <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="margin: 0 auto; display: block">
                <span style="margin-top: 5px; font-size: 12px">PREFEITURA MUNICIPAL DE BOQUIM<br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO<!--, CULTURA, ESPORTE, LAZER E TURISMO--><br>
                    BASE LEGAL: LEI FEDERAL - 9394/96 - LEI MUNICIPAL: 523/2006<br>
                    PARECER: 10/2007/CMEB, RESOLUÇÕES: 45/2010/CMEB, 44/2010/CMEB, 35/2009/CMEB, 23/2008/CMEB
                </span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/>

            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <div style="text-align: center; margin-bottom: 5px; font-size: 12px">GUIA DE TRANSFERÊNCIA</div>
            </div>            
            
            <table>
                <tr>
                    <td colspan="8">
                        <span style="font-weight: bold">UNIDADE ESCOLAR: </span><?php echo $school->name ?><br>
                        <span style="font-weight: bold">ENDEREÇO: </span><?php echo $school->address ?><br>
                        <span style="font-weight: bold">ATO DE CRIAÇÃO: </span>___________________________________________________________________________________________________________________________________________<br>
                        <span style="font-weight: bold">ATO DE AUTORIZAÇÃO: </span>____________________________________________________________________________________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">ALUNO(A)</td>
                    <td colspan="5">
                        <span class="name"></span>
                    </td>
                    <td style="text-align: center; font-weight: bold">ID. ALUNO</td>
                    <td style="text-align: center">
                        <span class="inep_id"></span>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" style="text-align: center; font-weight: bold">
                        LOCAL DE<br>
                        NASCIMENTO
                    </td>
                    <td colspan="3" style="text-align: center; font-weight: bold">CIDADE</td>
                    <td style="text-align: center; font-weight: bold">ESTADO</td>
                    <td style="text-align: center; font-weight: bold">NACIONALIDADE</td>
                    <td colspan="2" style="text-align: center; font-weight: bold">DATA DE NASCIMENTO</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center">
                        <span class="birth_city"></span>
                    </td>
                    <td style="text-align: center">
                        <span class="birth_state"></span>
                    </td>
                    <td style="text-align: center">
                        <?php 
                            switch ($nationality) {
                                case '1':
                                    echo "BRASILEIRA";
                                    break;
                                case '2':
                                    echo "BRASILEIRA: NASCIDO NO EXTERIOR OU NATURALIZADO";
                                    break;
                                case '3':
                                    echo "ESTRANGEIRA";
                                    break;
                            }
                        ?>
                    </td>
                    <td colspan="2" style="text-align: center">
                        <span class="birthday"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-right: none">
                        <span style="font-weight: bold">DOCUMENTO DE IDENTIDADE - RG: </span>
                        <span class="rg_number"></span>
                    </td>
                    <td colspan="2" style="border-right: none; border-left: none">
                        <span style="font-weight: bold">DATA DE EXPEDIÇÃO: </span>
                        <span class="rg_date"></span>
                    </td>    
                    <td colspan="2" style="border-right: none; border-left: none">
                        <span style="font-weight: bold">ORGÃO EXPEDIDOR: </span>
                        <span class="rg_emitter"></span>
                    </td>    
                    <td colspan="1" style="border-left: none">
                        <span style="font-weight: bold">ESTADO: </span>
                        <span class="rg_uf"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-right: none">
                        <span style="font-weight: bold">CERTIDÃO DE NASCIMENTO Nº: </span>
                        <span class="civil_certification"></span>
                    </td>
                    <td colspan="1" style="border-right: none; border-left: none">    
                        <span style="font-weight: bold">FLS: </span>
                        <span class="civil_certification_sheet"></span>
                    </td>    
                    <td colspan="1" style="border-right: none; border-left: none">    
                        <span style="font-weight: bold">LIVRO: </span>
                        <span class="civil_certification_book"></span>
                    </td>    
                    <td colspan="2" style="border-right: none; border-left: none">    
                        <span style="font-weight: bold">DISTRITO/MUNICÍPIO: </span>
                        <span class="civil_certification_city"></span>
                    </td>
                    <td colspan="1" style="border-left: none">    
                        <span style="font-weight: bold">ESTADO: </span>
                        <span class="civil_certification_uf"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        <span style="font-weight: bold">NOME DE PAI/MÃE: </span>
                        <span class="father"></span> / 
                        <span class="mother"></span></td>
                </tr>
            
            
                <tr>
                    <td colspan="8" style="text-align: center; font-weight: bold">RESULTADO DE ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL
                        <br>LEI FEDERAL 9394/96 - LEI MUNICIPAL: 523/2006
                    </td>                    
                </tr>
                <tr>
                    <td rowspan="10" style="text-align: center; font-weight: bold">
                        BASE<br>
                        NACIONAL<br>
                        COMUM</td>
                    <td rowspan="2" colspan="3" style="text-align: center; font-weight: bold">ÁREA DE CONHECIMENTO</td>
                    <td colspan="4" style="text-align: center; font-weight: bold">RESULTADO FINAL</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold">
                        1º CICLO PEDAGÓGICO<br>
                        1º ao 3º ESCOLAR
                    </td>
                    <td colspan="2" style="text-align: center; font-weight: bold">CORREÇÃO DE FLUXO</td>
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
                    <td rowspan="3" style="text-align: center; font-weight: bold">
                        PARTE<br>
                        DIVERSIDADE
                    </td>
                </tr>
                <tr><td colspan="3">REDAÇÃO</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>


                <tr><td colspan="8" style="text-align: center; font-weight: bold">ESTUDOS REALIZADOS NO ENSINO FUNDAMENTAL</td></tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">FORMA</td>
                    <td style="text-align: center; font-weight: bold">ANO</td>
                    <td style="text-align: center; font-weight: bold">
                        ANO DE<br>
                        CONCLUSÃO
                    </td>
                    <td style="text-align: center; font-weight: bold">
                        CARGA<br>
                        HORÁRIA
                    </td>
                    <td style="text-align: center; font-weight: bold">FREQUÊNCIA</td>
                    <td style="text-align: center; font-weight: bold">UNIDADE ESCOLAR</td>
                    <td style="text-align: center; font-weight: bold">MUNICÍPIO</td>
                    <td style="text-align: center; font-weight: bold">U.F.</td>
                </tr>
                <tr>
                    <td rowspan="3" style="text-align: center; transform: translate(5px, 0px) rotate(270deg); font-weight: bold">1º CICLO</td>
                    <td style="text-align: center; font-weight: bold">
                        1º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        2º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        3º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="4" style="text-align: center; transform: translate(5px, 0px) rotate(270deg); font-weight: bold">
                        CORREÇÃO<br>
                        DE FLUXO
                    </td>
                    <td style="text-align: center; font-weight: bold">
                        1º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        2º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        3º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        4º ANO
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            
            <!--
                *****************************************************************
                **                                                             **
                **   TIRAR A DIV ESCONDIDA ABAIXO QUANDO FOR USAR ESSA PÁGINA  **
                **                                                             **
                *****************************************************************
            -->
            <div style="display: none">
                
                
                
                
                
                
            <div class="page-break"></div>
            
            <div style="width: 100%; text-align: center;">TRANSFERÊNCIA DURANTE O PERÍODO LETIVO</div>
            <table style="text-align: center">
                <tr>
                    <td rowspan="7">
                        LEI FEDERAL 9394/96<br>
                        E<br>
                        LEI MUNICIPAL<br>
                        523/2006
                    </td>
                    <td rowspan="7">
                        ÁREAS<br>
                        DE<br>
                        CONHECIMENTO
                    </td>
                </tr>
                <tr><td colspan="16">SIGNIFICADOS E TOTAL DE FALTAS</td></tr>
                <tr>
                    <td colspan="12">1º CICLO PEDAGÓGICO</td>
                    <td colspan="4">
                        CORREÇÃO<br>
                        DE FLUXO
                    </td>
                </tr>
                <tr>
                    <td colspan="12">ANOS ESCOLARES</td>
                    <td rowspan="2" colspan="4">ANOS ESCOLARES</td>
                </tr>
                <tr>
                    <td colspan="4">1º ANO</td>
                    <td colspan="4">2º ANO</td>
                    <td colspan="4">3º ANO</td>
                </tr>
                <tr>
                    <td colspan="4">UNIDADES</td>
                    <td colspan="4">UNIDADES</td>
                    <td colspan="4">UNIDADES</td>
                    <td rowspan="2">1º</td>
                    <td rowspan="2">2º</td>
                    <td rowspan="2">3º</td>
                    <td rowspan="2">4º</td>
                </tr>
                <tr>
                    <td>1ª</td>
                    <td>2ª</td>
                    <td>3ª</td>
                    <td>4ª</td>
                    <td>1ª</td>
                    <td>2ª</td>
                    <td>3ª</td>
                    <td>4ª</td>
                    <td>1ª</td>
                    <td>2ª</td>
                    <td>3ª</td>
                    <td>4ª</td>
                </tr>
                <tr><td rowspan="9" style="transform: translate(5px, 0px) rotate(270deg);">BASE NACIONAL COMUM</td></tr>
                <tr>
                    <td style="text-align: left">LÍNGUA PORTUGUESA</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">ARTE</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">EDUCAÇÃO FÍSICA</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">HISTÓRIA</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">GEOGRAFIA</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">CIÊNCIAS NATURAIS</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">MATEMÁTICA</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">ENS. RELIGIOSO</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="3">
                        PARTE<br>
                        DIVERSIFICADA
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left">REDAÇÃO</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">TOTAL DE FALTAS</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="3">
                        CARGA HORÁRIA<br>
                        TOTAL ANUAL
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left">C/ ENSINO RELIGIOSO</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: left">S/ ENSINO RELIGIOSO</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            
            <br>
            <table>
                <tr><td>ESPAÇO DESTINADO À UNIDADE ESCOLAR</td></tr>
                <tr><td>OBSERVAÇÕES<br><br><br><br><br></td></tr>
            </table>
            
            <br>
            <table>
                <tr><td>ESPAÇO DESTINADO À DIVISÃO DE INSPEÇÃO DA SECRETARIA DE EDUCAÇÃO DE BOQUIM</td></tr>
                <tr><td>OBSERVAÇÕES<br><br><br><br><br></td></tr>
            </table>
            
            <br>
            <table>
                <tr><td>****O(a) aluno(a)**** tem o direito a matrícula no ano do ****1º ciclo organizado em 9 anos**** / ****(&nbsp;&nbsp;&nbsp;) na correção de fluxo****, correspondido ao ****________ ano**** escolar.</td></tr>
                <tr>
                    <td>
                        OBSERVAÇÕES:<br><br>
                        1 - Os significados são por unidade e resultam da análise do desempenho global ****do(a) aluno(a)****, sintetizada da seguinte forma:<br>
                        <p style="text-indent: 50px">
                            <span style="margin-right: 50px">I - Insatisfatório</span>
                            <span style="margin-right: 50px">S - Satisfatório</span>
                            <span>MS - Muito Satisfatório</span>
                        </p>                        
                        2 - O parecer conclusivo, resultante da análise de aproveitamento global ****do(a) aluno(a)**** nas diferentes áreas do conhecimento no fim do ano escolar, expresso da seguinte forma:<br>
                        <p style="text-indent: 50px">
                            <span style="margin-right: 100px">Pr - Promovido</span>
                            <span>R - Retido</span>
                        </p>
                    </td>
                </tr>
            </table>
            
            <br><br><br>
            <span class="pull-right">Boquim/SE, <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "."; ?></span>
            <br><br><br>
            <div class="pull-left" style="text-align: center">
                <span>______________________________________________________________________</span>
                <br>
                <span>Secretário(a) da Escola</span>
                
            </div>            
            <div class="pull-right" style="text-align: center">
                <span>______________________________________________________________________</span>
                <br>
                <span>Diretor(a)</span>
            </div>
            
            
            
            
            
            <!--
                ****************************
                **                        **
                **   //END DIV ESCONDIDA  **
                **                        **
                ****************************
            -->
            </div>
            
            
            
            
        </div>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>

<style>
    label {
        font-weight: bold;
    }
    table, td, tr, th {
        border: 1px solid;
        border-color: black;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td {
        padding: 5px
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
        .page-break {
            page-break-after: always;
        }
    }
</style>
<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileBoquimReport/_initialization.js', CClientScript::POS_END);

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


<div class="innerLR boquim">
    <div>
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function($) {
                jQuery.ajax({'type': 'GET', 
                    'data': {'student_id':<?php echo $student_id; ?>}, 
                    'url': '<?php echo Yii::app()->createUrl('reports/getStudentsFileBoquimInformation')?>', 
                    'success': function(data) {
                        gerarRelatorio(data);
                    }, 'error': function() {
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
            <br>
            <div style="width: 100%; margin: 0 auto; text-align:center;margin-top: -15px;">
                <div style=" height:100%;  border: 1px solid black; background-color: lightgray; margin-bottom: 5px;">
                    <?php
                    if ($_REQUEST['type'] == '0') {
                        $namereport = 'FICHA INDIVIDUAL DO ALUNO - EDUCAÇÃO INFANTIL';
                        $enrollment_situation = 'SITUAÇÃO DA MATRÍCULA: ☐ MP ☐ MPC ☐ MT ☐ MR';
                        $pre = 'NA __________';
                    } else {
                        $namereport = 'FICHA INDIVIDUAL DO ALUNO - ENSINO FUNDAMENTAL';
                        $enrollment_situation = 'SITUAÇÃO DA MATRÍCULA: ☐ MI ☐ MC ☐ MR ☐ MT';
                        $pre = 'NO __________ ANO';
                    }
                    ?>
                    <?php echo $namereport ?>
                </div>
                <span style="clear:both;display:block"></span>
                <div style="border:1px solid black; float:left; width: 2.5cm; height: 3cm; text-align:center;margin-right: 15px;"><br><br><span>F O T O<br>3 x 4</span></div>
                <table style="border: 1px solid black;">
                    <tr>
                        <th rowspan="4" style="border-right: 1px solid black; vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 15px;line-height: 53px;margin: 0px 10px 0px 0px;">REQUERIMENTO</div></th>
                    <td colspan="3" style="border-bottom: 1px solid black;"><?php echo $enrollment_situation ?></td>
                    </tr>
                    <tr>
                        <td colspan="3">O INDICADO ABAIXO, IDENTIFICADO, REPRESENTADO QUANDO MENOR, REQUER SUA MATRÍCULA <?php echo $pre ?> DESTA UNIDADE DE ENSINO, NESTE ANO LETIVO, NESTES TERMOS, PEDE DEFERIMENTO.</td>
                    </tr>
                    <tr>	
                        <td style="">DATA: <!--<?php echo date("d/m/Y") ?>--></td>
                        <td colspan="2"  style="font-size: 10px;line-height: 11px;padding-top: 12px;">__________________________________________________________________________________
                            <br>Pai, Mãe ou Responsável
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid black;">
                        <td>
                            USO EXCLUSIVO DA U.E.
                            <br>☐ DEFERIDO ☐ INDEFERIDO</td>
                        <td colspan="2" style="font-size: 10px;line-height: 11px;padding-top: 12px;">
                            _______/_______/_________&nbsp;&nbsp;&nbsp;&nbsp;________________________________________________
                            <br><span style="display: table;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diretor</span>
                        </td>
                    </tr>
                </table>
                <div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span9"><b>DENOMINAÇÃO DO ESTABELECIMENTO: </b><?php echo $school->name ?></div>
                    <div class="span2"><b>INEP: </b><?php echo $school->inep_id ?></div>
                    <br>
                    <div class="span10"><b>ENDEREÇO: </b><?php echo $school->address ?></div>
                    <br>
                    <div class="span4"><b>CIDADE: </b><?php echo $school->edcensoCityFk->name ?></div>
                    <div class="span3"><b>ESTADO: </b><?php echo $school->edcensoUfFk->name ?></div>
                    <div class="span2"><b>CEP: </b><?php echo $school->cep ?></div>
                </div>
            </div>
            <br>





            <table id="report-table" class="table table-bordered">
                <tr><th style="text-align: center">BLOCO 1 - IDENTIFICAÇÃO E CADASTRO</th></tr>
                <tr>
                    <td>
                        <div class="span6"><b>01 - Nome do(a) Aluno(a):</b>&nbsp;<span class="name"></span></div>
                        <div class="span2"><b>ID:</b><span class="inep_id"></span></div>
                        <div class="span2"><b>NIS:</b><span class="nis"></span></div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span2"><b>03 - Naturalidade:</b></div>
                        <div class="span3"><b>Município:</b>&nbsp;<span class="birth_city"></span> </div>
                        <div class="span1"><b>UF:</b>&nbsp;<span class="birth_uf"></span></div>
                        <div class="span3"><b>Data&nbsp;de&nbsp;Nascimento:</b>&nbsp;<span class="birthday"></span></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span4"><b>04 - Gênero:</b>&nbsp;<span class="gender"></span></div>
                        <div class="span4"><b>05 - Etnia:</b>&nbsp;<span class="color"></span></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span2"><b>06 - Filiação:</b></div>
                        <div class="span4"><b>Pai: </b><span class="father"></span></div>
                        <div class="span4"><b>Mãe: </b><span class="mother"></span></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="old_cc">
                            <div class="span9"><b>07 - Certidão Civil de <span class="cc_type"></span>:</b></div>
                            <br/>
                            <div class="span2"><b>Nº: </b><span class="cc_number"></span></div>
                            <div class="span2"><b>Livro: </b><span class="cc_book"></span></div>
                            <div class="span2"><b>Folha: </b><span class="cc_sheet"></span></div>
                            <br><div class="span4"><b>Nome do Cartório: </b><span class="cc_name"></span></div>
                            <div class="span4"><b>Cidade: </b><span class="cc_city"></span></div>
                            <div class="span1"><b>UF: </b><span class="cc_uf"></span></div>
                        </div>
                        <div id="new_cc">
                            <div class="span9"><b>07 - Certidão Civil:</b>
                            </div>
                            <br/>	
                            <div class="span9"><b>Nº:</b><span class="cc_new"></span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10">
                            <b>ENDEREÇO: </b><span class="address"></span>, <span class="number"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <b>Cidade: </b><span class="adddress_city"></span>&nbsp;&nbsp;&nbsp;
                            <b>UF: </b><span class="address_uf"></span>&nbsp;&nbsp;&nbsp;
                            <b>CEP: </b><span class="cep"></span>
                        </div>
                        <br>
                        <div class="span5">
                            <b>08 - RG: </b><span class="rg"></span>
                        </div>
                        <div class="span5">
                            <b>09 - CPF: </b><span class="cpf"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span7" ><b>11 - Nome do Responsável e Parentesco: </b>
                            <br><b>☐</b>&nbsp;<span class="father"></span>&nbsp;(Pai)
                            <br><b>☐</b>&nbsp;<span class="mother"></span>&nbsp;(Mãe)
                            <br><hr style="margin-top: 10px; border-top: 1px solid black;"></div>
                        <div class="span3"><b>RG: </b>__________________________
                            <br><b>CPF: </b>_____.______.______-____
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>12 - Grau de Escolaridade do Responsável:</b></div>
                        <br><div class="span3" style="margin-right: -20px;">
                            <b>☐</b> Não Sabe Ler e Escrever
                            <br><b>☐</b> Sabe Ler e Escrever
                        </div>
                        <div class="span3" style="margin-right: -20px;">
                            <b>☐</b> Ens. Fund. Completo
                            <br><b>☐</b> Ens. Fund. Incompleto
                        </div>
                        <div class="span3" style="margin-right: -20px;">
                            <b>☐</b> Ens. Médio Completo
                            <br><b>☐</b> Ens. Médio Incompleto
                        </div>
                        <div class="span3" style="margin-right: -20px;">
                            <b>☐</b> Ens. Sup. Completo
                            <br><b>☐</b> Ens. Sup. Incompleto</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>13 - Profissão do Responsável: </b>
                            <hr style="margin-top: 15px;  border-top: 1px solid black;"></div>
                    </td>
                </tr>
            </table>




            <table style ="margin-top: 5px;" id="report-table" class="table table-bordered">
                <tr><th style="text-align: center">CARACTERIZAÇÃO</th></tr>
                <tr>
                    <td>
                        <div class="span10"><b>14 - Documentos(s) que habilita(m) matrícula no segmento: </b></div>
                        <br><div class="span10">OBS.: Se o requerente apresentar declaração, a matrícula ficará pendente no máximo 30 dias, até a entrega da guia de transferência. Após 30 dias a declaração perderá a validade ficando a matrícula sem efeito.</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span9"><b>15 - Data de Ingresso nesta Escola: </b> <!--<?php echo date("d/m/Y") ?>--></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span9"><b>16 - Situação do Aluno na Série/Etapa: </b></div>
                        <br><div class="span9"><b>☐</b> Primeira matrícula no Curso (Nível e/ou modalidade de ensino)
                            <br><b>☐</b> Promovido na série/etapa anterior do mesmo curso (nível e/ou modalidade de ensino)
                            <br><b>☐</b> Repetente</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>17 - Situação do Aluno no ano Anterior: </b></div>
                        <br><div class="span3" style="margin-right: -20px;">
                            <b>☐</b> Não Frequentou
                            <br><b>☐</b> Reprovado
                        </div>
                        <div class="span4" style="margin-right: -20px;">
                            <b>☐</b> Afastado por transferência
                            <br><b>☐</b> Matricula final em Educação Infantil
                        </div>
                        <div class="span3">
                            <b>☐</b> Afastado por abandono
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>18 - Portador de Necessidades Especiais? </b></div>
                        <br><div class="span2"><b>☐</b> Sim</div>
                        <div class="span2"><b>☐</b> Não</div>
                        <div class="span6"><b>Tipo: </b>__________________________________________________________________________</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>19 - Participa do Programa Bolsa Família? </b></div>
                        <br><div class="span2"><b>☐</b> Sim</div>
                        <div class="span2"><b>☐</b> Não</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>20 - Utiliza Transporte Escolar? </b></div>
                        <br><div class="span2"><b>☐</b> Sim</div>
                        <div class="span2"><b>☐</b> Não</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>21 - Restrição alimentar ou alergia a: </b><hr style="margin-top: 15px; border-top: 1px solid black;"></div>
                    </td>
                </tr>
            </table>
            <!--
            <div style="page-break-before: always;"></div>
            
            <div style="margin: 50px 0 15px 0;text-align: center;line-height: 15px;";>_______________________<br>Ano Letivo</div>
            
            <table style="margin: 0px 0 0 50px; font-size: 8px; width: calc(100% - 51px);" class="table table-bordered report-table-empty">
                      <tr>
                        <th colspan="18" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
                      </tr>
                      <tr>
                        <td></td>
                        <td style="text-align: center;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                        <td colspan="9" style="text-align: center; font-weight: bold">BASE NACIONAL COMUM</td>
                        <td colspan="4" style="text-align: center; font-weight: bold">PARTE DIVERSIFICADA</td>
                        <td rowspan="2" class="vertical-text"><div>DIAS&nbsp;LETIVOS</div></td>
                        <td rowspan="2" class="vertical-text"><div>CARGA&nbsp;HORÁRIA</div></td>
                        <td rowspan="2" class="vertical-text"><div>Nº&nbsp;DE&nbsp;FALTAS</div></td>
                      </tr>
                      <tr>
                        <td style="vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 0;line-height: 53px;margin: 0px 10px 0px 0px;">BIMESTRES</div></td>
                        <td class="vertical-text"><canvas width="100%" height="100%"></canvas></td>
                        <td class="vertical-text"><div>LÍNGUA&nbsp;PORTUGUESA</div></td>
                        <td class="vertical-text"><div>MATEMÁTICA</div></td>
                        <td class="vertical-text"><div>HISTÓRIA</div></td>
                        <td class="vertical-text"><div>GEOGRAFIA</div></td>
                        <td class="vertical-text"><div>ARTES</div></td>
                        <td class="vertical-text"><div>EDUCAÇÃO&nbsp;FÍSICA</div></td>
                        <td class="vertical-text"><div>ENSINO&nbsp;RELIGIOSO</div></td>
                        <td class="vertical-text"><div>CIÊNCIAS&nbsp;NATURAIS</div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div>REDAÇÃO</div></td>
                        <td class="vertical-text"><div>SOCIEDADE&nbsp;BRASILEIRA</div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                      </tr>
                      <tr>
                        <td>1º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td>2º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td>3º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td>4º</td>
                        <td style="text-align: center;">AVALIAÇÃO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">MÉDIA ANUAL</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">NOTA DA PROVA FINAL</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">MÉDIA FINAL</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">TOTAL DE AULAS DADAS</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">TOTAL DE FALTAS</td>
                       <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">FREQUÊNCIAS %</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                    </table>
                    
                    <div style="transform: rotate(-90deg);transform-origin: left top 0;text-align: center;width: 300px;font-size: 16px;line-height: 20px;">QUADRO APLICÁVEL AO ENSINO<br> FUNDAMENTAL (1º ao 5º ANO)</div>
                    
                    <table style="margin: -20px 0 0 50px; font-size: 8px; width: calc(100% - 53px);" class="table table-bordered report-table-empty">
                      <tr>
                        <th colspan="22" style="text-align: center">RENDIMENTO ESCOLAR POR ATIVIDADES</th>
                      </tr>
                      <tr>
                        <td></td>
                        <td style="text-align: center;">PARTES&nbsp;DO&nbsp;CURRÍCULO</td>
                        <td colspan="11" style="text-align: center; font-weight: bold">BASE NACIONAL COMUM</td>
                        <td colspan="9" style="text-align: center; font-weight: bold">PARTE DIVERSIFICADA</td>
                      </tr>
                      <tr>
                        <td style="vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 0;line-height: 53px;margin: 0px 10px 0px 0px;">BIMESTRES</div></td>
                        <td id="canvas-td" class="vertical-text"></td>
                        <td class="vertical-text"><div>LÍNGUA&nbsp;PORTUGUESA</div></td>
                        <td class="vertical-text"><div>MATEMÁTICA</div></td>
                        <td class="vertical-text"><div>CIÊNCIAS&nbsp;NATURAIS</div></td>
                        <td class="vertical-text"><div>GEOGRAFIA</div></td>
                        <td class="vertical-text"><div>HISTÓRIA</div></td>
                        <td class="vertical-text"><div>ARTES</div></td>
                        <td class="vertical-text"><div>EDUCAÇÃO&nbsp;FÍSICA</div></td>
                        <td class="vertical-text"><div>ENSINO&nbsp;RELIGIOSO</div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div style="margin-bottom: -7px; line-height: 9px;">LÍNGUA&nbsp;ESTRANGEIRA INGLÊS</div></td>
                        <td class="vertical-text"><div>REDAÇÃO</div></td>
                        <td class="vertical-text"><div>SOCIEDADE&nbsp;BRASILEIRA</div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                        <td class="vertical-text"><div></div></td>
                      </tr>
                      <tr>
                        <td rowspan="3">1º</td>
                        <td style="text-align: center;">APROVEITAMENTO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">CARGA&nbsp;HORÁRIA</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">FALTAS</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td rowspan="3">2º</td>
                        <td style="text-align: center;">APROVEITAMENTO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">CARGA&nbsp;HORÁRIA</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">FALTAS</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td rowspan="3">3º</td>
                        <td style="text-align: center;">APROVEITAMENTO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">CARGA&nbsp;HORÁRIA</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">FALTAS</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td rowspan="3">4º</td>
                        <td style="text-align: center;">APROVEITAMENTO</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">CARGA&nbsp;HORÁRIA</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td style="text-align: center;">FALTAS</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      
                      <tr>
                        <td colspan="2">MÉDIA ANUAL</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">NOTA DA PROVA FINAL</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">MÉDIA FINAL</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">TOTAL DE AULAS DADAS</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">TOTAL DE FALTAS</td>
                       <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                      <tr>
                        <td colspan="2">FREQUÊNCIAS %</td>
                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                      </tr>
                    </table>
                    
                    <div style="transform: rotate(-90deg);transform-origin: left top 0;text-align: center;width: 430px;font-size: 16px;line-height: 20px;">QUADRO APLICÁVEL AO ENSINO<br> FUNDAMENTAL (6º ao 9º ANO)</div>
                    
                    <div style="text-align:right; margin-top: -25px;">Resultado Final _____________________________</div>
                    <div style="text-align:center">APTO PARA CURSAR O _____________ ANO DO ENSINO FUNDAMENTAL<div>
                    <div style="text-align: center;line-height: 15px;">_________________________________________________________<br>Local e data<div>
                    <div style="float: left;line-height: 15px; width:50%">_________________________________________________________<br>Assinatura do(a) Secretário (a)</div>
                    <div style="float: right;line-height: 15px;width:50%">_________________________________________________________<br>Assinatura do(a) Diretor(a)</div>
            -->
        </div>
    </div>
</div>
</div>

<style>
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
    </style>
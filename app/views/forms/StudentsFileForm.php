<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
$enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div id="body-students-file-form" class="innerLR district">
    <div>
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                jQuery.ajax({'type': 'GET',
                    'data': {'enrollment_id':<?php echo $enrollment_id; ?>},
                    'url': '<?php echo Yii::app()->createUrl('reports/getStudentsFileInformation') ?>',
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
            <div id="container-header" style="width: 100%; margin: -25px auto auto auto; text-align: center">
                <?php if(isset($school->act_of_acknowledgement)){?>
                <span style="display:block;clear:both;width: 40px;margin:0 auto;">
                    <?php
                    if(isset($school->logo_file_name)){
                        echo '<img src="data:'.$school->logo_file_type.';base64,'.base64_encode($school->logo_file_content).'" width="40px" style="margin: 0 auto; display: block">';
                    };
                ?>
                </span>
                <p style="font-weight:bold;font-size:15px"><?php echo $school->name ?></p>
                <p style="font-size:10px;font-weight: bold"><?php echo $school->act_of_acknowledgement ?></p>
                <p style="font-size:8px;font-weight: bold">
                    <?php echo $school->address ?>,
                    <?php echo $school->edcensoCityFk->name ?>/
                    <?php echo $school->edcensoUfFk->name ?> - CEP: <?php echo $school->cep ?>
                    CÓDIGO DO INEP: <?php echo $school->inep_id ?>
                </p>
                <span style="display: block; clear: both"></span>
                <?php }else{?>
                <img src="/images/boquim.png" width="40px" style="float: left; margin-right: 5px;">
                <span style="text-align: center; float: left; margin-top: 5px;">PREFEITURA MUNICIPAL DE <?=strtoupper($school->edcensoCityFk->name)?><br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO<br>
                    <?php echo $school->name ?>
                    CÓDIGO DO INEP: <?php echo $school->inep_id ?>
                    <?php //strtoupper($school->report_header)?></span>
                <span style="clear:both;display:block"></span>
                <?php }?>
            </div>
            <br/>
            <div style="width: 100%; margin: 0 auto; text-align:center;margin-top: -15px;">
                <?php
               /* if ($_REQUEST['type'] == '3') {
                    echo '<div style=" height:100%;  border: 1px solid black; background-color: lightgray; margin-bottom: 5px;">'
                    . 'PROCESSO DE RECONHECIMENTO NO CMEB Nº 216.02\2013 - RESOLUÇÃO CMEB Nº 75\2014'
                    . '</div>';
                }*/
                ?>
                <div style=" height:100%;  border: 1px solid black; background-color: lightgray; margin-bottom: 5px;">
<?php //echo $namereport ?>
                    <?php echo 'FICHA INDIVIDUAL DO ALUNO - '?>
                    <span class="stage"></span>
                </div>
                <span style="clear:both;display:block"></span>
                <div style="border:1px solid black; float:left; width: 2.5cm; height: 3cm; text-align:center;margin-right: 15px;"><br><br><span>F O T O<br>3 x 4</span></div>
                <table style="border: 1px solid black;">
                    <tr>
                        <?php
                            if ($_REQUEST['type'] == '0') {
                                echo '<th rowspan="4" style="border-right: 1px solid black; vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 15px;line-height: 53px;margin: 0px 10px 0px 0px;">REQUERIMENTO</div></th>';
                                echo '<td colspan="3" style="border-bottom: 1px solid black;">'
                                    . 'SITUAÇÃO DA MATRÍCULA: ☐ MPP ☐ MPC ☐ MPR'
                                    . '</td>';
                            } else if ($_REQUEST['type'] == '1' or $_REQUEST['type'] == '2') {
                                echo '<th rowspan="4" style="border-right: 1px solid black; vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 15px;line-height: 53px;margin: 0px 10px 0px 0px;">REQUERIMENTO</div></th>';
                                echo '<td colspan="3" style="border-bottom: 1px solid black;">'
                                    . 'SITUAÇÃO DA MATRÍCULA: ☐ MI ☐ MC ☐ MR ☐ MT'
                                    . '</td>';
                            } else if ($_REQUEST['type'] == '3') {
                                echo '<th rowspan="4" style="border-right: 1px solid black; vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 15px;line-height: 53px;margin: 0px 10px 0px 0px;">REQUERIMENTO</div></th>';
                                echo '<td colspan="3" style="border-bottom: 1px solid black;">'
                                    . 'SITUAÇÃO DA MATRÍCULA: ☐ MP ☐ MPC ☐ MT ☐ MR'
                                    . '</td>';
                            }
                        ?>
                    </tr>
                    <tr>
                        <?php
                        if ($_REQUEST['type'] == '2') {
                            echo '<td colspan="3">O(A) ALUNO(A) REQUER SUA MATRÍCULA ';
                        ?>
                            NO_________ANO
                        <!--<span class="class"></span>-->
                        <?php
                            echo ', DE ACORDO COM SITUAÇÃO APRESENTADA ABAIXO, A QUAL PEDE DEFERIMENTO.</td>';
                        } else {
                            echo '<td colspan="3">O INDICADO ABAIXO, IDENTIFICADO, REPRESENTADO QUANDO MENOR, REQUER SUA MATRÍCULA ';
                        ?>
                            <?php
                                if ($_REQUEST['type'] == '0'){
                                    echo '<span class="class"></span>';
                                }else{
                                    echo 'NO_________ANO';
                                }
                            ?>
                        <?php
                            echo ' DESTA UNIDADE DE ENSINO, NESTE ANO LETIVO, NESTES TERMOS, PEDE DEFERIMENTO.</td>';
                        }
                        ?>
                    </tr>
                    <tr>	
                        <td style="">DATA: <?php echo date('d/m/y',strtotime($enrollment->create_date));?></td>
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
                            <br><span style="display: table;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diretor(a)</span>
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
                        <div class="span6"><b>01 - Nome do(a) aluno(a):</b>&nbsp;<span class="name"></span></div>
                        <div class="span2"><b>ID:</b><span class="inep_id"></span></div>
                        <div class="span2"><b>NIS:</b><span class="nis"></span></div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span2"><b>03 - Naturalidade:</b></div>
                        <div class="span3"><b>Município:</b>&nbsp;<span class="birth_city"></span> </div>
                        <div class="span1"><b>UF:</b>&nbsp;<span class="birth_uf"></span></div>
                        <div class="span3"><b>Data&nbsp;de&nbsp;nascimento:</b>&nbsp;<span class="birthday"></span></div>
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
                            <div class="span9"><b>07 - Certidão Civil de <span class="cc_type"></span></b></div>
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
                        <div class="span5" >
                            <b>11 - Nome do responsável e parentesco: </b>
                            <br><span class="responsable_name"></span>
                        </div>
                        <div class="span5">
                            <br/>
                            <b>RG: </b><span class="responsable_rg"></span>
                            <b>CPF: </b><span class="responsable_cpf"></span>
                            <b>Telefone: </b><span class="responsable_telephone"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="span10"><b>12 - Grau de escolaridade do responsável:</b>
                            <br><span class="responsable_scholarity"></span>
                            </<div>
                                </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="span10"><b>13 - Profissão do responsável: </b>
                                            <br><span class="responsable_job"></span>
                                        </div>
                                    </td>
                                </tr>
                                </table>




                                <table style ="margin-top: 5px;" id="report-table" class="table table-bordered">
                                    <tr><th style="text-align: center">CARACTERIZAÇÃO</th></tr>
                                    <tr>
                                        <td>
                                            <div class="span10"><b>14 - Documentos(s) que habilita(m) matrícula no segmento: </b>
                                                <!--CORRIGIR AQUI--->
                                                <!--<div class="received_documents"></div>-->
                                                <br><b>OBS.</b>: Se o requerente apresentar declaração, a matrícula ficará pendente no máximo 30 dias, até a entrega da guia de transferência. Após 30 dias a declaração perderá a validade ficando a matrícula sem efeito.
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="span9"><b>15 - Data de ingresso nesta escola: <span style="font-size:12px;" class="school_admission_date"></span></b>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="span9"><b>16 - Situação do aluno na série/etapa: </b>
                                                    <br>
                                                    <div style="margin-right: -20px;">
                                                        <?php
                                                        if ($_REQUEST['type'] == '0'){
                                                            echo ' <span class="current_stage_situation"></span>';
                                                        }else{?>
                                                            <b>☐</b> Primeira matrícula no Curso (Nível e/ou modalidade de ensino)
                                                            <br/> <b>☐</b> Promovido na série/etapa anterior do mesmo curso(nível e/ou modalidade de ensino)
                                                            <br/> <b>☐</b> Repetente
                                                        <?php } ?>

                                                    </div>

                                            </div>


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
                                    <!--<tr>
                                        <td>
                                            <div class="span10"><b>17 - Situação do aluno no ano anterior: </b>
                                                <br/>
                                                <br>
                                                <!--<br><span class="previous_stage_situation"></span>
                                            </div>
                                        </td>
                                    </tr>-->
                                    <tr><td>
                                            <div class="span11"><b>18 - Portador de Necessidades Especiais? </b></div>
                                            <br><div class="span2"><b>☐</b> Sim</div>
                                            <div class="span2"><b>☐</b> Não</div>
                                            <div class="span7"><b>Tipo: </b>__________________________________________________________________________</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="span10"><b>19 - Participa do Programa Bolsa Família? </b>
                                                <br><span class="bf_participator"></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="span10"><b>20 - Utiliza transporte escolar? </b>
                                                <br><span class="public_transport"></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="span10"><b>21 - Restrição alimentar ou alergia a: </b>
                                                <br><span class="food_restrictions"></span>
                                            </div>
                                        </td>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <td>-->
<!--                                            <div class="span10"><b>22 - Aluno com restrição na Justiça?</b>-->
<!--                                                <br><span class="justice_restriction"></span>-->
<!--                                            </div>-->
<!--                                        </td>-->
<!--                                    </tr>-->
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
                        <?php $this->renderPartial('footer'); ?>
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
                                #body-students-file-form{zoom:170%;}
                            }
                        </style>
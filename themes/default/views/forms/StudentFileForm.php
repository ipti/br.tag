<?php

/**
* @var ReportsController $this ReportsController 
* @var $report mixed 
*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
$enrollment = StudentEnrollment::model()->findByPk($enrollment_id);

$turns = ['M'=>'Manhã', 'T' => 'Tarde', 'N' => 'Noite'];



?>


<div id="body-students-file-form" class="pageA4V">
    <?php

    $this->renderPartial('head');
    $data = $enrollment->getFileInformation($enrollment_id);
    $birth_uf = $enrollment->studentFk->edcensoUfFk->acronym;

    ?>
    <br>
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
            <span class="stage"><?php echo $data['stage']?></span>
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
                        . 'SITUAÇÃO DA MATRÍCULA: ☐ MI ☐ MC ☐ MR ☐ MT'
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
                <td>DATA: _______/_______/_________</td>
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
                    <br><span style="display: table;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diretor(a)</span>
                </td>
            </tr>
        </table>
        <style type="text/css">
            .subheader {margin:5px 0px}
            .subheader div{margin:0px !important;}
        </style>

        <div class="subheader" style="float: left; text-align: justify;line-height: 16px;">
            <div class="span11"><b>DENOMINAÇÃO DO ESTABELECIMENTO: </b><?php echo $school->name ?></div>
            <div class="span1"><b>INEP: </b><?php echo $school->inep_id ?></div>
            <br>
            <div class="span10"><b>ENDEREÇO: </b><?php echo $school->address ?></div>
            <br>
            <div class="span6"><b>CIDADE: </b><?php echo $school->edcensoCityFk->name ?></div>
            <div class="span3"><b>ESTADO: </b><?php echo $school->edcensoUfFk->name ?></div>
            <div class="span3"><b>CEP: </b><?php echo $school->cep ?></div>
        </div>
    </div>
    <br>





    <table id="report-table" class="table table-bordered">
        <tr><th style="text-align: center">BLOCO 1 - IDENTIFICAÇÃO E CADASTRO</th></tr>
        <tr>
            <td>
                <div class="span12"><b>01 - Nome do(a) aluno(a):</b>&nbsp;<span class="name"><?= $data['name'] ?></span></div>
                <br>
                <div class="span12"><b>02 - Nome social:</b>&nbsp;<span class="name"></span></div>
                <br>
                <div class="span4"><b>Data&nbsp;de&nbsp;nascimento:</b>&nbsp;<span class="birthday"><?= $data['birthday'] ?></span></div>
                <div class="span4"><b>ID INEP: </b><span class="inep_id"><?= $data['inep_id'] ?> </span></div>
                <div class="span4"><b>NIS: </b><span class="nis"><?= $data['nis'] ?> </span></div>

            </td>
        </tr>
        <tr>
            <td>
                <div class="span4"><b>03 - Naturalidade:</b></div>
                <div class="span8"><b>Município: </b>&nbsp;<span class="birth_city"><?= $data['birth_city'] ?></span>/<?php echo $birth_uf ?> </div>
                <div class="span12">
                    <b>ENDEREÇO: </b><span class="address"><?= $data['address'] ?></span>, <span class="number"><?= $data['number'] ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>Cidade: </b><span class="adddress_city"><?= $data['adddress_city'] ?></span>&nbsp;&nbsp;&nbsp;
                    <b>UF: </b><span class="address_uf"><?= $data['address_uf'] ?></span>&nbsp;&nbsp;&nbsp;
                    <b>CEP: </b><span class="cep"><?= $data['cep'] ?></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span4"><b>04 - Gênero:</b>&nbsp;<span class="gender"><?= $data['gender'] ?></span></div>
                <div class="span4"><b>05 - Etnia:</b>&nbsp;<span class="color"><?= $data['color'] ?></span></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span12"><b>06 - Mãe</b></div>
                <div class="span12"><b>Nome: </b><span class="mother"><?= $data['mother'] ?></span></div>
                <div class="span4"><b>RG: </b><span class="cc_number"><?= $data['mother_rg'] ?></div>
                <div class="span8"><b>CPF: </b><span class="cc_number"><?= $data['mother_cpf'] ?><span class="father"></span></div>
                <br/>
                <div class="span4"><b>Profissão: </b><span class="mother"><?= $data['mother_job'] ?></span></div>
                <div class="span6"><b>Grau de instrução: </b><span class="mother"><?= $data['mother_scholarity'] ?></span></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span12"><b>07 - Pai</b></div>
                <div class="span12"><b>Nome: </b><span class="mother"><?= $data['father'] ?></span></div>
                <div class="span4"><b>RG: </b><span class="cc_number"><?= $data['father_rg'] ?></span></div>
                <div class="span8"><b>CPF: </b><span class="father"><span class="cc_number"><?= $data['father_cpf'] ?></span></div>
                <br/>
                <div class="span4"><b>Profissão: </b><span class="mother"><?= $data['father_job'] ?></span></div>
                <div class="span6"><b>Grau de instrução: </b><span class="mother"><?= $data['father_scholarity'] ?></span></div>
            </td>
        </tr>
        <tr>
            <td>
                <?php if($data['cc'] == 1){ ?>
                    <div id="old_cc">
                        <div class="span9"><b>08 - Certidão Civil de <span class="cc_type"><?= $data['cc_type'] ?></span></b></div>
                        <br/>
                        <div class="span2"><b>Nº: </b><span class="cc_number"><?= $data['cc_number'] ?></span></div>
                        <div class="span2"><b>Livro: </b><span class="cc_book"><?= $data['cc_book'] ?></span></div>
                        <div class="span2"><b>Folha: </b><span class="cc_sheet"><?= $data['cc_sheet'] ?></span></div>
                        <br><div class="span4"><b>Nome do Cartório: </b><span class="cc_name"><?= $data['cc_name'] ?></span></div>
                        <div class="span4"><b>Cidade: </b><span class="cc_city"><?= $data['cc_city'] ?></span></div>
                        <div class="span1"><b>UF: </b><span class="cc_uf"></span><?= $data['cc_uf'] ?></div>
                    </div>
                <?php } else{ ?>
                    <div id="new_cc">
                        <div class="span9"><b>08 - Certidão Civil:</b>
                        </div>
                        <br/>
                        <div class="span9"><b>Nº:</b><span class="cc_new"><?= $data['cc_new'] ?></span></div>
                    </div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span4">
                    <b>09 - RG: </b><span class="rg"><?= $data['rg'] ?></span>
                </div>
                <div class="span4">
                    <b>10 - CPF: </b><span class="cpf"><?= $data['cpf'] ?></span>
                </div>
                <div class="span4">
                    <b>11 - CNS: </b><span class="cns"><?= $data['cns'] ?></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span12" >
                    <b>12 - Nome do responsável e parentesco: </b><span class="responsable_name"><?= $data['responsable_name'] ?></span>
                </div>
                <div class="span4">
                    <b>RG: </b><span class="responsable_rg"><?= $data['responsable_rg'] ?></span>
                </div>
                <div class="span4">
                    <b>CPF: </b><span class="responsable_cpf"><?= $data['responsable_cpf'] ?></span>
                </div>
                <div class="span4">
                    <b>Telefone: </b><span class="responsable_telephone"><?= $data['responsable_telephone'] ?></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span10"><b>13 - Grau de escolaridade do responsável:</b>
                    <br><span class="responsable_scholarity"><?= $data['responsable_scholarity'] ?></span>
                </<div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span10"><b>14 - Profissão do responsável: </b>
                    <br><span class="responsable_job"><?= $data['responsable_job'] ?></span>
                </div>
            </td>
        </tr>
    </table>




    <table style="margin-top:10px;" id="report-table" class="table table-bordered">
        <tr><th style="text-align: center">CARACTERIZAÇÃO</th></tr>
        <?php if(GLOGALGROUP != 1){?>
            <tr>
                <td>
                    <div class="span12"><b>15 - Matrícula do aluno: </b></div>
                    <br>
                    <div class="span3"><b>Ano letivo: </b><span><?= $enrollment->classroomFk->school_year ?></span></div>
                    <div class="span4"><b>Série: </b><span><?= $enrollment->classroomFk->name ?></span></div>
                    <div class="span5"><b>Turma: </b><span><?= $enrollment->classroomFk->name ?></span></div>
                    <div class="span9"><b>Situação do aluno: </b><span>
                        <?php 
                              
                                switch ($enrollment->status) {
                                    case "1":
                                        echo "Matriculado";
                                        break;
                                    case "2":
                                        echo "Transferido";
                                        break;
                                    case "3":
                                        echo "Cancelado";
                                        break;
                                    case "4":
                                        echo "Evadido";
                                        break;
                                    default:
                                       echo '';
                                }
                    
                        ?>
                    </span></div>
                    <div class="span3"><b>Turno: </b><span> <?php echo $turns[$enrollment->classroomFk->turn]; ?></span></div>
                </td>
            </tr>
        <?php }?>
        <tr>
            <td>
                <div class="span10"><b>16 - Documentos(s) que habilita(m) matrícula no segmento: </b>
                    <!--CORRIGIR AQUI--->
                    <!--<div class="received_documents"></div>-->
                    <br>
                    <br>
                    <b>OBS.</b>: <span> Se o requerente apresentar declaração, a matrícula ficará pendente no máximo 30 dias, até a entrega da guia de transferência. Após 30 dias a declaração perderá a validade ficando a matrícula sem efeito.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span9"><b>17 - Data de ingresso nesta escola: <span style="font-size:12px;" class="school_admission_date"><?= $data['school_admission_date'] ?></span></b>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span9"><b>18 - Situação do aluno na série/etapa: </b>
                    <br>
                    <div style="margin-right: -20px;">
                        <?php
                        if ($_REQUEST['type'] == '0'){ ?>
                            <span class="current_stage_situation"><?= $data['current_stage_situation'] ?></span>
                            <?php
                        }else{?>
                            <div class="padding-5"><b>☐</b> Primeira matrícula no Curso (Nível e/ou modalidade de ensino)</div>
                            <div class="padding-5"><b>☐</b> Promovido na série/etapa anterior do mesmo curso (nível e/ou modalidade de ensino)</div>
                            <div class="padding-5"> <b>☐</b> Repetente</div>
                        <?php } ?>

                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span10"><b>19 - Situação do Aluno no ano Anterior: </b></div>
                <br><div class="span3 padding-5" style="margin-right: -20px;">
                    <b>☐</b> Não Frequentou
                    <br><b>☐</b> Reprovado
                </div>
                <div class="span4 padding-5" style="margin-right: -20px;">
                    <b>☐</b> Afastado por transferência
                    <br><b>☐</b> Matricula final em Educação Infantil
                </div>
                <div class="span3 padding-5">
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
                <div class="span12"><b>20 - Portador de Necessidades Especiais? </b></div>
                <br>
                <div class="span2"><b>☐</b> Sim</div>
                <div class="span2"><b>☐</b> Não</div>
                <div class="span8" style="margin-bottom:8px;"><b>Tipo: </b><span>__________________________________________________________</span></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span12"><b>21 - Participa do Programa Bolsa Família? </b></div>
                <!-- <br><span class="bf_participator"><?= $data['bf_participator'] ?></span> -->
                <div class="span4"><b>☐</b> Bolsa Família</div>
                <div class="span4"><b>☐</b> PETI</div>
                <div class="span4"><b>☐</b> Pro Jovem</div>
                <br>
                <div class="span12 margin-15"><b>Outro: </b>____________________________________________________</div>

            </td>
        </tr>
        <tr>
            <td>
                <div class="span10"><b>22 - Utiliza transporte escolar? </b>
                    <br><span class="public_transport"><?= $data['public_transport'] ?></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="span10"><b>23 - Restrição alimentar ou alergia a: </b>
                    <br><span class="food_restrictions"><?= $data['food_restrictions'] ?></span>
                </div>
            </td>
        </tr>
    </table>
</div>

<?php if(GLOGALGROUP != 1){?>
    <div class='container-report mt-30 mb-30'>
    <table class="table-border">
      <tr class="blue-background titleBig text-center">
        <th colspan="8">Controle de Matrícula e Rematrícula</th>
      </tr>
      <tr>
        <th>Ano Letivo</th>
        <th>Data da Matrícula</th>
        <th>Nível/Ano/Série</th>
        <th>Etapa Modalidade*</th>
        <th>Turno</th>
        <th>Situação Final do(a) Aluno(a)</th>
        <th>Assinatura do(a) Responsável pela Matrícula</th>
        <th>Assinatura dos Pais ou Responsável</th>
      </tr>
      <? for($i=1; $i <= 12; $i++): ?>
        <tr>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
        </tr>
      <? endfor; ?>
    </table>
  </div>
</div>
<?php } ?>
<style>

    .container-report {
        width: 980px;
        margin: auto;
    }

    .container-report:after {
        clear: both;
    }

    .container-report:before, .container-report:after {
        display: table;
        content: "";
        line-height: 0;
    }

    .mt-60 {
        margin-top: 60px;
    }

    .mt-30 {
        margin-top: 30px;
    }

    .mb-30 {
        margin-bottom: 60px;
    }

    .blue-background {
        background-color: #C6D9F1;
    }

    .contentEditable {
        padding: 5px;
    }
    

    .titleBig {
        font-size: 15px;
        line-height: 25px;
        font-weight: bold;
    }
    
    .text-uppercase {
        text-transform: uppercase;
    }
    
    .table-border, .table-border th, .table-border td {
       border: 1px solid black;
        border-collapse: collapse;
    }

    .table-border td, .table-border th {
        padding: 8px;
    }
    
    .table-border { 
        width: 100%;
        page-break-inside:auto
    }

    @media screen{
        .pageA4V{width:980px; height:1400px; margin:0 auto;}
        .pageA4H{width:1400px; height:810px; margin:0 auto;}
        #header-report ul#info, #header-report ul#addinfo {
            float: right;
            width: 970px;
            margin: 0;
            overflow: hidden;
        }
    }
    @media print {
        .pageA4V{width:960px; height:1200px; margin:0 auto; font-size: 15px; }
        .pageA4H{width:1122px; height:810px; margin:0 auto; font-size: 15px;}

        .padding-5{
            padding: 5px 0 0 0;
        }

        .margin-15{
            margin-top: 8px;
            margin-bottom: 7px;
        }

        #header-report{
            width:820px;
        }

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
            line-height: 16px;
            margin: 0px 10px 0px 0px;
        }
        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat:no-repeat;
            background-position:center center;
            background-size: 100% 100%, auto;
        }

        .blue-background {
          --webkit-print-color-adjust: exact;
          background-color: #C6D9F1 !important;
        }

        #body-students-file-form {
            page-break-after: always;
        }

        .titleBig {
            border-right: 1px solid black !important;
        }

        .legend {
            line-height: 40px;
        }

        .btn-print {
            border-top: 1px solid black !important;
        }

    }
</style>
<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileBoquimReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Reports') => array('/reports'),
    Yii::t('default', 'Students\'s File'),
);

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Ficha Individual do Aluno'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div><?php
        echo CHtml::dropDownList('student', null, chtml::listData(StudentIdentification::model()->findAll(), 'id', 'name'), array(
            'class' => 'select-search-on hidden-print',
            'prompt' => 'Selecione um Aluno',
            'ajax' => array(
                'type' => 'GET',
                'data' => array('student_id' => 'js:this.value'),
                'url' => CController::createUrl('reports/getStudentsFile'./*Boquim*/'Information'),
                'success' => "function(data){ gerarRelatorio(data); }",
                'error' => "function(){ limpar(); }"
        )));
        ?>
        <br>
        <div id="report">
			
			<div id="report-logo" style="width: 600px; margin: 0 auto;">
			    <img src="../../../images/boquim.png" width="40px" style="float: left">
			    <span style="text-align: center; float: left; margin-top: 5px;">PREFEITURA MUNICIPAL DE BOQUIM<br>
			    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
			</div>
            <span style="clear:both;display:block"></span>
			<br>
			<div style="width: 100%; margin: 0 auto; text-align:center;">
				<div style=" height:100%;  border: 1px solid black; background-color: lightgray; margin-bottom: 5px;">FICHA INDIVIDUAL DO ALUNO - ENSINO FUNDAMENTAL</div>
                <span style="clear:both;display:block"></span>
				<div style="border:1px solid black; float:left; width: 3cm; height: 4cm; text-align:center;"><br><br><span>F O T O<br>3 x 4</span></div>
				<table style="width:calc(100% - 3cm - 30px);  border: 1px solid black; margin-left: auto;">
					<tr>
						<th rowspan="4" style="border-right: 1px solid black; transform: rotate(-90deg);">REQUERIMENTO</th>
						<td colspan="3" style="border-bottom: 1px solid black;">SITUAÇÃO DA MATRÍCULA: ☐ MI ☐ MC ☐ MR ☐ MT ☐ MPP ☐ MPC ☐ MPR</td>
					</tr>
					<tr>
						<td colspan="3">O INDICADO ABAIXO, IDENTIFICADO, REPRESENTADO QUANDO MENOR, REQUER SUA MATRÍCULA NO __________ ANO DESTA UNIDADE DE ENSINO, NESTE ANO LETIVO, NESTES TERMOS, PEDE DEFERIMENTO.</td>
					</tr>
					<tr>	
						<td style="border-right: 1px solid black;">DATA: <?php echo date("d/m/Y")?></td>
						<td colspan="2"  style="border-top: 1px solid black;">USO EXCLUSIVO DA U.E.
						<br>☐ DEFERIDO ☐ INDEFERIDO
						</td>
					</tr>
					<tr>
						<td>
						________________________________
						<br>Pai, Mãe ou Responsável</td>
						<td colspan="2" style="border-left: 1px solid black;">
						<br>Data ___/___/_____&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;________________________________
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diretor
						</td>
					</tr>
				</table>
				<div style="float: left; text-align: justify;">
					<br><div class="span12"><b>DENOMINAÇÃO DO ESTABELECIMENTO: </b><?php echo $school->inep_id . " - " . $school->name ?></div>
					<br><div class="span12"><b>ENDEREÇO: </b><?php echo $school->address ?></div>
					<br>
						<div class="span4"><b>CIDADE: </b><?php echo $school->edcensoCityFk->name ?></div>
						<div class="span3"><b>ESTADO: </b><?php echo $school->edcensoUfFk->name?></div>
						<div class="span2"><b>CEP: </b><?php echo $school->cep ?></div>
				</div>
			</div>
			<br>
            <table id="report-table" class="table table-bordered">
            	<tr><th style="text-align: center">BLOCO 1 - IDENTIFICAÇÃO E CADASTRO</th></tr>
                <tr><td>
                	<div class="span9"><b>01 - Nome do(a) Aluno(a):</b>
                	<br><span id="name"></span></div>
                </td></tr>
                <tr><td>
                	<div class="span9"><b>03 - Naturalidade:</b></div>
                	<br>
                	<div class="span3"><b>Município: </b> <span id="birth_city"></span> </div>
                	<div class="span3"><b>UF: </b> <span id="birth_uf"></span></div>
                	<div class="span3"><b>Data de Nascimento: </b> <span id="birthday"></span></div>
                </td></tr>
				<tr><td>
					<div class="span3"><b>04 - Gẽnero:</b>
					<br><span id="gender"></span></div>
					<div class="span6"><b>05 - Etinia:</b>
					<br><span id="etny"></span></div>
				</td></tr>
				<tr><td>
					<div class="span9"><b>06 - Filiação:</b></div>
					<br><div class="span9"><b>Pai: </b><span id="father"></span></div>
					<br><div class="span9"><b>Mãe: </b><span id="mother"></span></div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>07 - Certidão Civil:</b></div>
					<br><div class="span3"><b>☐ Nasci. ☐ Casam.</b></div>
						<div class="span3"><b>Nº: </b><span id="cc_number"></span></div>
						<div class="span3"><b>Livro: </b><span id="cc_book"></span></div>
						<div class="span2"><b>Folha: </b><span id="cc_sheet"></span></div>
					<br><div class="span6"><b>Nome do Cartório: </b><span id="cc_name"></span></div>
						<div class="span3"><b>Cidade: </b><span id="cc_city"></span></div>
						<div class="span2"><b>UF: </b><span id="cc_uf"></span></div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>08 - RG: </b><span id="rg"></span></div>
					<br><div class="span9"><b>09 - CPF: </b><span id="cpf"></span></div>
					<br><div class="span9"><b>10 - Endereço: </b><span id="address"></span></div>
					<br><div class="span6"><b>Cidade: </b><span id="address_city"></span></div>
						<div class="span3"><b>UF: </b><span id="address_uf"></span></div>
						<div class="span2"><b>CEP: </b><span id="cep"></span></div>
					
	            </td></tr>
				<tr><td>
					<div class="span6"><b>11 - Nome do Responsável e Parentesco: </b>____________________________________________________________________________________</div>
					<div class="span3"><b>RG: </b>__________________________
						<br><b>CPF: </b>_____.______.______-____</div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>12 - Grau de Escolaridade do Responsável:</b></div>
					<br><div class="span5">
					<br><b>☐</b> Não Sabe Ler e Escrever
					<br><b>☐</b> Sabe Ler e Escrever
					<br>
					<br><b>Ensino Fundamental:</b>
					<br><b>☐</b> Completo
					<br><b>☐</b> Incompleto
					</div>
					<br><div class="span5">
					<b>Ensino Médio:</b>
					<br><b>☐</b> Completo
					<br><b>☐</b> Incompleto
					<br>
					<br><b>Ensino Superior:</b>
					<br><b>☐</b> Completo
					<br><b>☐</b> Incompleto</div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>13 - Profissão do Responsável: </b>
					<br>____________________________________________________________________________________</div>
	            </td></tr>
      		</table>
            <table id="report-table" class="table table-bordered">
            	<tr><th style="text-align: center">CARACTERIZAÇÃO</th></tr>
				<tr><td>
					<div class="span11"><b>14 - Documentos(s) que habilita(m) matrpicula no segmento: </b></div>
					<br><div class="span11">OBS.: Se o requerente apresentar declaração, a matrícula ficará pendente no máximo 30 dias, até a entrega da guia de transferência. Após 30 dias a declaração perderá a validade ficando a matrícula sem efeito.</div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>15 - Data de Ingresso nesta Escola: </b> <?php echo date("d/m/Y")?></div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>16 - Situação do Aluno na Série/Etapa: </b></div>
					<br><div class="span9"><b>☐</b> Primeira matrícula no Curso (Nível e/ou modalidade de ensino)
					<br><b>☐</b> Promovido na série/etapa anterior do mesmo curso (nível e/ou modalidade de ensino)
					<br><b>☐</b> Repetente</div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>17 - Situação do Aluno no ano Anterior: </b></div>
					<br><div class="span4">
						<b>☐</b> Não Frequentou
						<br><b>☐</b> Reprovado
					</div>
					<div class="span4">
						<b>☐</b> Afastado por transferência
						<br><b>☐</b> Matricula finall em Educação Infantil
					</div>
					<div class="span4">
						<b>☐</b> Afastado por abandono
					</div>
	            </td></tr>
				<tr><td>
					<div class="span11"><b>18 - Portador de Necessidades Especiais? </b></div>
					<br><div class="span2"><b>☐</b> Sim</div>
					<div class="span2"><b>☐</b> Não</div>
					<div class="span7"><b>Tipo: </b>__________________________________________________________________________</div>
	            </td></tr>
				<tr><td>
					<div class="span11"><b>19 - Participa do Programa Bolsa Família? </b></div>
					<br><div class="span2"><b>☐</b> Sim</div>
					<div class="span2"><b>☐</b> Não</div>
	            </td></tr>
				<tr><td>
					<div class="span11"><b>20 - Utiliza Transporte Escolar? </b></div>
					<br><div class="span2"><b>☐</b> Sim</div>
					<div class="span2"><b>☐</b> Não</div>
	            </td></tr>
				<tr><td>
					<div class="span11"><b>21 - Restrição alimentar ou alergia a: </b></div>
					<div class="span9">____________________________________________________________________________________</div>
	            </td></tr>
      		</table>
            <br>
            <?php $this->renderPartial('footer'); ?>
        </div>
    </div>
</div>
</div>

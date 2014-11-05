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


<div class="innerLR boquim">
    <div><?php
        echo CHtml::dropDownList('student', null, chtml::listData(StudentIdentification::model()->findAll(), 'id', 'name'), array(
            'class' => 'select-search-on hidden-print',
            'prompt' => 'Selecione um Aluno',
            'ajax' => array(
                'type' => 'GET',
                'data' => array('student_id' => 'js:this.value'),
                'url' => CController::createUrl('reports/getStudentsFileBoquimInformation'),
                'success' => "function(data){ gerarRelatorio(data); }",
                'error' => "function(){ limpar(); }"
        )));
        ?>
        <br>
        <div id="report">
			
			<div style="width: 600px; margin: 0 auto;margin-top: -20px;">
			    <img src="../../../images/boquim.png" width="40px" style="float: left; margin-right: 5px;">
			    <span style="text-align: center; float: left; margin-top: 5px;">PREFEITURA MUNICIPAL DE BOQUIM<br>
			    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
			    <span style="clear:both;display:block"></span>
			</div>
			<br>
			<div style="width: 100%; margin: 0 auto; text-align:center;margin-top: -15px;">
				<div style=" height:100%;  border: 1px solid black; background-color: lightgray; margin-bottom: 5px;">FICHA INDIVIDUAL DO ALUNO - ENSINO FUNDAMENTAL</div>
                <span style="clear:both;display:block"></span>
				<div style="border:1px solid black; float:left; width: 2.5cm; height: 3cm; text-align:center;margin-right: 15px;"><br><br><span>F O T O<br>3 x 4</span></div>
				<table style="border: 1px solid black;">
					<tr>
						<th rowspan="4" style="border-right: 1px solid black; vertical-align: bottom;"><div style="transform: translate(5px, 0px) rotate(270deg);width: 15px;line-height: 53px;margin: 0px 10px 0px 0px;">REQUERIMENTO</div></th>
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
				<div style="float: left; text-align: justify;margin: 5px 0 5px -20px;line-height: 14px;">
					<div class="span10"><b>DENOMINAÇÃO DO ESTABELECIMENTO: </b><?php echo $school->inep_id . " - " . $school->name ?></div>
					<br><div class="span10"><b>ENDEREÇO: </b><?php echo $school->address ?></div>
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
                	<div class="span9"><b>01 - Nome do(a) Aluno(a):</b>&nbsp;
                	<span id="name"></span></div>
                </td></tr>
                <tr><td>
                	<div class="span2"><b>03 - Naturalidade:</b></div>
                	<div class="span3"><b>Município:</b>&nbsp;<span id="birth_city"></span> </div>
                	<div class="span1"><b>UF:</b>&nbsp;<span id="birth_uf"></span></div>
                	<div class="span3"><b>Data&nbsp;de&nbsp;Nascimento:</b>&nbsp;<span id="birthday"></span></div>
                </td></tr>
				<tr><td>
					<div class="span4"><b>04 - Gẽnero:</b>&nbsp;<span id="gender"></span></div>
					<div class="span4"><b>05 - Etnia:</b>&nbsp;<span id="color"></span></div>
				</td></tr>
				<tr><td>
					<div class="span2"><b>06 - Filiação:</b></div>
					<div class="span4"><b>Pai: </b><span id="father"></span></div>
					<div class="span4"><b>Mãe: </b><span id="mother"></span></div>
	            </td></tr>
				<tr><td>
					<div class="span9"><b>07 - Certidão Civil:</b></div>
					<br><div class="span4"><b>☐ Nasci. ☐ Casam.</b></div>
						<div id="old_cc">
							<div class="span2"><b>Nº: </b><span id="cc_number"></span></div>
							<div class="span2"><b>Livro: </b><span id="cc_book"></span></div>
							<div class="span2"><b>Folha: </b><span id="cc_sheet"></span></div>
						<br><div class="span4"><b>Nome do Cartório: </b><span id="cc_name"></span></div>
							<div class="span4"><b>Cidade: </b><span id="cc_city"></span></div>
							<div class="span1"><b>UF: </b><span id="cc_uf"></span></div>
						</div>
						<div class="span9" id="new_cc"><b>Nº:</b><span id="cc_new"></span></div>
	            </td></tr>
				<tr><td>
					<div class="span4"><b>08 - RG: </b><span id="rg"></span>
					<br><b>09 - CPF: </b><span id="cpf"></span></div>
					<div class="span4"><b>Cidade: </b><span id="address_city"></span>
						<br><b>CEP: </b><span id="cep"></span></div>
					<div class="span1"><b>UF: </b><span id="address_uf"></span></div>
					
	            </td></tr>
				<tr><td>
					<div class="span6"><b>11 - Nome do Responsável e Parentesco: </b><hr style="border-top: 1px solid black;"></div>
					<div class="span3"><b>RG: </b>__________________________
						<br><b>CPF: </b>_____.______.______-____</div>
	            </td></tr>
				<tr><td>
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
	            </td></tr>
				<tr><td>
					<div class="span10"><b>13 - Profissão do Responsável: </b>
					<hr style="border-top: 1px solid black;"></div>
	            </td></tr>
      		</table>
      		
      		
      		
      		
            <table id="report-table" class="table table-bordered">
            	<tr><th style="text-align: center">CARACTERIZAÇÃO</th></tr>
				<tr><td>
					<div class="span10"><b>14 - Documentos(s) que habilita(m) matrpicula no segmento: </b></div>
					<br><div class="span10">OBS.: Se o requerente apresentar declaração, a matrícula ficará pendente no máximo 30 dias, até a entrega da guia de transferência. Após 30 dias a declaração perderá a validade ficando a matrícula sem efeito.</div>
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
					<div class="span10"><b>17 - Situação do Aluno no ano Anterior: </b></div>
					<br><div class="span3" style="margin-right: -20px;">
						<b>☐</b> Não Frequentou
						<br><b>☐</b> Reprovado
					</div>
					<div class="span4" style="margin-right: -20px;">
						<b>☐</b> Afastado por transferência
						<br><b>☐</b> Matricula finall em Educação Infantil
					</div>
					<div class="span3">
						<b>☐</b> Afastado por abandono
					</div>
	            </td></tr>
				<tr><td>
					<div class="span10"><b>18 - Portador de Necessidades Especiais? </b></div>
					<br><div class="span2"><b>☐</b> Sim</div>
					<div class="span2"><b>☐</b> Não</div>
					<div class="span6"><b>Tipo: </b>__________________________________________________________________________</div>
	            </td></tr>
				<tr><td>
					<div class="span10"><b>19 - Participa do Programa Bolsa Família? </b></div>
					<br><div class="span2"><b>☐</b> Sim</div>
					<div class="span2"><b>☐</b> Não</div>
	            </td></tr>
				<tr><td>
					<div class="span10"><b>20 - Utiliza Transporte Escolar? </b></div>
					<br><div class="span2"><b>☐</b> Sim</div>
					<div class="span2"><b>☐</b> Não</div>
	            </td></tr>
				<tr><td>
					<div class="span10"><b>21 - Restrição alimentar ou alergia a: </b><hr style="border-top: 1px solid black;"></div>
	            </td></tr>
      		</table>
        </div>
    </div>
</div>
</div>

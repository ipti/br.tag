<?php
defined('YII_DEBUG') or define('YII_DEBUG',false);
define("TAG_VERSION",'2.10.7E');
define("BOARD_MSG",'<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>13/05/2018 - TAG VERSÃO 2.10.7E:</strong>
		<br>
		<ul>
		    <li>Educacenso 2018</li>
				<ul>
					<li>Todas as escolas podem exportar os dados do TAG para o CENSO 2018</li>.
					<li>No menu lateral acessar a opção Educacenso. Essa função validará todos os dados que serão enviados para o Educacenso</li>
					<li>Caso algum registro não esteja correto, clicar na opção corrigir</li>
					<li>Obs: Informar dados dos Alunos, Professores e Turmas por completo</li>
					<li>Obs2: Verificar no relatório de transporte escolar a quantidade de alunos marcados com essa opção</li>
					<li>Obs3: Lembrar de informar o professor que leciona na turma</li>
				</ul>
        	</ul>
	</div>');
if(YII_DEBUG){
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    error_reporting(E_ALL);
}else {
    ini_set('display_errors','0');
    error_reporting(0);
    define("YII_ENBLE_ERROR_HANDLER",false);
    define("YII_ENBLE_EXCEPTION_HANDLER",false);
}
date_default_timezone_set('America/Maceio');
ini_set('always_populate_raw_post_data','-1');
setlocale(LC_ALL, 'portuguese', 'pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8',"ptb", 'ptb.UTF8');

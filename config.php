<?php
defined('YII_DEBUG') or define('YII_DEBUG',false);
define("TAG_VERSION",'2.11.0');
define("BOARD_MSG",'
		
		<div class="alert alert-success">
		
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>17/08/2021 - TAG VERSÃO 2.11.7.20:</strong>
		<br>
		<ul>
		    <li>Adptação do Censo 2021</li>
				<ul>
					<li>Novos campos adicionados</li>
					<li>Exportação dos dados</li>
					<li>Importação dos dados</li>
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
date_default_timezone_set('America/Sao_Paulo');
ini_set('always_populate_raw_post_data','-1');
setlocale(LC_ALL, 'portuguese','pt_BR.UTF-8','pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8',"ptb", 'ptb.UTF8');

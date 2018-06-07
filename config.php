<?php
defined('YII_DEBUG') or define('YII_DEBUG',false);
define("TAG_VERSION",'2.10.7F');
define("BOARD_MSG",'<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>07/06/2018 - TAG VERSÃO 2.10.7F:</strong>
		<br>
		<ul>
		    <li>Educacenso 2018</li>
				<ul>
					<li>Melhoria nas validações do Educacenso</li>
					<li>Aviso Importante: Todas as escolas devem validar os dados antes da exportação, esse processo deve
					ser feito clicando no link do menu lateral "EDUCACENSO"</li>
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

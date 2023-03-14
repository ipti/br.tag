<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
define("TAG_VERSION", '2.11.9'.'('.Yii::getVersion().')');
define("BOARD_MSG", '
		
		<div class="alert alert-success">
		
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>20/06/2022: TAG Versão 2.12.4:</strong>
		<br>
        <ul>
            <li>Correção de Bugs</li>
            <li>Adaptação de Campos Para o EducaCenso 2022 (Exportação) - v.5</li>
        </ul>
		<strong>08/03/2022 - 18/06/2022: TAG Versões Anteriores:</strong>
		<br>
        <ul>
            <li>Responsividade no Diário Eletrônico</li>
            <li>Melhorias no quadro de horário e Calendário Escolar</li>
            <li>Correção de Campos</li>
            <li>Melhoria Visual</li>
            <li>Diário Eletrônico</li>
            <li>Login de Professor</li>
            <li>Novas Unidades de Medida da Merenda Escolar (KG e ML)</li>
            <li>Adaptação de Campos Para o EducaCenso 2022 (Exportação) - v.4</li>
            <li>Gerenciamento de Etapas para Notas por Conceito</li>
            <li>Aprimoramento da Função Calendário Escolar</li>
            <li>Quadro de Horário Remodelado</li>
            <li>Frequência Remodelada</li>
            <li>Relatório do Bolsa Família Corrigido</li>
            <li>Calendário Remodelado</li>
        </ul>
	</div>');
if (YII_DEBUG) {
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
    define("YII_ENBLE_ERROR_HANDLER", false);
    define("YII_ENBLE_EXCEPTION_HANDLER", false);
}
date_default_timezone_set('America/Sao_Paulo');
ini_set('always_populate_raw_post_data', '-1');
setlocale(LC_ALL, 'portuguese', 'pt_BR.UTF-8', 'pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8', "ptb", 'ptb.UTF8');

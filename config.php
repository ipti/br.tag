<?php

// Normaliza YII_DEBUG como boolean
$debugEnv = getenv('YII_DEBUG') ?? '';
$debug = filter_var($debugEnv, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
defined('YII_DEBUG') or define('YII_DEBUG', $debug ?? false);

// Sessão (1h por padrão)
defined('SESSION_MAX_LIFETIME') or define('SESSION_MAX_LIFETIME', 3600);

define("TAG_VERSION", '3.2.4');

define("YII_VERSION", Yii::getVersion());
define("BOARD_MSG", '<div class="alert alert-success">Novas atualizações no TAG. Confira clicando <a class="changelog-link" href="?r=admin/changelog">aqui</a>.</div>');

if (YII_DEBUG) {
    // Trace do Yii
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    define("YII_ENABLE_ERROR_HANDLER", true);
    define("YII_ENABLE_EXCEPTION_HANDLER", true);
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

} else {
    // Produção: não mostrar, mas LOGAR
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');

    // Defina um caminho de log adequado do servidor/app
    // ini_set('error_log', '/var/log/php/php-errors.log');

    error_reporting(0);
    define("YII_ENABLE_ERROR_HANDLER", false);
    define("YII_ENABLE_EXCEPTION_HANDLER", false);
}

date_default_timezone_set('America/Sao_Paulo');
ini_set('always_populate_raw_post_data', '-1');
setlocale(LC_ALL, 'portuguese', 'pt_BR.UTF-8', 'pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8', "ptb", 'ptb.UTF8');
ini_set('session.gc_maxlifetime', SESSION_MAX_LIFETIME);


<?php




$debug = getenv("YII_DEBUG");
defined('YII_DEBUG') or define('YII_DEBUG', $debug);
defined("SESSION_MAX_LIFETIME") or define('SESSION_MAX_LIFETIME', 3600);

define("TAG_VERSION", '3.96.222');

define("YII_VERSION", Yii::getVersion());
define("BOARD_MSG", '<div class="alert alert-success">Novas atualizações no TAG. Confira clicando <a class="changelog-link" href="?r=admin/changelog">aqui</a>.</div>');
if (YII_DEBUG) {
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
    define("YII_ENBLE_ERROR_HANDLER", false);
    define("YII_ENBLE_EXCEPTION_HANDLER", false);
}
date_default_timezone_set('America/Sao_Paulo');
ini_set('always_populate_raw_post_data', '-1');
setlocale(LC_ALL, 'portuguese', 'pt_BR.UTF-8', 'pt_BR.UTF8', 'pt_br.UTF8', 'ptb_BRA.UTF8', "ptb", 'ptb.UTF8');
ini_set('session.gc_maxlifetime',  SESSION_MAX_LIFETIME);

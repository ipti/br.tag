<?php

// Normaliza YII_DEBUG como boolean
$debugEnv = getenv('YII_DEBUG') ?? '';
$debug = filter_var($debugEnv, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
defined('YII_DEBUG') or define('YII_DEBUG', $debug ?? false);

// Sessão (1h por padrão)
defined('SESSION_MAX_LIFETIME') or define('SESSION_MAX_LIFETIME', 3600);

// Metadados do app
define('TAG_VERSION', '3.2.3');

// Versão do Yii (já disponível via Yii::getVersion(); manter ok)
define('YII_VERSION', Yii::getVersion());

// Banner (cuidado ao tornar dinâmico no futuro -> sanitizar)
define(
    'BOARD_MSG',
    '<div class="alert alert-success">Novas atualizações no TAG. Confira clicando <a class="changelog-link" href="?r=admin/changelog">aqui</a>.</div>'
);

// Modo debug vs produção
if (YII_DEBUG) {
    // Trace do Yii
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

    // Em dev: ver tudo (se muito verboso, remova E_DEPRECATED)
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // Habilita handlers do Yii (default já é true, mas deixamos explícito)
    defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', true);
    defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', true);
} else {
    // Produção: não mostrar, mas LOGAR
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');

    // Defina um caminho de log adequado do servidor/app
    // ini_set('error_log', '/var/log/php/php-errors.log');

    error_reporting(0);

    // Se quiser delegar a outro handler (ex.: Sentry/Monolog) pode desabilitar os do Yii
    defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', true);
    defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', true);
}

// TZ & locale
date_default_timezone_set('America/Sao_Paulo');

// Removido: 'always_populate_raw_post_data' (obsoleto desde PHP 7)
// ini_set('always_populate_raw_post_data', '-1');

// Locale (certifique-se de que o SO tem esses locales instalados)
setlocale(
    LC_ALL,
    'pt_BR.UTF-8',
    'pt_BR.UTF8',
    'pt_BR',
    'ptb_BRA.UTF8',
    'ptb',
    'ptb.UTF8',
    'portuguese'
);

// Charset e mbstring são boas práticas
ini_set('default_charset', 'UTF-8');
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

// Sessão
ini_set('session.gc_maxlifetime', (string)SESSION_MAX_LIFETIME);
// Se quiser o cookie com mesmo prazo:
ini_set('session.cookie_lifetime', (string)SESSION_MAX_LIFETIME);

<?php

define('ROOT_DIR', __dir__.'/..');

require_once ROOT_DIR.'/includes/config.php';
require_once ROOT_DIR.'/vendor/autoload.php';

// Autoloader
function autoloader($className) {
    $class = explode('/', str_replace('\\','/',$className));
    if ($class[0] == 'BWBlog') {
        array_shift($class);
    }
    $filename = ROOT_DIR.'/functions/'.implode('/', $class).'.php';
    if (is_readable($filename)) {
        require $filename;
    }
}
spl_autoload_register('autoloader');

// SSL
define('ENV_SSL', isset($_SERVER['SSL_SESSION_ID']) && strlen($_SERVER['SSL_SESSION_ID']) > 0);
define('ENV_HOST', $_SERVER['HTTP_HOST']);

if (CONFIG_ENFORCESSL) {
    define('ENV_PREFERED_PROTOCOL', 'https');
} else {
    if (ENV_SSL) {
        define('ENV_PREFERED_PROTOCOL', 'https');
    } else {
        define('ENV_PREFERED_PROTOCOL', 'http');
    }
}

if (ENV_HOST != CONFIG_HOST) {

    header('HTTP/1.1 301 Moved Permanently');
    
    if (CONFIG_ENFORCESSL) {
        header('Location: https://'.CONFIG_HOST.$_SERVER['REQUEST_URI']);
    } else {
        header('Location: http://'.CONFIG_HOST.$_SERVER['REQUEST_URI']);
    }
    
    exit();
}

if (CONFIG_ENFORCESSL) {
    header('Strict-Transport-Security: max-age=2592000');
}

if (!ENV_SSL && CONFIG_ENFORCESSL) {

    if
    (
        !isset($_SERVER['HTTP_USER_AGENT'])
        || stripos($_SERVER['HTTP_USER_AGENT'], 'Baiduspider') === false
        && stripos($_SERVER['HTTP_USER_AGENT'], 'Sogou web spider') === false
        && stripos($_SERVER['HTTP_USER_AGENT'], 'Sosospider') === false
    ) {

        header('HTTP/1.1 301 Moved Permanently');
        header('Location: https://'.CONFIG_HOST.$_SERVER['REQUEST_URI']);
        exit();

    }

}

// Charset & XSS Protection
header('Content-Type: text/html; charset=UTF-8');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// Multi-byte
mb_internal_encoding('UTF-8');

// Debug
if (CONFIG_DEBUG) {
    error_reporting(E_ALL | E_STRICT);
    \BWBlog\Service\Whoops::initialize();
} else {
    error_reporting(0);
}

// Buffer
ob_start();

// Timezone
date_default_timezone_set(CONFIG_TIMEZONE);

// Database
\BWBlog\Service\Database::initMongoDB();
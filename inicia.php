<?php
define('RAIZ', str_replace('\\', '/', __DIR__) . '/');
include 'vendor/autoload.php';
include RAIZ . 'core/ini.php';

// iniciações definidas pelo desenvolvedor.
define('ORIGEM', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "");
define('APP_FILES', SITE . 'static/');
define('OPT_FILES', RAIZ . 'static/');
define('APP_HTTP', SITE . 'app/');
define('URL_ATUAL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('LIB_HTTP', SITE . 'lib/');

// Níveis de acesso ao sistema
define('ADMINISTRADOR', '0');
define('USUARIO', '1');
define('CLIENTE', '2');
define('FUNCIONARIO', '3');
define('VISITANTE', '4');

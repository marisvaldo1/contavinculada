<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
define('RAIZ', str_replace('\\', '/', dirname(__DIR__)) . '/');
include RAIZ . 'core/funcoes.php';
spl_autoload_register('autoload');
define('AMBIENTE_PUBLICACAO', $_SERVER['AMBIENTE_PUBLICACAO']);
cli();
Sistema::inicia();
\bd\MySQL::inicia();
\componentes\Componentes::inicia();
\componentes\Rest::inicia();
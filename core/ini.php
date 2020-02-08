<?php
//Inicialização principal do pphp. Não altere!

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
include RAIZ . 'core/funcoes.php';
//include RAIZ . 'vendor/autoload.php';
spl_autoload_register('autoload');
header('Content-type: text/html; charset=utf-8');
define('AMBIENTE_PUBLICACAO', 'D');
//define('AMBIENTE_PUBLICACAO', $_SERVER['AMBIENTE_PUBLICACAO']);
define('SITE', $_SERVER['REQUEST_SCHEME'] . '://' . str_replace('//', '/', $_SERVER['HTTP_HOST'] . '/' .
        str_replace([$_SERVER['DOCUMENT_ROOT'], '/'], '', RAIZ) . '/'));
//define('PAGINA', $_SERVER['REQUEST_URI']);
define('PAGINA', substr($_SERVER['SCRIPT_NAME'], strripos($_SERVER['SCRIPT_NAME'], "app") - 1));
define('APP', RAIZ . 'app/');
Sistema::inicia();
session_name(Sistema::$sigla);
session_start();

//TODO: Alterar o conteúdo desta sessão no login
//$_SESSION["nivel_acesso"] = 1;

//Autenticacao::inicia();
bd\MySQL::inicia();
componentes\Componentes::inicia();
Ext::ini(); 
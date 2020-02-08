<?php

include 'inicia.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
d('Variáveis de servidor');
d($_SERVER);
echo ('<hr>');
d('Variáveis de sessão');
d($_SESSION);
echo ('<hr>');
d('Variáveis de Globais');
echo ('SITE -> ' . SITE . '<br>');
echo ('APP -> ' . APP . '<br>');
echo ('APP_FILES -> ' . APP_FILES . '<br>');
echo ('APP_HTTP -> ' . APP_HTTP . '<br>');
echo ('LIB_HTTP -> ' . LIB_HTTP . '<br>');
echo ('URL_ATUAL -> ' . URL_ATUAL . '<br>');

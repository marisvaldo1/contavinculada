<?php
include '../../../inicia.php';


modelo\Usuario::validaToken();

//Utilizado para preencher o filtro na página de histórico de capitura
$filtro = $_REQUEST;

include 'historico.html.php';


<?php

include '../../../inicia.php';

use modelo\Usuario;

/*
 *Seta o timezone para Brasil
 */
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
        $retorno = Usuario::buscaUsuario($_REQUEST);
    } else if ($acao == 'finalizaSessao') {
        $retorno = Usuario::finalizaSessao($_REQUEST['id_usuario']);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);
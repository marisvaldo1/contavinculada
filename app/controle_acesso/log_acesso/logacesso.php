<?php

include '../../../inicia.php';

use modelo\RegistroLog;

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
        $retorno = RegistroLog::listaLogAcesso($_REQUEST);
    } else if ($acao == 'registrarLogAcesso') {
        $retorno = RegistroLog::registraLogAcesso($_REQUEST);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);
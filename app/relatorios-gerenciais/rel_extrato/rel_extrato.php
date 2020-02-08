<?php

include '../../../inicia.php';

use modelo\Extrato;

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
            $retorno = Extrato::buscaExtrato($_REQUEST);
    } else if ($acao == 'listarLiberacao') {
            $retorno = Extrato::listarLiberacao($_REQUEST);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

<?php

include '../../../inicia.php';

use modelo\Cliente;

try {
    $acao = $_REQUEST['acao'];
    $id_cliente = $_REQUEST['id_cliente'];

    if ($acao == 'listar') {
        if ($id_cliente) {
            $retorno = Cliente::buscaCliente($id_cliente);
        } else {
            $retorno = Cliente::buscaCliente();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Cliente::gravaCliente($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Cliente::excluiCliente($id_cliente);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

<?php

include '../../../inicia.php';

use modelo\Empresa;

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
        $retorno = Empresa::buscaEmpresa($_REQUEST);
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Empresa::gravaEmpresa($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Empresa::excluiEmpresa($_REQUEST);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);
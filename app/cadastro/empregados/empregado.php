<?php

include '../../../inicia.php';

use modelo\Empregado;

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
        $retorno = Empregado::buscaEmpregado($_REQUEST);
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Empregado::gravaEmpregado($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Empregado::excluiEmpregado($_REQUEST);
    } else if ($acao == 'listarEmpregadosEmpresa') {
        $retorno = Empregado::listarEmpregadosEmpresa($_REQUEST);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

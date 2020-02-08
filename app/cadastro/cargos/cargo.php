<?php

include '../../../inicia.php';

use modelo\Cargo;

try {
    $acao = $_REQUEST['acao'];
    $id_cargo = $_REQUEST['id_cargo'];

    if ($acao == 'listar') {
        if ($id_cargo) {
            $retorno = Cargo::buscaCargo($id_cargo);
        } else {
            $retorno = Cargo::buscaCargo();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Cargo::gravaCargo($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Cargo::excluiCargo($id_cargo);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

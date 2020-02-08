<?php

include '../../../inicia.php';

use modelo\Administrador;

try {
    $acao = $_REQUEST['acao'];
    $id_administrador = $_REQUEST['id_administrador'];

    if ($acao == 'listar') {
        if ($id_administrador) {
            $retorno = Administrador::buscaAdministrador($id_administrador);
        } else {
            $retorno = Administrador::buscaAdministrador();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Administrador::gravaAdministrador($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Administrador::excluiAdministrador($id_administrador);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

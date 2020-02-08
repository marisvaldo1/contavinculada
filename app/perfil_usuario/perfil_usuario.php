<?php

include '../../../inicia.php';

use modelo\Usuario;

try {
    $acao = $_REQUEST['acao'];
    $id_usuario = $_REQUEST['id_usuario'];

    if ($acao == 'listar') {
        if ($id_usuario) {
            $retorno = Usuario::buscaUsuario($id_usuario);
        } else {
            $retorno = Usuario::buscaUsuario();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Usuario::gravaUsuario($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Usuario::excluiUsuario($id_usuario);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

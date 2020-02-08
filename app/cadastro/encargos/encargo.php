<?php

include '../../../inicia.php';

use modelo\Encargo;

try {
    $acao = $_REQUEST['acao'];
    $id_encargo = $_REQUEST['id_encargo'];

    if ($acao == 'listar') {
        if ($id_encargo) {
            $retorno = Encargo::buscaEncargo($id_encargo);
        } else {
            $retorno = Encargo::buscaEncargo();
        }
    //} else if ($acao == 'listarInserirContrato') {
    //    $retorno = Encargo::buscaEncargo(null, true);
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Encargo::gravaEncargo($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Encargo::excluiEncargo($id_encargo);
    } else if ($acao == 'mudaInsereAutomatico') {
        $retorno = Encargo::mudaInsereAutomaticoEncargoContrato($id_encargo);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

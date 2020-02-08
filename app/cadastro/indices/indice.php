<?php

include '../../../inicia.php';

use modelo\Indice;

try {
    $acao = $_REQUEST['acao'];
    $id_indice = $_REQUEST['id_indice'];

    if ($acao == 'listar') {
        if ($id_indice) {
            $retorno = Indice::buscaIndice($id_indice);
        } else {
            $retorno = Indice::buscaIndice();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Indice::gravaIndice($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Indice::excluiIndice($id_indice);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

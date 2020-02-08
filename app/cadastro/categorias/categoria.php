<?php

include '../../../inicia.php';

use modelo\Categoria;

try {
    $acao = $_REQUEST['acao'];
    $id_categoria = $_REQUEST['id_categoria'];

    if ($acao == 'listar') {
        if ($id_categoria) {
            $retorno = Categoria::buscaCategoria($id_categoria);
        } else {
            $retorno = Categoria::buscaCategoria();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Categoria::gravaCategoria($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Categoria::excluiCategoria($id_categoria);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

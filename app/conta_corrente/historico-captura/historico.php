<?php

include '../../../inicia.php';

use modelo\Historico;

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
        $retorno = Historico::buscaHistorico($_REQUEST);
    } else if ($acao == 'excluirHistorico') {
        $retorno = Historico::excluirHistorico($_REQUEST['id_captura']);
    } else if ($acao == 'excluirCaptura') {
        $retorno = Historico::excluirCaptura($_REQUEST['id_captura']);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

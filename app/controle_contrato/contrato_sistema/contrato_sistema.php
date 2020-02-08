<?php

include '../../../inicia.php';

use modelo\ContratoSistema;

try {
    $acao = $_REQUEST['acao'];
    $id_contrato = $_REQUEST['id_contrato'];

    if ($acao === 'listar') {
        if ($id_contrato) {
            $retorno = ContratoSistema::buscaContratoSistema($id_contrato);
        } else {
            $retorno = ContratoSistema::buscaContratoSistema();
        }
    } else if ($acao == 'listarClientesSemContrato') {
        $retorno = ContratoSistema::listarClientesSemContrato($_REQUEST);
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = ContratoSistema::gravaContratoSistema($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = ContratoSistema::excluiContratoSistema($id_contrato);
    } else if ($acao == 'listarContratosCategoria') {
        //$retorno = ContratoSistema::listarContratosCategoria();
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

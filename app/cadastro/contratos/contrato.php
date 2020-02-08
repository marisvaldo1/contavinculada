<?php

include '../../../inicia.php';

use modelo\Contrato;

try {
    $acao = $_REQUEST['acao'];
    $id_contrato = $_REQUEST['id_contrato'];
    $id_empresa = $_REQUEST['empresa'];

    if ($acao == 'listar') {
        if ($id_contrato) {
            $retorno = Contrato::buscaContrato($id_contrato);
        } else if($id_empresa){
            $retorno = Contrato::buscaContrato(null, $id_empresa);
        } else {
            $retorno = Contrato::buscaContrato();
        }
    } else if ($acao == 'alterar' || $acao == 'novo') {
        $retorno = Contrato::gravaContrato($_REQUEST);
    } else if ($acao == 'excluir') {
        $retorno = Contrato::excluiContrato($id_contrato);
    } else if ($acao == 'listarEncargosContrato') {
        $retorno = Contrato::listarEncargosContrato($_REQUEST);
    } else if ($acao == 'listarEncargosNovoContrato') {
        $retorno = Contrato::listarEncargosNovoContrato();
    } else if ($acao == 'listarEmpregadosContrato') {
        $retorno = Contrato::listarEmpregadosContrato($_REQUEST);
    } else if ($acao == 'buscarNovoContrato') {
        $retorno = Contrato::buscarNovoNumeroContrato();
    } else if ($acao == 'listarContratosEmpresa') {
        $retorno = Contrato::listarContratosEmpresa($_REQUEST);
    } else if ($acao == 'listarLancamentosContrato') {
        $retorno = Contrato::listarLancamentosContrato($_REQUEST);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => 'true',
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

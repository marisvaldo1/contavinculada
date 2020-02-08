<?php

include '../../inicia.php';

try {
    $acao = $_REQUEST['acao'];
    
    $empresa = $_REQUEST['empresa'];
    $empresa = ($empresa === 'Todas')? '': $empresa;
    
    if ($acao == 'clientes') {
        $retorno = modelo\Cliente::listarClientes();
    } else if ($acao == 'empresas') {
        $retorno = modelo\Empresa::listarEmpresas();
    } else if ($acao == 'contratos') {
        $retorno = modelo\Contrato::listarContratos($_REQUEST);
    } else if ($acao == 'empregados') {
        $retorno = modelo\Empregado::quantidadeEmpregadosCategoria($_REQUEST);
    } else if ($acao == 'saldoContas') {
        $retorno = modelo\Lancamento::listarSadoContas($_REQUEST);
    } else if ($acao == 'listarEmpregadosCategoria') {
        $retorno = modelo\Empregado::listarEmpregadosCategoria($empresa);
    } else if ($acao == 'listarContratosCategoria') {
        $retorno = modelo\ContratoSistema::listarContratosCategoria($empresa);
    } else if ($acao == 'contas') {
        //$retorno = modelo\Contas::listarContas($empresa);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

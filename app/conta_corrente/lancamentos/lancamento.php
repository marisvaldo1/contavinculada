<?php

include '../../../inicia.php';

use modelo\Lancamento;

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'listar') {
        $retorno = Lancamento::buscaLancamento($_REQUEST);
    } else if ($acao == 'listar_cargos') {
        //$retorno = \modelo\Cargo::buscaCargo();
    } else if ($acao == 'listaEmpregadosContrato') {
        $retorno = \modelo\Contrato::listarEmpregadosContrato($_REQUEST);
    } else if ($acao == 'detalharLancamentosEmpregados') {
        $retorno = Lancamento::detalharLancamentosEmpregados($_REQUEST);
    } else if ($acao == 'liberacoes') {
        $retorno = Lancamento::liberacoes($_REQUEST);
    } else if ($acao == 'liberarVerba') {
        $retorno = Lancamento::liberarVerba($_REQUEST);
    } else if ($acao == 'cancelarLiberacao') {
        $retorno = Lancamento::cancelarLiberacao($_REQUEST);
    } else if ($acao == 'retencoesLiberacoes') {
        $retorno = Lancamento::retencoesLiberacoes($_REQUEST);
    } else if ($acao == 'saldos') {
        $retorno = Lancamento::saldos($_REQUEST);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

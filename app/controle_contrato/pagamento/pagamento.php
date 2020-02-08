<?php

include '../../../inicia.php';

use modelo\Pagamento;

try {
    $acao = $_REQUEST['acao'];
    $id_contrato = $_REQUEST['id_contrato'];

    $empresa = $_REQUEST['empresa'];
    $empresa = ($empresa === 'Todas')? '': $empresa;

    if ($acao == 'listar') {
        if ($id_contrato) {
            $retorno = Pagamento::buscaPagamentos($_REQUEST);
        } else {
            $retorno = Pagamento::buscaPagamentos();
        }
    } else if ($acao == 'alterar') {
        $retorno = Pagamento::gravaPagamento($_REQUEST);
    } else if ($acao == 'listaPagamentosContrato') {
        $retorno = Pagamento::listarPagamentosContrato($_REQUEST);
    } else if ($acao == 'detalharPagamentosContrato') {
        $retorno = Pagamento::listarPagamentosContrato($empresa);
    } else if ($acao == 'recebimentosPrevistosRealizados') {
        $retorno = $retorno = Pagamento::recebimentosPrevistosRealizados($empresa);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

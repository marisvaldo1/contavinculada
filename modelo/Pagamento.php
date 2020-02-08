<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Pagamento {

    public $id_contrato;
    public $id_cliente;
    public $id_parcela;
    public $data_vencimento;
    public $data_pagamento;
    public $valor_parcela;
    public $valor_pagamento;
    public $observacao_parcela;
    public $status_pagamento;
    public $mes;

    function getId_contrato() {
        return $this->id_contrato;
    }

    function getId_cliente() {
        return $this->id_cliente;
    }

    function getId_parcela() {
        return $this->id_parcela;
    }

    function getData_vencimento() {
        return $this->data_vencimento;
    }

    function getData_pagamento() {
        return $this->data_pagamento;
    }

    function getValor_parcela() {
        return $this->valor_parcela;
    }

    function getValor_pagamento() {
        return $this->valor_pagamento;
    }

    function getObservacao_parcela() {
        return $this->observacao_parcela;
    }

    function getStatus_pagamento() {
        return $this->status_pagamento;
    }

    function getMes() {
        return $this->mes;
    }

    function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }

    function setId_parcela($id_parcela) {
        $this->id_parcela = $id_parcela;
    }

    function setData_vencimento($data_vencimento) {
        $this->data_vencimento = $data_vencimento;
    }

    function setData_pagamento($data_pagamento) {
        $this->data_pagamento = $data_pagamento;
    }

    function setValor_parcela($valor_parcela) {
        $this->valor_parcela = $valor_parcela;
    }

    function setValor_pagamento($valor_pagamento) {
        $this->valor_pagamento = $valor_pagamento;
    }

    function setObservacao_parcela($observacao_parcela) {
        $this->observacao_parcela = $observacao_parcela;
    }

    function setStatus_pagamento($status_pagamento) {
        $this->status_pagamento = $status_pagamento;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    public static function buscaPagamentos($dados) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
                . ' id_contrato, '
                . ' id_cliente, '
                . ' id_parcela, '
                . ' data_vencimento,'
                . ' data_pagamento,'
                . ' valor_pagamento, '
                . ' valor_parcela, '
                . ' observacao_parcela, '
                . ' status_pagamento '
                . ' FROM pagamentos ';

        /* Para usuário administrador o cliente deve ser passado
         * Para os demais usuários o cliente é o mesmo logado
         */
        if ($_SESSION["dados_usuario"]->getNivel_acesso() == 0) {
            if ($dados['id_cliente']) {
                $sql .= ' WHERE id_cliente =  ' . $dados['id_cliente'];
            } else {
                $sql .= ' WHERE 1=1 ';
            }
        } else {
            $sql .= ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();
        }

        if ($dados['id_contrato']) {
            $sql .= ' AND id_contrato =  ' . $dados['id_contrato'];
        }

        $sql .= ' ORDER BY id_parcela ASC';

        $pagamento = new \stdClass();
        $pagamentos = new \stdClass();
        $pagamentos->erro = false;
        $pagamentos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $pagamento = new Pagamento();
                $pagamento->setId_contrato($row['id_contrato']);
                $pagamento->setId_cliente($row['id_cliente']);
                $pagamento->setId_parcela($row['id_parcela']);
                $pagamento->setData_vencimento($row['data_vencimento']);
                $pagamento->setData_pagamento($row['data_pagamento']);
                $pagamento->setValor_parcela($row['valor_parcela']);
                $pagamento->setValor_pagamento($row['valor_pagamento']);
                $pagamento->setObservacao_parcela($row['observacao_parcela']);
                $pagamentos->dados[] = $pagamento;
            }
        } else {
            $pagamentos->erro = true;
            $pagamentos->mensagem = 'Nenhum lançamento encontrado.';
        }

        return $pagamentos;
    }

    public static function gravaPagamento($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE pagamentos SET ';
            $sql .= ' data_vencimento = STR_TO_DATE("' . $dados['data_vencimento'] . '", "%d/%m/%Y" ), ';
            
            if( $dados['data_pagamento'] === '' ) {
                $sql .= ' data_pagamento = null, ';
            } else {
                $sql .= ' data_pagamento = STR_TO_DATE("' . $dados['data_pagamento'] . '", "%d/%m/%Y" ), ';
            }
            
            $sql .= ' valor_pagamento = ' . floatval($dados['valor_pagamento']) . ', '
                    . ' observacao_parcela = "' . $dados['observacao_parcela'] . '", '
                    . ' status_pagamento = "PAGO" '
                    . ' WHERE id_cliente = ' . $dados['id_cliente']
                    . ' AND id_contrato = ' . $dados['id_contrato']
                    . ' AND id_parcela = ' . $dados['id_parcela'];
        }

        $r = $conexao->query($sql);
        $pagamentos = new \stdClass();
        $pagamentos->erro = false;

        if ($dados['acao'] == 'alterar')
            $pagamentos->mensagem = 'Alteração efetuada com sucesso';
        else
            $pagamentos->mensagem = 'Inclusão efetuada com sucesso';

        return $pagamentos;
    }

    public static function excluiPagamento($id_contrato, $data_pagamento) {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE pagamento SET '
                . ' status_pagamento = "EXCLUIDO" '
                . ' WHERE id_contrato = ' . $id_contrato
                . ' AND data_pagamento = ' . $data_pagamento;

        $r = $conexao->query($sql);
        $pagamentos = new \stdClass();
        $pagamentos->erro = false;
        $pagamentos->mensagem = 'Pagamento excluído com sucesso';

        return $pagamentos;
    }

    public static function listarPagamentosContrato($id_empresa) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT COUNT(*) AS quantidade FROM pagamentos '
                . ' WHERE status_pagamento = "ATIVO"';

        $pagamentos = new \stdClass();
        $resultado = $conexao->query($sql);
        $pagamentos->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $pagamentos->quantidade = $l['quantidade'];
        } else {
            $pagamentos->quantidade = 0;
        }

        return $pagamentos;
    }

    public static function recebimentosPrevistosRealizados() {
        $conexao = MySQL::conexao();

        $sql = 'SELECT data_vencimento, MONTH(data_vencimento) mes, SUM(valor_parcela) valor_parcela, SUM(valor_pagamento) valor_pagamento 
                FROM pagamentos 
                GROUP BY data_vencimento, MONTH(data_vencimento)
                ORDER BY data_vencimento, MONTH(data_vencimento)';
        $resultado = $conexao->query($sql);

        $recebimentos = new \stdClass();
        $recebimentos->erro = false;

        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $recebimento = new Pagamento();
                $recebimento->setMes($row['mes']);
                $recebimento->setValor_parcela($row['valor_parcela']);
                $recebimento->setValor_pagamento($row['valor_pagamento']);
                $recebimentos->dados[] = $recebimento;
            }
        } else {
            $recebimentos->erro = true;
            $recebimentos->mensagem = 'Nenhum recebimento encontrado.';
        }

        return $recebimentos;
    }

}

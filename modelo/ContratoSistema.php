<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class ContratoSistema {

    public $cnpj;
    public $razao;
    public $id_contrato;
    public $nu_contrato_sistema;
    public $id_cliente;
    public $dt_inicio;
    public $dt_final;
    public $tipo_pagamento;
    public $valor_contrato;
    public $status_contrato_sistema;
    public $id_parcela;
    public $data_vencimento;
    public $data_pagamento;
    public $valor_parcela;
    public $valor_pagamento;
    public $status_pagamento;
    public $nome_categoria;
    public $qt_contratos;

    function getCnpj() {
        return $this->cnpj;
    }

    function getRazao() {
        return $this->razao;
    }

    function getId_contrato() {
        return $this->id_contrato;
    }

    function getNu_contrato_sistema() {
        return $this->nu_contrato_sistema;
    }

    function getId_cliente() {
        return $this->id_cliente;
    }

    function getDt_inicio() {
        return $this->dt_inicio;
    }

    function getDt_final() {
        return $this->dt_final;
    }

    function getTipo_pagamento() {
        return $this->tipo_pagamento;
    }

    function getValor_contrato() {
        return $this->valor_contrato;
    }

    function getStatus_contrato_sistema() {
        return $this->status_contrato_sistema;
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

    function getStatus_pagamento() {
        return $this->status_pagamento;
    }

    function getNome_categoria() {
        return $this->nome_categoria;
    }

    function getQt_contratos() {
        return $this->qt_contratos;
    }

    function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    function setRazao($razao) {
        $this->razao = $razao;
    }

    function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    function setNu_contrato_sistema($nu_contrato_sistema) {
        $this->nu_contrato_sistema = $nu_contrato_sistema;
    }

    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }

    function setDt_inicio($dt_inicio) {
        $this->dt_inicio = $dt_inicio;
    }

    function setDt_final($dt_final) {
        $this->dt_final = $dt_final;
    }

    function setTipo_pagamento($tipo_pagamento) {
        $this->tipo_pagamento = $tipo_pagamento;
    }

    function setValor_contrato($valor_contrato) {
        $this->valor_contrato = $valor_contrato;
    }

    function setStatus_contrato_sistema($status_contrato_sistema) {
        $this->status_contrato_sistema = $status_contrato_sistema;
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

    function setStatus_pagamento($status_pagamento) {
        $this->status_pagamento = $status_pagamento;
    }

    function setNome_categoria($nome_categoria) {
        $this->nome_categoria = $nome_categoria;
    }

    function setQt_contratos($qt_contratos) {
        $this->qt_contratos = $qt_contratos;
    }

    public static function buscaContratoSistema($id_contrato = null) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
                . ' cli.cnpj, cli.razao, '
                . ' cs.id_contrato, cs.nu_contrato_sistema, cs.id_cliente, cs.dt_inicio, cs.dt_final, cs.tipo_pagamento, cs.valor_contrato, cs.status_contrato_sistema '
                . ' FROM contrato_sistema cs, clientes cli '
                . ' WHERE cs.status_contrato_sistema = "ATIVO" '
                . ' AND cs.id_cliente = cli.id_cliente ';

        if ($id_contrato) {
            $sql = $sql . ' AND cs.id_contrato =  ' . $id_contrato;
        }

        $contrato = new \stdClass();
        $contratos = new \stdClass();
        $contratos->erro = false;
        $contratos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $contrato = new ContratoSistema();
                $contrato->setCnpj($row['cnpj']);
                $contrato->setRazao($row['razao']);
                $contrato->setId_contrato($row['id_contrato']);
                $contrato->setNu_contrato_sistema($row['nu_contrato_sistema']);
                $contrato->setId_cliente($row['id_cliente']);
                $contrato->setDt_inicio($row['dt_inicio']);
                $contrato->setDt_final($row['dt_final']);
                $contrato->setTipo_pagamento($row['tipo_pagamento']);
                $contrato->setValor_contrato($row['valor_contrato']);
                $contrato->setStatus_contrato_sistema($row['status_contrato_sistema']);
                $contratos->dados[] = $contrato;
            }
        } else {
            $contratos->erro = true;
            $contratos->mensagem = 'Nenhum contrato cadastrado.';
        }

        return $contratos;
    }

    public static function gravaContratoSistema($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = ' UPDATE contrato_sistema SET ';
            $sql .= ' nu_contrato_sistema = "' . $dados['nu_contrato_sistema'] . '",';

            if (substr($dados['dt_inicio'], 0, 4) != '0000') {
                $sql .= ' dt_inicio = STR_TO_DATE("' . $dados['dt_inicio'] . '", "%d/%m/%Y" ), ';
            }
            if (substr($dados['dt_final'], 0, 4) != '0000') {
                $sql .= ' dt_final = STR_TO_DATE("' . $dados['dt_final'] . '", "%d/%m/%Y" ), ';
            }

            $sql .= ' tipo_pagamento = "' . $dados['tipo_pagamento'] . '",';
            $sql .= ' valor_contrato = ' . floatval($dados['valor_contrato']);

            $sql .= ' WHERE id_contrato = ' . $dados['id_contrato'];
        } else {
            $sql = 'INSERT '
                    . 'INTO contrato_sistema ('
                    . 'id_cliente, '
                    . 'nu_contrato_sistema, '
                    . 'dt_inicio, '
                    . 'dt_final, '
                    . 'tipo_pagamento, '
                    . 'valor_contrato, '
                    . 'status_contrato_sistema) '
                    . ' VALUES ( '
                    . $dados['id_cliente'] . ','
                    . '"' . $dados['nu_contrato_sistema'] . '",'
                    . ' STR_TO_DATE("' . $dados['dt_inicio'] . '","%d/%m/%Y"),'
                    . ' STR_TO_DATE("' . $dados['dt_final'] . '","%d/%m/%Y"),'
                    . '"' . $dados['tipo_pagamento'] . '",'
                    . floatval($dados['valor_contrato']) . ','
                    . '"ATIVO")';
        }

        $r = $conexao->query($sql);
        $id_contrato_iserido = $conexao->id();
        $contratos = new \stdClass();
        $contratos->erro = false;

        if ($dados['acao'] == 'alterar') {
            $contratos->mensagem = 'Alteração efetuada com sucesso';
            $id_contrato_iserido = $dados['id_contrato'];
        } else {
            $contratos->mensagem = 'Inclusão efetuada com sucesso';
        }

        /*
         * Verifica se existem parcelas. Se não, insere
         */
        if ( $dados['acao'] === 'novo' || $dados['insere_parcelas'] ) {

            //Insere as parcelas à pagar para o contrato
            foreach ($dados[parcelas] as $key => $value) {
                $sql = 'INSERT '
                        . 'INTO pagamentos ('
                        . 'id_contrato, '
                        . 'id_cliente, '
                        . 'id_parcela, '
                        . 'data_vencimento, '
                        . 'valor_parcela, '
                        . 'status_pagamento) '
                        . ' VALUES ( '
                        . $id_contrato_iserido . ','
                        . $dados['id_cliente'] . ','
                        . $value['id_parcela'] . ','
                        . ' STR_TO_DATE("' . $value['dt_vencimento'] . '","%d/%m/%Y"),'
                        . floatval($value['valor']) . ','
                        . '"ABERTO")';

                $r = $conexao->query($sql);
                $pagamentos = new \stdClass();
                $pagamentos->erro = false;
            }
        }

        return $contratos;
    }

    public static function excluiContratoSistema($id_contrato) {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE contrato_sistema SET '
                . 'status_contrato_sistema = "EXCLUIDO" '
                . 'WHERE id_contrato = ' . $id_contrato;

        $r = $conexao->query($sql);
        $contratos = new \stdClass();
        $contratos->erro = false;
        $contratos->mensagem = 'Contrato excluído com sucesso';

        return $contratos;
    }

    public static function listarContratosSitema() {
        $conexao = MySQL::conexao();

        $sql = 'select count(*) as quantidade from contrato_sistema where status_contrato_sistema = "ATIVO"';

        $contratos = new \stdClass();
        $resultado = $conexao->query($sql);
        $contratos->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $contratos->quantidade = $l['quantidade'];
        } else {
            $contratos->quantidade = 0;
        }

        return $contratos;
    }

    public static function listarContratosCategoria($id_empresa) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT cat.nome_categoria, count(cat.id_categoria) qt_contratos
                FROM contratos con, clientes cli, categorias cat
                WHERE con.id_cliente = cli.id_cliente
                AND status_contrato = "ATIVO" 
                AND cli.id_categoria = cat.id_categoria ';

        if ($id_empresa)
            $sql .= ' AND con.id_empresa = ' . $id_empresa;

        $sql .= ' GROUP BY cat.id_categoria ';

        $contratos = new \stdClass();
        $resultado = $conexao->query($sql);
        $contratos->erro = false;

        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $contrato = new ContratoSistema();
                $contrato->setNome_categoria($row['nome_categoria']);
                $contrato->setQt_contratos($row['qt_contratos']);
                $contratos->dados[] = $contrato;
            }
        } else {
            $contratos->erro = true;
            $contratos->mensagem = 'Nenhum contrato encontrado.';
        }

        return $contratos;
    }    
    public static function listarClientesSemContrato() {
        $conexao = MySQL::conexao();

        $sql = ' SELECT cli.id_cliente, cli.cnpj, cli.razao
        FROM clientes cli
        WHERE cli.id_cliente NOT IN (
        SELECT id_cliente FROM contrato_sistema WHERE status_contrato_sistema != "EXCLUIDO")';

        $contratos = new \stdClass();
        $resultado = $conexao->query($sql);
        $contratos->erro = false;

        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $contrato = new ContratoSistema();
                $contrato->setId_cliente($row['id_cliente']);
                $contrato->setRazao($row['razao']);
                $contrato->setCnpj($row['cnpj']);
                $contratos->dados[] = $contrato;
            }
        } else {
            $contratos->erro = true;
            $contratos->mensagem = 'Nenhum cliente sem contrato.<hr><i><b>* Para criar um novo contrato, cadastre um novo cliente.';
        }

        return $contratos;
    }    
}

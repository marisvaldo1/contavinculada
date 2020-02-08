<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Encargo {

    public $id_encargo;
    public $nome_encargo;
    public $insere_automatico_contrato;
    public $percentual_encargo;
    public $status_encargo;

    function getId_encargo() {
        return $this->id_encargo;
    }

    function getNome_encargo() {
        return $this->nome_encargo;
    }

    function getInsere_automatico_contrato() {
        return $this->insere_automatico_contrato;
    }

    function getPercentual_encargo() {
        return $this->percentual_encargo;
    }

    function getStatus_encargo() {
        return $this->status_encargo;
    }

    function setId_encargo($id_encargo) {
        $this->id_encargo = $id_encargo;
    }

    function setNome_encargo($nome_encargo) {
        $this->nome_encargo = $nome_encargo;
    }

    function setInsere_automatico_contrato($insere_automatico_contrato) {
        $this->insere_automatico_contrato = $insere_automatico_contrato;
    }

    function setPercentual_encargo($percentual_encargo) {
        $this->percentual_encargo = $percentual_encargo;
    }

    function setStatus_encargo($status_encargo) {
        $this->status_encargo = $status_encargo;
    }

    //public static function buscaEncargo($id_encargo = null, $insereAutomaticoContrato = null) {
    public static function buscaEncargo($id_encargo = null) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
                . ' id_encargo, '
                . ' nome_encargo, '
                . ' percentual_encargo,'
                . ' insere_automatico_contrato,'
                . ' status_encargo '
                . ' FROM encargos '
                . ' WHERE status_encargo = "ATIVO" '
                . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($id_encargo) {
            $sql .= ' AND id_encargo =  ' . $id_encargo;
        }

        $encargo = new \stdClass();
        $encargos = new \stdClass();
        $encargos->erro = false;
        $encargos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $encargo = new Encargo();
                $encargo->setId_encargo($row['id_encargo']);
                $encargo->setNome_encargo($row['nome_encargo']);
                $encargo->setInsere_automatico_contrato($row['insere_automatico_contrato']);
                $encargo->setPercentual_encargo($row['percentual_encargo']);
                $encargo->setStatus_encargo($row['status_encargo']);
                $encargos->dados[] = $encargo;
            }
        } else {
            $encargos->erro = true;
            $encargos->mensagem = 'Nenhum encargo cadastrado.';
        }

        return $encargos;
    }

    public static function gravaEncargo($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar' ) {
            $sql = 'UPDATE encargos SET '
                    . ' nome_encargo = "' . $dados['nome_encargo'] . '", '
                    . ' percentual_encargo = ' . $dados['percentual_encargo']
                    . ' WHERE id_encargo = ' . $dados['id_encargo'];
        } else {
            $sql = 'INSERT '
                    . 'INTO encargos ('
                    . 'id_cliente, '
                    . 'nome_encargo, '
                    . 'percentual_encargo, '
                    . 'status_encargo) '
                    . ' VALUES ( '
                    . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                    . '"' . $dados['nome_encargo'] . '",'
                    . $dados['percentual_encargo'] . ','
                    . '"ATIVO")';
        }

        $r = $conexao->query($sql);
        $encargos = new \stdClass();
        $encargos->erro = false;
        
        if($dados['acao'] == 'alterar')
            $encargos->mensagem = 'Alteração efetuada com sucesso';
        else
            $encargos->mensagem = 'Inclusão efetuada com sucesso';
            
        return $encargos;
    }

    public static function excluiEncargo($id_encargo) {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE encargos SET '
                . 'status_encargo = "EXCLUIDO" '
                . 'WHERE id_encargo = ' . $id_encargo
                . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        $r = $conexao->query($sql);
        $encargos = new \stdClass();
        $encargos->erro = false;
        $encargos->mensagem = 'Encargo excluído com sucesso';

        return $encargos;
    }

    /*
     * Muda o flag de inserção automática
     * Todos os contratos marcarcados serão inseridos automaticamente no novo contrato
     */
    public static function mudaInsereAutomaticoEncargoContrato($id_encargo) {
        $conexao = MySQL::conexao();
            $sql = 'UPDATE encargos SET 
                    insere_automatico_contrato =
                        CASE 
                            WHEN insere_automatico_contrato = 1 THEN 0
                            WHEN insere_automatico_contrato = 0 THEN 1 END
                    WHERE id_encargo = ' . $id_encargo;
        $r = $conexao->query($sql);
        $encargos = new \stdClass();
        $encargos->erro = false;
        
        $encargos->mensagem = 'Encargo alterado com sucesso';
        
        return $encargos;
    }    
}
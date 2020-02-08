<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Cargo {

    public $id_cargo;
    public $nome_cargo;
    public $remuneracao_cargo;
    public $status_cargo;
    public $turno;

    function getId_cargo() {
        return $this->id_cargo;
    }

    function getNome_cargo() {
        return $this->nome_cargo;
    }

    function getRemuneracao_cargo() {
        return $this->remuneracao_cargo;
    }

    function getStatus_cargo() {
        return $this->status_cargo;
    }

    function getTurno() {
        return $this->turno;
    }

    function setId_cargo($id_cargo) {
        $this->id_cargo = $id_cargo;
    }

    function setNome_cargo($nome_cargo) {
        $this->nome_cargo = $nome_cargo;
    }

    function setRemuneracao_cargo($remuneracao_cargo) {
        $this->remuneracao_cargo = $remuneracao_cargo;
    }

    function setStatus_cargo($status_cargo) {
        $this->status_cargo = $status_cargo;
    }

    function setTurno($turno) {
        $this->turno = $turno;
    }

    public static function buscaCargo($id_cargo = null) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT 
                    id_cargo, 
                    nome_cargo, 
                    remuneracao_cargo, 
                    status_cargo,
                    (CASE 
                        WHEN id_turno = 1 THEN "Diurno" 
                        WHEN id_turno = 2 THEN "Noturno" END
                    ) turno               
                FROM cargos
                WHERE status_cargo = "ATIVO" 
                AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($id_cargo) {
            $sql = $sql . ' AND id_cargo =  ' . $id_cargo;
        }

        $sql .= ' ORDER BY nome_cargo ';
        
        $cargo = new \stdClass();
        $cargos = new \stdClass();
        $cargos->erro = false;
        $cargos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $cargo = new Cargo();
                $cargo->setId_cargo($row['id_cargo']);
                $cargo->setNome_cargo($row['nome_cargo']);
                $cargo->setRemuneracao_cargo($row['remuneracao_cargo']);
                $cargo->setStatus_cargo($row['status_cargo']);
                $cargo->setTurno($row['turno']);
                $cargos->dados[] = $cargo;
            }
        } else {
            $cargos->erro = true;
            $cargos->mensagem = 'Nenhum cargo cadastrado.';
        }

        return $cargos;
    }

    public static function gravaCargo($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE cargos SET '
                    . ' nome_cargo = "' . $dados['nome_cargo'] . '", '
                    . ' remuneracao_cargo = ' . floatval($dados['remuneracao_cargo']) . ', '
                    . ' id_turno = ' . $dados['id_turno']
                    . ' WHERE id_cargo = ' . $dados['id_cargo']
                    . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();
        } else {
            $sql = 'INSERT '
                    . 'INTO cargos ('
                    . 'id_cliente, '
                    . 'nome_cargo, '
                    . 'remuneracao_cargo, '
                    . 'status_cargo,  '
                    . 'id_turno ) '
                    . ' VALUES ( '
                    . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                    . '"' . $dados['nome_cargo'] . '",'
                    . floatval($dados['remuneracao_cargo']) . ', '
                    . '"ATIVO", '
                    . $dados['id_turno'] . ') ';
        }

        $r = $conexao->query($sql);
        $cargos = new \stdClass();
        $cargos->erro = false;

        if ($dados['acao'] == 'alterar')
            $cargos->mensagem = 'Alteração efetuada com sucesso';
        else
            $cargos->mensagem = 'Inclusão efetuada com sucesso';

        return $cargos;
    }

    public static function excluiCargo($id_cargo) {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE cargos SET '
                . 'status_cargo = "EXCLUIDO" '
                . 'WHERE id_cargo = ' . $id_cargo
                . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();
        
        $r = $conexao->query($sql);
        $cargos = new \stdClass();
        $cargos->erro = false;
        $cargos->mensagem = 'Cargo excluído com sucesso';

        return $cargos;
    }
}
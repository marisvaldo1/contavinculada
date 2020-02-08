<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Administrador {

    public $id_administrador;
    public $nome_administrador;
    public $email;
    public $telefone;
    public $status_administrador;

    function getId_administrador() {
        return $this->id_administrador;
    }

    function getNome_administrador() {
        return $this->nome_administrador;
    }

    function getEmail() {
        return $this->email;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function getStatus_administrador() {
        return $this->status_administrador;
    }

    function setId_administrador($id_administrador) {
        $this->id_administrador = $id_administrador;
    }

    function setNome_administrador($nome_administrador) {
        $this->nome_administrador = $nome_administrador;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function setStatus_administrador($status_administrador) {
        $this->status_administrador = $status_administrador;
    }

    public static function buscaAdministrador($id_administrador = null) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
                . 'id_administrador, '
                . 'nome_administrador, '
                . 'email,'
                . 'telefone, '
                . 'status_administrador '
                . 'FROM administradores '
                . 'WHERE status_administrador = "ATIVO" ';

        if ($id_administrador) {
            $sql = $sql . 'AND id_administrador =  ' . $id_administrador;
        }

        $administrador = new \stdClass();
        $administradores = new \stdClass();
        $administradores->erro = false;
        $administradores->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $administrador = new Administrador();
                $administrador->setId_administrador($row['id_administrador']);
                $administrador->setNome_administrador($row['nome_administrador']);
                $administrador->setEmail($row['email']);
                $administrador->setTelefone($row['telefone']);
                $administrador->setStatus_administrador($row['status_administrador']);
                $administradores->dados[] = $administrador;
            }
        } else {
            $administradores->erro = true;
            $administradores->mensagem = 'Nenhum administrador cadastrado.';
        }

        return $administradores;
    }

    public static function gravaAdministrador($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE administradores SET '
                    . 'nome_administrador = "' . $dados['nome_administrador'] . '", '
                    . 'email = "' . $dados['email'] . '", '
                    . 'telefone = "' . $dados['telefone'] . '" '
                    . 'WHERE id_administrador = ' . $dados['id_administrador'];
        } else {
            $sql = 'INSERT '
                    . 'INTO administradores ('
                    . 'nome_administrador, '
                    . 'email, '
                    . 'telefone, '
                    . 'status_administrador) '
                    . ' VALUES ( '
                    . '"' . $dados['nome_administrador'] . '",'
                    . '"' . $dados['email'] . '",'
                    . '"' . $dados['telefone'] . '",'
                    . '"ATIVO")';
        }

        $r = $conexao->query($sql);
        $administradores = new \stdClass();
        $administradores->erro = false;

        if ($dados['acao'] == 'alterar')
            $administradores->mensagem = 'Alteração efetuada com sucesso';
        else
            $administradores->mensagem = 'Inclusão efetuada com sucesso';

        return $administradores;
    }

    public static function excluiAdministrador($id_administrador) {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE administradores SET '
                . 'status_administrador = "EXCLUIDO" '
                . 'WHERE id_administrador = ' . $id_administrador;

        $r = $conexao->query($sql);
        $administradores = new \stdClass();
        $administradores->erro = false;
        $administradores->mensagem = 'Administrador excluído com sucesso';

        return $administradores;
    }
}

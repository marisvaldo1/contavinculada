<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Categoria {

    public $id_categoria;
    public $nome_categoria;
    public $status_categoria;

    function getId_categoria() {
        return $this->id_categoria;
    }

    function getNome_categoria() {
        return $this->nome_categoria;
    }

    function getStatus_categoria() {
        return $this->status_categoria;
    }

    function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    function setNome_categoria($nome_categoria) {
        $this->nome_categoria = $nome_categoria;
    }

    function setStatus_categoria($status_categoria) {
        $this->status_categoria = $status_categoria;
    }

    public static function buscaCategoria($id_categoria = null) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
                . ' id_categoria, '
                . ' nome_categoria, '
                . ' status_categoria '
                . ' FROM categorias '
                . ' WHERE status_categoria = "ATIVO" ';

        if ($id_categoria) {
            $sql = $sql . ' AND id_categoria =  ' . $id_categoria;
        }

        $categoria = new \stdClass();
        $categorias = new \stdClass();
        $categorias->erro = false;
        $categorias->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $categoria = new Categoria();
                $categoria->setId_categoria($row['id_categoria']);
                $categoria->setNome_categoria($row['nome_categoria']);
                $categoria->setStatus_categoria($row['status_categoria']);
                $categorias->dados[] = $categoria;
            }
        } else {
            $categorias->erro = true;
            $categorias->mensagem = 'Nenhum categoria cadastrada.';
        }

        return $categorias;
    }

    public static function gravaCategoria($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE categorias SET '
                    . 'nome_categoria = "' . $dados['nome_categoria'] . '" '
                    . 'WHERE id_categoria = ' . $dados['id_categoria'];
        } else {
            $sql = 'INSERT '
                    . 'INTO categorias ('
                    . 'nome_categoria, '
                    . 'status_categoria) '
                    . ' VALUES ( '
                    . '"' . $dados['nome_categoria'] . '",'
                    . '"ATIVO")';
        }

        $r = $conexao->query($sql);
        $categorias = new \stdClass();
        $categorias->erro = false;

        if ($dados['acao'] == 'alterar')
            $categorias->mensagem = 'Alteração efetuada com sucesso';
        else
            $categorias->mensagem = 'Inclusão efetuada com sucesso';

        return $categorias;
    }

    public static function excluiCategoria($id_categoria) {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE categorias SET '
                . 'status_categoria = "EXCLUIDO" '
                . 'WHERE id_categoria = ' . $id_categoria;
        
        $r = $conexao->query($sql);
        $categorias = new \stdClass();
        $categorias->erro = false;
        $categorias->mensagem = 'Categoria excluído com sucesso';

        return $categorias;
    }
}
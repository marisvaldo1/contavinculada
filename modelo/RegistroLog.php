<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class RegistroLog {

    public $id_acesso;
    public $nome;
    public $dt_hr_acesso;
    public $url_acessada;
    
    function getId_acesso() {
        return $this->id_acesso;
    }

    function getNome() {
        return $this->nome;
    }

    function getDt_hr_acesso() {
        return $this->dt_hr_acesso;
    }

    function getUrl_acessada() {
        return $this->url_acessada;
    }

    function setId_acesso($id_acesso) {
        $this->id_acesso = $id_acesso;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDt_hr_acesso($dt_hr_acesso) {
        $this->dt_hr_acesso = $dt_hr_acesso;
    }

    function setUrl_acessada($url_acessada) {
        $this->url_acessada = $url_acessada;
    }

    public static function registraLogAcesso($dados) {
        $conexao = MySQL::conexao();
        try {
            $sql = 'INSERT INTO log_acesso 
                ( id_usuario, dt_hr_acesso, url_acessada )
                VALUES ( ' . $_SESSION["dados_usuario"]->getUsuario() . ', NOW(), "' . $dados['url'] . '")';

            $r = $conexao->query($sql);
            $acesso = new \stdClass();
            $acesso->erro = false;
        } catch (Exception $ex) {
            $acesso->erro = true;
            $acesso->mensagem = 'Log não criado.';
        }

        return $acesso;
    }

    public static function listaLogAcesso($dados) {
        //return json_decode('{"erro":false,"dados":[{"nuUsuario":"1","nomeUsuario":"Usuário de teste 1","dtInicio":"2018-10-01","dtFinal":"2018-10-01","url":"http://localhost:8080/contasvinculadas/app/cadastro/usuarios/index.php"},{"nuUsuario":"1","nomeUsuario":"Usuário de teste 1","dtInicio":"2018-09-30","dtFinal":"2018-09-30","url":"http://localhost:8080/contasvinculadas/app/cadastro/empresas/index.php"},{"nuUsuario":"2","nomeUsuario":"Usuário de teste 2","dtInicio":"2018-10-01","dtFinal":"2018-10-01","url":"http://localhost:8080/contasvinculadas/app/cadastro/usuarios/index.php"},{"nuUsuario":"3","nomeUsuario":"Usuário de teste 3","dtInicio":"2018-09-30","dtFinal":"2018-09-30","url":"http://localhost:8080/contasvinculadas/app/cadastro/empresas/index.php"}]}');
        $conexao = MySQL::conexao();

        $sql = ' SELECT log.id_acesso, usu.nome, log.dt_hr_acesso, log.url_acessada 
                 FROM log_acesso log, usuarios usu, clientes cli
                 WHERE log.id_usuario = usu.id_usuario
                 AND usu.id_cliente = cli.id_cliente
                 AND cli.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();
        
        if ($dados['data']) {
            $sql .= ' AND date_format(log.dt_hr_acesso,"%Y-%m-%d") = "' . $dados['data'] . '"';
        }

        if ($dados['id_usuario']) {
            $sql .= ' AND log.id_usuario = ' . $dados['id_usuario'];
        }

        $acesso = new \stdClass();
        $acessos = new \stdClass();
        $acessos->erro = false;
        $acessos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $acesso = new RegistroLog();
                $acesso->setId_acesso($row['id_acesso']);
                $acesso->setNome($row['nome']);
                $acesso->setDt_hr_acesso($row['dt_hr_acesso']);
                $acesso->setUrl_acessada($row['url_acessada']);
                $acessos->dados[] = $acesso;
            }
        } else {
            $acessos->erro = true;
            $acessos->mensagem = 'Nenhum acesso cadastrado.';
        }

        return $acessos;
    }

}

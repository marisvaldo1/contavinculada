<?php

namespace componentes;

class LogAcesso {

    public $url_acessada;
    public $id_usuario;
    public $senha;
    public $nomeUsuario;
    public $dt_hr_acesso;
    
    function getUrl_acessada() {
        return $this->url_acessada;
    }

    function getId_usuario() {
        return $this->id_usuario;
    }

    function getSenha() {
        return $this->senha;
    }

    function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    function getDt_hr_acesso() {
        return $this->dt_hr_acesso;
    }

    function setUrl_acessada($url_acessada) {
        $this->url_acessada = $url_acessada;
    }

    function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

    function setNomeUsuario($nomeUsuario) {
        $this->nomeUsuario = $nomeUsuario;
    }

    function setDt_hr_acesso($dt_hr_acesso) {
        $this->dt_hr_acesso = $dt_hr_acesso;
    }

    public function registraLogAcesso($url) {
        try {
            $sql = 'INSERT INTO log_acesso 
                ( id_usuario, dt_hr_acesso, url_acessada )
                VALUES 
                ( ' . $_SESSION["dados_usuario"]->getCliente_usuario() . ',
                NOW(), ' . $url . ')';

            $r = $conexao->query($sql);
            $acesso = new \stdClass();
            $acesso->erro = false;
        } catch (Exception $ex) {
            $acesso->erro = true;
            $acesso->mensagem = 'Log não criado.';
        }

        return $acesso;
    }

    public function listaLogAcesso($data = null, $id_usuario = null) {
        //return json_decode('{"erro":false,"dados":[{"nuUsuario":"1","nomeUsuario":"Usuário de teste 1","dtInicio":"2018-10-01","dtFinal":"2018-10-01","url":"http://localhost:8080/contasvinculadas/app/cadastro/usuarios/index.php"},{"nuUsuario":"1","nomeUsuario":"Usuário de teste 1","dtInicio":"2018-09-30","dtFinal":"2018-09-30","url":"http://localhost:8080/contasvinculadas/app/cadastro/empresas/index.php"},{"nuUsuario":"2","nomeUsuario":"Usuário de teste 2","dtInicio":"2018-10-01","dtFinal":"2018-10-01","url":"http://localhost:8080/contasvinculadas/app/cadastro/usuarios/index.php"},{"nuUsuario":"3","nomeUsuario":"Usuário de teste 3","dtInicio":"2018-09-30","dtFinal":"2018-09-30","url":"http://localhost:8080/contasvinculadas/app/cadastro/empresas/index.php"}]}');
        $conexao = MySQL::conexao();

        $sql = ' SELECT log.id_usuario, usu.nome, log.dt_hr_acesso, log.url_acessada
                 FROM log_acesso log, usuarios usu
                 WHERE log.id_usuario = usu.id_usuario ';
        
        if ($data) {
            $sql .= ' AND log.dt_hr_acesso = ' . $data;
        }

        if ($id_usuario) {
            $sql .= ' AND log.id_usuario = ' . $id_usuario;
        }

        $acesso = new \stdClass();
        $acessos = new \stdClass();
        $acessos->erro = false;
        $acessos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $acesso = new LogAcesso();
                $acesso->setId_usuario($row['id_usuario']);
                $acesso->setNomeUsuario($row['nome_usuario']);
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

<?php

namespace componentes;

class Login {

    private $url;
    private $usuario;
    private $senha;

    public function __construct($pars) {
        $this->url = $pars['url'];
        $this->usuario = $pars['usuario'];
        $this->senha = $pars['senha'];
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function listaLogin($email = null) {
        $resultado = ['erro' => false];

        try {
            $resultado = Login::listaLogin();
        } catch (Exception $ex) {
            $resultado = ['erro' => true, 'mensagem' => $ex->getMessage()];
        }
        gzip();
        echo $resultado;
    }

}

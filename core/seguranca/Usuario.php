<?php

namespace seguranca;

use bd\Formatos;

class Usuario
{

    private $nome;
    private $email;
    private $nivel_acesso;
    private $id_empresa_visitante;
    private $nome_empresa_visitante;
    private $opcoes_acesso;
    private $cliente_usuario;
    private $usuario;
    private $nome_cliente;
    private $status_usuario;
    private $IP_login;
    private $token_login;


    /**
     * @return bool
     * @throws \Exception
     */
    public function capturaUsuario()
    {
        if (!$this->login) {
            throw new \Exception('Impossível localizar usuário.');
        }
        return $this->login;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getNivel_acesso()
    {
        return $this->nivel_acesso;
    }

    public function getId_empresa_visitante()
    {
        return $this->id_empresa_visitante;
    }

    function getOpcoes_acesso()
    {
        return $this->opcoes_acesso;
    }

    function getCliente_usuario()
    {
        return $this->cliente_usuario;
    }

    function getUsuario()
    {
        return $this->usuario;
    }

    function getNome_cliente()
    {
        return $this->nome_cliente;
    }

    function getStatus_usuario()
    {
        return $this->status_usuario;
    }

    function getIP_login()
    {
        return $this->IP_login;
    }

    function getToken_login()
    {
        return $this->token_login;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setNivel_acesso($nivel_acesso)
    {
        $this->nivel_acesso = $nivel_acesso;
    }

    public function setId_empresa_visitante($id_empresa_visitante)
    {
        $this->id_empresa_visitante = $id_empresa_visitante;

        return $this;
    }

    function setOpcoes_acesso($opcoes_acesso)
    {
        $this->opcoes_acesso = $opcoes_acesso;
    }

    function setCliente_usuario($cliente_usuario)
    {
        $this->cliente_usuario = $cliente_usuario;
    }

    function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    function setNome_cliente($nome_cliente)
    {
        $this->nome_cliente = $nome_cliente;
    }

    function setStatus_usuario($status_usuario)
    {
        $this->status_usuario = $status_usuario;
    }

    function setIP_login($IP_login)
    {
        $this->IP_login = $IP_login;
    }

    function setToken_login($token_login)
    {
        $this->token_login = $token_login;
    }

    /**
     * Get the value of nome_empresa_visitante
     *
     * @return  mixed
     */
    public function getNome_empresa_visitante()
    {
        return $this->nome_empresa_visitante;
    }

    /**
     * Set the value of nome_empresa_visitante
     *
     * @param   mixed  $nome_empresa_visitante  
     *
     * @return  self
     */
    public function setNome_empresa_visitante($nome_empresa_visitante)
    {
        $this->nome_empresa_visitante = $nome_empresa_visitante;

        return $this;
    }
}
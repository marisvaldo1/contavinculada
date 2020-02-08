<?php

namespace componentes;

class Componentes
{
    /** Lista de componentes do arquivo de configuração.
     *
     * @var array 
     */

    public static $componentes;

    public static function inicia()
    {
        $componentes = require(RAIZ . 'config/componentes.php');
        self::$componentes = $componentes[AMBIENTE_PUBLICACAO];
    }

    /**
     *
     * @return \Lista de Clientes
     */
    public static function cliente()
    {
        $pars = self::$componentes['cliente'];
        return new Cliente($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }

    /**
     *
     * @return \Lista de Contratos
     */
    public static function contrato()
    {
        $pars = self::$componentes['contrato'];
        return new Contrato($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }
    
    /**
     *
     * @return \Lista de Contratos
     */
    public static function encargo()
    {
        $pars = self::$componentes['encargo'];
        return new Encargo($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }
    
    /**
     *
     * @return \Lista de Contratos
     */
    public static function indice()
    {
        $pars = self::$componentes['indice'];
        return new Indice($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }

    /**
     *
     * @return \Lista de Contratos
     */
    public static function empregado()
    {
        $pars = self::$componentes['empregado'];
        return new Empregado($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }
    
    /**
     *
     * @return \Lista de Contratos
     */
    public static function usuario()
    {
        $pars = self::$componentes['usuario'];
        return new Usuario($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }

    /**
     *
     * @return \Lista de Contratos
     */
    public static function login()
    {
        $pars = self::$componentes['login'];
        return new Login($pars['wsdl'], $pars['login'], $pars['senha']);
    }

    /**
     *
     * @return \Acesso ao sistema
     */
    public static function logacesso()
    {
        $pars = self::$componentes['logacesso'];
        return new LogAcesso($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }

    /**
     *
     * @return \Acesso ao sistema
     */
    public static function registraAcesso()
    {
        $pars = self::$componentes['registraAcesso'];
        return new LogAcesso($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }

    /**
     *
     * @return \Lista de Contratos
     */
    public static function listaUsuario()
    {
        $pars = self::$componentes['usuario'];
        return new Usuario($pars['wsdl'], $pars['usuario'], $pars['senha']);
    }

}
<?php
namespace componentes;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CepService
 *
 * @author 81092610
 */
class CepService
{
    private $wsdl;
    private $usuario;
    private $senha;
    private $soap;

    /**
     *
     * @param Conf $conf
     * @throws Exception
     */
    public function __construct($wsdl, $usuario, $senha)
    {
        $this->wsdl = $wsdl;
        $this->usuario = $usuario;
        $this->senha = $senha;
        try {
            $this->soap = new \SoapClient($this->wsdl, ['login' => $this->usuario, 'password' => $this->senha]);
        } catch (\Exception $ex) {
            throw new \Exception('Erro ao instanciar componente de CEP.', 0, $ex);
       }
    }

    public function getWsdl()
    {
        return $this->wsdl;
    }

    public function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getSoap()
    {
        return $this->soap;
    }

    public function setSoap($soap)
    {
        $this->soap = $soap;
    }

    /**
     * Retorna um objeto Cep, contendo os dados do CEP informado no parâmetro. Estes dados são atualizados quinzenalmente, no dia 01 e 15 de cada mês.
     * Cuidado ao usar esse Método para comparar com CEPs de outras fontes de CEP que são atualizadas trimestralmente
     *
     * @param string $cep CEP no formato 00000000
     * @return array
     * @throws \Exception Erro ao consultar componente de CEP
     */
    public function getDadosCep($cep)
    {
        try {
            $retorno = $this->soap->getDadosCep(['cep' => $cep]);

            if ($retorno->CEPS) {
                $dadosCep = [
                    "uf" => $retorno->CEPS->VUF,
                    "localidade" => $retorno->CEPS->VLOC_NO,
                    "logradouro" => $retorno->CEPS->VLOG_NO_DNEC,
                    "tipoLogradouro" => $retorno->CEPS->VTLO_TX,
                    "bairro" => $retorno->CEPS->VBAIRRO,
                  //  "complemento" => $retorno->CEPS->VCOMPLEMENTO,
                    "valido" => $retorno->CEPS->VCEP_VALIDO,
                ];

                return $dadosCep;
            }
            return false;
        } catch (\Exception $ex) {
            throw new \Exception('Erro ao consultar componente de CEP.', 1, $ex);
        }
    }

    public function CalcDataMaxima()
    {
        try {
            $retorno = $this->soap->CalcDataMaxima(['codigoObjeto' => 'OF385107428BR']);
            dd($retorno);
        } catch (\Exception $ex) {
            throw new \Exception('Erro ao consultar componente de CEP.', 1, $ex);
        }
    }

}

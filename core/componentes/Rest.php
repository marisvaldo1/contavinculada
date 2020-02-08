<?php

namespace componentes;

use Autenticacao;
use GuzzleHttp;


class Rest
{
    public static $componentes;

    public static function inicia()
    {
        $componentes = require(RAIZ . 'config/componentes.php');
        self::$componentes = $componentes[AMBIENTE_PUBLICACAO];
    }

    public static function chamaApiPost($comp,$useToken=false,$json=false)
    {
        try {
            $client = new GuzzleHttp\Client();
            $pars = self::$componentes[$comp];
            if($json){
                $json = json_decode($json);
            }
            if ($useToken === true) {
                $header = [
                    'Authorization' => "Bearer " . Autenticacao::obtemToken(),
                    'Content-Type' => 'application/json'
                ];
            } else {
                $header = array('Accept' => 'application/json');
            }
            $result = $client->request('POST',$pars['url'], [
                'auth'=>[$pars['usuario'],$pars['senha']],
                'headers'=> $header

            ]);
            if (!$result->getStatusCode()) {
                throw new \Exception('Erro ao carregar a API ' . $comp . ' via POST ' . 'Status-Code: ' . $result->getStatusCode());
            }
            return json_decode($result->getBody()->getContents(), true);
        } catch (\Exception $ex) {
            throw new \Exception('Erro ao carregar a API ' . $comp . ' via POST ' . $ex);
        }
    }


    public static function chamaApiGet($comp, $param, $useToken)
    {
        
        try {
            $client = new GuzzleHttp\Client();
            $pars = self::$componentes[$comp];           

            if ($useToken === true) {
                $headers = [
                    'Authorization' => "Bearer " . Autenticacao::obtemToken(),
                    'Content-Type' => 'application/json'
                ];
            } else {
                $headers = array('Accept' => 'application/json');
            }
                       

            
            $result = $client->request('GET',$pars['url'].$param, [
                'auth'=>[$pars['usuario'],$pars['senha']],
                'headers'=> $headers
            ]);


     
            
            
            if (!$result->getStatusCode()) {
                throw new \Exception('Erro ao carregar a API ' . $comp . ' via GET ' . 'Status-Code: ' . $result->getStatusCode());
            }
            return json_decode($result->getBody()->getContents(), true);
        } catch (\Exception $ex) {
            throw new \Exception('Erro ao carregar a API ' . $comp . ' via GET ' . $ex);
        }
    }

    public static function chamaApiDelete($comp, $param, $useToken)
    {
        try {
            $client = new GuzzleHttp\Client();
            $pars = self::$componentes[$comp];

            if ($useToken === true) {
                $headers = [
                    'Authorization' => "Bearer " . Autenticacao::obtemToken(),
                    'Content-Type' => 'application/json'
                ];
            } else {
                $headers = array('Accept' => 'application/json');
            }
            $result = $client->request('DELETE',$pars['url'].$param, [
                'headers'=> $headers
            ]);

            if (!$result->getStatusCode()) {
                throw new \Exception('Erro ao carregar a API ' . $comp . ' via DELETE ' . 'Status-Code: ' . $result->getStatusCode());
            }
            return json_decode($result->getBody()->getContents(), true);
        } catch (\Exception $ex) {
            throw new \Exception('Erro ao carregar a API ' . $comp . ' via DELETE ' . $ex);
        }
    }


}
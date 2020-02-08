<?php

use seguranca\Usuario;

class Autenticacao extends AutenticacaoBase {

    public static function carregaSessaoComLogin($retorno) {
        //Cria um objeto para guardar em sessao
        $usuario = new Usuario();
        if ($retorno && $retorno->erro == false) {

            define('NIVEL_ACESSO', $retorno->nivel_acesso);
            define('USUARIO_VISITANTE', NIVEL_ACESSO === "4");

            // Se não é visitante, seta os valores default como vazios
            if(USUARIO_VISITANTE){
                define('EMPRESA_VISITANTE', $retorno->id_empresa_visitante);
                define('NOME_EMPRESA_VISITANTE', $retorno->nome_empresa_visitante);
            } else {
                define('EMPRESA_VISITANTE', 0);
                define('NOME_EMPRESA_VISITANTE', '');
            }
            
            $usuario->setNome_empresa_visitante(NOME_EMPRESA_VISITANTE);
            $usuario->setId_Empresa_visitante(EMPRESA_VISITANTE);
            $usuario->setNome($retorno->nome);
            $usuario->setEmail($retorno->email);
            $usuario->setNivel_acesso($retorno->nivel_acesso);
            //$usuario->setId_empresa_visitante($retorno->id_empresa_visitante);
            $usuario->setCliente_usuario($retorno->id_cliente);
            $usuario->setUsuario($retorno->id_usuario);
            $usuario->setNome_cliente($retorno->nome_cliente);
            $usuario->setOpcoes_acesso($retorno->opcoes_acesso);
            $usuario->setStatus_usuario($retorno->status_usuario);
            $usuario->setIP_login($retorno->IP_login);
            $usuario->setToken_login($retorno->token_login);
            $_SESSION["dados_usuario"] = $usuario;
        }
    }
}

<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class EnviaEmail {

    public static function envia($dados) {
        $email = $dados['email'];
        $emailenvio = "administrador@contavinculada.com.br";
        $subject = "Recuperar a senha!";

        $message = 'Foi gerada uma senha provisória para acesso ao sistema contavinculada. Senha: ' . $dados['senha'];
        $message = wordwrap($message, 70);

        $header = "MIME-Version: 1.1\n";
        $header .= "Content-type: text/plain; charset=iso-8859-1\n";
        $header .= "From: $emailenvio\n";
        $header .= "Return-Path: $email_remetente\n";

        $retorno = new \stdClass();
        $retorno = [
            'erro' => false,
            'mensagem' => "Email enviado com sucesso"
        ];

        $envio = mail($email, $subject, $message, $header);

        if (!$envio) {
            $retorno = [
                'erro' => true,
                'mensagem' => "Erro no envio do email"
            ];
        } else {
            $conexao = MySQL::conexao();
            $sql = 'SELECT id_cliente, id_usuario '
                    . ' FROM usuarios '
                    . ' WHERE email = "' . $dados['email'] . '" ';
            $r = $conexao->query($sql);

            if ($r->num_rows) {
                $l = $conexao->fetch($r);

                $id_cliente = $l['id_cliente'];
                $id_usuario = $l['id_usuario'];

                $sql = 'UPDATE usuarios SET '
                        . ' senha = "' . $dados['senha'] . '", '
                        . ' token_login = "" '
                        . ' WHERE id_usuario = ' . $id_usuario
                        . ' AND id_cliente = ' . $id_cliente;

                $r = $conexao->query($sql);
            } else {
                $retorno = [
                    'erro' => true,
                    'mensagem' => "Usuário não encontrado"
                ];
            }
        }

        return $retorno;
    }

    public static function enviarMensagem($dados) {
        
        ini_set('display_errors', 1);
        error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
            
        $emaiAdministrador = "mm@contavinculada.com.br";
        //$emaiAdministrador = "marisvaldo@gmail.com";
        $subject = "Mensagem de visitante do site";

        $message  = 'Visitante: <b>' . $dados['nomeVisitante'] . '</b><br>';
        $message .= 'E-Mail: <b>' . $dados['emailVisitante'] . '</br><hr>';
        $message .= $dados['mensagemVisitante'] . '<hr>';
        $message  = wordwrap($message, 70);

            // headers que prepara a mensagem
        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: " . $dados['nomeVisitante'] . "<" . $dados['emailVisitante'] . ">\r\n";
        $headers .= "Reply-To: " . $dados['emailVisitante'] . "\r\n";

        $retorno = new \stdClass();
        $envio = mail($emaiAdministrador, $subject, $message, $headers);
        try {

            if (!$envio) {
                $retorno = ['erro' => true, 'mensagem' => "Erro no envio do email"];
            } else {
                $retorno = ['erro' => false, 'mensagem' => "Email enviado com sucesso"];
            }
        } catch (Exception $ex) {
            $retorno = [
                'erro' => true,
                'mensagem' => $ex->getMessage()
            ];
        }

        return $retorno;
    }

}

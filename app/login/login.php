<?php

include '../../inicia.php';

use modelo\Usuario;
use modelo\EnviaEmail;

/*
 * Seta o timezone para Brasil
 */
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

try {
    $acao = $_REQUEST['acao'];

    if ($acao == 'envia-email') {
        $retorno = EnviaEmail::envia($_REQUEST);
    } else if ($acao == 'logar') {
        $retorno = Usuario::buscaLogin($_REQUEST['email'], $_REQUEST['senha']);
    } else if ($acao == 'enviar_mensagem') {
        $retorno = EnviaEmail::enviarMensagem($_REQUEST);
    } else if ($acao == 'logar_nova_sessao') {
        $retorno = Usuario::novaSessao($_REQUEST['email'], $_REQUEST['senha']);
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
} finally {
    if (!$retorno->erro && ($acao !== 'enviar_mensagem')) {
        Autenticacao::carregaSessaoComLogin($retorno);
    }
    echo json_encode($retorno);
}

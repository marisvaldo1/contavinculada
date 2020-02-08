<?php
include '../inicia.php';
include 'funcoes.php';
try {
    \api\Aut::ini();
    header('Content-Type: application/json');
    $q = explode('/', $_GET['q']);
    $nomeClasse = array_shift($q);
    $nomeClasse = 'api\\' . $nomeClasse;
    $recurso = new $nomeClasse();
} catch (\Exception $e) {
    $ret = ['erro' => true, 'mensagem' => $e->getMessage(), 'codigo' => $e->getCode()];
    header("HTTP/1.0 500");
    echo json_encode($ret);
} catch (\Error $e) {
    if (preg_match('/^Class (.*) not found$/', $e->getMessage())) {
        $mensagem = 'Recurso não encontrado.';
        $codigo = 1;
        header("HTTP/1.0 404");
    } else {
        $mensagem = $e->getMessage();
        $codigo = $e->getCode();
        header("HTTP/1.0 500");
    }
    $ret = ['erro' => true, 'mensagem' => $mensagem, 'codigo' => $codigo];
    echo json_encode($ret);
}

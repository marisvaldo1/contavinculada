<?php

use bd\MySQL;

include '../../../inicia.php';

try {
    $c = MySQL::conexao('pphp');
    $sql = 'SELECT mcu, nome, tipo2 FROM unidade_negocio WHERE mcu = ?';
    $p = [
        $_GET['mcu']
    ];
    $r = $c->query($sql, $p);
    if ($r->num_rows) {
        $l = $r->fetch_assoc();
        $ret = ['erro' => false, 'mcu' => $l['mcu'], 'nome' => $l['nome'], 'tipo' => $l['tipo2']];
    } else {
        throw new Exception('Nenhum registro encontrado.');
    }
} catch (Exception $ex) {
    $ret = ['erro' => true, 'mensagem' => $ex->getMessage()];
}
echo json_encode($ret, JSON_PRETTY_PRINT);

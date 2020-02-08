<?php

use bd\MySQL;

include '../../../inicia.php';
define('MAXIMO_REGISTROS', 20000);
try {
    $t = str_replace(['dr/', 'DR/', 'dr ', 'DR '], ['dr', 'DR', 'dr', 'DR'], $_GET['t']);
    $t = MySQL::ft($t);
    $c = MySQL::conexao('pphp');
    if (isset($_GET['tipos'])) {
        $tipos = explode(',', $_GET['tipos']);
        if ($tipos[0]) {
            foreach ($tipos as &$tipo) {
                if (preg_match('/^[0-9]+$/', $tipo)) {
                    $tipo = "'" . $tipo . "'";
                } else {
                    throw new \Exception('Tipos de unidade de negócio precisam ser uma lista de códigos '
                        . 'separados por vírgula');
                }
            }
        } else {
            unset($tipos);
        }
    }
    $sql = 'SELECT mcu, nome, sigla, tipo, tipo2, uf, cod_dr, nome_dr, sigla_dr, cidade
            FROM unidade_negocio
            WHERE MATCH(texto) AGAINST(? IN BOOLEAN MODE)';
    if (isset($tipos)) {
        $sql .= ' AND tipo2 IN (' . implode(', ', $tipos) . ')';
    }
    $sql .= ' ORDER BY nome';
    $p = [
        $t
    ];
    $r = $c->query($sql, $p);
    if ($r->num_rows) {
        if ($r->num_rows >= MAXIMO_REGISTROS) {
            throw new Exception('Muitos registros. Tente ser mais específico.');
        }
        $ret = [];
        while ($l = $r->fetch_assoc()) {
            foreach ($l as &$v) {
                $v = trim($v);
            }
            unset($v);
            $ret[] = $l;
        }
        if ($_GET['apenas_superiores']) {
            $ret_filtrado = [];
            foreach ($ret as $l) {
                if (strpos($l['nome'], '/') === false) {
                    $ret_filtrado[] = $l;
                }
            }
            $ret = $ret_filtrado;
        }
    } else {
        throw new Exception('Nenhum registro encontrado.');
    }
} catch (Exception $ex) {
    $ret = ['erro' => true, 'mensagem' => $ex->getMessage()];
}
echo json_encode($ret, JSON_PRETTY_PRINT);

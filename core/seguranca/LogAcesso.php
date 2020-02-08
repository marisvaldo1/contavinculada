<?php

namespace seguranca;

class LogAcesso
{

    public static $codigo;

    public static function registra()
    {
        $cod_usuario = Aut::codigo();
        $c = \conexao();
        $sql = 'INSERT INTO log_acesso'
            . '(cod_usuario, `data`, ip, browser, url, hash_url, query_string, sessao)'
            . 'VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)';
        $par = [
            $cod_usuario,
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['PHP_SELF'],
            \hasha256($_SERVER['PHP_SELF']),
            $_SERVER['QUERY_STRING'],
            session_id(),
        ];
        $c->query($sql, $par);
        self::$codigo = $c->id();
    }

    public static function info($dados)
    {
        $json = json_encode($dados);
        $sql = 'UPDATE log_acesso SET info = ? WHERE codigo = ?';
        $c = \conexao();
        $c->query($sql, [$json, self::$codigo]);
    }

    public static function selecionaUsuarios($dataInicial, $dataFinal)
    {
        $di = \DateTime::createFromFormat('d/m/Y', $dataInicial);
        $df = \DateTime::createFromFormat('d/m/Y', $dataFinal);
        if (!$di) {
            throw new \Exception('Informe a data inicial.');
        }
        if (!$df) {
            throw new \Exception('Informe a data final.');
        }
        $c = \conexao();
        $sql = 'SELECT la.codigo, la.data, la.url, la.cod_usuario, u.usuario
                FROM log_acesso la
                INNER JOIN usuario u ON la.cod_usuario = u.codigo
                WHERE   u.codigo <> 1 AND
                        la.data BETWEEN ? AND ?
                ORDER BY codigo DESC LIMIT 100';
        $params = [
            $di->format('Y-m-d'),
            $df->format('Y-m-d') . ' 23:59:59',
        ];
        $r = $c->query($sql, $params);
        $ret = [];
        while ($l = $r->fetch_assoc()) {
            $ret[] = $l;
        }
        return $ret;
    }

}

<?php

namespace bd;

class MySQL
{

    const ERR_DUPLICIDADE = 1062;
    const ERR_CHAVE_ESTRANGEIRA = 1451;

    private static $conf = [];
    private static $conexoes = [];
    /**
     *
     * @var Formatos;
     */
    private static $formatos;
    private $servidor;
    private $usuario;
    private $senha;
    private $banco;
    /**
     *
     * @var mysqli
     */
    private $mysqli;

    public function __construct($servidor, $usuario, $senha, $banco)
    {
        \mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->servidor = $servidor;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->banco = $banco;
    }

    public function conecta()
    {
        if (!$this->mysqli) {
            $this->mysqli = \mysqli_init();
            //$this->mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
            $this->mysqli->real_connect($this->servidor, $this->usuario, $this->senha, $this->banco);
            $this->mysqli->set_charset('utf8');
        }
    }

    /**
     *
     * @return \mysqli;
     */
    public function mysqli()
    {
        $this->conecta();
        return $this->mysqli;
    }

    public function setConexao(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     *
     * @param string $query
     * @return \mysqli_result|FALSE
     */
    public function query($sql, array $par = null)
    {
        if ($par) {
            return $this->prepareExecute($sql, $par);
        }
        $results = [];
        $this->conecta();
        $this->mysqli->multi_query($sql);
        do {
            if ($r = $this->mysqli->store_result()) {
                $results[] = $r;
            }
        } while ($this->mysqli->more_results() && $this->mysqli->next_result());
        if (count($results) == 1) {
            return $results[0];
        }
        return $results;
    }

    public function multiQuery($query)
    {
        $results = [];
        $this->conecta();
        $this->mysqli->multi_query($query);
        do {
            if ($r = $this->mysqli->store_result()) {
                $results[] = $r;
            }
        } while ($this->mysqli->more_results() && $this->mysqli->next_result());
        if (count($results) == 1) {
            return $results[0];
        }
        return $results;
    }

    /**
     *
     * @param string $query
     * @return \mysqli_result|FALSE
     */
    public function querySingle($query)
    {
        $this->conecta();
        return $this->mysqli->query($query);
    }

    public function prepare($sql)
    {
        $this->conecta();
        return $this->mysqli->prepare($sql);
    }

    public function execute($comando, $params)
    {
        $types = '';
        foreach ($params as &$p) {
            $type = substr(gettype($p), 0, 1);
            if ($type == 'N') {
                $type = 's';
            }
            if (!in_array($type, ['i', 'd', 's'])) {
                throw new \Exception('Tipo de variável inválido para bind_param (' . $type . ').');
            }
            $types .= $type;
            if ($p === '') {
                $p = null;
            }
        }
        unset($p);
        $comando->bind_param($types, ...$params);
        $comando->execute();
        $results = [];
        do {
            if ($r = $comando->get_result()) {
                $results[] = $r;
            }
        } while ($comando->more_results() && $comando->next_result());
        if (count($results) > 1) {
            return $results;
        } else {
            if ($results) {
                return $results[0];
            }
        }
        return false;
    }

    /**
     *
     * @param type $sql
     * @param type $params
     * @return \mysqli_result[]||\mysqli_result
     */
    public function prepareExecute($sql, $params)
    {
        $comando = $this->prepare($sql);
        return $this->execute($comando, $params);
    }

    /*     * Executa mysqli_prepare sobre um SQL com placeholders e retorna o stmt criado.
     *
     * @param string $sql
     * @return \mysqli_stmt
     */

    public function start()
    {
        $this->conecta();
        mysqli_autocommit($this->mysqli, false);
    }

    public function commit()
    {
        $this->conecta();
        mysqli_commit($this->mysqli);
        mysqli_autocommit($this->mysqli, true);
    }

    public function rollback()
    {
        $this->conecta();
        mysqli_rollback($this->mysqli);
        mysqli_autocommit($this->mysqli, true);
    }

    public function bind($valor, $tipo = null)
    {
        $this->conecta();
        if (is_null($valor) || $valor === '') {
            return 'NULL';
        }
        if ($tipo) {
            $valor = self::$formatos->bd($valor, $tipo);
        }
        if (is_null($valor)) {
            return 'NULL';
        }
        if (is_string($valor)) {
            return '\'' . $this->mysqli->real_escape_string($valor) . '\'';
        }
        return $valor;
    }

    public function criaCall($procedure, $parametros)
    {
        $parametros_formatados = [];
        foreach ($parametros as $parametro) {
            if (is_array($parametro)) {
                $parametros_formatados[] = $this->bind($parametro[0], $parametro[1]);
            } else {
                $parametros_formatados[] = $this->bind($parametro);
            }
        }
        $query = 'CALL ' . $procedure . '(' . \implode(', ', $parametros_formatados) . ')';
        return $query;
    }

    /**
     *
     * @param type $procedure
     * @param type $parametros
     * @return \mysqli_result[]|\mysqli_result
     */
    public function call($procedure, $parametros)
    {
        return $this->query($this->criaCall($procedure, $parametros));
    }

    public function callDebug($procedure, $parametros)
    {
        $this->conecta();
        dd($this->criaCall($procedure, $parametros));
    }

    public function id(\mysqli_result $r = null)
    {
        if ($r) {
            return $r->fetch_assoc()['id'];
        }
        return $this->mysqli->insert_id;
    }

    public static function inicia()
    {
        $conf = include RAIZ . 'config/mysql.php';
        self::$conf = $conf[AMBIENTE];
        //self::$formatos = new Formatos;
    }

    /**
     *
     * @param string $nome Nome da conexão configurada.
     * @return \bd\MySQL
     * @throws \Exception
     */
    public static function conexao($nome = null)
    {
        return self::con($nome);
    }

    /**
     *
     * @param string $nome Nome da conexão configurada.
     * @return \bd\MySQL
     * @throws \Exception
     */
    public static function con($nome = null)
    {
        if (count(self::$conf)) {
            if (!$nome) {
                $nome = current(array_keys(self::$conf));
            }
            if (!isset(self::$conexoes[$nome])) {
                if (self::$conf[$nome]) {
                    $conf = self::$conf[$nome];
                    self::$conexoes[$nome] = new MySQL(
                        $conf['servidor'], $conf['usuario'], $conf['senha'], $conf['banco']
                    );
                } else {
                    throw new \Exception('Conexão MySQL de nome "' . $nome . '" não configurada.');
                }
            }
            return self::$conexoes[$nome];
        } else {
            throw new \Exception('Não há nenhuma configuração de banco de dados MySQL.');
        }
    }

    /*     * Retorna o insert_id do objeto mysqli.
     *
     * @return int
     */

    public static function fromMySQL($valor, $tipo = null)
    {
        if (is_null($valor)) {
            return null;
        }
        if ($tipo) {
            $valor = self::$formatos->app($valor, $tipo);
        }
        return $valor;
    }

    /**
     *
     * @param \mysqli_result|FALSE $r
     */
    public static function fetch($r, $parametros = [])
    {
        $l = $r->fetch_assoc();
        if ($l) {
            foreach ($parametros as $k => $v) {
                $l[$k] = self::fromMySQL($l[$k], $v);
            }
            return $l;
        }
        return false;
    }

    public static function ft($texto)
    {
        $palavras = explode(' ', trim_all($texto));
        $palavras_modificadas = [];
        foreach ($palavras as $p) {
            if (!is_null($p) && $p != '') {
                $palavras_modificadas[] = '+' . $p . '*';
            }
        }
        return str_replace(['.', '-'], '', implode(' ', $palavras_modificadas));
    }

}

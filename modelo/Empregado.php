<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Empregado
{

    public $id_empregado;
    public $cpf;
    public $nome;
    public $turno;
    public $horario;
    public $dt_admissao;
    public $dt_desligamento;
    public $id_empresa;
    public $razao;
    public $id_cargo;
    public $nome_cargo;
    public $status_empregado;
    public $nome_categoria;
    public $qt_empregados;
    public $observacao;

    public static function buscaEmpregado($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT 
                    emp.id_empregado, 
                    emp.id_empresa, 
                    empr.razao,
                    emp.cpf, 
                    emp.nome, 
                    (CASE 
                        WHEN car.id_turno = 1 THEN "Diurno" 
                        WHEN car.id_turno = 2 THEN "Noturno" END
                    ) turno,                     
                    emp.horario,
                    DATE_FORMAT( emp.dt_admissao, "%d/%m/%Y" ) AS dt_admissao, 
                    DATE_FORMAT( emp.dt_desligamento, "%d/%m/%Y" ) AS dt_desligamento, 
                    emp.id_cargo, 
                    IF(emp.observacao IS NULL, "", emp.observacao) observacao,
                    car.nome_cargo, 
                    emp.status_empregado 
                    FROM empregados emp, cargos car, empresas empr
                    WHERE emp.id_cargo = car.id_cargo 
                    AND emp.status_empregado = "ATIVO"
                    AND emp.id_empresa = empr.id_empresa ';
        $sql .= ' AND emp.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['id_empresa']) {
            $sql .= ' AND emp.id_empresa =  ' . $dados['id_empresa'];
        }

        if ($dados['id_empregado']) {
            $sql .= ' AND emp.id_empregado =  ' . $dados['id_empregado'];
        }

        $sql .= ' ORDER BY emp.nome ';

        $empregado = new \stdClass();
        $empregados = new \stdClass();
        $empregados->erro = false;
        $empregados->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $empregado = new Empregado();
                $empregado->setId_empregado($row['id_empregado']);
                $empregado->setId_empresa($row['id_empresa']);
                $empregado->setRazao($row['razao']);
                $empregado->setCpf($row['cpf']);
                $empregado->setNome($row['nome']);
                $empregado->setId_cargo($row['id_cargo']);
                $empregado->setNome_cargo($row['nome_cargo']);
                $empregado->setTurno($row['turno']);
                $empregado->setDt_admissao($row['dt_admissao']);
                $empregado->setDt_desligamento($row['dt_desligamento']);
                $empregado->setStatus_empregado($row['status_empregado']);
                $empregado->setObservacao($row['observacao']);
                $empregados->dados[] = $empregado;
            }
        } else {
            $empregados->erro = true;
            $empregados->mensagem = 'Nenhum empregado cadastrado.';
        }

        return $empregados;
    }

    public static function gravaEmpregado($dados)
    {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE empregados SET ';
            $sql .= ' id_empresa = ' . $dados['id_empresa'] . ', ';
            $sql .= ' cpf = "' . $dados['cpf'] . '", ';
            $sql .= ' nome = "' . $dados['nome'] . '", ';
            $sql .= ' dt_admissao = "' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_admissao']))) . '", ';
            $sql .= ' observacao = "' . $dados['observacao'] . '", ';

            if ($dados['dt_desligamento'] !== '') {
                $sql .= ' dt_desligamento = "' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_desligamento']))) . '", ';
            }

            $sql .= ' id_cargo = ' . $dados['id_cargo'];
            $sql .= ' WHERE id_empregado = ' . $dados['id_empregado'];
        } else {
            /*
             * Verifica se o empregado já existe buscando pelo CPF
             */
            $temEmpregado = Captura::obtemEmpregado($dados['id_empresa'], null, $dados['cpf']);

            $sql = 'INSERT '
                . 'INTO empregados ('
                . 'id_empresa, '
                . 'cpf, '
                . 'nome, '
                . 'dt_admissao, '
                . 'status_empregado, '
                . 'id_cliente, '
                . 'observacao, '
                . 'id_cargo) '
                . ' VALUES ( '
                . $dados['id_empresa'] . ', '
                . '"' . $dados['cpf'] . '",'
                . '"' . $dados['nome'] . '",'
                . '"' . $dados['observacao'] . '",'
                . '"' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_admissao']))) . '",'
                . '"ATIVO", '
                . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                . $dados['id_cargo'] . ')';
        }

        $r = $conexao->query($sql);
        $empregados = new \stdClass();
        $empregados->erro = false;

        if ($dados['acao'] == 'alterar')
            $empregados->mensagem = 'Alteração efetuada com sucesso';
        else
            $empregados->mensagem = 'Inclusão efetuada com sucesso';

        return $empregados;
    }

    public static function excluiEmpregado($dados)
    {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE empregados SET '
            . 'status_empregado = "EXCLUIDO" '
            . 'WHERE id_empregado = ' . $dados['id_empregado'];

        $r = $conexao->query($sql);
        $empregados = new \stdClass();
        $empregados->erro = false;
        $empregados->mensagem = 'Empregado excluído com sucesso';

        return $empregados;
    }

    public static function capturaEmpregado($dados)
    {

        $registro = '';
        $empresa = 1;

        $conexao = MySQL::conexao();

        foreach ($dados as $reg) {
            $sql = 'INSERT '
                . 'INTO empregados ('
                . 'cpf, '
                . 'nome, '
                . 'id_empresa, '
                . 'id_cargo, '
                . 'status_empregado) '
                . ' VALUES ( '
                . '"' . $reg->cpf . '",'
                . '"' . $reg->nome . '",'
                . '"' . $reg->id_cargo . '",'
                . '"' . $empresa . '",'
                . '"ATIVO")';

            $r = $conexao->query($sql);
        }

        $empregados = new \stdClass();
        $empregados->erro = false;
        $empregados->mensagem = 'Empregados capturados com sucesso';

        return $empregados;
    }

    public static function listarEmpregados()
    {
        $conexao = MySQL::conexao();

        $sql = ' select count(*) as quantidade '
            . ' from empregados '
            . ' where status_empregado = "ATIVO"';

        //Só filtra por cliente se não for administrador
        if ($_SESSION["dados_usuario"]->getNivel_acesso() > '0')
            $sql .= ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        $empregados = new \stdClass();
        $resultado = $conexao->query($sql);
        $empregados->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $empregados->quantidade = $l['quantidade'];
        } else {
            $empregados->quantidade = 0;
        }

        return $empregados;
    }

    public static function listarEmpregadosEmpresa($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' select id_empresa, id_empregado, nome '
            . ' from empregados '
            . ' where status_empregado = "ATIVO"'
            . ' AND id_empresa = ' . $dados['id_empresa']
            . ' ORDER BY nome ';

        $empregados = new \stdClass();
        $resultado = $conexao->query($sql);
        $empregados->erro = false;

        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $empregado = new Empregado();
                $empregado->setId_empresa($row['id_empresa']);
                $empregado->setId_empregado($row['id_empregado']);
                $empregado->setNome($row['nome']);
                $empregados->dados[] = $empregado;
            }
        } else {
            $empregados->erro = true;
            $empregados->mensagem = 'Nenhum empregado encontrado.';
        }

        return $empregados;
    }

    public static function listarEmpregadosCategoria($id_empresa = null)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT cat.nome_categoria, count(cat.id_categoria) qt_empregados
                FROM empregados emp, clientes cli, categorias cat
                WHERE emp.id_cliente = cli.id_cliente
                AND cli.id_categoria = cat.id_categoria ';

        if ($id_empresa)
            $sql .= ' AND emp.id_empresa = ' . $id_empresa;

        $sql .= ' GROUP BY cat.id_categoria ';

        $empregados = new \stdClass();
        $resultado = $conexao->query($sql);
        $empregados->erro = false;

        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $empregado = new Empregado();
                $empregado->setNome_categoria($row['nome_categoria']);
                $empregado->setQt_empregados($row['qt_empregados']);
                $empregados->dados[] = $empregado;
            }
        } else {
            $empregados->erro = true;
            $empregados->mensagem = 'Nenhum empregado encontrado.';
        }

        return $empregados;
    }

    public static function quantidadeEmpregadosCategoria($dados)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT cat.nome_categoria, count(cat.id_categoria) quantidade
                FROM empregados emp, clientes cli, categorias cat
                WHERE emp.id_cliente = cli.id_cliente
                AND cli.id_categoria = cat.id_categoria
                AND emp.status_empregado = "ATIVO"';

        //Só filtra por cliente se não for administrador
        if ($_SESSION["dados_usuario"]->getNivel_acesso() > '0')
            $sql .= ' AND emp.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['id_empresa'])
            $sql .= ' AND emp.id_empresa = ' . $dados['id_empresa'];

        $empregados = new \stdClass();
        $resultado = $conexao->query($sql);
        $empregados->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $empregados->quantidade = $l['quantidade'];
        } else {
            $empregados->quantidade = 0;
        }

        return $empregados;
    }

    function getId_empregado()
    {
        return $this->id_empregado;
    }

    function getCpf()
    {
        return $this->cpf;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getTurno()
    {
        return $this->turno;
    }

    function getHorario()
    {
        return $this->horario;
    }

    function getDt_admissao()
    {
        return $this->dt_admissao;
    }

    function getDt_desligamento()
    {
        return $this->dt_desligamento;
    }

    function getId_empresa()
    {
        return $this->id_empresa;
    }

    function getRazao()
    {
        return $this->razao;
    }

    function getId_cargo()
    {
        return $this->id_cargo;
    }

    function getNome_cargo()
    {
        return $this->nome_cargo;
    }

    function getStatus_empregado()
    {
        return $this->status_empregado;
    }

    function getNome_categoria()
    {
        return $this->nome_categoria;
    }

    function getQt_empregados()
    {
        return $this->qt_empregados;
    }

    function setId_empregado($id_empregado)
    {
        $this->id_empregado = $id_empregado;
    }

    function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setTurno($turno)
    {
        $this->turno = $turno;
    }

    function setHorario($horario)
    {
        $this->horario = $horario;
    }

    function setDt_admissao($dt_admissao)
    {
        $this->dt_admissao = $dt_admissao;
    }

    function setDt_desligamento($dt_desligamento)
    {
        $this->dt_desligamento = $dt_desligamento;
    }

    function setId_empresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;
    }

    function setRazao($razao)
    {
        $this->razao = $razao;
    }

    function setId_cargo($id_cargo)
    {
        $this->id_cargo = $id_cargo;
    }

    function setNome_cargo($nome_cargo)
    {
        $this->nome_cargo = $nome_cargo;
    }

    function setStatus_empregado($status_empregado)
    {
        $this->status_empregado = $status_empregado;
    }

    function setNome_categoria($nome_categoria)
    {
        $this->nome_categoria = $nome_categoria;
    }

    function setQt_empregados($qt_empregados)
    {
        $this->qt_empregados = $qt_empregados;
    }


    /**
     * Get the value of observacao
     */ 
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Set the value of observacao
     *
     * @return  self
     */ 
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }
}

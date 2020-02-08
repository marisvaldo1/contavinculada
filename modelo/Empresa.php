<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Empresa
{

    public $id_empresa;
    public $cnpj;
    public $razao;
    public $endereco;
    public $cidade;
    public $estado;
    public $cep;
    public $telefone;
    public $nome_contato;
    public $telefone_contato;
    public $email;
    public $status_empresa;

    function getId_empresa()
    {
        return $this->id_empresa;
    }

    function getCnpj()
    {
        return $this->cnpj;
    }

    function getRazao()
    {
        return $this->razao;
    }

    function getEndereco()
    {
        return $this->endereco;
    }

    function getCidade()
    {
        return $this->cidade;
    }

    function getEstado()
    {
        return $this->estado;
    }

    function getCep()
    {
        return $this->cep;
    }

    function getTelefone()
    {
        return $this->telefone;
    }

    function getNome_contato()
    {
        return $this->nome_contato;
    }

    function getTelefone_contato()
    {
        return $this->telefone_contato;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getStatus_empresa()
    {
        return $this->status_empresa;
    }

    function setId_empresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;
    }

    function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }

    function setRazao($razao)
    {
        $this->razao = $razao;
    }

    function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    function setEstado($estado)
    {
        $this->estado = $estado;
    }

    function setCep($cep)
    {
        $this->cep = $cep;
    }

    function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    function setNome_contato($nome_contato)
    {
        $this->nome_contato = $nome_contato;
    }

    function setTelefone_contato($telefone_contato)
    {
        $this->telefone_contato = $telefone_contato;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setStatus_empresa($status_empresa)
    {
        $this->status_empresa = $status_empresa;
    }

    public static function buscaEmpresa($dados = null)    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
            . ' emp.id_empresa, '
            . ' emp.cnpj, '
            . ' emp.razao,'
            . ' emp.endereco,'
            . ' emp.cidade, '
            . ' emp.estado, '
            . ' emp.cep, '
            . ' emp.telefone, '
            . ' emp.email, '
            . ' emp.nome_contato, '
            . ' emp.telefone_contato, '
            . ' emp.status_empresa '
            . ' FROM clientes_empresas cli_emp, empresas emp '
            . ' WHERE emp.status_empresa = "ATIVO" '
            . ' AND cli_emp.id_empresa = emp.id_empresa ';

        if (isset($dados['id_cliente'])) {
            $sql .= ' AND cli_emp.id_cliente =  ' . $dados['id_cliente'];
        } else {
            $sql .= ' AND cli_emp.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();
        }

        if (isset($dados['id_empresa']) && $dados['id_empresa'] > "0") {
            $sql .= ' AND emp.id_empresa =  ' . $dados['id_empresa'];
        }

        $empresa = new \stdClass();
        $empresas = new \stdClass();
        $empresas->erro = false;
        $empresas->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $empresa = new Empresa();
                $empresa->setId_empresa($row['id_empresa']);
                $empresa->setCnpj($row['cnpj']);
                $empresa->setRazao($row['razao']);
                $empresa->setEndereco($row['endereco']);
                $empresa->setCidade($row['cidade']);
                $empresa->setEstado($row['estado']);
                $empresa->setCep($row['cep']);
                $empresa->setTelefone($row['telefone']);
                $empresa->setEmail($row['email']);
                $empresa->setNome_contato($row['nome_contato']);
                $empresa->setTelefone_contato($row['telefone_contato']);
                $empresa->setStatus_empresa($row['status_empresa']);
                $empresas->dados[] = $empresa;
            }
        } else {
            $empresas->erro = true;
            $empresas->mensagem = 'Nenhum empresa cadastrada.';
        }

        return $empresas;
    }

    public static function gravaEmpresa($dados)
    {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE empresas SET '
                . 'cnpj = "' . $dados['cnpj'] . '", '
                . 'razao = "' . $dados['razao'] . '", '
                . 'endereco = "' . $dados['endereco'] . '", '
                . 'cidade = "' . $dados['cidade'] . '", '
                . 'estado = "' . $dados['uf'] . '", '
                . 'cep = "' . $dados['cep'] . '", '
                . 'telefone = "' . $dados['telefone'] . '", '
                . 'nome_contato = "' . $dados['nome_contato'] . '", '
                . 'telefone_contato = "' . $dados['telefone_contato'] . '", '
                . 'email = "' . $dados['email'] . '" '
                . 'WHERE id_empresa = ' . $dados['id_empresa'];
        } else {
            $sql = 'INSERT '
                . 'INTO empresas ('
                . 'cnpj, '
                . 'razao, '
                . 'endereco, '
                . 'cidade, '
                . 'estado, '
                . 'cep, '
                . 'telefone, '
                . 'nome_contato, '
                . 'telefone_contato, '
                . 'email, '
                . 'status_empresa) '
                . ' VALUES ( '
                . '"' . $dados['cnpj'] . '",'
                . '"' . $dados['razao'] . '",'
                . '"' . $dados['endereco'] . '",'
                . '"' . $dados['cidade'] . '",'
                . '"' . $dados['estado'] . '",'
                . '"' . $dados['cep'] . '",'
                . '"' . $dados['telefone'] . '",'
                . '"' . $dados['nome_contato'] . '",'
                . '"' . $dados['telefone_contato'] . '",'
                . '"' . $dados['email'] . '",'
                . '"ATIVO")';
        }

        $r = $conexao->query($sql);
        $empresas = new \stdClass();
        $empresas->erro = false;

        //TODO: verificar se $id_empresa retorna o último inserido
        if ($dados['acao'] != 'alterar') {

            //Insere na tabela associativa
            $id_empresa = $conexao->mysqli()->insert_id;
            $sql = 'INSERT '
                . 'INTO clientes_empresas ('
                . 'id_cliente, '
                . 'id_empresa ) '
                . ' VALUES ( '
                . $_SESSION["dados_usuario"]->getCliente_usuario() . ','
                . $id_empresa . ')';

            $resultado = $conexao->query($sql);
        }

        if ($dados['acao'] == 'alterar')
            $empresas->mensagem = 'Alteração efetuada com sucesso';
        else
            $empresas->mensagem = 'Inclusão efetuada com sucesso';

        return $empresas;
    }

    public static function excluiEmpresa($id_empresa)
    {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE empresas SET '
            . 'status_empresa = "EXCLUIDO" '
            . 'WHERE id_empresa = ' . $id_empresa;

        $r = $conexao->query($sql);
        $empresas = new \stdClass();
        $empresas->erro = false;
        $empresas->mensagem = 'Empresa excluída com sucesso';

        return $empresas;
    }

    public static function listarEmpresas()    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT  count(*) quantidade
                FROM clientes_empresas cli_emp, empresas emp  
                WHERE emp.status_empresa = "ATIVO"  
                AND cli_emp.id_empresa = emp.id_empresa  
                AND cli_emp.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        // se usuário visitante, filtra somente a empresa onde ele pode visitar
        if ($_SESSION["dados_usuario"]->getId_empresa_visitante() === "4"){
            $sql .= ' AND emp.id_empresa = ' . $_SESSION["dados_usuario"]->getId_empresa_visitante();
        }

        $sql .= ' GROUP BY cli_emp.id_cliente';

        $empresas = new \stdClass();
        $resultado = $conexao->query($sql);
        $empresas->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $empresas->quantidade = $l['quantidade'];
        } else {
            $empresas->quantidade = 0;
        }

        return $empresas;
    }
}

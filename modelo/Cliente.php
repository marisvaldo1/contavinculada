<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Cliente {

    public $id_cliente;
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
    public $id_categoria;
    public $status_cliente;
    
    public $decimo_terceiro;
    public $ferias_abono;
    public $multa_fgts;

    function getId_cliente() {
        return $this->id_cliente;
    }

    function getCnpj() {
        return $this->cnpj;
    }

    function getRazao() {
        return $this->razao;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getEstado() {
        return $this->estado;
    }

    function getCep() {
        return $this->cep;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function getNome_contato() {
        return $this->nome_contato;
    }

    function getTelefone_contato() {
        return $this->telefone_contato;
    }

    function getEmail() {
        return $this->email;
    }

    function getId_categoria() {
        return $this->id_categoria;
    }

    function getStatus_cliente() {
        return $this->status_cliente;
    }

    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }

    function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    function setRazao($razao) {
        $this->razao = $razao;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function setNome_contato($nome_contato) {
        $this->nome_contato = $nome_contato;
    }

    function setTelefone_contato($telefone_contato) {
        $this->telefone_contato = $telefone_contato;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    function setStatus_cliente($status_cliente) {
        $this->status_cliente = $status_cliente;
    }

    public function getDecimo_terceiro()
    {
        return $this->decimo_terceiro;
    }

    public function setDecimo_terceiro($decimo_terceiro)
    {
        $this->decimo_terceiro = $decimo_terceiro;

        return $this;
    }

    public function getFerias_abono()
    {
        return $this->ferias_abono;
    }

    public function setFerias_abono($ferias_abono)
    {
        $this->ferias_abono = $ferias_abono;

        return $this;
    }

    public function getMulta_fgts()
    {
        return $this->multa_fgts;
    }

    public function setMulta_fgts($multa_fgts)
    {
        $this->multa_fgts = $multa_fgts;

        return $this;
    }

    public static function buscaCliente($id_cliente = null) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT 
                id_cliente, cnpj, razao, endereco, cidade, estado, cep, telefone, 
                nome_contato, telefone_contato, email, id_categoria, status_cliente,
                decimo_terceiro, ferias_abono, multa_fgts
                FROM clientes ';

        if ($id_cliente) {
            $sql = $sql . ' WHERE id_cliente =  ' . $id_cliente;
        }

        $cliente = new \stdClass();
        $clientes = new \stdClass();
        $clientes->erro = false;
        $clientes->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $cliente = new Cliente();
                $cliente->setId_cliente($row['id_cliente']);
                $cliente->setCnpj($row['cnpj']);
                $cliente->setRazao($row['razao']);
                $cliente->setEndereco($row['endereco']);
                $cliente->setCidade($row['cidade']);
                $cliente->setEstado($row['estado']);
                $cliente->setCep($row['cep']);
                $cliente->setTelefone($row['telefone']);
                $cliente->setNome_contato($row['nome_contato']);
                $cliente->setTelefone_contato($row['telefone_contato']);
                $cliente->setEmail($row['email']);
                $cliente->setId_categoria($row['id_categoria']);
                $cliente->setStatus_cliente($row['status_cliente']);
                $cliente->setDecimo_terceiro($row['decimo_terceiro']);
                $cliente->setFerias_abono($row['ferias_abono']);
                $cliente->setMulta_fgts($row['multa_fgts']);
                $clientes->dados[] = $cliente;
            }
        } else {
            $clientes->erro = true;
            $clientes->mensagem = 'Nenhum cliente cadastrado.';
        }

        return $clientes;
    }

    public static function gravaCliente($dados) {
        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE clientes SET '
                    . 'cnpj = "' . $dados['cnpj'] . '", '
                    . 'razao = "' . $dados['razao'] . '", '
                    . 'endereco = "' . $dados['endereco'] . '", '
                    . 'cidade = "' . $dados['cidade'] . '", '
                    . 'estado = "' . $dados['uf'] . '", '
                    . 'cep = "' . $dados['cep'] . '", '
                    . 'telefone = "' . $dados['telefone'] . '", '
                    . 'nome_contato = "' . $dados['nome_contato'] . '", '
                    . 'telefone_contato = "' . $dados['telefone_contato'] . '", '
                    . 'email = "' . $dados['email'] . '", '
                    . 'status_cliente = "' . $dados['status_cliente'] . '", '

                    . 'decimo_terceiro = ' . (float) str_replace(',', '.', $dados['decimo_terceiro']) . ', '
                    . 'ferias_abono = ' . (float) str_replace(',', '.', $dados['ferias_abono']) . ', '
                    . 'multa_fgts = ' . (float) str_replace(',', '.', $dados['multa_fgts']) . ', '

                    . 'id_categoria = ' . $dados['id_categoria']
                    . ' WHERE id_cliente = ' . $dados['id_cliente'];
        } else {
            $sql = 'INSERT '
                    . 'INTO clientes ('
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
                    . 'id_categoria, '
                    . 'status_cliente, '
                    . 'decimo_terceiro, '
                    . 'multa_fgts, '
                    . 'ferias_abono ) '
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
                    . $dados['id_categoria'] . ','
                    . '"ATIVO", '
                    . (float) str_replace(',', '.', $dados['decimo_terceiro']) . ', '
                    . (float) str_replace(',', '.', $dados['ferias_abono']) . ', '
                    . (float) str_replace(',', '.', $dados['multa_fgts']) . ' )';
        }

        $r = $conexao->query($sql);
        $clientes = new \stdClass();
        $clientes->erro = false;

        if ($dados['acao'] == 'alterar')
            $clientes->mensagem = 'Alteração efetuada com sucesso';
        else
            $clientes->mensagem = 'Inclusão efetuada com sucesso';

        return $clientes;
    }

    public static function excluiCliente($id_cliente) {
        $conexao = MySQL::conexao();

        $resultado = $conexao->query('DELETE FROM contratos_encargos WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM encargos WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM lancamentos WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM contratos_empregados WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM empregados WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM contratos WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM cargos WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM pagamentos WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM historico_captura WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM contrato_sistema WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM usuarios WHERE id_cliente = ' . $id_cliente);

        //Desativa a checagem de constraint
        $resultado = $conexao->query('SET foreign_key_checks = 0');
        $sql = 'DELETE FROM empresas
                WHERE id_empresa in 
                        ( SELECT id_empresa FROM clientes_empresas WHERE id_cliente = ' . $id_cliente . ')';
        $resultado = $conexao->query($sql);
        
        $resultado = $conexao->query('DELETE FROM clientes WHERE id_cliente = ' . $id_cliente);
        $resultado = $conexao->query('DELETE FROM clientes_empresas WHERE id_cliente = ' . $id_cliente);
        
        //Ative a checagem de constraint
        $resultado = $conexao->query('SET foreign_key_checks = 1');

        $resultado->erro = false;
        $resultado->mensagem = 'Cliente excluído com sucesso';

        return $resultado;
    }

    public static function listarClientes() {
        $conexao = MySQL::conexao();

        $sql = 'SELECT COUNT(*) AS quantidade
                FROM clientes 
                WHERE status_cliente = "ATIVO"';

        $clientes = new \stdClass();
        $resultado = $conexao->query($sql);
        $clientes->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $clientes->quantidade = $l['quantidade'];
        } else {
            $clientes->quantidade = 0;
        }

        return $clientes;
    }
}

<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Contrato {

    public $id_cliente;
    public $id_empresa;
    public $id_contrato;
    public $nu_contrato;
    public $id_empregado;
    public $dt_inicio;
    public $dt_final;
    public $valor;
    public $objeto_contrato;
    public $status_contrato;
    public $razao;
    public $cnpj;
    public $id_encargo;
    public $nome_encargo;
    public $percentual_encargo;
    public $cpf;
    public $nome;
    public $cargo;
    public $turno;

    function getId_cliente() {
        return $this->id_cliente;
    }

    function getId_empresa() {
        return $this->id_empresa;
    }

    function getId_contrato() {
        return $this->id_contrato;
    }

    function getNu_contrato() {
        return $this->nu_contrato;
    }

    function getId_empregado() {
        return $this->id_empregado;
    }

    function getDt_inicio() {
        return $this->dt_inicio;
    }

    function getDt_final() {
        return $this->dt_final;
    }

    function getValor() {
        return $this->valor;
    }

    function getObjeto_contrato() {
        return $this->objeto_contrato;
    }

    function getStatus_contrato() {
        return $this->status_contrato;
    }

    function getRazao() {
        return $this->razao;
    }

    function getCnpj() {
        return $this->cnpj;
    }

    function getId_encargo() {
        return $this->id_encargo;
    }

    function getNome_encargo() {
        return $this->nome_encargo;
    }

    function getPercentual_encargo() {
        return $this->percentual_encargo;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getNome() {
        return $this->nome;
    }

    function getCargo() {
        return $this->cargo;
    }

    function getTurno() {
        return $this->turno;
    }

    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }

    function setId_empresa($id_empresa) {
        $this->id_empresa = $id_empresa;
    }

    function setId_contrato($id_contrato) {
        $this->id_contrato = $id_contrato;
    }

    function setNu_contrato($nu_contrato) {
        $this->nu_contrato = $nu_contrato;
    }

    function setId_empregado($id_empregado) {
        $this->id_empregado = $id_empregado;
    }

    function setDt_inicio($dt_inicio) {
        $this->dt_inicio = $dt_inicio;
    }

    function setDt_final($dt_final) {
        $this->dt_final = $dt_final;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setObjeto_contrato($objeto_contrato) {
        $this->objeto_contrato = $objeto_contrato;
    }

    function setStatus_contrato($status_contrato) {
        $this->status_contrato = $status_contrato;
    }

    function setRazao($razao) {
        $this->razao = $razao;
    }

    function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    function setId_encargo($id_encargo) {
        $this->id_encargo = $id_encargo;
    }

    function setNome_encargo($nome_encargo) {
        $this->nome_encargo = $nome_encargo;
    }

    function setPercentual_encargo($percentual_encargo) {
        $this->percentual_encargo = $percentual_encargo;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    function setTurno($turno) {
        $this->turno = $turno;
    }

    public static function obtemEncargoContrato($id_empresa = null, $id_contrato = null, $id_encargo = null) {
        $conexao = MySQL::conexao();
        $encargo_retorno = [];

        $sql = ' SELECT id_encargo
                FROM contratos_encargos
                WHERE status_contrato_encargo = "ATIVO"
                AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                AND id_empresa = ' . $id_empresa . '
                AND id_contrato = ' . $id_contrato . '
                AND id_encargo = ' . $id_encargo;
        $encargo = $conexao->query($sql);
        return $encargo->num_rows;
    }

//    public static function obtemEmpregadoContrato($id_empresa = null, $id_contrato = null, $id_empregado = null) {
//        $conexao = MySQL::conexao();
//
//        $sql = ' SELECT id_empregado
//                FROM contratos_empregados
//                WHERE status_contrato_empregado = "ATIVO"
//                AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
//                AND id_empresa = ' . $id_empresa . '
//                AND id_contrato = ' . $id_contrato . '
//                AND id_empregado = ' . $id_empregado;
//        $empregado = $conexao->query($sql);
//        return $empregado->num_rows;
//    }

    public static function buscaContrato($id_contrato = null, $id_empresa = null) {
        $conexao = MySQL::conexao();

        $sql = ' SELECT 
                    con.id_cliente, con.id_empresa, 
                    con.id_contrato, con.nu_contrato, 
                    con.dt_inicio, con.dt_final,con.valor, 
                    con.objeto_contrato,con.status_contrato, emp.razao, emp.cnpj 
                    FROM 
                        contratos con, empresas emp 
                    WHERE con.status_contrato = "ATIVO" 
                        AND con.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                        AND con.id_empresa = emp.id_empresa';

        if ($id_contrato) {
            $sql = $sql . ' AND con.id_contrato =  ' . $id_contrato;
        }

        if ($id_empresa) {
            $sql = $sql . ' AND emp.id_empresa =  ' . $id_empresa;
        }

        $contrato = new \stdClass();
        $contratos = new \stdClass();
        $contratos->erro = false;
        $contratos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $contrato = new Contrato();
                $contrato->setId_empresa($row['id_empresa']);
                $contrato->setId_contrato($row['id_contrato']);
                $contrato->setNu_contrato($row['nu_contrato']);
                $contrato->setDt_inicio($row['dt_inicio']);
                $contrato->setDt_final($row['dt_final']);
                $contrato->setValor($row['valor']);
                $contrato->setObjeto_contrato($row['objeto_contrato']);
                $contrato->setStatus_contrato($row['status_contrato']);
                $contrato->setRazao($row['razao']);
                $contrato->setCnpj($row['cnpj']);
                $contratos->dados[] = $contrato;
            }
        } else {
            $contratos->erro = true;
            $contratos->mensagem = 'Nenhum contrato cadastrado.';
        }

        return $contratos;
    }

    public static function gravaContrato($dados) {

        $conexao = MySQL::conexao();

        /*
         * Dados do contrato   
         * nu_contrato náo poderá ser alterado
         */
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE contratos SET '
                    . ' dt_inicio = "' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_inicio']))) . '", ';

            if ($dados['dt_final'] !== '') {
                $sql .= ' dt_final  = "' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_final']))) . '", ';
            }


            $sql .= ' objeto_contrato = "' . $dados['objeto'] . '", '
                    . ' valor = "' . $dados['valor'] . '" '
                    . ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . ' AND id_empresa = ' . $dados['id_empresa']
                    . ' AND id_contrato = ' . $dados['id_contrato'];
            $r = $conexao->query($sql);
        } else {
            $sql = 'INSERT ';
            $sql .= 'INTO contratos ( ';
            $sql .= 'id_cliente, ';
            $sql .= 'id_empresa, ';
            $sql .= 'nu_contrato, ';
            $sql .= 'dt_inicio, ';

            if ($dados['dt_final'] !== '') {
                $sql .= 'dt_final, ';
            }

            $sql .= 'valor, ';
            $sql .= 'objeto_contrato, ';
            $sql .= 'status_contrato ) ';
            $sql .= ' VALUES ( ';
            $sql .= $_SESSION["dados_usuario"]->getCliente_usuario() . ", ";
            $sql .= $dados['id_empresa'] . ', ';
            $sql .= '"' . $dados['nu_contrato'] . '", ';

            if (substr($dados['dt_inicio'], 0, 4) != '0000' && $dados['dt_inicio'] !== '') {
                $sql .= '"' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_inicio']))) . '", ';
            }

            if ($dados['dt_final'] !== '') {
                $sql .= '"' . date("Y-m-d", strtotime(str_replace('/', '-', $dados['dt_final']))) . '", ';
            }

            $sql .= $dados['valor'] . ', ';
            $sql .= '"' . $dados['objeto'] . '", ';
            $sql .= '"ATIVO")';
            $r = $conexao->query($sql);
            $id_contrato_iserido = $conexao->id();
        }

        $contratos = new \stdClass();
        $contratos->erro = false;

        /*
         * Atualisa os dados dos encargos no contrato
         * ******************************************* */
        try {

            /*
             * Obtem os novos encargos que foram colocados no datatable
             */
            $encargos_selecionados = '';

            foreach ($dados['encargos'] as $i => $value) {
                $encargos_selecionados .= $value['id'] . ', ';
            }
            $encargos_selecionados = substr($encargos_selecionados, 0, -2);

            /*
             * Excluir logicamente todos os encargos do contrato
             */
            if ($dados['acao'] == 'alterar') {
                $sql = 'UPDATE contratos_encargos SET 
                        status_contrato_encargo = "INATIVO"
                        WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                        AND id_empresa = ' . $dados['id_empresa'] . '
                        AND id_contrato = ' . $dados['id_contrato'];
                $r = $conexao->query($sql);

                /*
                 * Inclui todos os encargos que estão no datatable
                 */
                $sql = 'UPDATE contratos_encargos SET 
                    status_contrato_encargo = "ATIVO"
                    WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                    AND id_empresa = ' . $dados['id_empresa'] . '
                    AND id_contrato = ' . $dados['id_contrato'] . '
                    AND id_encargo IN (' . $encargos_selecionados . ')';
                $r = $conexao->query($sql);
            }

            /*
             * Insere novo encargos caso nunca tenham sido inseridos no contrato
             */
            //TODO: não inseriu um novo encargo depois de inserir um novo contrato
            foreach ($dados['encargos'] as $i => $value) {
                if ($dados['acao'] === 'alterar') {

                    //TODO:
                    //Inserir o encargo caso seja um novo contrato
                    //Usar a mesma funcionalidade para os empregados do contrato
                    //Já existe o encargo no contrato
                    if (Contrato::obtemEncargoContrato($dados['id_empresa'], $dados['id_contrato'], $value['id']) > 0) {
                        $sql = 'UPDATE contratos_encargos SET 
                                status_contrato_encargo = "ATIVO", ' . '
                                percentual_encargo = ' . $value['valor'] . '
                                WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                                AND id_empresa = ' . $dados['id_empresa'] . '
                                AND id_contrato = ' . $dados['id_contrato'] . '
                                AND id_encargo = ' . $value['id'];
                        //$r = $conexao->query($sql);
                    } else {
                        $sql = 'INSERT 
                                INTO contratos_encargos (
                                id_cliente, 
                                id_empresa,
                                id_contrato,
                                id_encargo,
                                percentual_encargo,
                                status_contrato_encargo )
                                VALUES ( 
				' . $_SESSION["dados_usuario"]->getCliente_usuario() . ', 
				' . $dados['id_empresa'] . ', 
				' . $dados['id_contrato'] . ', 
				' . $value['id'] . ', 
				' . $value['valor'] . ',
				"ATIVO")';
                    }
                    //$r = $conexao->query($sql);
                } else { //Novo contrato
                    $sql = 'INSERT 
                                INTO contratos_encargos (
                                id_cliente, 
                                id_empresa,
                                id_contrato,
                                id_encargo,
                                percentual_encargo,
                                status_contrato_encargo )
                                VALUES ( 
				' . $_SESSION["dados_usuario"]->getCliente_usuario() . ', 
				' . $dados['id_empresa'] . ', 
				' . $id_contrato_iserido . ', 
				' . $value['id'] . ', 
				' . $value['valor'] . ',
				"ATIVO")';
                    //$r = $conexao->query($sql);
                }
                try {
                    $r = $conexao->query($sql);
                } catch (Exception $ex) {
                    $contratos->erro = true;
                    $contratos->mensagem = 'Problemas na inclusão do contrato.';
                }
            }
        } catch (Exception $ex) {
            $contratos->erro = true;
            $contratos->mensagem = 'Problemas na crição do novo contrato.';
        }

        return $contratos;
    }

    //**********************************************
    public static function excluiContrato($id_contrato) {
        $conexao = MySQL::conexao();
        try {
            $sql = 'UPDATE contratos SET '
                    . 'status_contrato = "EXCLUIDO" '
                    . ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . ' AND id_contrato = ' . $id_contrato;
            $r = $conexao->query($sql);

            $contratos = new \stdClass();
            $contratos->erro = false;
            $contratos->mensagem = 'Contrato excluÃ­do com sucesso';
        } catch (Exception $ex) {
            $contratos->erro = true;
            $contratos->mensagem = 'não excluiu os encargos do contrato.';
        }

        return $contratos;
    }

    public static function listarContratos($dados) {
        $conexao = MySQL::conexao();

        try {
            if ($_SESSION["dados_usuario"]->getNivel_acesso() > 0) {
                $sql = ' SELECT COUNT(*) AS quantidade '
                        . ' FROM contratos '
                        . ' WHERE status_contrato = "ATIVO" '
                        . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

                if ($dados['id_empresa']) {
                    $sql .= ' AND id_empresa = ' . $dados['id_empresa'];
                }
            } else {
                $sql = 'SELECT COUNT(*) quantidade 
                        FROM contrato_sistema
                        WHERE status_contrato_sistema = "ATIVO" ';
            }

            $contratos = new \stdClass();

            $resultado = $conexao->query($sql);
            $contratos->erro = false;

            if ($resultado->num_rows) {
                $l = $conexao->fetch($resultado);
                $contratos->quantidade = $l['quantidade'];
            } else {
                $contratos->quantidade = 0;
            }
        } catch (Exception $ex) {
            $contratos->erro = true;
            $contratos->mensagem = 'Problemas com os contratos. Verifique com o administrador.';
        }

        return $contratos;
    }

    public static function buscarNovoNumeroContrato() {
        $conexao = MySQL::conexao();

        try {
            $sql = "SELECT `AUTO_INCREMENT` novoContrato FROM INFORMATION_SCHEMA.TABLES "
                    . " WHERE TABLE_SCHEMA = 'contavinculada' AND TABLE_NAME = 'contratos'";

            $novoContrato = new \stdClass();
            $resultado = $conexao->query($sql);
            $novoContrato->erro = false;

            if ($resultado->num_rows) {
                $l = $conexao->fetch($resultado);
                $novoContrato->novoContrato = $l['novoContrato'];
            } else {
                $novoContrato->novoContrato = 0;
            }
        } catch (Exception $ex) {
            $novoContrato->erro = true;
            $novoContrato->mensagem = 'Problemas no contrato. Verifique com o administrador.';
        }

        return $novoContrato;
    }

    public static function listarEncargosContrato($dados) {
        $conexao = MySQL::conexao();

        try {
            $sql = "SELECT  "
                    . " coi.id_encargo,  "
                    . " enc.nome_encargo, "
                    . " coi.percentual_encargo  "
                    . " FROM contratos_encargos coi, encargos enc  "
                    . " WHERE coi.id_cliente = " . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . " AND coi.id_empresa = " . $dados['id_empresa']
                    . " AND coi.id_contrato = " . $dados['id_contrato']
                    . " AND coi.id_cliente = enc.id_cliente "
                    . " AND coi.id_encargo = enc.id_encargo "
                    . " AND coi.status_contrato_encargo != 'INATIVO' ";
            $resultado = $conexao->query($sql);

            $encargo = new \stdClass();
            $encargosContrato = new \stdClass();
            $encargosContrato->erro = false;
            $encargosContrato->dados = [];

            if ($resultado->num_rows) {
                while ($row = $conexao->fetch($resultado)) {
                    $encargo = new Contrato();
                    $encargo->setId_encargo($row['id_encargo']);
                    $encargo->setNome_encargo($row['nome_encargo']);
                    $encargo->setPercentual_encargo($row['percentual_encargo']);
                    $encargosContrato->dados[] = $encargo;
                }
            } else {
                $encargosContrato->erro = false;
                $encargosContrato->mensagem = 'Nenhum contrato cadastrado.';
            }
        } catch (Exception $ex) {
            $encargosContrato->erro = true;
            $encargosContrato->mensagem = 'Problemas com os encargos do contrato. Verifique com o administrador';
        }

        return $encargosContrato;
    }

    public static function listarEncargosNovoContrato() {
        $conexao = MySQL::conexao();

        try {
            $sql = "SELECT  "
                    . " coi.id_encargo,  "
                    . " enc.nome_encargo, "
                    . " coi.percentual_encargo  "
                    . " FROM contratos_encargos coi, encargos enc  "
                    . " WHERE coi.id_cliente = " . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . " AND enc.insere_automarico_contrato = 1"
                    . " AND coi.id_cliente = enc.id_cliente "
                    . " AND coi.id_encargo = enc.id_encargo ";
            $resultado = $conexao->query($sql);

            $encargo = new \stdClass();
            $encargosContrato = new \stdClass();
            $encargosContrato->erro = false;
            $encargosContrato->dados = [];

            if ($resultado->num_rows) {
                while ($row = $conexao->fetch($resultado)) {
                    $encargo = new Contrato();
                    $encargo->setId_encargo($row['id_encargo']);
                    $encargo->setNome_encargo($row['nome_encargo']);
                    $encargo->setPercentual_encargo($row['percentual_encargo']);
                    $encargosContrato->dados[] = $encargo;
                }
            } else {
                $encargosContrato->erro = false;
                $encargosContrato->mensagem = 'Nenhum contrato cadastrado.';
            }
        } catch (Exception $ex) {
            $encargosContrato->erro = true;
            $encargosContrato->mensagem = 'Problemas com os encargos do contrato. Verifique com o administrador';
        }

        return $encargosContrato;
    }

    public static function listarEmpregadosContrato($dados) {
        $conexao = MySQL::conexao();

        try {
            $sql = " SELECT "
                    . " coe.id_cliente, "
                    . " coe.id_empresa, "
                    . " coe.id_contrato, "
                    . " coe.id_empregado, "
                    . " emp.nome, "
                    . " emp.cpf, "
                    . " car.nome_cargo cargo, "
                    . " (CASE "
                    . "     WHEN car.id_turno = 1 THEN 'Diurno' "
                    . "     WHEN car.id_turno = 2 THEN 'Noturno' END "
                    . " ) turno   "
                    . " FROM contratos_empregados coe, empregados emp, cargos car "
                    . " WHERE coe.id_cliente = " . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . " AND coe.id_empresa = " . $dados['id_empresa']
                    . " AND coe.id_contrato = " . $dados['id_contrato']
                    . " AND coe.id_empregado = emp.id_empregado "
                    . " AND emp.id_cargo = car.id_cargo "
                    . " AND coe.status_contrato_empregado != 'INATIVO' ";
            $resultado = $conexao->query($sql);

            $empregado = new \stdClass();
            $empregadosContrato = new \stdClass();
            $empregadosContrato->erro = false;
            $empregadosContrato->dados = [];

            if ($resultado->num_rows) {
                while ($row = $conexao->fetch($resultado)) {
                    $empregado = new Contrato();
                    $empregado->setId_cliente($row['id_cliente']);
                    $empregado->setId_empresa($row['id_empresa']);
                    $empregado->setId_contrato($row['id_contrato']);
                    $empregado->setId_empregado($row['id_empregado']);
                    $empregado->setNome($row['nome']);
                    $empregado->setCpf($row['cpf']);
                    $empregado->setCargo($row['cargo']);
                    $empregado->setTurno($row['turno']);
                    $empregadosContrato->dados[] = $empregado;
                }
            } else {
                $empregadosContrato->erro = false;
                $empregadosContrato->mensagem = 'Nenhum empregado cadastrado.';
            }
        } catch (Exception $ex) {
            $empregadosContrato->erro = true;
            $empregadosContrato->mensagem = 'Problemas com os empregados do contrato. Verifique com o administrador';
        }

        return $empregadosContrato;
    }

    public static function listarContratosEmpresa($dados) {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
                . 'id_cliente, '
                . 'id_empresa, '
                . 'id_contrato, '
                . 'nu_contrato, '
                . 'objeto_contrato '
                . 'FROM contratos '
                . 'WHERE status_contrato = "ATIVO" '
                . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
                . ' AND id_empresa = ' . $dados['id_empresa'];

        $contrato = new \stdClass();
        $contratos = new \stdClass();
        $contratos->erro = false;
        $contratos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $contrato = new Contrato();
                $contrato->setId_empresa($row['id_empresa']);
                $contrato->setId_contrato($row['id_contrato']);
                $contrato->setNu_contrato($row['nu_contrato']);
                $contrato->setObjeto_contrato($row['objeto_contrato']);
                $contratos->dados[] = $contrato;
            }
        } else {
            $contratos->erro = true;
            $contratos->mensagem = 'Nenhum contrato encontrado.';
        }

        return $contratos;
    }

    /*
     * Verifica se o contrato já possui algum lançamento.
     * Se sim não permite que os encargos e os empregados alocados neste
     * contrato sejam alterados.
     */

    public static function listarLancamentosContrato($dados) {
        $conexao = MySQL::conexao();

        try {

            $sql = 'SELECT COUNT(*) quantidade
                    FROM lancamentos
                    WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();
            $sql .= ' AND id_empresa = ' . $dados['id_empresa'];
            $sql .= ' AND id_contrato = ' . $dados['id_contrato'];
            $contratos = new \stdClass();

            $resultado = $conexao->query($sql);
            $contratos->erro = false;
            $contratos->quantidade = 0;

            if ($resultado->num_rows) {
                $l = $conexao->fetch($resultado);
                $contratos->quantidade = $l['quantidade'];
            }
        } catch (Exception $ex) {
            $contratos->erro = true;
            $contratos->mensagem = 'Problemas com os contratos. Verifique com o administrador.';
        }

        return $contratos;
    }

}

<?php

namespace modelo;

use bd\MySQL;

class Captura
{

    public $id_empregado;
    public $cpf;
    public $nome;
    public $horario;
    public $remuneracao;
    public $dt_admissao;
    public $dt_desligamento;
    public $id_empresa;
    public $id_cargo;
    public $id_turno;
    public $nome_cargo;
    public $remuneracao_cargo;
    public $status_empregado;
    public $id_encargo;
    public $nome_encargo;
    public $percentual_encargo;
    public $resumo = [];
    public $codigo;
    public $observacao_retencao;
    public $decimo_terceiro;
    public $ferias_abono;
    public $multa_fgts; 

    public static function obtemCargo($nome_cargo = null, $id_cargo = null, $id_turno = null)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT id_cargo, nome_cargo, remuneracao_cargo, id_turno '
            . ' FROM cargos '
            . ' WHERE status_cargo = "ATIVO" '
            . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($nome_cargo != null) {
            $sql .= ' AND nome_cargo = "' . $nome_cargo . '"';
        }

        if ($id_turno != null) {
            $sql .= ' AND id_turno = "' . $id_turno . '"';
        }

        if ($id_cargo != null) {
            $sql .= ' AND id_cargo = ' . $id_cargo;
        }

        $cargo_retorno = new Captura();
        $cargo_retorno->erro = false;
        $cargo_retorno->dados = [];

        $cargo = $conexao->query($sql);
        if ($cargo->num_rows) {
            $l = $conexao->fetch($cargo);
            $cargo_retorno->setId_cargo($l['id_cargo']);
            $cargo_retorno->setNome_cargo($l['nome_cargo']);
            $cargo_retorno->setId_turno($l['id_turno']);
            $cargo_retorno->setRemuneracao_cargo($l['remuneracao_cargo']);
        } else {
            $cargo_retorno->erro = true;
            $cargo_retorno->mensagem = 'Cargo ou Turno não encontrado.';
        }
        return $cargo_retorno;
    }

    public static function obtemEncargo($nome_encargo = null, $id_encargo = null)
    {
        $conexao = MySQL::conexao();
        $encargo_retorno = [];

        $sql = ' SELECT id_encargo, nome_encargo, percentual_encargo '
            . ' FROM encargos '
            . ' WHERE status_encargo = "ATIVO" '
            . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($nome_encargo != null) {
            $sql .= ' AND nome_encargo = "' . $nome_encargo . '"';
        }
        if ($id_encargo != null) {
            $sql .= ' AND id_encargo = ' . $id_encargo;
        }

        $encargo_retorno = new Captura();
        $encargo_retorno->erro = false;
        $encargo_retorno->dados = [];

        $encargo = $conexao->query($sql);
        if ($encargo->num_rows) {
            $l = $conexao->fetch($encargo);
            $encargo_retorno->setId_encargo($l['id_encargo']);
            $encargo_retorno->setNome_encargo($l['nome_encargo']);
            $encargo_retorno->setPercentual_encargo($l['percentual_encargo']);
        } else {
            $encargo_retorno->erro = true;
            $encargo_retorno->mensagem = 'Encargo não encontrado.';
        }
        return $encargo_retorno;
    }

    /*
     * Obtem a somatória dos encargos que estão inseridos no contrato
     */
    public static function obtemSomatoriaEncargos($id_empresa, $id_contrato)
    {
        $conexao = MySQL::conexao();
        $somatoria_encargo = [];

        try {
            $sql = ' SELECT SUM(percentual_encargo) somatoria_encargo
                 FROM contratos_encargos 
                 WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                 AND id_empresa = ' . $id_empresa . '
                 AND id_contrato = ' . $id_contrato . '
                 AND status_contrato_encargo = "ATIVO" ';
            $somatoria_encargo = new \stdClass();

            $resultado = $conexao->query($sql);
            $somatoria_encargo->erro = false;

            if ($resultado->num_rows) {
                $l = $conexao->fetch($resultado);
                $somatoria_encargo->somatoria_encargo = $l['somatoria_encargo'];
            } else {
                $somatoria_encargo->somatoria_encargo = 0;
            }
        } catch (\Exception $ex) {
            $somatoria_encargo->erro = true;
            $somatoria_encargo->mensagem = 'Problemas na somatória dos encargos. Verifique com o administrador.';
        }

        return $somatoria_encargo;
    }

    public static function obtemEmpregado($empresa = null, $nome_empregado = null, $cpf = null, $id_cargo = null)
    {
        $conexao = MySQL::conexao();
        $sql = ' SELECT cpf, nome, id_empregado '
            . ' FROM empregados '
            . ' WHERE status_empregado = "ATIVO" '
            . ' AND id_empresa = ' . $empresa
            . ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($nome_empregado != null) {
            $sql .= ' AND nome = "' . $nome_empregado . '"';
        }
        if ($cpf != null) {
            $sql .= ' AND cpf = "' . $cpf . '"';
        }
        if ($id_cargo != null) {
            $sql .= ' AND id_cargo = "' . $id_cargo . '"';
        }

        $empregado_retorno = new Captura();
        $empregado_retorno->erro = false;
        $empregado_retorno->dados = [];

        $empregado = $conexao->query($sql);
        if ($empregado->num_rows) {
            $l = $conexao->fetch($empregado);
            $empregado_retorno->setId_empregado($l['id_empregado']);
            $empregado_retorno->setNome($l['nome']);
        } else {
            $empregado_retorno->erro = true;
            $empregado_retorno->mensagem = 'Empregado não encontrado.';
        }
        return $empregado_retorno;
    }

    public static function obtemIndices()
    {
        $conexao = MySQL::conexao();
        $sql = ' SELECT decimo_terceiro, ferias_abono, multa_fgts '
            . ' FROM clientes '
            . ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        $indice_retorno = new Captura();
        $indice_retorno->erro = false;
        $indice_retorno->dados = [];

        $indice = $conexao->query($sql);
        if ($indice->num_rows) {
            $l = $conexao->fetch($indice);
            $indice_retorno->setDecimo_terceiro( $l['decimo_terceiro'] > 0 ? $l['decimo_terceiro'] : 8.33);
            $indice_retorno->setFerias_abono($l['ferias_abono'] > 0 ? $l['ferias_abono'] : 11.11);
            $indice_retorno->setMulta_fgts($l['multa_fgts'] > 0 ? $l['multa_fgts'] : 4.00);
        } else {
            $indice_retorno->setDecimo_terceiro(8.33);
            $indice_retorno->setFerias_abono(11.11);
            $indice_retorno->setMulta_fgts(4.00);
        }
        return $indice_retorno;
    }

    public static function erroPlanilha($dados)
    {
        $conexao = MySQL::conexao();

        //Valida CPF
        if (!validaCPF($dados->cpf)) {
            $dadosCaptura = [
                'empresa' => $dados->empresa,
                'contrato' => $dados->contrato,
                'historico' => 'CPF: ' . $dados->cpf,
                'mes' => $dados->mesLancamento,
                'ano' => $dados->anoLancamento,
                'observacao_retencao' => $dados->observacao_retencao,
                'status_captura' => 'CPF Inválido'
            ];

            Captura::insereHistoricoCaptura($dadosCaptura);
            return true;
        }

        /*
         * Se o turno for vazio
         */
        if ($dados->id_turno !== 1 && $dados->id_turno !== 2) {
            $dadosCaptura = [
                'empresa' => $dados->empresa,
                'contrato' => $dados->contrato,
                'historico' => 'Cargo: ' . $dados->nome_cargo . ' Turno: ' . $dados->id_turno,
                'mes' => $dados->mesLancamento,
                'ano' => $dados->anoLancamento,
                'observacao_retencao' => $dados->observacao_retencao,
                'status_captura' => 'Turno inválido'
            ];

            Captura::insereHistoricoCaptura($dadosCaptura);
            return true;
        }

        /*
         * Se o cargo não existir ou for vazio
         */
        $temCargo = Captura::obtemCargo($dados->nome_cargo, null, $dados->id_turno);
        if ($temCargo->erro || $dados->nome_cargo == '') {
            $dadosCaptura = [
                'empresa' => $dados->empresa,
                'contrato' => $dados->contrato,
                'historico' => 'Cargo: ' . $dados->nome_cargo . ' Turno: ' . $dados->id_turno,
                'mes' => $dados->mesLancamento,
                'ano' => $dados->anoLancamento,
                'observacao_retencao' => $dados->observacao_retencao,
                'status_captura' => 'Cargo inexistente'
            ];

            Captura::insereHistoricoCaptura($dadosCaptura);
            return true;
        }

        /*
         * Se a quantidade de dias trabalhados 
         * for vazia, igual a 0 ou maior que 30
         */
        // A pedido do Marcelo, em 05/02/2020, retirada a limitação da quantidade de dias trabalhados maior que 30 
        // if ($dados->dias_trabalhados == '' || $dados->dias_trabalhados < 0 || $dados->dias_trabalhados > 30) {
        if ($dados->dias_trabalhados == '' || $dados->dias_trabalhados < 0) {
            $dadosCaptura = [
                'empresa' => $dados->empresa,
                'contrato' => $dados->contrato,
                'historico' => 'Dias Trabalhados: ' . $dados->dias_trabalhados,
                'mes' => $dados->mesLancamento,
                'ano' => $dados->anoLancamento,
                'observacao_retencao' => $dados->observacao_retencao,
                'status_captura' => 'Quantidade de dias Trabalhados inválida'
            ];

            Captura::insereHistoricoCaptura($dadosCaptura);
            return true;
        }

        return false;
    }

    public static function capturaLancamento($dados)
    {
        $conexao = MySQL::conexao();

        /* Obtem o código do histórico da captura que será 
         * utilizado em todas as inserções para possivel exclusão das capturas
         */
        $sql = "SHOW TABLE STATUS LIKE 'historico_captura'";
        $resultado = $conexao->query($sql);
        $l = $conexao->fetch($resultado);
        $codigo_captura = $l['Auto_increment'];

        //Verifica se existe o cargo na tabela de cargos
        $temCargo = Captura::obtemCargo($dados->nome_cargo, null, $dados->id_turno);

        //Verifica pelo CPF se o empregado existe na tabela de empregados
        //$temEmpregado = Captura::obtemEmpregado($dados->empresa, null, $dados->cpf, $temCargo->id_cargo);
        $temEmpregado = Captura::obtemEmpregado($dados->empresa, null, $dados->cpf);

        //Se o usuário não existir, cadastra na tabela de empregados
        if ($temEmpregado->erro) {
            try {
                $sql = 'INSERT '
                    . 'INTO empregados ('
                    . 'id_empresa, '
                    . 'cpf, '
                    . 'nome, '
                    . 'status_empregado, '
                    . 'id_cliente, '
                    . 'id_cargo, '
                    . 'id_captura) '
                    . ' VALUES ( '
                    . $dados->empresa . ', '
                    . '"' . $dados->cpf . '",'
                    . '"' . $dados->nome . '",'
                    . '"ATIVO", '
                    . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                    . $temCargo->id_cargo . ', '
                    . $codigo_captura . ')';

                $empregado = $conexao->query($sql);
                $temEmpregado->setId_empregado($conexao->id());

                $mensagem = 'CPF: ' . $dados->cpf
                    . '|Nome: ' . $dados->nome;
                $status_captura = 'Empregado Cadastrado';
            } catch (\Exception $ex) {
                //$erro = true;
                $mensagem = 'Erro na captura do Empregado';
                $status_captura = 'Empregado: ' . $dados->nome . ' não Cadastrado';
            }

            $dadosCaptura = [
                'empresa' => $dados->empresa,
                'contrato' => $dados->contrato,
                'historico' => $mensagem,
                'mes' => $dados->mesLancamento,
                'ano' => $dados->anoLancamento,
                'observacao_retencao' => $dados->observacao_retencao,
                'status_captura' => $status_captura
            ];

            //Captura::insereHistoricoCaptura($dadosCaptura);
        } else {
            try {
                $sql = 'UPDATE empregados SET '
                    . ' id_cargo = ' . $temCargo->id_cargo . ', '
                    . ' id_captura = ' . $codigo_captura
                    . ' WHERE  '
                    . ' id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . ' AND id_empresa = ' . $dados->empresa
                    . ' AND id_empregado = ' . $temEmpregado->getId_empregado();
                $empregado = $conexao->query($sql);
                //$temEmpregado->setId_empregado($conexao->id());

                $mensagem = 'CPF: ' . $dados->cpf
                    . '|Nome: ' . $dados->nome;
                $status_captura = 'Turno do Empregado Atualizado';
            } catch (\Exception $ex) {
                //$erro = true;
                $mensagem = 'Erro na captura do Empregado';
                $status_captura = 'Empregado: ' . $dados->nome . ' não Cadastrado';
            }
        }

        //Verifica se exite o empregado no contrato
        $sql = ' SELECT *  '
            . ' FROM contratos_empregados '
            . ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
            . ' AND id_empresa = ' . $dados->empresa
            . ' AND id_contrato = ' . $dados->contrato
            . ' AND id_empregado = ' . $temEmpregado->id_empregado;
        $empregado_contrato = $conexao->query($sql);

        //Se não existe, insere o empregado no contrato
        if (!$empregado_contrato->num_rows) {
            try {
                $sql = ' INSERT INTO contratos_empregados ( '
                    . ' id_cliente, '
                    . ' id_empresa, '
                    . ' id_contrato, '
                    . ' id_empregado, '
                    . ' status_contrato_empregado, '
                    . ' id_captura )'
                    . ' VALUES ( '
                    . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                    . $dados->empresa . ', '
                    . $dados->contrato . ', '
                    . $temEmpregado->id_empregado . ', '
                    . ' "ATIVO", '
                    . $codigo_captura . ' )';
                $empregado_contrato = $conexao->query($sql);

                $mensagem = 'CPF: ' . $dados->cpf
                    . '|Nome: ' . $dados->nome;
                $status_captura = 'Empregado Inserido no contrato';
            } catch (\Exception $ex) {
                $mensagem = 'Erro na inserção dos dados do empregado';
                $status_captura = 'Empregado: ' . $dados->nome . ' não inserido no contrato';
            }

            $dadosCaptura = [
                'empresa' => $dados->empresa,
                'contrato' => $dados->contrato,
                'historico' => $mensagem,
                'mes' => $dados->mesLancamento,
                'ano' => $dados->anoLancamento,
                'observacao_retencao' => $dados->observacao_retencao,
                'status_captura' => $status_captura
            ];

            //Captura::insereHistoricoCaptura($dadosCaptura);
        }

        //Verifica exite o lançamento
        $sql = ' SELECT '
            . ' id_cliente, '
            . ' id_empresa, '
            . ' id_contrato, '
            . ' id_empregado, '
            . ' ano, '
            . ' mes, '
            . ' remuneracao, '
            . ' observacao_retencao, '
            . ' observacao_liberacao '
            . ' FROM lancamentos '
            . ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
            . ' AND id_empresa = ' . $dados->empresa
            . ' AND id_contrato = ' . $dados->contrato
            . ' AND id_empregado = ' . $temEmpregado->id_empregado
            . ' AND ano = ' . $dados->anoLancamento
            . ' AND mes = ' . $dados->mesLancamento;
        $lancamento = $conexao->query($sql);

        //Se o lançamento existir, exclui para inserir de novo e evitar duplicidade
        if ($lancamento->num_rows) {
            try {
                $sql = ' DELETE  '
                    . ' FROM lancamentos '
                    . ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . ' AND id_empresa = ' . $dados->empresa
                    . ' AND id_contrato = ' . $dados->contrato
                    . ' AND id_empregado = ' . $temEmpregado->id_empregado
                    . ' AND ano = ' . $dados->anoLancamento
                    . ' AND mes = ' . $dados->mesLancamento;
                //$lancamento = $conexao->query($sql);

                $mensagem = 'Cliente: ' . $_SESSION["dados_usuario"]->getCliente_usuario()
                    . '|Empresa: ' . $dados->empresa
                    . '|Contrato: ' . $dados->contrato
                    . '|Empregado: ' . $dados->nome;
                $status_captura = 'Lançamento Excluído';
            } catch (\Exception $ex) {
                $mensagem = 'Erro na exclusão do lançamento';
                $status_captura = 'Empregado: ' . $dados->nome . ' não Excluido';
            }
        }

        //Insere o novo lançamento
        try {

            //Busca o ídice do cliente. Caso não tenha indice cadastrado, usa os indices padões
            $indices = Captura::obtemIndices();

            $indice13 = $indices->decimo_terceiro;
            $indiceferiasAbono = $indices->ferias_abono;
            $indiceMultaFGTS = $indices->multa_fgts;

            // Obtem a somatória dos encargos sobre o resultado dos cálculos
            $somatorioEncargos = Captura::obtemSomatoriaEncargos($dados->empresa, $dados->contrato);

            //$remuneracao = (($dados->remuneracao_cargo) / 30) * $dados->dias_trabalhados;
            $remuneracao = (($temCargo->remuneracao_cargo) / 30) * $dados->dias_trabalhados;
            $decimo_terceiro = ($remuneracao * $indice13) / 100;
            $ferias_abono = ($remuneracao * $indiceferiasAbono) / 100;
            $multa_FGTS = ($remuneracao * $indiceMultaFGTS) / 100;

            $impacto13 = ($decimo_terceiro * $somatorioEncargos->somatoria_encargo) / 100;
            $impacto_ferias_abono = ($ferias_abono * $somatorioEncargos->somatoria_encargo) / 100;

            $sql = ' INSERT '
                . ' INTO lancamentos ('
                . ' id_cliente, '
                . ' id_empresa, '
                . ' id_contrato, '
                . ' id_empregado, '
                . ' remuneracao, '
                . ' dias_trabalhados, '
                . ' decimo_terceiro, '
                . ' ferias_abono, '
                . ' multa_FGTS, '
                . ' impacto_encargos_13, '
                . ' impacto_ferias_abono, '
                . ' ano, '
                . ' mes, '
                . ' observacao_retencao, '
                . ' id_captura )'
                . ' VALUES ( '
                . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                . $dados->empresa . ', '
                . $dados->contrato . ', '
                . $temEmpregado->id_empregado . ', '
                . str_replace(',', '.', ($remuneracao . '')) . ', '
                . $dados->dias_trabalhados . ', '
                . str_replace(',', '.', ($decimo_terceiro . '')) . ', '
                . str_replace(',', '.', ($ferias_abono . '')) . ', '
                . str_replace(',', '.', ($multa_FGTS . '')) . ', '
                . str_replace(',', '.', ($impacto13 . '')) . ', '
                . str_replace(',', '.', ($impacto_ferias_abono . '')) . ', '
                . '"' . $dados->anoLancamento . '", '
                . '"' . $dados->mesLancamento . '", '
                . '"' . $dados->observacao_retencao . '", '
                . $codigo_captura . ') ';

            $lancamento = $conexao->query($sql);

            $mensagem = 'Empregado: ' . $dados->nome;
            $status_captura = 'Lançamento Inserido';
        } catch (\Exception $ex) {
            $mensagem = 'Erro na inserção do lançamento';
            $status_captura = 'Empregado: ' . $dados->nome . ' não inserido';
        }

        $dadosCaptura = [
            'empresa' => $dados->empresa,
            'contrato' => $dados->contrato,
            'historico' => $mensagem,
            'mes' => $dados->mesLancamento,
            'ano' => $dados->anoLancamento,
            'status_captura' => $status_captura,
            'id_captura' => $codigo_captura,
            // 'observacao_retencao' => $observacao_retencao,
            'id_captura' => $codigo_captura,
        ];

        //Captura::insereHistoricoCaptura($dadosCaptura);

        return $dadosCaptura;
    }

    public static function insereHistoricoCaptura($dados)
    {
        $conexao = MySQL::conexao();

        try {
            $sql = ' INSERT '
                . ' INTO historico_captura ( id_cliente, id_empresa, id_contrato, datahora, mes, ano, historico, status_captura ) VALUES ( '
                . $_SESSION["dados_usuario"]->getCliente_usuario() . ', '
                . $dados['empresa'] . ', '
                . $dados['contrato'] . ', "'
                . date('Y-m-d H:i:s') . '", '
                . $dados['mes'] . ', '
                . $dados['ano'] . ', '
                . '"' . $dados['historico'] . '", '
                . '"' . $dados['status_captura'] . '") ';
            $historico = $conexao->query($sql);
            $retorno = 'Dados inseridos no histórico de captura';
        } catch (\Exception $ex) {
            $retorno = 'Erro na inserção do histórico de captura';
        }

        return $retorno;
    }

    public function getId_empregado()
    {
        return $this->id_empregado;
    }

    public function setId_empregado($id_empregado)
    {
        $this->id_empregado = $id_empregado;

        return $this;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    public function getHorario()
    {
        return $this->horario;
    }

    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    public function getRemuneracao()
    {
        return $this->remuneracao;
    }

    public function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;

        return $this;
    }

    public function getDt_admissao()
    {
        return $this->dt_admissao;
    }

    public function setDt_admissao($dt_admissao)
    {
        $this->dt_admissao = $dt_admissao;

        return $this;
    }

    public function getDt_desligamento()
    {
        return $this->dt_desligamento;
    }

    public function setDt_desligamento($dt_desligamento)
    {
        $this->dt_desligamento = $dt_desligamento;

        return $this;
    }

    public function getId_empresa()
    {
        return $this->id_empresa;
    }

    public function setId_empresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;

        return $this;
    }

    public function getId_cargo()
    {
        return $this->id_cargo;
    }

    public function setId_cargo($id_cargo)
    {
        $this->id_cargo = $id_cargo;

        return $this;
    }

    public function getId_turno()
    {
        return $this->id_turno;
    }

    public function setId_turno($id_turno)
    {
        $this->id_turno = $id_turno;

        return $this;
    }

    public function getNome_cargo()
    {
        return $this->nome_cargo;
    }

    public function setNome_cargo($nome_cargo)
    {
        $this->nome_cargo = $nome_cargo;

        return $this;
    }

    public function getRemuneracao_cargo()
    {
        return $this->remuneracao_cargo;
    }

    public function setRemuneracao_cargo($remuneracao_cargo)
    {
        $this->remuneracao_cargo = $remuneracao_cargo;

        return $this;
    }

    public function getStatus_empregado()
    {
        return $this->status_empregado;
    }

    public function setStatus_empregado($status_empregado)
    {
        $this->status_empregado = $status_empregado;

        return $this;
    }

    public function getId_encargo()
    {
        return $this->id_encargo;
    }

    public function setId_encargo($id_encargo)
    {
        $this->id_encargo = $id_encargo;

        return $this;
    }

    public function getNome_encargo()
    {
        return $this->nome_encargo;
    }

    public function setNome_encargo($nome_encargo)
    {
        $this->nome_encargo = $nome_encargo;

        return $this;
    }

    public function getPercentual_encargo()
    {
        return $this->percentual_encargo;
    }

    public function setPercentual_encargo($percentual_encargo)
    {
        $this->percentual_encargo = $percentual_encargo;

        return $this;
    }

    public function getResumo()
    {
        return $this->resumo;
    }

    public function setResumo($resumo)
    {
        $this->resumo = $resumo;

        return $this;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getObservacao_liberacao()
    {
        return $this->observacao_liberacao;
    }

    public function setObservacao_liberacao($observacao_liberacao)
    {
        $this->observacao_liberacao = $observacao_liberacao;

        return $this;
    }

    public function getPadrao_indice13()
    {
        return $this->padrao_indice13;
    }

    public function setPadrao_indice13($padrao_indice13)
    {
        $this->padrao_indice13 = $padrao_indice13;

        return $this;
    }

    public function getPadrao_indiceFeriasAbono()
    {
        return $this->padrao_indiceFeriasAbono;
    }

    public function setPadrao_indiceFeriasAbono($padrao_indiceFeriasAbono)
    {
        $this->padrao_indiceFeriasAbono = $padrao_indiceFeriasAbono;

        return $this;
    }

    public function getPadrao_indiceMultaFGTS()
    {
        return $this->padrao_indiceMultaFGTS;
    }

    public function setPadrao_indiceMultaFGTS($padrao_indiceMultaFGTS)
    {
        $this->padrao_indiceMultaFGTS = $padrao_indiceMultaFGTS;

        return $this;
    }


    public function getObservacao_retencao()
    {
        return $this->observacao_retencao;
    }

    public function setObservacao_retencao($observacao_retencao)
    {
        $this->observacao_retencao = $observacao_retencao;

        return $this;
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
}

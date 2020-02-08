<?php

namespace modelo;

use bd\MySQL;

class Lancamento
{

    public $id_empregado;
    public $cpf;
    public $nome;
    public $turno;
    public $horario;
    public $remuneracao;
    public $dias_trabalhados;
    public $decimo_terceiro;
    public $decimo_terceiro_impacto;
    public $ferias_abono;
    public $ferias_abono_impacto;
    public $multa_FGTS;
    public $impacto_encargos_13;
    public $impacto_ferias_abono;
    public $dt_admissao;
    public $dt_desligamento;
    public $id_empresa;
    public $id_cargo;
    public $nome_cargo;
    public $retencoes;
    public $liberacoes;
    public $mes;
    public $ano;
    public $status_empregado;
    public $observacao_libaracao;

    public static function buscaLancamento($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT '
            . ' emp.id_empregado, '
            . ' emp.cpf, '
            . ' emp.nome, '
            . ' (CASE '
            . '     WHEN car.id_turno = 1 THEN "Diurno" '
            . '     WHEN car.id_turno = 2 THEN "Noturno" END '
            . ' ) turno, '
            . ' emp.horario,'
            . ' emp.remuneracao, '
            . ' emp.dt_admissao, '
            . ' emp.dt_desligamento, '
            . ' emp.id_empresa, '
            . ' emp.id_cargo, '
            . ' car.nome_cargo, '
            . ' emp.status_empregado, '
            . ' emp.observacao_liberacao '
            . ' FROM empregados emp, cargos car '
            . ' WHERE emp.id_cargo = car.id_cargo '
            . ' AND emp.status_empregado = "ATIVO" ';

        if ($dados['id_empregado']) {
            $sql = $sql . 'AND emp.id_empregado =  ' . $dados['id_empregado'];
        }

        $lancamento = new \stdClass();
        $lancamentos = new \stdClass();
        $lancamentos->erro = false;
        $lancamentos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $lancamento = new Lancamento();
                $lancamento->setId_empregado($row['id_empregado']);
                $lancamento->setCpf($row['cpf']);
                $lancamento->setNome($row['nome']);
                $lancamento->setId_cargo($row['id_cargo']);
                $lancamento->setNome_cargo($row['nome_cargo']);
                $lancamento->setTurno($row['turno']);
                $lancamento->setRemuneracao($row['remuneracao']);
                $lancamento->setDt_admissao($row['dt_admissao']);
                $lancamento->setDt_desligamento($row['dt_desligamento']);
                $lancamento->setStatus_empregado($row['status_empregado']);
                $lancamentos->dados[] = $lancamento;
            }
        } else {
            $lancamentos->erro = true;
            $lancamentos->mensagem = 'Nenhum empregado cadastrado.';
        }
        return $lancamentos;
    }

    public static function detalharLancamentosEmpregados($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT '
            . ' lan.id_empresa, lan.id_empregado, lan.ano, lan.mes, lan.ano, lan.remuneracao, lan.dias_trabalhados, '
            . ' lan.decimo_terceiro, lan.ferias_abono, lan.multa_FGTS, lan.impacto_encargos_13, lan.impacto_ferias_abono, '
            . ' emp.cpf, emp.nome, '
            . ' (CASE '
            . '     WHEN car.id_turno = 1 THEN "Diurno" '
            . '     WHEN car.id_turno = 2 THEN "Noturno" END '
            . ' ) turno, '
            . ' car.id_cargo, car.nome_cargo, car.remuneracao_cargo '
            . ' FROM lancamentos lan, empregados emp, cargos car '
            . ' WHERE lan.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario()
            . ' AND lan.id_empresa = ' . $dados['id_empresa']
            . ' AND lan.id_contrato = ' . $dados['id_contrato']
            . ' AND lan.id_empregado = ' . $dados['id_empregado']
            . ' AND lan.id_empregado = emp.id_empregado '
            . ' AND car.id_cargo = emp.id_cargo ';

        $lancamento = new \stdClass();
        $lancamentos = new \stdClass();
        $lancamentos->erro = false;
        $lancamentos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $lancamento = new Lancamento();
                $lancamento->setId_empresa($row['id_empresa']);
                $lancamento->setId_empregado($row['id_empregado']);
                $lancamento->setCpf($row['cpf']);
                $lancamento->setNome($row['nome']);
                $lancamento->setId_cargo($row['id_cargo']);
                $lancamento->setNome_cargo($row['nome_cargo']);
                $lancamento->setTurno($row['turno']);
                $lancamento->setRemuneracao($row['remuneracao']);
                $lancamento->setDias_trabalhados($row['dias_trabalhados']);
                $lancamento->setDecimo_terceiro($row['decimo_terceiro']);
                $lancamento->setFerias_abono($row['ferias_abono']);
                $lancamento->setMulta_FGTS($row['multa_FGTS']);
                $lancamento->setImpacto_encargos_13($row['impacto_encargos_13']);
                $lancamento->setImpacto_ferias_abono($row['impacto_ferias_abono']);
                $lancamento->setAno($row['ano']);
                $lancamento->setMes($row['mes']);
                $lancamentos->dados[] = $lancamento;
            }
        } else {
            $lancamentos->erro = true;
            $lancamentos->mensagem = 'Nenhum Lançamento encontrado para este Empregado.';
        }
        return $lancamentos;
    }

    public static function liberacoes($dados)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT ano, mes, 
                sum(IFNULL(liberacao_decimo_terceiro, 0)) decimo_terceiro,
                sum(IFNULL(liberacao_ferias_abono, 0)) ferias_abono,  
                sum(IFNULL(liberacao_multa_FGTS, 0)) multa_fgts,
                sum(IFNULL(liberacao_impacto_encargos_13, 0)) impacto_encargos_13, 
                sum(IFNULL(liberacao_impacto_ferias_abono, 0)) impacto_ferias_abono
                FROM lancamentos
                WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['id_empresa'])
            $sql .= ' AND id_empresa = ' . $dados['id_empresa'];

        if ($dados['id_empregado'])
            $sql .= ' AND id_empregado = ' . $dados['id_empregado'];

        if ($dados['id_contrato'])
            $sql .= ' AND id_contrato = ' . $dados['id_contrato'];

        //$sql .= ' GROUP BY ano ';

        $liberacao = new \stdClass();
        $liberacoes = new \stdClass();
        $liberacoes->erro = false;
        $liberacoes->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $liberacao = new Lancamento();
                $liberacao->setMes($row['mes']);
                $liberacao->setDecimo_terceiro($row['decimo_terceiro']);
                $liberacao->setFerias_abono($row['ferias_abono']);
                $liberacao->setMulta_FGTS($row['multa_fgts']);
                $liberacao->setImpacto_encargos_13($row['impacto_encargos_13']);
                $liberacao->setImpacto_ferias_abono($row['impacto_ferias_abono']);
                $liberacoes->dados[] = $liberacao;
            }
        } else {
            $liberacoes->erro = true;
            $liberacoes->mensagem = 'Nenhum Lançamento encontrado.';
        }
        return $liberacoes;
    }

    public static function saldos($dados)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT ano, mes, 
                sum(decimo_terceiro) decimo_terceiro_impacto, 
                sum(impacto_encargos_13) impacto_encargos_13, 
                sum(ferias_abono) ferias_abono_impacto, 
                sum(impacto_ferias_abono) impacto_ferias_abono, 
                sum(multa_fgts) multa_fgts
                FROM lancamentos
                WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['id_empresa'])
            $sql .= ' AND id_empresa = ' . $dados['id_empresa'];

        if ($dados['id_empregado'])
            $sql .= ' AND id_empregado = ' . $dados['id_empregado'];

        if ($dados['id_contrato'])
            $sql .= ' AND id_contrato = ' . $dados['id_contrato'];

        //$sql .= ' GROUP BY ano, mes ';

        $saldo = new \stdClass();
        $saldos = new \stdClass();
        $saldos->erro = false;
        $saldos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $saldo = new Lancamento();
                $saldo->setMes($row['mes']);
                $saldo->setDecimo_terceiro_impacto($row['decimo_terceiro_impacto']);
                $saldo->setImpacto_encargos_13($row['impacto_encargos_13']);
                $saldo->setFerias_abono_impacto($row['ferias_abono_impacto']);
                $saldo->setImpacto_ferias_abono($row['impacto_ferias_abono']);
                $saldo->setMulta_FGTS($row['multa_fgts']);
                $saldos->dados[] = $saldo;
            }
        } else {
            $saldos->erro = true;
            $saldos->mensagem = 'Nenhum Lançamento encontrado.';
        }
        return $saldos;
    }

    public static function listarSadoContas($dados)
    {
        $conexao = MySQL::conexao();

        //Query atende apenas a usuários não administradores
        $sql = ' SELECT IFNULL(SUM(retencoes), 0) quantidadeRetencoes, IFNULL(SUM(liberacoes), 0) quantidadeLiberacoes,
               IFNULL(SUM(retencoes), 0) - IFNULL(SUM(liberacoes), 0) quantidade FROM
            (
                SELECT 
                sum(decimo_terceiro + ferias_abono + multa_FGTS + impacto_encargos_13 + impacto_ferias_abono) retencoes,
                sum(IFNULL(liberacao_decimo_terceiro, 0) + 
                    IFNULL(liberacao_ferias_abono, 0) + 
                    IFNULL(liberacao_multa_FGTS, 0) +
                    IFNULL(liberacao_impacto_encargos_13, 0) + 
                    IFNULL(liberacao_impacto_ferias_abono, 0)) liberacoes
                FROM lancamentos
                WHERE 1 = 1';

        //Só filtra por cliente se não for administrador
        if ($_SESSION["dados_usuario"]->getNivel_acesso() > '0')
            $sql .= ' AND id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['id_empresa'])
            $sql .= ' AND id_empresa = ' . $dados['id_empresa'];

        if ($dados['id_empregado'])
            $sql .= ' AND id_empregado = ' . $dados['id_empregado'];

        $sql .= ' ) somatoria';

        $saldos = new \stdClass();
        $resultado = $conexao->query($sql);
        $saldos->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $saldos->quantidade = $l['quantidade'];
            $saldos->quantidadeRetencoes = $l['quantidadeRetencoes'];
            $saldos->quantidadeLiberacoes = $l['quantidadeLiberacoes'];
        } else {
            $saldos->quantidade = 0;
            $saldos->quantidadeRetencoes = 0;
            $saldos->quantidadeLiberacoes = 0;
        }

        return $saldos;
    }

    public static function liberarVerba($dados)
    {
        $conexao = MySQL::conexao();

        /*
         * Verifica se é liberação pelo total
         */
        $liberacaoTotal = false;
        if (strpos($dados['verba'], 'otal_')) {
            $dados['verba'] = substr($dados['verba'], 6, 20);
            $liberacaoTotal = true;
        }

        $sql = 'UPDATE lancamentos
                SET liberacao_' . $dados['verba'] . ' = ' . $dados['verba'] . ', 
                 observacao_liberacao = "' . $dados["observacao_liberacao"] . '"';

        //Se liberar decimo terceiro, libera também impacto sobre decimo terceiro
        if ($dados['verba'] == 'decimo_terceiro' && $dados['liberacao_casada'] == 'true') {
            $sql .= ', liberacao_impacto_encargos_13 = impacto_encargos_13 ';
        }

        if ($dados['verba'] == 'impacto_encargos_13' && $dados['liberacao_casada'] == 'false') {
            $sql .= ', liberacao_impacto_encargos_13 = impacto_encargos_13 ';
        }

        //Se liberar ferias abono, libera também impacto sobre férias abono
        if ($dados['verba'] == 'ferias_abono' && $dados['liberacao_casada'] == 'true') {
            $sql .= ', liberacao_impacto_ferias_abono = impacto_ferias_abono ';
        }

        if ($dados['verba'] == 'impacto_ferias_abono' && $dados['liberacao_casada'] == 'false') {
            $sql .= ', liberacao_impacto_ferias_abono = impacto_ferias_abono ';
        }

        $sql .= ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if (!$liberacaoTotal) {
            $sql .= ' AND id_lancamento = ' . $dados['id_lancamento'];
        }

        $sql .= ' AND id_empresa = ' . $dados['id_empresa'];
        $sql .= ' AND id_contrato = ' . $dados['id_contrato'];

        if (!$liberacaoTotal) {
            $sql .= ' AND mes = ' . $dados['mesIni'];
            $sql .= ' AND ano = ' . $dados['anoIni'];
        } else {
            // Se especificou o período de início e fim
            if ($dados['mesIni'] !== "" || $dados['mesFim'] !== "") {
                $sql .= ' AND DATE_FORMAT(concat(ano, "-", mes, "-01"), "%Y-%m-%d") BETWEEN DATE_FORMAT("' . $dados['anoIni'] . '-' . $dados['mesIni'] . '-01", "%Y-%m-%d") 
                      AND DATE_FORMAT("' . $dados['anoFim'] . '-' . $dados['mesFim'] . '-01", "%Y-%m-%d") ';
            }
        }

        //Caso esteja com filtro pelo usuário
        if ($dados['id_empregado']) {
            $sql .= ' AND id_empregado = ' . $dados['id_empregado'];
        }

        //evita atualização no banco
        $r = $conexao->query($sql);
        $liberacao = new \stdClass();
        $liberacao->erro = false;
        // $liberacao->mensagem = $sql; 
        $liberacao->mensagem = 'Verba liberada com sucesso';

        return $liberacao;
    }

    public static function cancelarLiberacao($dados)
    {
        $conexao = MySQL::conexao();

        /*
         * Verifica se é cancelamento pelo total
         */
        $cancelarTotal = false;
        if (strpos($dados['verba'], 'otal_')) {
            $dados['verba'] = substr($dados['verba'], 6, 20);
            $cancelarTotal = true;
        }

        $sql = 'UPDATE lancamentos
                SET liberacao_' . $dados['verba'] . ' = 0, 
                observacao_liberacao = "" ';

        //Se cancelar decimo terceiro, cancela também impacto sobre decimo terceiro
        if ($dados['verba'] == 'decimo_terceiro') {
            $sql .= ', liberacao_impacto_encargos_13 = 0 ';
        }

        //Se cancelar liberação de ferias abono, cancela também impacto sobre férias abono
        if ($dados['verba'] == 'ferias_abono') {
            $sql .= ', liberacao_impacto_ferias_abono = 0 ';
        }

        $sql .= ' WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if (!$cancelarTotal) {
            $sql .= ' AND id_lancamento = ' . $dados['id_lancamento'];
        }

        $sql .= ' AND id_empresa = ' . $dados['id_empresa'];
        $sql .= ' AND id_contrato = ' . $dados['id_contrato'];

        if (!$cancelarTotal) {
            $sql .= ' AND mes = ' . $dados['mesIni'];
            $sql .= ' AND ano = ' . $dados['anoIni'];
        } else {
            
            // Se especificou o período de início e fim
            if ($dados['mesIni'] !== "" || $dados['mesFim'] !== "") {
                $sql .= ' AND DATE_FORMAT(concat(ano, "-", mes, "-01"), "%Y-%m-%d") BETWEEN DATE_FORMAT("' . $dados['anoIni'] . '-' . $dados['mesIni'] . '-01", "%Y-%m-%d") 
                      AND DATE_FORMAT("' . $dados['anoFim'] . '-' . $dados['mesFim'] . '-01", "%Y-%m-%d") ';
            }
        }

        //Caso esteja com filtro pelo usuário
        if ($dados['id_empregado']) {
            $sql .= ' AND id_empregado = ' . $dados['id_empregado'];
        }

        //evita atualização no banco
        $r = $conexao->query($sql);
        $cancelamento = new \stdClass();
        $cancelamento->erro = false;
        // $cancelamento->mensagem = $sql;
        $cancelamento->mensagem = 'Liberação cancelada com sucesso';

        return $cancelamento;
    }

    public static function retencoesLiberacoes($dados)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT 
                mes, ano, 
                sum(decimo_terceiro + ferias_abono + multa_FGTS + impacto_encargos_13 + impacto_ferias_abono) retencoes,
                sum(IFNULL(liberacao_decimo_terceiro, 0) + 
                    IFNULL(liberacao_ferias_abono, 0) + 
                    IFNULL(liberacao_multa_FGTS, 0) +
                    IFNULL(liberacao_impacto_encargos_13, 0) + 
                    IFNULL(liberacao_impacto_ferias_abono, 0)) liberacoes
                FROM lancamentos
                WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['observacao_liberacao'])
            $sql .= ' AND observacao_liberacao = ' . $dados['observacao_liberacao'];

        if ($dados['id_empresa'])
            $sql .= ' AND id_empresa = ' . $dados['id_empresa'];

        if ($dados['id_empregado'])
            $sql .= ' AND id_empregado = ' . $dados['id_empregado'];

        if ($dados['id_contrato'])
            $sql .= ' AND id_contrato = ' . $dados['id_contrato'];

        $sql .= ' GROUP BY mes, ano
                  ORDER BY ano asc, mes asc';

        $resultado = $conexao->query($sql);

        $retencoesLiberacoes = new \stdClass();
        $retencoesLiberacoes->erro = false;

        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $retencaoLiberacao = new Lancamento();
                $retencaoLiberacao->setMes($row['mes']);
                $retencaoLiberacao->setAno($row['ano']);
                $retencaoLiberacao->setRetencoes($row['retencoes']);
                $retencaoLiberacao->setLiberacoes($row['liberacoes']);
                $retencoesLiberacoes->dados[] = $retencaoLiberacao;
            }
        } else {
            $retencoesLiberacoes->erro = true;
            $retencoesLiberacoes->mensagem = 'Nenhuma Retenção / Liberação encontrada';
        }

        return $retencoesLiberacoes;
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

    function getRemuneracao()
    {
        return $this->remuneracao;
    }

    function getDias_trabalhados()
    {
        return $this->dias_trabalhados;
    }

    function getDecimo_terceiro()
    {
        return $this->decimo_terceiro;
    }

    function getDecimo_terceiro_impacto()
    {
        return $this->decimo_terceiro_impacto;
    }

    function getFerias_abono()
    {
        return $this->ferias_abono;
    }

    function getFerias_abono_impacto()
    {
        return $this->ferias_abono_impacto;
    }

    function getMulta_FGTS()
    {
        return $this->multa_FGTS;
    }

    function getImpacto_encargos_13()
    {
        return $this->impacto_encargos_13;
    }

    function getImpacto_ferias_abono()
    {
        return $this->impacto_ferias_abono;
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

    function getId_cargo()
    {
        return $this->id_cargo;
    }

    function getNome_cargo()
    {
        return $this->nome_cargo;
    }

    function getRetencoes()
    {
        return $this->retencoes;
    }

    function getLiberacoes()
    {
        return $this->liberacoes;
    }

    function getAno()
    {
        return $this->ano;
    }

    function getMes()
    {
        return $this->mes;
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

    function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
    }

    function setDias_trabalhados($dias_trabalhados)
    {
        $this->dias_trabalhados = $dias_trabalhados;
    }

    function setDecimo_terceiro($decimo_terceiro)
    {
        $this->decimo_terceiro = $decimo_terceiro;
    }

    function setDecimo_terceiro_impacto($decimo_terceiro_impacto)
    {
        $this->decimo_terceiro_impacto = $decimo_terceiro_impacto;
    }

    function setFerias_abono($ferias_abono)
    {
        $this->ferias_abono = $ferias_abono;
    }

    function setFerias_abono_impacto($ferias_abono_impacto)
    {
        $this->ferias_abono_impacto = $ferias_abono_impacto;
    }

    function setMulta_FGTS($multa_FGTS)
    {
        $this->multa_FGTS = $multa_FGTS;
    }

    function setImpacto_encargos_13($impacto_encargos_13)
    {
        $this->impacto_encargos_13 = $impacto_encargos_13;
    }

    function setImpacto_ferias_abono($impacto_ferias_abono)
    {
        $this->impacto_ferias_abono = $impacto_ferias_abono;
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

    function setId_cargo($id_cargo)
    {
        $this->id_cargo = $id_cargo;
    }

    function setNome_cargo($nome_cargo)
    {
        $this->nome_cargo = $nome_cargo;
    }

    function setRetencoes($retencoes)
    {
        $this->retencoes = $retencoes;
    }

    function setLiberacoes($liberacoes)
    {
        $this->liberacoes = $liberacoes;
    }

    function setAno($ano)
    {
        $this->ano = $ano;
    }

    function setMes($mes)
    {
        $this->mes = $mes;
    }


    public function getObservacao_libaracao()
    {
        return $this->observacao_libaracao;
    }

    public function setObservacao_libaracao($observacao_libaracao)
    {
        $this->observacao_libaracao = $observacao_libaracao;

        return $this;
    }

    /**
     * Get the value of status_empregado
     */
    public function getStatus_empregado()
    {
        return $this->status_empregado;
    }

    /**
     * Set the value of status_empregado
     *
     * @return  self
     */
    public function setStatus_empregado($status_empregado)
    {
        $this->status_empregado = $status_empregado;

        return $this;
    }
}

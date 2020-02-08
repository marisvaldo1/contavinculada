<?php

namespace modelo;

use bd\MySQL;

class Extrato
{
    public $id_cliente;
    public $id_lancamento;
    public $id_empresa;
    public $id_contrato;
    public $id_empregado;
    public $remuneracao;
    public $total_provisionado;
    public $total_liberado;
    public $dias_trabalhados;
    public $r_decimo_terceiro;
    public $r_ferias_abono;
    public $r_multa_FGTS;
    public $r_impacto_encargos_13;
    public $r_impacto_ferias_abono;
    public $l_decimo_terceiro;
    public $l_ferias_abono;
    public $l_multa_FGTS;
    public $l_impacto_encargos_13;
    public $l_impacto_ferias_abono;
    public $decimo_terceiro;
    public $ferias_abono;
    public $multa_FGTS;
    public $impacto_encargos_13;
    public $impacto_ferias_abono;
    public $liberacao_decimo_terceiro;
    public $liberacao_ferias_abono;
    public $liberacao_multa_FGTS;
    public $liberacao_impacto_encargos_13;
    public $liberacao_impacto_ferias_abono;
    public $observacao_liberacao;
    public $observacao_retencao;
    public $mes;
    public $ano;
    public $nome;
    public $cpf;

    public static function buscaExtrato($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT lan.id_lancamento, mes, ano, emp.nome, emp.cpf, decimo_terceiro r_decimo_terceiro, ferias_abono r_ferias_abono,
                    multa_FGTS r_multa_FGTS,impacto_encargos_13 r_impacto_encargos_13, impacto_ferias_abono r_impacto_ferias_abono,
                    lan.observacao_liberacao, lan.observacao_retencao,
                    IFNULL(liberacao_decimo_terceiro, 0) l_decimo_terceiro,
                    IFNULL(liberacao_ferias_abono, 0) l_ferias_abono,
                    IFNULL(liberacao_multa_FGTS, 0) l_multa_FGTS,
                    IFNULL(liberacao_impacto_encargos_13, 0) l_impacto_encargos_13,
                    IFNULL(liberacao_impacto_ferias_abono, 0) l_impacto_ferias_abono
                    FROM lancamentos lan, empregados emp
                    WHERE lan.id_empregado = emp.id_empregado
                    AND lan.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if ($dados['id_empresa']) {
            $sql .= ' AND lan.id_empresa = ' . $dados['id_empresa'];
        }

        if ($dados['id_contrato']) {
            $sql .= ' AND lan.id_contrato = ' . $dados['id_contrato'];
        }

        if ($dados['dataInicio']) {
            $anoInicio = substr($dados['dataInicio'], 0, 4);
            $mesInicio = substr($dados['dataInicio'], 4, 2);
            $mesInicio = intval($mesInicio) < 10 ? '0' . $mesInicio : $mesInicio;
            $sql .= ' AND CONCAT( lan.ano, lan.mes) >= ' . $anoInicio . $mesInicio;
        }

        if ($dados['dataFim']) {
            $anoFim = substr($dados['dataFim'], 0, 4);
            $mesFim = substr($dados['dataFim'], 4, 2);
            $mesFim = intval($mesFim) < 10 ? '0' . $mesFim : $mesFim;
            $sql .= ' AND CONCAT( lan.ano, lan.mes) <= ' . $anoFim . $mesFim;
        }

        if ($dados['id_empregado']) {
            $sql .= ' AND lan.id_empregado = ' . $dados['id_empregado'];
        }

        if ($dados['observacao_liberacao']) {
            $sql .= " AND lan.observacao_liberacao LIKE '%" . $dados['observacao_liberacao'] . "%'";
        }

        if ($dados['observacao_retencao']) {
            $sql .= " AND lan.observacao_retencao LIKE '%" . $dados['observacao_retencao'] . "%'";
        }

        $sql .= ' GROUP BY lan.id_lancamento, lan.id_cliente, lan.id_empresa, lan.id_contrato, mes, ano, lan.id_empregado 
                  ORDER BY ano DESC, mes DESC';

        $extrato = new \stdClass();
        $extratos = new \stdClass();
        $extratos->erro = false;
        $extratos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $extrato = new Extrato();
                $extrato->setRemuneracao($row['remuneracao']);

                $extrato->setR_decimo_terceiro($row['r_decimo_terceiro']);
                $extrato->setR_ferias_abono($row['r_ferias_abono']);
                $extrato->setR_multa_FGTS($row['r_multa_FGTS']);
                $extrato->setR_impacto_encargos_13($row['r_impacto_encargos_13']);
                $extrato->setR_impacto_ferias_abono($row['r_impacto_ferias_abono']);

                $extrato->setL_decimo_terceiro($row['l_decimo_terceiro']);
                $extrato->setL_ferias_abono($row['l_ferias_abono']);
                $extrato->setL_multa_FGTS($row['l_multa_FGTS']);
                $extrato->setL_impacto_encargos_13($row['l_impacto_encargos_13']);
                $extrato->setL_impacto_ferias_abono($row['l_impacto_ferias_abono']);

                $extrato->setMes($row['mes']);
                $extrato->setAno($row['ano']);
                $extrato->setNome($row['nome']);
                $extrato->setCpf(str_pad($row['cpf'], 11, '0', STR_PAD_LEFT));

                $extrato->setObservacao_liberacao($row['observacao_liberacao']);
                $extrato->setObservacao_retencao($row['observacao_retencao']);
                $extratos->dados[] = $extrato;
            }
        } else {
            $extratos->erro = true;
            $extratos->mensagem = 'Nenhum lançamento cadastrado.';
        }
        return $extratos;
    }

    public static function listarLiberacao($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT emp.nome, emp.cpf, lan.id_lancamento, lan.id_cliente, lan.id_empresa, lan.id_contrato, lan.id_empregado,
                 IFNULL(lan.liberacao_decimo_terceiro, 0) liberacao_decimo_terceiro,
                 IFNULL(lan.liberacao_ferias_abono, 0) liberacao_ferias_abono,
                 IFNULL(lan.liberacao_multa_FGTS, 0) liberacao_multa_FGTS,
                 IFNULL(lan.liberacao_impacto_encargos_13, 0) liberacao_impacto_encargos_13,
                 IFNULL(lan.liberacao_impacto_ferias_abono, 0) liberacao_impacto_ferias_abono,
                 lan.decimo_terceiro, lan.ferias_abono, lan.multa_FGTS, lan.impacto_encargos_13, 
                 lan.impacto_ferias_abono, mes, ano, lan.observacao_liberacao, lan.observacao_retencao
                 FROM lancamentos lan, empregados emp
                 WHERE lan.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                 AND lan.id_empregado = emp.id_empregado ';

        if ($dados['observacao_retencao']) {
            $sql .= " AND lan.observacao_retencao LIKE '%" . $dados['observacao_retencao'] . "%'";
        }

        if ($dados['observacao_liberacao']) {
            $sql .= " AND lan.observacao_liberacao LIKE '%" . $dados['observacao_liberacao'] . "%'";
        }

        if ($dados['id_empresa']) {
            $sql .= ' AND lan.id_empresa = ' . $dados['id_empresa'];
        }

        if ($dados['id_contrato']) {
            $sql .= ' AND lan.id_contrato = ' . $dados['id_contrato'];
        }

        if ($dados['dataInicio']) {
            $anoInicio = substr($dados['dataInicio'], 0, 4);
            $mesInicio = substr($dados['dataInicio'], 4, 2);
            $mesInicio = intval($mesInicio) < 10 ? '0' . $mesInicio : $mesInicio;
            $sql .= ' AND CONCAT( lan.ano, lan.mes) >= ' . $anoInicio . $mesInicio;
        }

        if ($dados['dataFim']) {
            $anoFim = substr($dados['dataFim'], 0, 4);
            $mesFim = substr($dados['dataFim'], 4, 2);
            $mesFim = intval($mesFim) < 10 ? '0' . $mesFim : $mesFim;
            $sql .= ' AND CONCAT( lan.ano, lan.mes) <= ' . $anoFim . $mesFim;
        }

        if ($dados['id_empregado']) {
            $sql .= ' AND lan.id_empregado = ' . $dados['id_empregado'];
        }

        $sql .= ' ORDER BY ano DESC, mes DESC';

        $extrato = new \stdClass();
        $extratos = new \stdClass();
        $extratos->erro = false;
        $extratos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $extrato = new Extrato();
                $extrato->setNome($row['nome']);
                $extrato->setCpf(str_pad($row['cpf'], 11, '0', STR_PAD_LEFT));
                $extrato->setId_lancamento($row['id_lancamento']);
                $extrato->setId_cliente($row['id_cliente']);
                $extrato->setId_empresa($row['id_empresa']);
                $extrato->setId_contrato($row['id_contrato']);
                $extrato->setId_empregado($row['id_empregado']);

                $extrato->setMes($row['mes']);
                $extrato->setAno($row['ano']);

                $extrato->setLiberacao_decimo_terceiro($row['liberacao_decimo_terceiro']);
                $extrato->setLiberacao_ferias_abono($row['liberacao_ferias_abono']);
                $extrato->setLiberacao_multa_FGTS($row['liberacao_multa_FGTS']);
                $extrato->setLiberacao_impacto_encargos_13($row['liberacao_impacto_encargos_13']);
                $extrato->setLiberacao_impacto_ferias_abono($row['liberacao_impacto_ferias_abono']);

                $extrato->setDecimo_terceiro($row['decimo_terceiro']);
                $extrato->setFerias_abono($row['ferias_abono']);
                $extrato->setMulta_FGTS($row['multa_FGTS']);
                $extrato->setImpacto_encargos_13($row['impacto_encargos_13']);
                $extrato->setImpacto_ferias_abono($row['impacto_ferias_abono']);

                $extrato->setObservacao_liberacao($row['observacao_liberacao']);
                $extrato->setObservacao_retencao($row['observacao_retencao']);

                $extratos->dados[] = $extrato;
            }
        } else {
            $extratos->erro = true;
            $extratos->mensagem = 'Nenhum lançamento cadastrado.';
        }
        return $extratos;
    }

    public static function listarRetencao($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT 
                 emp.nome, emp.cpf, lan.id_lancamento, lan.id_cliente, lan.id_empresa, 
                 lan.id_contrato, lan.id_empregado, lan.ferias_abono, lan.decimo_terceiro, 
                 lan.multa_FGTS, lan.impacto_encargos_13, lan.impacto_ferias_abono, mes, ano,
                 lan.observacao_retencao, lan.observacao_liberacao
                 FROM lancamentos lan, empregados emp
                 WHERE lan.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                 AND lan.id_empregado = emp.id_empregado ';

        if ($dados['observacao_retencao']) {
            $sql .= " AND lan.observacao_retencao LIKE '%" . $dados['observacao_retencao'] . "%'";
        }

        if ($dados['observacao_liberacao']) {
            $sql .= " AND lan.observacao_liberacao LIKE '%" . $dados['observacao_liberacao'] . "%'";
        }

        if ($dados['id_empresa']) {
            $sql .= ' AND lan.id_empresa = ' . $dados['id_empresa'];
        }

        if ($dados['id_contrato']) {
            $sql .= ' AND lan.id_contrato = ' . $dados['id_contrato'];
        }

        if ($dados['dataInicio']) {
            $anoInicio = substr($dados['dataInicio'], 0, 4);
            $mesInicio = substr($dados['dataInicio'], 4, 2);
            $mesInicio = intval($mesInicio) < 10 ? '0' . $mesInicio : $mesInicio;
            $sql .= ' AND CONCAT( lan.ano, lan.mes) >= ' . $anoInicio . $mesInicio;
        }

        if ($dados['dataFim']) {
            $anoFim = substr($dados['dataFim'], 0, 4);
            $mesFim = substr($dados['dataFim'], 4, 2);
            $mesFim = intval($mesFim) < 10 ? '0' . $mesFim : $mesFim;
            $sql .= ' AND CONCAT( lan.ano, lan.mes) <= ' . $anoFim . $mesFim;
        }

        if ($dados['id_empregado']) {
            $sql .= ' AND lan.id_empregado = ' . $dados['id_empregado'];
        }

        $sql .= ' ORDER BY ano DESC, mes DESC';

        $extrato = new \stdClass();
        $extratos = new \stdClass();
        $extratos->erro = false;
        $extratos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $extrato = new Extrato();
                $extrato->setNome($row['nome']);
                $extrato->setCpf(str_pad($row['cpf'], 11, '0', STR_PAD_LEFT));
                $extrato->setId_lancamento($row['id_lancamento']);
                $extrato->setId_cliente($row['id_cliente']);
                $extrato->setId_empresa($row['id_empresa']);
                $extrato->setId_contrato($row['id_contrato']);
                $extrato->setId_empregado($row['id_empregado']);
                $extrato->setMes($row['mes']);
                $extrato->setAno($row['ano']);

                $extrato->setDecimo_terceiro($row['decimo_terceiro']);
                $extrato->setFerias_abono($row['ferias_abono']);
                $extrato->setMulta_FGTS($row['multa_FGTS']);

                $extrato->setImpacto_encargos_13($row['impacto_encargos_13']);
                $extrato->setImpacto_ferias_abono($row['impacto_ferias_abono']);

                $extrato->setObservacao_liberacao($row['observacao_liberacao']);
                $extrato->setObservacao_retencao($row['observacao_retencao']);

                $extratos->dados[] = $extrato;
            }
        } else {
            $extratos->erro = true;
            $extratos->mensagem = 'Nenhum lançamento cadastrado.';
        }
        return $extratos;
    }

    function getId_cliente()
    {
        return $this->id_cliente;
    }

    function getId_lancamento()
    {
        return $this->id_lancamento;
    }

    function getId_empresa()
    {
        return $this->id_empresa;
    }

    function getId_contrato()
    {
        return $this->id_contrato;
    }

    function getId_empregado()
    {
        return $this->id_empregado;
    }

    function getRemuneracao()
    {
        return $this->remuneracao;
    }

    function getTotal_provisionado()
    {
        return $this->total_provisionado;
    }

    function getTotal_liberado()
    {
        return $this->total_liberado;
    }

    function getDias_trabalhados()
    {
        return $this->dias_trabalhados;
    }

    function getR_decimo_terceiro()
    {
        return $this->r_decimo_terceiro;
    }

    function getR_ferias_abono()
    {
        return $this->r_ferias_abono;
    }

    function getR_multa_FGTS()
    {
        return $this->r_multa_FGTS;
    }

    function getR_impacto_encargos_13()
    {
        return $this->r_impacto_encargos_13;
    }

    function getR_impacto_ferias_abono()
    {
        return $this->r_impacto_ferias_abono;
    }

    function getL_decimo_terceiro()
    {
        return $this->l_decimo_terceiro;
    }

    function getL_ferias_abono()
    {
        return $this->l_ferias_abono;
    }

    function getL_multa_FGTS()
    {
        return $this->l_multa_FGTS;
    }

    function getL_impacto_encargos_13()
    {
        return $this->l_impacto_encargos_13;
    }

    function getL_impacto_ferias_abono()
    {
        return $this->l_impacto_ferias_abono;
    }

    function getDecimo_terceiro()
    {
        return $this->decimo_terceiro;
    }

    function getFerias_abono()
    {
        return $this->ferias_abono;
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

    function getLiberacao_decimo_terceiro()
    {
        return $this->liberacao_decimo_terceiro;
    }

    function getLiberacao_ferias_abono()
    {
        return $this->liberacao_ferias_abono;
    }

    function getLiberacao_multa_FGTS()
    {
        return $this->liberacao_multa_FGTS;
    }

    function getLiberacao_impacto_encargos_13()
    {
        return $this->liberacao_impacto_encargos_13;
    }

    function getLiberacao_impacto_ferias_abono()
    {
        return $this->liberacao_impacto_ferias_abono;
    }

    function getMes()
    {
        return $this->mes;
    }

    function getAno()
    {
        return $this->ano;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getCpf()
    {
        return $this->cpf;
    }

    function setId_cliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }

    function setId_lancamento($id_lancamento)
    {
        $this->id_lancamento = $id_lancamento;
    }

    function setId_empresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;
    }

    function setId_contrato($id_contrato)
    {
        $this->id_contrato = $id_contrato;
    }

    function setId_empregado($id_empregado)
    {
        $this->id_empregado = $id_empregado;
    }

    function setRemuneracao($remuneracao)
    {
        $this->remuneracao = $remuneracao;
    }

    function setTotal_provisionado($total_provisionado)
    {
        $this->total_provisionado = $total_provisionado;
    }

    function setTotal_liberado($total_liberado)
    {
        $this->total_liberado = $total_liberado;
    }

    function setDias_trabalhados($dias_trabalhados)
    {
        $this->dias_trabalhados = $dias_trabalhados;
    }

    function setR_decimo_terceiro($r_decimo_terceiro)
    {
        $this->r_decimo_terceiro = $r_decimo_terceiro;
    }

    function setR_ferias_abono($r_ferias_abono)
    {
        $this->r_ferias_abono = $r_ferias_abono;
    }

    function setR_multa_FGTS($r_multa_FGTS)
    {
        $this->r_multa_FGTS = $r_multa_FGTS;
    }

    function setR_impacto_encargos_13($r_impacto_encargos_13)
    {
        $this->r_impacto_encargos_13 = $r_impacto_encargos_13;
    }

    function setR_impacto_ferias_abono($r_impacto_ferias_abono)
    {
        $this->r_impacto_ferias_abono = $r_impacto_ferias_abono;
    }

    function setL_decimo_terceiro($l_decimo_terceiro)
    {
        $this->l_decimo_terceiro = $l_decimo_terceiro;
    }

    function setL_ferias_abono($l_ferias_abono)
    {
        $this->l_ferias_abono = $l_ferias_abono;
    }

    function setL_multa_FGTS($l_multa_FGTS)
    {
        $this->l_multa_FGTS = $l_multa_FGTS;
    }

    function setL_impacto_encargos_13($l_impacto_encargos_13)
    {
        $this->l_impacto_encargos_13 = $l_impacto_encargos_13;
    }

    function setL_impacto_ferias_abono($l_impacto_ferias_abono)
    {
        $this->l_impacto_ferias_abono = $l_impacto_ferias_abono;
    }

    function setDecimo_terceiro($decimo_terceiro)
    {
        $this->decimo_terceiro = $decimo_terceiro;
    }

    function setFerias_abono($ferias_abono)
    {
        $this->ferias_abono = $ferias_abono;
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

    function setLiberacao_decimo_terceiro($liberacao_decimo_terceiro)
    {
        $this->liberacao_decimo_terceiro = $liberacao_decimo_terceiro;
    }

    function setLiberacao_ferias_abono($liberacao_ferias_abono)
    {
        $this->liberacao_ferias_abono = $liberacao_ferias_abono;
    }

    function setLiberacao_multa_FGTS($liberacao_multa_FGTS)
    {
        $this->liberacao_multa_FGTS = $liberacao_multa_FGTS;
    }

    function setLiberacao_impacto_encargos_13($liberacao_impacto_encargos_13)
    {
        $this->liberacao_impacto_encargos_13 = $liberacao_impacto_encargos_13;
    }

    function setLiberacao_impacto_ferias_abono($liberacao_impacto_ferias_abono)
    {
        $this->liberacao_impacto_ferias_abono = $liberacao_impacto_ferias_abono;
    }

    function setMes($mes)
    {
        $this->mes = $mes;
    }

    function setAno($ano)
    {
        $this->ano = $ano;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setCpf($cpf)
    {
        $this->cpf = $cpf;
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

    public function getObservacao_retencao()
    {
        return $this->observacao_retencao;
    }

    public function setObservacao_retencao($observacao_retencao)
    {
        $this->observacao_retencao = $observacao_retencao;

        return $this;
    }
}

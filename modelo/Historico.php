<?php

namespace modelo;

use bd\MySQL;

class Historico
{

    public $id_captura;
    public $id_cliente;
    public $id_empresa;
    public $id_contrato;
    public $nu_contrato;
    public $datahora;
    public $historico;
    public $status_captura;
    public $mes;
    public $ano;

    function getId_captura()
    {
        return $this->id_captura;
    }

    function getId_cliente()
    {
        return $this->id_cliente;
    }

    function getId_empresa()
    {
        return $this->id_empresa;
    }

    function getId_contrato()
    {
        return $this->id_contrato;
    }

    function getNu_contrato()
    {
        return $this->nu_contrato;
    }

    function getDatahora()
    {
        return $this->datahora;
    }

    function getHistorico()
    {
        return $this->historico;
    }

    function getStatus_captura()
    {
        return $this->status_captura;
    }

    function getMes()
    {
        return $this->mes;
    }

    function getAno()
    {
        return $this->ano;
    }

    function setId_captura($id_captura)
    {
        $this->id_captura = $id_captura;
    }

    function setId_cliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }

    function setId_empresa($id_empresa)
    {
        $this->id_empresa = $id_empresa;
    }

    function setId_contrato($id_contrato)
    {
        $this->id_contrato = $id_contrato;
    }

    function setNu_contrato($nu_contrato)
    {
        $this->nu_contrato = $nu_contrato;
    }

    function setDatahora($datahora)
    {
        $this->datahora = $datahora;
    }

    function setHistorico($historico)
    {
        $this->historico = $historico;
    }

    function setStatus_captura($status_captura)
    {
        $this->status_captura = $status_captura;
    }

    function setMes($mes)
    {
        $this->mes = $mes;
    }

    function setAno($ano)
    {
        $this->ano = $ano;
    }

    public static function buscaHistorico($dados)
    {
        $conexao = MySQL::conexao();

        $sql = ' SELECT 
                 his.id_cliente, his.id_captura, his.id_empresa, con.nu_contrato, 
                 his.datahora, his.mes, his.ano, his.historico, his.status_captura
                 FROM historico_captura his, contratos con
                 WHERE his.id_contrato = con.id_contrato
                 AND his.id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario();

        if (!empty($dados['id_empresa'])) {
            $sql .= ' AND his.id_empresa = ' . $dados['id_empresa'];
        }

        if (!empty($dados['id_contrato'])) {
            $sql .= ' AND his.id_contrato = ' . $dados['id_contrato'];
        }

        if (!empty($dados['nu_mes'])) {
            $sql .= ' AND his.mes = ' . $dados['nu_mes'];
        }

        if (!empty($dados['nu_ano'])) {
            $sql .= ' AND his.ano = ' . $dados['nu_ano'];
        }

        //$sql .= ' ORDER BY his.datahora DESC';
        $sql .= ' ORDER BY his.id_captura DESC';

        $historico = new \stdClass();
        $historicos = new \stdClass();
        $historicos->erro = false;
        $historicos->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $historico = new Historico();
                $historico->setId_cliente($row['id_cliente']);
                $historico->setId_captura($row['id_captura']);
                $historico->setId_empresa($row['id_empresa']);
                $historico->setId_contrato($row['id_contrato']);
                $historico->setNu_contrato($row['nu_contrato']);
                $historico->setDatahora($row['datahora']);
                $historico->setHistorico($row['historico']);
                $historico->setMes($row['mes']);
                $historico->setAno($row['ano']);
                $historico->setStatus_captura($row['status_captura']);
                $historicos->dados[] = $historico;
            }
        } else {
            $historicos->erro = true;
            $historicos->mensagem = 'Nenhum lançamento encontrado.';
        }
        return $historicos;
    }

    public static function excluirHistorico($id_captura)
    {
        $conexao = MySQL::conexao();

        $historico_captura = new \stdClass();
        $historico_captura->erro = false;
        $historico_captura->mensagem = 'Item Excluído.';

        try {
            $sql = ' DELETE  '
                . ' FROM historico_captura '
                . ' WHERE id_captura = ' . $id_captura;
            $historicoCaptura = $conexao->query($sql);
        } catch (\Exception $ex) {
            $historico_captura->erro = true;
            $historico_captura->mensagem = 'Erro na exclusão do item.';
        }
        return $historico_captura;
    }

    public static function excluirCaptura($id_captura)
    {
        $conexao = MySQL::conexao();

        $exclusao_captura = new \stdClass();
        $exclusao_captura->erro = false;
        $exclusao_captura->mensagem = 'Item Excluído.';

        try {
            $sql = ' DELETE  '
                . ' FROM historico_captura '
                . ' WHERE id_captura = ' . $id_captura;
            $exclusaoCaptura = $conexao->query($sql);

            // Desabilita a verificação da constraint para permitir a exclusão
            $sql = ' DELETE  '
                . ' FROM lancamentos '
                . ' WHERE id_captura = ' . $id_captura;
            $exclusaoCaptura = $conexao->query($sql);

            // Desabilita a verificação da constraint para permitir a exclusão
            $sql = ' DELETE  '
                . ' FROM encargos '
                . ' WHERE id_captura = ' . $id_captura;
            $exclusaoCaptura = $conexao->query($sql);

            // Desabilita a verificação da constraint para permitir a exclusão
            $sql = ' DELETE  '
                . ' FROM contratos_encargos '
                . ' WHERE id_captura = ' . $id_captura;
            $exclusaoCaptura = $conexao->query($sql);

            // Desabilita a verificação da constraint para permitir a exclusão
            $sql = ' DELETE  '
                . ' FROM contratos_empregados '
                . ' WHERE id_captura = ' . $id_captura;
            $exclusaoCaptura = $conexao->query($sql);

            // Desabilita a verificação da constraint para permitir a exclusão
            $sql = ' DELETE  '
                . ' FROM empregados '
                . ' WHERE id_captura = ' . $id_captura;
            $exclusaoCaptura = $conexao->query($sql);
        } catch (\Exception $ex) {
            $exclusao_captura->erro = false;
            $exclusao_captura->mensagem = 'Erro na exclusão do item.';
        }

        return $exclusao_captura;
    }
}

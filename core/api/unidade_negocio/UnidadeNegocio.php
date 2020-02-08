<?php

namespace api\unidade_negocio;

use bd\MySQL;

class UnidadeNegocio
{

    private $c;

    /**
     *
     * @param MySQL $c
     */
    public function __construct(MySQL $c = null)
    {
        if (!$c) {
            $c = MySQL::conexao('pphp');
        }
        $this->c = $c;
    }

    public function getUnidadeNegocioPorMcu($mcu)
    {
        $sql = 'SELECT mcu, nome, tipo2 tipo, cod_dr FROM unidade_negocio WHERE mcu = ?';
        $par = [
            trim($mcu),
        ];
        $r = $this->c->query($sql, $par);
        if ($r->num_rows) {
            $l = $r->fetch_assoc();
            return [
                'mcu' => $l['mcu'],
                'nome' => $l['nome'],
                'tipo' => $l['tipo'],
                'cod_dr' => $l['cod_dr'],
            ];
        } else {
            throw new \Exception('MCU ' . e($mcu) . ' não contém nenhuma unidade de negócio no banco de dados.');
        }
    }
}

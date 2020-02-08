<?php

namespace fs;

class Diretorios
{
    private static $diretorios;

    /**
     * @throws \Exception
     */
    public static function inicia()
    {
        $diretorios = config('diretorios');
        if (!isset($diretorios[AMBIENTE_PUBLICACAO])) {
            throw new \Exception(
                'Configuração de diretórios não definida para o ambiente ' . AMBIENTE_PUBLICACAO . '.'
            );
        }
        self::$diretorios = config('diretorios')[AMBIENTE_PUBLICACAO];
    }

    /**
     * @param $chave
     * @return mixed
     * @throws \Exception
     */
    public static function get($chave)
    {
        if (!isset(self::$diretorios[$chave])) {
            throw new \Exception(
                'Configuração de diretório ' . $chave . ' não definida para o ambiente ' . AMBIENTE_PUBLICACAO . '.'
            );
        }
        return self::$diretorios[$chave];
    }
}

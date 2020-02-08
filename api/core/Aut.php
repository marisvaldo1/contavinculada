<?php
namespace api;

class Aut
{
    /**
     * @var Usuario
     */
    private static $usuario;

    public static function ini()
    {
        $headers = apache_request_headers();
        $key = isset($headers['Key']) ? $headers['Key'] : null;
        if (isset($_SESSION['usuarioapi'])) {
            self::$usuario = unserialize($_SESSION['usuarioapi']);
        } else {
            self::$usuario = new Usuario();
        }
        if (!self::$usuario->logado || self::$usuario->key != $key) {
            if (!$key) {
                self::$usuario->logado = false;
                return;
            }

            //Autenticação fake, sem banco de dados
            //todo remover autenticação fake e ligar a um banco de dados.

            $keys = file_get_contents('keys.txt');
            $start = strpos($keys, $key);
            if ($start === false) {
                self::$usuario->logado = false;
                return;
            }
            $length = strpos(substr($keys, $start), "\n");
            $linha = substr($keys, $start, $length);
            list($key, $id, $perfil) = explode(' ', $linha);
            self::$usuario->id = $id;
            self::$usuario->perfil = $perfil;
            self::$usuario->logado = true;
            self::$usuario->key = $key;
            $_SESSION['usuarioapi'] = serialize(self::$usuario);
        }
    }

    /**
     * @return Usuario
     */
    public static function usuario()
    {
        return self::$usuario;
    }

    public static function filtraAutenticado()
    {
        if (!self::$usuario->logado) {
            throw new \Exception('Usuário não autenticado.');
        }
    }

    public static function filtraPerfil(...$perfis)
    {
        self::filtraAutenticado();
        $perfil = Aut::usuario()->perfil;
        foreach ($perfis as $p) {
            if ($perfil == $p) {
                return;
            }
        }
        throw new \Exception('Usuário não possui privilégios.');
    }


}
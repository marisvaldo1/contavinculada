<?php
namespace bd;

use Predis\Client;

class RedisUtil
{
    /**
     * @var array
     */
    private static $confAmbiente;

    /**
     * @var Client[]
     */
    private static $predis;

    public static function ini()
    {
        $confs = config('redis');
        include $confs['autoload'];
        if (!isset($confs['ambientes'][AMBIENTE])) {
            throw new \Exception('Redis: configurações do ambiente inexistentes.');
            return null;
        }
        self::$confAmbiente = $confs['ambientes'][AMBIENTE];
    }

    /**
     * @param $dsn string
     * @return Client
     * @throws \Exception configurações do ambiente inexistentes
     */
    public static function con($dsn = null)
    {
        if (isset(self::$predis[$dsn])) {
            return self::$predis[$dsn];
        }
        if (!$dsn) {
            $dsn = array_keys(self::$confAmbiente)[0];
        }
        if (!self::$confAmbiente[$dsn]) {
            throw new \Exception('Data source de redis não configurado.');
        }
        $conf = self::$confAmbiente[$dsn];
        $predis = new Client($conf['parameters'], ['prefix' => \Sistema::$sigla . ':']);
        if ($conf['auth']) {
            $predis->auth($conf['auth']);
        }
        self::$predis[$dsn] = $predis;
        return $predis;
    }

    /**
     * @param $predis Client
     * @param $pattern
     */
    public static function delKeys($predis, $pattern)
    {
        $keys = $predis->keys($pattern);
        foreach ($keys as $key) {
            $k = str_replace(\Sistema::$sigla . ':', '', $key);
            $predis->del($k);
        }
    }
}
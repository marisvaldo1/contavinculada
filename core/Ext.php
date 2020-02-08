<?php

class Ext
{
    public static $bootstrapEnable;
    /**
     * @var bool
     */
    public static $bootstrapCdn;

    public static function ini()
    {
        $conf = config('ext');
        self::$bootstrapEnable = isset($conf['bootstrap']['enable']) ? $conf['bootstrap']['enable'] : false;
        self::$bootstrapCdn = isset($conf['bootstrap']['cdn']) ? $conf['bootstrap']['cdn'] : false;
    }
}
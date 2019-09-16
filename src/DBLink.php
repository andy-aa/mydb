<?php


namespace TexLab\LightDB;


use mysqli;

class DBLink
{
    private static $instances = [];
    private const DEFAULT_OPTIONS = [
        'host' => null,
        'username' => null,
        'password' => null,
        'dbname' => null,
        'port' => null,
        'socket' => null
    ];

    private static function new(array $options)
    {
        $opt = array_merge(self::DEFAULT_OPTIONS, $options);

        return new mysqli(
            $opt['host'],
            $opt['username'],
            $opt['password'],
            $opt['dbname'],
            $opt['port'],
            $opt['socket']
        );
    }

    public static function Link(array $options)
    {
        return self::$instances[$key = serialize($options)] ?? self::$instances[$key] = self::new($options);
    }


}
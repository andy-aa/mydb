<?php

namespace TexLab\LightDB;

use mysqli;

class DB
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
        return new mysqli(
            $options['host'],
            $options['username'],
            $options['password'],
            $options['dbname'],
            $options['port'],
            $options['socket']
        );
    }

    public static function Link(array $options)
    {
        return self::$instances[$key = serialize($options)] ?? self::$instances[$key] = self::new(
            array_merge(
                self::DEFAULT_OPTIONS,
                $options
            )
            );
    }

}
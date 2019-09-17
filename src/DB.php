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

    private static function new(array $options): mysqli
    {
        $mysqli = @new mysqli(
            $options['host'],
            $options['username'],
            $options['password'],
            $options['dbname'],
            $options['port'],
            $options['socket']
        );

        if ($mysqli->connect_error) {
            static::errorHandler(['connect_error' => $mysqli->connect_error]);
        }

        return $mysqli;

    }

    public static function errorHandler(array $error)
    {
        die("MySql connect error: $error[connect_error]");
    }

    public static function Link(array $options): mysqli
    {
        return static::$instances[$key = serialize($options)] ?? static::$instances[$key] = static::new(
                array_merge(
                    static::DEFAULT_OPTIONS,
                    $options
                )
            );
    }

}
<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;

/**
 * Class DB
 *
 * The class implements a singleton pattern to
 * create a new connection to the MySql server.
 *
 * @package TexLab\MyDB
 */
class DB implements DBInterface
{
    /**
     * @var mysqli[]
     */
    private static $instances = [];

    private const DEFAULT_OPTIONS = [
        'host' => null,
        'username' => null,
        'password' => null,
        'dbname' => null,
        'port' => null,
        'socket' => null
    ];

    /**
     * @param array<string, mixed> $options
     * @return mysqli
     * @throws Exception
     */
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
            static::errorHandler([
                'connect_error' => $mysqli->connect_error
            ]);
        }

        return $mysqli;
    }

    /**
     * @param string[] $error
     * @return void
     * @throws Exception
     */
    public static function errorHandler(array $error): void
    {
        throw new Exception(
            "MySql connect error:" . $error['connect_error']
        );
    }

    /**
     * @param array<string, mixed> $options
     * @return mysqli
     * @throws Exception
     */
    public static function link(array $options): mysqli
    {
        return static::$instances[$key = serialize($options)] ?? static::$instances[$key] = static::new(
            array_merge(
                static::DEFAULT_OPTIONS,
                $options
            )
        );
    }
}

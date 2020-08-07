<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;

/**
 * Class DB
 *
 * Create a new connection to the MySql server.
 *
 * @package TexLab\MyDB
 */
class DB implements DBInterface
{
    /**
     * @var mysqli[]
     */
    private static $instances = [];

    /**
     * @var array<string, mixed>
     */
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
     * @param callable $errorHandler
     * @return mysqli
     */
    private static function new(array $options, callable $errorHandler): mysqli
    {
        $mysqli = @new mysqli(
            $options['host'],
            $options['username'],
            $options['password'],
            $options['dbname'],
            $options['port'],
            $options['socket']
        );

        if ($mysqli->connect_errno) {
            $errorHandler($mysqli);
        }

        return $mysqli;
    }

    /**
     * @param array<string, mixed> $options
     * @param callable|null $errorHandler
     * @return mysqli
     */
    public static function link(array $options, callable $errorHandler = null): mysqli
    {
        $userErrorHandler = is_callable($errorHandler) ? $errorHandler : function (mysqli $mysqli): void {
            throw new Exception("MySql connect error : $mysqli->connect_error", $mysqli->connect_errno);
        };

        $key = serialize($options);

        if (empty(static::$instances[$key])) {
            static::$instances[$key] = static::new(
                array_merge(static::DEFAULT_OPTIONS, $options),
                $userErrorHandler
            );
        }

        return static::$instances[$key];
    }
}

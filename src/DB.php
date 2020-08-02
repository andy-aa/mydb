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
     * @var callable|null
     */
    private static $errorHandler = null;

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

        if ($mysqli->connect_errno) {
            static::errorHandler([
                'connect_error' => $mysqli->connect_error,
                'connect_errno' => $mysqli->connect_errno
            ]);
        }

        return $mysqli;
    }

    /**
     * @param mixed[] $error
     * @return void
     */
    protected static function errorHandler(array $error)
    {
        if (is_callable(static::$errorHandler)) {
            (static::$errorHandler)($error);
        }
    }

    /**
     * @param array<string, mixed> $options
     * @param callable|null $errorHandler
     * @return mysqli
     */
    public static function link(array $options, callable $errorHandler = null): mysqli
    {
        if (is_callable($errorHandler)) {
            static::$errorHandler = $errorHandler;
        } else {
            /**
             * @param mixed[] $error
             */
            static::$errorHandler = function (array $error): void {
                throw new Exception("MySql connect error : $error[connect_error]", $error['connect_errno']);
            };
        }

        $key = serialize($options);

        if (empty(static::$instances[$key])) {
            static::$instances[$key] = static::new(
                array_merge(static::DEFAULT_OPTIONS, $options)
            );
        }

        return static::$instances[$key];

//        return static::$instances[$key] ?? static::$instances[$key] = static::new(
//                array_merge(static::DEFAULT_OPTIONS, $options)
//            );
    }
}

<?php

namespace TexLab\LightDB;

use mysqli;
use mysqli_sql_exception;

mysqli_report(MYSQLI_REPORT_STRICT);

class Database
{

    private static $instance = null;

    static public function toUtf($string)
    {
        // If it's not already UTF-8, convert to it
        if (mb_detect_encoding($string, 'utf-8', true) === false) {
            $string = mb_convert_encoding($string, 'utf-8', 'windows-1251');
        }

        return $string;
    }

    static public function Link()
    {
        try {
            return self::$instance ?? self::$instance = @new mysqli(
                    Conf::MYSQL_HOST,
                    Conf::MYSQL_USER,
                    Conf::MYSQL_PASS,
                    Conf::MYSQL_DB
                );
        } catch (mysqli_sql_exception $e) {
            echo self::toUtf($e->getMessage());
        }
    }
}

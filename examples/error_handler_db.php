<?php

require '../vendor/autoload.php';

use TexLab\MyDB\DB;

$link = DB::link(
    [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '123',
        'dbname' => 'unknown_database'
    ],
    function ($mysqli) {
        //put your error handling code here
        var_dump($mysqli->connect_error);
    }
);

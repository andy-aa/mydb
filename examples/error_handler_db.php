<?php

require '../vendor/autoload.php';

use TexLab\MyDB\DB;

$link = DB::link(
    [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'fake_pass',
        'dbname' => 'unknown_database'
    ],
    //error handler for database connection
    function ($mysqli) {
        //put your error handling code here
        var_dump($mysqli->connect_error);
    }
);

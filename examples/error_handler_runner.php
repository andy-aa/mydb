<?php

require '../vendor/autoload.php';

use TexLab\MyDB\DB;
use TexLab\MyDB\Runner;

$runner = new Runner(
    DB::link(
        [
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'root',
            'dbname' => 'test_db'
        ]
    )
);

$runner->setErrorHandler(
    function ($mysqli, $sql) {
        //put your error handling code here
        print_r([$mysqli->error, $mysqli->errno, $sql]);
    }
);

$runner->runSQL("SELECT * FROM unknown_table");

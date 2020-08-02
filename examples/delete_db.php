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
        ]
    )
);

$runner->runSQL("DROP DATABASE `test_db`;");

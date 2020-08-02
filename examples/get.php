<?php

require '../vendor/autoload.php';

use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;

$link = DB::link([
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'dbname' => 'test_db'
]);

$table1 = new DbEntity('table1', $link);

print_r($table1->get());


<?php

//require '../../vendor/autoload.php';

use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;


$link = DB::link([
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'dbname' => 'mydb'
]);

$table1 = new DbEntity('table1', $link);

$table1->add([
    'name' => "O'Conner",
    'description' => 'Director'
]);

print_r($table1->get());

//$table1->runSQL("USE `mydb`; 2");


//$table1->runSQL("CREATE DATABASE IF NOT EXISTS `mydb`;");
//$table1->runSQL("USE `mydb`;");
//
//
//
//
//$table1->runSQL(<<<SQL
//CREATE TABLE IF NOT EXISTS `table1` (
//  `id` int(11) NOT NULL AUTO_INCREMENT,
//  `name` varchar(50) NOT NULL,
//  `description` varchar(200) NOT NULL,
//  PRIMARY KEY (`id`)
//) ENGINE=InnoDB DEFAULT CHARSET=utf8;
//SQL
//);





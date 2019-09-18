<?php

require '../../vendor/autoload.php';

use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;


$link = DB::Link([
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'mydb'
]);

$table1 = new DbEntity('table1', $link);

//$table1->add([
//    'name' => 'Peter',
//    'description' => 'Director'
//]);

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





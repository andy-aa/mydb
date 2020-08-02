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

$script = <<<SQL
CREATE DATABASE IF NOT EXISTS `test_db` DEFAULT CHARACTER SET utf16 COLLATE utf16_bin;

USE `test_db`;

CREATE TABLE IF NOT EXISTS `table1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `table1` (`name`, `description`) VALUES ('Viktor', 'manager');
INSERT INTO `table1` (`name`, `description`) VALUES ('Peter', 'director');

SQL;

$sql = array_filter(array_map('trim', explode(";", $script)));

foreach ($sql as $value) {
    $runner->runSQL($value);
}

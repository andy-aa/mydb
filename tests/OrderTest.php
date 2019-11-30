<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;
use TexLab\MyDB\Runner;


class OrderTest extends TestCase
{
    protected $table;

    protected function createEnvironment(): void
    {
        $link = DB::Link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass']
        ]);

        $runner = new Runner($link);

        $runner->runSQL("CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db_order]`;");
        $runner->runSQL("USE `$GLOBALS[mysql_db_order]`;");


        $runner->runSQL(
            <<<SQL
CREATE TABLE IF NOT EXISTS `$GLOBALS[mysql_test_table]` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL
        );

        $link = DB::Link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass'],
            'dbname' => $GLOBALS['mysql_db_order']
        ]);

        $this->table = new DbEntity(
            $GLOBALS['mysql_test_table'],
            $link
        );

        $this->table->add([
            'name' => 'Max',
            'description' => 'Manager'
        ]);

        $this->table->add([
            'name' => 'Alex',
            'description' => 'Manager'
        ]);

        $this->table->add([
            'name' => 'Peter',
            'description' => 'Manager'
        ]);
    }

    function testCRUD()

    {

        $this->createEnvironment();

        $this->assertEquals(
            [
                0 => [
                    'id' => 3,
                    'name' => 'Peter',
                    'description' => 'Manager'
                ],

                1 => [
                    'id' => 1,
                    'name' => 'Max',
                    'description' => 'Manager'
                ]
            ],
            $this->table->setPageSize(2)->setOrderBy('name DESC')->getPage(1)
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => 2,
                    'name' => 'Alex',
                    'description' => 'Manager'
                ],

                1 => [
                    'id' => 1,
                    'name' => 'Max',
                    'description' => 'Manager'
                ]
            ],
            $this->table->setPageSize(2)->setOrderBy('name')->getPage(1)
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => 3,
                    'name' => 'Peter',
                    'description' => 'Manager'
                ]
            ],
            $this->table->setPageSize(2)->setOrderBy('name')->getPage(2)
        );


        $this->destroyEnvironment();
    }

    protected function destroyEnvironment(): void
    {
        $this->table->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db_order]`;");
    }
}

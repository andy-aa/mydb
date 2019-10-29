<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;
use TexLab\MyDB\Runner;


class PropertiesTest extends TestCase
{
    protected $table;

    protected function setUp(): void
    {
        $link = DB::Link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass']
        ]);

        $runner = new Runner($link);

        $runner->runSQL("CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db]`;");
        $runner->runSQL("USE `$GLOBALS[mysql_db]`;");


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

        $this->table = new DbEntity(
            $GLOBALS['mysql_test_table'],
            $link
        );
    }

    /**
     * @covers DbEntity::getColumnsTypes
     */
    function testCRUD()
    {
        $this->assertEquals(
            [
                'id' => 'int(11)',
                'name' => 'varchar(50)',
                'description' => 'varchar(200)'
            ],
            $this->table->getColumnsTypes()
        );
    }

    protected function tearDown(): void
    {
        $this->table->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db]`;");
    }
}

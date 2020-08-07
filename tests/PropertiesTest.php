<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;
use TexLab\MyDB\Runner;

class PropertiesTest extends TestCase
{
    /**
     * @var DbEntity
     */
    protected $table;

    protected function setUp(): void
    {
        $link = DB::link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass']
        ]);

        $this->table = new DbEntity(
            $GLOBALS['mysql_test_table'],
            $link
        );

        $this->table->runScript(<<<SQL
CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db]`;
USE `$GLOBALS[mysql_db]`;

CREATE TABLE IF NOT EXISTS `$GLOBALS[mysql_test_table]` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '№',
  `name` varchar(50) NOT NULL COMMENT 'Name',
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `$GLOBALS[mysql_test_table]` (`name`, `description`) 
VALUES ('Alex', 'Manager');
SQL
        );
    }


    public function testProperties(): void
    {
        $this->assertEquals(
            [
                'id' => 'int',
                'name' => 'varchar',
                'description' => 'varchar'
            ],
            $this->table->getColumnsTypes()
        );

        $this->assertEquals(
            [
                'id' => '11',
                'name' => '50',
                'description' => '200'
            ],
            $this->table->getColumnsTypesLength()
        );

        $this->assertEquals(
            [1 => 'Alex'],
            $this->table->getColumn('name')
        );

        $this->assertEquals(
            1,
            $this->table->rowCount()
        );

        $this->assertEquals(
            [
                'id' => [
                    'Field' => 'id',
                    'Type' => 'int(11)',
                    'Collation' => null,
                    'Null' => 'NO',
                    'Key' => 'PRI',
                    'Default' => null,
                    'Extra' => 'auto_increment',
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => '№'
                ],
                'name' => [
                    'Field' => 'name',
                    'Type' => 'varchar(50)',
                    'Collation' => 'utf8_general_ci',
                    'Null' => 'NO',
                    'Key' => '',
                    'Default' => null,
                    'Extra' => '',
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => 'Name'
                ],
                'description' => [
                    'Field' => 'description',
                    'Type' => 'varchar(200)',
                    'Collation' => 'utf8_general_ci',
                    'Null' => 'NO',
                    'Key' => '',
                    'Default' => null,
                    'Extra' => '',
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => '']
            ],
            $this->table->getColumnsProperties()
        );

        $this->assertEquals(
            [
                'name' => [
                    'Field' => 'name',
                    'Type' => 'varchar(50)',
                    'Collation' => 'utf8_general_ci',
                    'Null' => 'NO',
                    'Key' => '',
                    'Default' => null,
                    'Extra' => '',
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => 'Name'
                ],
                'description' => [
                    'Field' => 'description',
                    'Type' => 'varchar(200)',
                    'Collation' => 'utf8_general_ci',
                    'Null' => 'NO',
                    'Key' => '',
                    'Default' => null,
                    'Extra' => '',
                    'Privileges' => 'select,insert,update,references',
                    'Comment' => '']
            ],
            $this->table->getColumnsPropertiesWithoutId()
        );

        $this->assertEquals(
            [
                0 => 'id',
                1 => 'name',
                2 => 'description'
            ],
            $this->table->getColumnsNames()
        );
        $this->assertEquals(
            [
                'id' => '№',
                'name' => 'Name',
                'description' => ''
            ],
            $this->table->getColumnsComments()
        );
    }

    protected function tearDown(): void
    {
        $this->table->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db]`;");
    }
}

<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;


class CRUDTest extends TestCase
{
    protected $table;

    protected function setUp(): void
    {
        $link = DB::Link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass']
        ]);

        $this->table = new DbEntity('', $link);

        $this->table->runSQL("CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db]`;");
        $this->table->runSQL("USE `$GLOBALS[mysql_db]`;");


        $this->table->runSQL(<<<SQL
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
            'dbname' => $GLOBALS['mysql_db']
        ]);

        $this->table = new DbEntity(
            $GLOBALS['mysql_test_table'],
            $link
        );

    }

    /**
     * @covers DbEntity::get
     * @covers DbEntity::add
     * @covers DbEntity::edit
     * @covers DbEntity::del
     */
    function testCRUD()
    {
        $this->assertIsArray(
            $this->table->get()
        );

        $this->assertIsInt(
            $id = $this->table->add([
                'name' => 'Alex',
                'description' => 'Manager'
            ])
        );

        $this->assertEquals(1, $id);

        $this->assertEquals(
            ['name' => 'Alex', 'description' => 'Manager'],
            $this->table->get($id)
        );

        $this->assertEquals(
            1,
            $this->table->edit(
                $id,
                ['name' => 'Peter', 'description' => 'Director']
            )
        );

        $this->assertEquals(
            ['name' => 'Peter', 'description' => 'Director'],
            $this->table->get($id)
        );

        $this->assertEquals(
            1,
            $this->table->del($id)
        );

        $this->assertEquals(
            [],
            $this->table->get($id)
        );

    }

    protected function tearDown(): void
    {
        $this->table->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db]`;");
    }

}
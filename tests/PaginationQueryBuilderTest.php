<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\CRUDInterface;
use TexLab\MyDB\DB;
use TexLab\MyDB\DbEntity;
use TexLab\MyDB\Runner;

class PaginationQueryBuilderTest extends TestCase
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

        $runner = new Runner($link);

        $runner->runSQL("CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db]`;");
        $runner->runSQL("USE `$GLOBALS[mysql_db]`;");


        $runner->runSQL(<<<SQL
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

        $this->table->add([
            'name' => 'Alex',
            'description' => 'Manager'
        ]);

        $this->table->add([
            'name' => 'Peter',
            'description' => 'Manager'
        ]);

        $this->table->add([
            'name' => 'Max',
            'description' => 'Worker'
        ]);

        $this->table->add([
            'name' => 'Victor',
            'description' => 'Director'
        ]);
    }


    public function testPagination(): void
    {
        $this->assertIsObject(
            $this->table->setPageSize(3)
        );

        $this->assertEquals(
            1,
            $this->table->setPageSize(5)->pageCount()
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => '1',
                    'name' => 'Alex',
                    'description' => 'Manager'
                ],
                1 => [
                    'id' => '2',
                    'name' => 'Peter',
                    'description' => 'Manager'
                ]
            ],
            $this->table->setPageSize(2)->getPage(1)
        );
    }

    public function testQueryBuilder(): void
    {

        $this->assertEquals(
            [
                0 => ['id' => '3'],
                1 => ['id' => '2']
            ],
            $this
                ->table
                ->reset()
                ->setSelect('id')
                ->setFrom($GLOBALS['mysql_test_table'])
                ->setWhere('id >= 2')
                ->addWhere('id <= 3')
                ->setOrderBy('id DESC')
                ->get()
        );

        $this->assertEquals(
            [
                0 => ['id' => '2'],
                1 => ['id' => '3']
            ],
            $this
                ->table
                ->reset()
                ->setSelect('id')
                ->setLimit('1, 2')
                ->get()
        );

        $this->assertEquals(
            [
                0 => ['c' => '2']
            ],
            $this
                ->table
                ->reset()
                ->setSelect('count(*) as c')
                ->setGroupBy('description')
                ->setHaving('count(*) >= 2')
                ->get()
        );
    }

    protected function tearDown(): void
    {
        $this->table->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db]`;");
    }
}

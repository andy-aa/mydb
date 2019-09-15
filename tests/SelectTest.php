<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\LightDB\DbEntity;

class SelectTest extends TestCase
{
    protected $table;

    protected function setUp(): void
    {
        $this->table = new DbEntity($GLOBALS['mysql_test_table'],
            new mysqli(
                $GLOBALS['mysql_host'],
                $GLOBALS['mysql_user'],
                $GLOBALS['mysql_pass'],
                $GLOBALS['mysql_db']
            )
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

}
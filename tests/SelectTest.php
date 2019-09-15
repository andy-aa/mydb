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
     */
    function testSelect()
    {
        $this->assertIsArray(
            $this->table->get(),
            'Is not Array'
        );
    }

}
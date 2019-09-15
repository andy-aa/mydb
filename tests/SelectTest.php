<?php
/**
 * from phpunit.xml
 * @var string $mysql_db
 * @var string $mysql_host
 * @var string $mysql_user
 * @var string $mysql_pass
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\LightDB\DbEntity;

class SelectTest extends TestCase
{

    function testSelect()
    {
        global $mysql_db,
               $mysql_host,
               $mysql_user,
               $mysql_pass,
               $mysql_test_table;

        $table = new DbEntity($mysql_test_table,
            new mysqli(
                $mysql_host,
                $mysql_user,
                $mysql_pass,
                $mysql_db
            )
        );

        $this->assertIsArray(
            $table->get(),
            'Is not Object'
        );
    }

}
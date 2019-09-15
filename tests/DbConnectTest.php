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

class DbConnectTest extends TestCase
{

    function testConnect()
    {
        global $mysql_db,
               $mysql_host,
               $mysql_user,
               $mysql_pass;

        $this->assertIsObject(new DbEntity('no_matter',
            new mysqli(
                $mysql_host,
                $mysql_user,
                $mysql_pass,
                $mysql_db
            )
        ),
            'Is not Object'
        );

    }

}
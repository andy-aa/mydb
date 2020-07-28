<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\Runner;


class ExceptionTest extends TestCase
{
    protected $table;

    protected function setUp(): void
    {
        $this->table = new Runner(
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass']
            ])
        );
    }


    function testSelect()
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(1064);
        $this->expectWarningMessageMatches('/^MySql query error:/');

        $this->table->runSQL("SELECT * FROM 123;");
    }

    function testDbConnect()
    {
        $this->expectException("Exception");
        $this->expectWarningMessageMatches('/Access denied/');

        DB::link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => '---------------------------------'
        ]);
    }

}
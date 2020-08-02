<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\Runner;

class ExceptionTest extends TestCase
{
    /**
     * @var Runner
     */
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

    public function testSelect(): void
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(1064);
        $this->expectWarningMessageMatches('/^MySql query error :/');

        $this->table->runSQL("SELECT * FROM 123;");
    }

    public function testErrorHandler(): void
    {
        $this->assertSame(
            [],
            $this
                ->table
                ->setErrorHandler(function () {
                })
                ->runSQL("SELECT * FROM 123;")
        );
    }

    public function testDbConnect(): void
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(1045);
        $this->expectWarningMessageMatches('/Access denied/');

        DB::link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => '---------------------------------'
        ]);
    }
}

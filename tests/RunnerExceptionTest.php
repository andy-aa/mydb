<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\Runner;

class RunnerExceptionTest extends TestCase
{
    /**
     * @var mysqli
     */
    protected $mysqli;

    protected function setUp(): void
    {
        $this->mysqli = DB::link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass']
        ]);
    }

    public function testSelect(): void
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(1064);
        $this->expectWarningMessageMatches('/^MySql query error :/');

        (new Runner($this->mysqli))->runSQL("SELECT * FROM 123;");
    }

    public function testErrorHandler(): void
    {
        $this->assertSame(
            [],
            (new Runner($this->mysqli))
                ->setErrorHandler(function () {
                })
                ->runSQL("SELECT * FROM 456;")
        );
    }

//    public function testEmptyQuery(): void
//    {
//        $this->assertSame(
//            [],
//            (new Runner($this->mysqli))->runSQL('')
//        );
//    }
}

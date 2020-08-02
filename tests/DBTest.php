<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TexLab\MyDB\DB;
use TexLab\MyDB\Runner;

class DBTest extends TestCase
{
    /**
     * @var Runner
     */
    protected $runner;

    protected function setUp(): void
    {
        $link = DB::link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => $GLOBALS['mysql_pass']
        ]);

        $this->runner = new Runner($link);

        $this->runner->runSQL("CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db]`;");
        $this->runner->runSQL("CREATE DATABASE IF NOT EXISTS `$GLOBALS[mysql_db_2]`;");
    }

    public function testDB(): void
    {
        $this->assertSame(
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass'],
                'dbname' => $GLOBALS['mysql_db']
            ]),
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass'],
                'dbname' => $GLOBALS['mysql_db']
            ])
        );

        $this->assertNotSame(
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass'],
                'dbname' => $GLOBALS['mysql_db']
            ]),
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass'],
            ])
        );

        $this->assertNotSame(
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass'],
                'dbname' => $GLOBALS['mysql_db']
            ]),
            DB::link([
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => $GLOBALS['mysql_pass'],
                'dbname' => $GLOBALS['mysql_db_2']
            ])
        );

        $this->assertIsObject(
            DB::link(
                [
                    'host' => $GLOBALS['mysql_host'],
                    'username' => $GLOBALS['mysql_user'],
                    'password' => $GLOBALS['mysql_pass'],
                    'dbname' => $GLOBALS['mysql_db']
                ],
                function ($mysqli) {
                }
            )
        );
    }


    public function testTrowErrorHandler(): void
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(1045);
        $this->expectWarningMessageMatches('/Custom error/');

        DB::link(
            [
                'host' => $GLOBALS['mysql_host'],
                'username' => $GLOBALS['mysql_user'],
                'password' => '---------------------------------'
            ],
            function ($mysqli) {
                throw new Exception("Custom error : $mysqli->connect_error", $mysqli->connect_errno);
            }
        );
    }

    public function testTrowExceptionDbConnect(): void
    {
        $this->expectException("Exception");
        $this->expectExceptionCode(1045);
        $this->expectWarningMessageMatches('/Access denied/');

        DB::link([
            'host' => $GLOBALS['mysql_host'],
            'username' => $GLOBALS['mysql_user'],
            'password' => 'fake_password'
        ]);
    }

    protected function tearDown(): void
    {
        $this->runner->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db]`;");
        $this->runner->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db_2]`;");
    }
}

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
    }

    protected function tearDown(): void
    {
        $this->runner->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db]`;");
        $this->runner->runSQL("DROP DATABASE IF EXISTS `$GLOBALS[mysql_db_2]`;");
    }
}

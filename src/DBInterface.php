<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;

interface DBInterface
{
    /**
     * @param array<string, mixed> $options
     * @param callable|null $errorHandler
     * @return mysqli
     * @throws Exception
     */
    public static function link(array $options, callable $errorHandler = null): mysqli;
}

<?php

namespace TexLab\MyDB;

use Exception;

interface RunnerInterface
{
    /**
     * @param string $sql
     * @return string[][]
     */
    public function runSQL(string $sql): array;
}

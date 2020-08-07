<?php

namespace TexLab\MyDB;

use Exception;

interface RunnerInterface
{
    /**
     * @param string $sql
     * @return mixed[][]
     */
    public function runSQL(string $sql): array;

    /**
     * @param string $script
     */
    public function runScript(string $script): void;
}

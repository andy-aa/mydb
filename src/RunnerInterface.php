<?php

namespace TexLab\MyDB;

interface RunnerInterface
{
    /**
     * @param string $sql
     * @return string[][]
     * @throws Exception
     */
    public function runSQL(string $sql): array;
}

<?php

namespace TexLab\MyDB;

interface RunnerInterface
{
    public function runSQL(string $sql): array;
}
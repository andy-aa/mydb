<?php

namespace TexLab\MyDB;

use Exception;

interface CRUDInterface
{
    /**
     * @param array<string, string> $data
     * @return int
     */
    public function add(array $data): int;

    /**
     * @param array<string, mixed> $conditions
     * @return string[][]
     */
    public function get(array $conditions = []): array;

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, string> $data
     * @return int
     */
    public function edit(array $conditions, array $data): int;

    /**
     * @param array<string, mixed> $conditions
     * @return int
     */
    public function del(array $conditions): int;
}

<?php

namespace TexLab\MyDB;

use Exception;

interface CRUDInterface
{
    /**
     * @param array<string, string> $data
     * @return int
     * @throws Exception
     */
    public function add(array $data): int;

    /**
     * @param array<string, string> $conditions
     * @return string[][]
     * @throws Exception
     */
    public function get(array $conditions = []): array;

    /**
     * @param array<string, string> $conditions
     * @param array<string, string> $data
     * @return int
     * @throws Exception
     */
    public function edit(array $conditions, array $data): int;

    /**
     * @param array<string, string> $conditions
     * @return int
     * @throws Exception
     */
    public function del(array $conditions): int;
}

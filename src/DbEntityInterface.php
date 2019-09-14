<?php

namespace TexLab\LightDB;

interface DbEntityInterface
{
    public function runSQL(string $sql): array;

    public function add(array $data): int;

    public function get(int $id = null): array;

    public function edit(int $id, array $data): int;

    public function del(int $id): int;

    public function rowCount(): ?int;
}
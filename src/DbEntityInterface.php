<?php

namespace TexLab\LightDB;

interface DbEntityInterface
{
    public function getSQL(): string;

    public function get(): array;

    public function getRowById(int $id): array;

    public function runSQL(string $sql): array;

    public function add(array $data): int;

    public function del(int $id): int;

    public function edit(int $id, array $data): int;

    public function rowCount(): ?int;
}
<?php

namespace TexLab\LightDB;

interface CRUDInterface
{
    public function add(array $data): int;

    public function get(int $id = null): array;

    public function edit(int $id, array $data): int;

    public function del(int $id): int;
}
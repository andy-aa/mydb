<?php

namespace TexLab\MyDB;

interface CRUDInterface
{
    public function add(array $data): int;

    public function get(array $conditions = []): array;

    public function edit(array $conditions, array $data): int;

    public function del(array $conditions): int;
}
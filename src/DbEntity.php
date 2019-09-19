<?php

namespace TexLab\MyDB;

class DbEntity extends Table
{
    use PaginationTrait,
        QueryBuilderTrait,
        PropertiesTrait;
}
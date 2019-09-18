<?php

namespace TexLab\MyDB;

class DbEntity extends CRUD
{
    use PaginationTrait,
        QueryBuilderTrait,
        PropertiesTrait;
}
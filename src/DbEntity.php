<?php

namespace TexLab\MyDB;

class DbEntity extends Table
{
    use PaginationTrait;
    use QueryBuilderTrait;
    use PropertiesTrait;
}

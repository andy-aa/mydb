<?php

namespace TexLab\MyDB;

use mysqli;

interface DBInterface
{
    public static function errorHandler(array $error);

    public static function Link(array $options): mysqli;
}
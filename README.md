# lightdb

```php
<?php

require 'vendor/autoload.php';

use TexLab\LightDB\DbEntity;

$table1 = new DbEntity('table1', new \mysqli('localhost', 'root', '', 'mydb'));

print_r($table1->get());

```
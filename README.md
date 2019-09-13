# lightdb

### Install via composer

```
$ composer require texlab/lightdb
```


```php
<?php

require 'vendor/autoload.php';

use TexLab\LightDB\DbEntity;

$table1 = new DbEntity('table1', new \mysqli('localhost', 'root', '', 'mydb'));

echo json_encode($table1->get());

```
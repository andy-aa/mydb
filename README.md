# lightdb

## Install via composer

```
$ composer require texlab/lightdb
```

## Database for examples
```sql
CREATE DATABASE IF NOT EXISTS `mydb`;

USE `mydb`;

CREATE TABLE IF NOT EXISTS `table1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## Usage example

```php
<?php

require 'vendor/autoload.php';

use TexLab\LightDB\DbEntity;

$table1 = new DbEntity(
    'table1',
    new \mysqli(
        'localhost',
        'root',
        '',
        'mydb'
    )
);

echo json_encode($table1->get());
```

## CRUD
Adding data:
```php
$table1->add([
    'name' => 'Peter',
    'description' => 'Director'
]);
```

Reading data:
```php
$table1->get();
```

Updating data:
```php
$table1->edit(2, [
    'name' => 'Alex',
    'description' => 'Manager'
]);
```

Data deletion:
```php
$table1->del(1);
```
## Custom queries

```php
echo json_encode($table1->runSQL("SELECT * FROM table1"));
```

```php
echo json_encode(
    $table1
        ->reset()
        ->setSelect('id, name')
        ->setWhere("name like 'A%'")
        ->get()
);
```

```php
$table1
    ->reset()
    ->setSelect('name, description')
    ->setWhere("description = 'Manager'")
    ->setOrderBy('name');

echo json_encode(
    $table1->get()
);

$table1->setSelect('*');

echo json_encode(
    $table1->get()
);
```
## Error handling

```php
<?php

require 'vendor/autoload.php';

use TexLab\LightDB\DbEntity;

class DBTable extends DbEntity
{
    protected function errorHandler(array $error)
    {
        //put your error handling code here
        print_r($error);
    }
}

$table1 = new DBTable(
    'table1',
    new \mysqli(
        'localhost',
        'root',
        '',
        'mydb'
    )
);

$table1->runSQL("SELECT * FROM unknown_table");
```
Result:
```
Array
(
    [errno] => 1146
    [error] => Table 'mydb.unknown_table' doesn't exist
    [sql] => SELECT * FROM unknown_table
)
```
## Pagination

```php
echo $table1->setPageSize(2)->pageCount();
```

```php
echo json_encode($table1->setPageSize(2)->getPage(1));
```





[![Source Code](http://img.shields.io/badge/source-andy--aa/mydb-blue.svg?style=flat-square)](https://github.com/andy-aa/mydb)
[![Build Status](https://travis-ci.com/andy-aa/mydb.svg?branch=master)](https://travis-ci.com/andy-aa/mydb)
[![License: MIT](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](https://opensource.org/licenses/MIT)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)
[![Packagist](https://img.shields.io/packagist/vpre/texlab/mydb.svg?style=flat-square)](https://packagist.org/packages/texlab/mydb)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat-square)](https://phpstan.org/)
[![Psalm](https://img.shields.io/badge/Psalm-Level%202-brightgreen.svg?style=flat-square)](https://psalm.dev/) 
[![Coverage Status](https://img.shields.io/coveralls/github/andy-aa/mydb/master.svg?style=flat-square)](https://coveralls.io/github/andy-aa/mydb?branch=master)

# MyDB

- [Install](#install-via-composer)
- [Class diagram](#class-diagram)
- [Database for examples](#database-for-examples)
- [Usage example](#usage-example)
- [CRUD](#crud)
    - [Adding data](#adding-data)
    - [Reading data](#reading-data)
    - [Updating data](#updating-data)
    - [Data deletion](#data-deletion)
- [Query builder](#query-builder)
- [Error handling](#error-handling)
- [Pagination](#pagination)



## Install via composer

Command line
```
composer require texlab/mydb
```
Example **composer.json** file
```
{
    "require": {
        "texlab/mydb": "^0.0.5"
    }
}
```

## Class diagram
![Class diagram](https://user-images.githubusercontent.com/46691193/73173964-abc3a380-4117-11ea-99b1-9424892a2fcd.png)
![Class diagram](https://user-images.githubusercontent.com/46691193/73174260-5b991100-4118-11ea-8fff-eab60f969af9.png)

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

![Database for examples](https://user-images.githubusercontent.com/46691193/73180546-8b4f1580-4126-11ea-85c8-e75731668e7a.png)

## Usage example

```php
<?php

require 'vendor/autoload.php';

use TexLab\MyDB\DbEntity;
use TexLab\MyDB\DB;

$link = DB::link([
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'mydb'
]);

$table1 = new DbEntity('table1', $link);

echo json_encode($table1->get());
```

## CRUD
### Adding data:
```php
$table1->add([
    'name' => 'Peter',
    'description' => 'Director'
]);
```

### Reading data:
```php
$table1->get();
```
or a row with the given id

```php
$table1->get(['id' => 3]);
```

### Updating data:
```php
$table1->edit(['id' => 2], [
    'name' => 'Alex',
    'description' => 'Manager'
]);
```

### Data deletion:
```php
$table1->del(['id' => 1]);
```
## Custom queries

```php
echo json_encode($table1->runSQL("SELECT * FROM table1"));
```
## Query builder

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

require '../vendor/autoload.php';

use TexLab\MyDB\DB;
use TexLab\MyDB\Runner;

$runner = new Runner(
    DB::link(
        [
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'root',
            'dbname' => 'test_db'
        ]
    )
);

$runner->setErrorHandler(
    function ($mysqli, $sql) {
        //put your error handling code here
        print_r([$mysqli->error, $mysqli->errno, $sql]);
    }
);

$runner->runSQL("SELECT * FROM unknown_table");
```
Result:
```
Array
(
    [0] => Table 'test_db.unknown_table' doesn't exist
    [1] => 1146
    [2] => SELECT * FROM unknown_table
)
```
## Pagination

```php
echo $table1
        ->setPageSize(2)
        ->pageCount();
```

```php
echo json_encode($table1->setPageSize(2)->getPage(1));
```





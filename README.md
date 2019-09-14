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
require 'vendor/autoload.php';

use TexLab\LightDB\DbEntity;

$table1 = new DbEntity('table1', new \mysqli('localhost', 'root', '', 'mydb'));

echo json_encode($table1->get());
```

## CRUD

### adding data
```php
$table1->add([
    'name' => 'Peter',
    'description' => 'Director'
]);
```

### reading data
```php
$table1->get();
```

### updating data
```php
$table1->edit(2, ['description' => 'Manager']);
```

### data deletion
```php
$table1->del(1);
```

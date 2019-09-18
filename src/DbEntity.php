<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;

class DbEntity implements CRUDInterface
{
    use PaginationTrait,
        QueryBuilderTrait,
        PropertiesTrait;

    protected $tableName;
    protected $mysqli;

    protected $primaryKey = 'id';

    protected $queryCustom = [];
    private $queryDefault = [
        'SELECT' => '*',
        'FROM' => '',
        'WHERE' => null,
        'GROUP BY' => null,
        'HAVING' => null,
        'ORDER BY' => null,
        'LIMIT' => null
    ];

    public function __construct(string $tableName, mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->setTableName($tableName);
    }

    public function setTableName(string $tableName): self
    {
        if (!empty($tableName)) {
            $this->queryCustom['FROM'] = $this->tableName = $tableName;
            $this->primaryKey = $this->seekPrimaryKeyName($this->tableName);
        }

        return $this;
    }


    public function runSQL(string $sql): array
    {
        return $this->queryObjectToArray($this->query($sql));
    }

    protected function queryObjectToArray($queryResult): array
    {
        $tableResult = [];

        if (is_object($queryResult)) {
            while ($row = $queryResult->fetch_assoc()) {
                $tableResult[] = $row;
            }
        }

        return $tableResult;
    }

    protected function query(string $sql)
    {
        $queryResult = $this->mysqli->query($sql);

        if ($this->mysqli->errno) {
            $this->errorHandler([
                'errno' => $this->mysqli->errno,
                'error' => $this->mysqli->error,
                'sql' => $sql
            ]);
        }

        return $queryResult;
    }

    protected function errorHandler(array $error)
    {
        throw new Exception("MySql query error: \n" . join("\n", $error));
    }

    protected function getRowById(array $conditions): array
    {
        $bufWHERE = $this->queryCustom['WHERE'];

        $this->queryCustom['WHERE'] .= (empty($bufWHERE) ? '' : 'AND ') . $this->createWhereCondition($conditions);

        $result = array_diff_key(
            (array)($this->runSQL($this->getSQL())[0]),
            [$this->primaryKey => null]
        );

        $this->queryCustom['WHERE'] = $bufWHERE;

        return $result;
    }

    public function get(array $conditions = []): array
    {

        if (empty($conditions)) {
            $result = $this->runSQL($this->getSQL());
        } else {
            $result = $this->getRowById($conditions);
        }

        return $result;

    }

    protected function getSQL(): string
    {
        $sql = '';

        foreach (array_merge($this->queryDefault, $this->queryCustom) as $k => $v) {
            if (!empty($v)) {
                $sql .= "$k $v\n";
            }
        }

        return substr_replace($sql, ';', -1);
    }

    public function add(array $data): int
    {
        $this->query("INSERT INTO $this->tableName (" . implode(', ', array_keys($data)) .
            ") VALUES('" . implode("', '", $data) . "');");

        return $this->mysqli->insert_id;
    }

    private function createWhereCondition(array $conditions): string
    {
        $arrayConditions = [];

        foreach ($conditions as $field => $value) {
            $arrayConditions[] = "$field = '$value'";
        }

        return join(' AND ', $arrayConditions);
    }

    public function del(array $conditions): int
    {
        $this->query("DELETE FROM $this->tableName WHERE " . $this->createWhereCondition($conditions) . ';');

        return $this->mysqli->affected_rows;
    }

    public function edit(array $conditions, array $data): int
    {
        $fields_values = [];
        foreach ($data as $k => $v) {
            $fields_values[] = "$k = '$v'";
        }

        $this->query("UPDATE $this->tableName SET " . implode(", ", $fields_values) .
            " WHERE " . $this->createWhereCondition($conditions) . ';');

        return $this->mysqli->affected_rows;
    }

    public function getPrimaryKey(): ?string
    {
        return $this->primaryKey;
    }

    private function seekPrimaryKeyName(string $tableName): ?string
    {
        return $this->runSQL("SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'")[0]['Column_name'];
    }

}

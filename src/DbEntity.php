<?php

namespace TexLab\LightDB;

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
//        die("MySql query error: \n" . join("\n", $error));
    }

    protected function getRowById(int $id): array
    {
        $bufWHERE = $this->queryCustom['WHERE'];

        $this->queryCustom['WHERE'] = (empty($bufWHERE) ? '' : 'AND ') . "$this->primaryKey = $id";

        $result = array_diff_key(
            (array)($this->runSQL($this->getSQL())[0]),
            [$this->primaryKey => null]
        );

        $this->queryCustom['WHERE'] = $bufWHERE;

        return $result;
    }

    public function get(int $id = null): array
    {

        if (is_null($id)) {
            $result = $this->runSQL($this->getSQL());
        } else {
            $result = $this->getRowById($id);
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

    public function del(int $id): int
    {
        $this->query("DELETE FROM $this->tableName WHERE $this->primaryKey = $id");

        return $this->mysqli->affected_rows;
    }

    public function edit(int $id, array $data): int
    {
        $fields_values = [];
        foreach ($data as $k => $v) {
            $fields_values[] = "$k = '$v'";
        }

        $this->query("UPDATE $this->tableName SET " . implode(", ", $fields_values) . " WHERE $this->primaryKey = $id;");

        return $this->mysqli->affected_rows;
    }

    public function rowCount(): ?int
    {
        return $this->runSQL("SELECT COUNT(*) AS C FROM $this->tableName;")[0]['C'];
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

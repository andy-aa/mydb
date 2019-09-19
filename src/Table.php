<?php

namespace TexLab\MyDB;

use mysqli;

class Table extends Runner implements CRUDInterface
{

    protected $tableName;

    protected $queryCustom = [];
    private const QUERY_DEFAULT = [
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
        parent::__construct($mysqli);
        $this->queryCustom['FROM'] = $this->tableName = $tableName;
    }

    public function get(array $conditions = []): array
    {

        if (empty($conditions)) {

            $result = $this->runSQL($this->getSQL());

        } else {

            $bufWHERE = $this->queryCustom['WHERE'];

            $this->queryCustom['WHERE'] .= (empty($bufWHERE) ? '' : 'AND ') . $this->createWhereCondition($conditions);

            $result = $this->runSQL($this->getSQL());

            $this->queryCustom['WHERE'] = $bufWHERE;

        }

        return $result;

    }

    protected function getSQL(): string
    {
        $sql = '';

        foreach (array_merge(self::QUERY_DEFAULT, $this->queryCustom) as $k => $v) {
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

}

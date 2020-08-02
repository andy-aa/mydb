<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;

class Table extends Runner implements CRUDInterface
{

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var array<string, mixed>
     */
    protected $queryCustom = [];

    /**
     * @var array<string, mixed>
     */
    private const QUERY_DEFAULT = [
        'SELECT' => '*',
        'FROM' => '',
        'WHERE' => null,
        'GROUP BY' => null,
        'HAVING' => null,
        'ORDER BY' => null,
        'LIMIT' => null
    ];

    /**
     * Table constructor.
     * @param string $tableName
     * @param mysqli $mysqli
     */
    public function __construct(string $tableName, mysqli $mysqli)
    {
        parent::__construct($mysqli);
        $this->queryCustom['FROM'] = $this->tableName = $tableName;
    }

    /**
     * @param array<string, mixed> $conditions
     * @return string[][]
     */
    public function get(array $conditions = []): array
    {

        if (empty($conditions)) {
            $result = $this->runSQL($this->getSQL());
        } else {
            $bufWHERE = $this->queryCustom['WHERE'] ?? null;

            $this->queryCustom['WHERE'] =
                (is_null($bufWHERE) ? '' : "$bufWHERE AND ") . $this->createWhereCondition($conditions);

            $result = $this->runSQL($this->getSQL());

            $this->queryCustom['WHERE'] = $bufWHERE;
        }

        return $result;
    }

    /**
     * @return string
     */
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

    /**
     * @param array<string, string> $data
     * @return int
     */
    public function add(array $data): int
    {
        $this->query("INSERT INTO $this->tableName (" . implode(', ', array_keys($data)) .
            ") VALUES('" . implode("', '", $data) . "');");

        return $this->mysqli->insert_id;
    }

    /**
     * @param array<string, string> $conditions
     * @return string
     */
    private function createWhereCondition(array $conditions): string
    {
        $arrayConditions = [];

        foreach ($conditions as $field => $value) {
            $arrayConditions[] = "$field = '$value'";
        }

        return join(' AND ', $arrayConditions);
    }

    /**
     * @param array<string, mixed> $conditions
     * @return int
     */
    public function del(array $conditions): int
    {
        $this->query("DELETE FROM $this->tableName WHERE " . $this->createWhereCondition($conditions) . ';');

        return $this->mysqli->affected_rows;
    }

    /**
     * @param array<string, mixed> $conditions
     * @param array<string, string> $data
     * @return int
     */
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

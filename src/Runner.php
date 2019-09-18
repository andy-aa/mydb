<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;

class Runner implements RunnerInterface
{
    protected $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function runSQL(string $sql): array
    {
        return $this->queryObjectToArray($this->query($sql));
    }

    /**
     * @param \mysqli_result $queryResult
     * @return array
     */
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

}
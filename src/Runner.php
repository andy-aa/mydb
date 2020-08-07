<?php

namespace TexLab\MyDB;

use Exception;
use mysqli;
use mysqli_result;

class Runner implements RunnerInterface
{
    /**
     * @var mysqli
     */
    protected $mysqli;

    /**
     * @var callable
     */
    protected $errorHandler;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;

        /**
         * @param mysqli $mysqli
         * @param string $sql
         */
        $this->errorHandler = function (mysqli $mysqli, string $sql): void {
            throw new Exception(
                "MySql query error : $mysqli->error\nSQL : $sql",
                $mysqli->errno
            );
        };
    }

    /**
     * @param string $sql
     * @return mixed[][]
     */
    public function runSQL(string $sql): array
    {
        return $this->queryObjectToArray($this->query($sql));
    }

    /**
     * @param string $script
     */
    public function runScript(string $script): void
    {
        foreach (array_filter(array_map('trim', explode(";", $script))) as $sql) {
            $this->runSQL($sql);
        }
    }

    /**
     * @param bool|mysqli_result $queryResult
     * @return mixed[][]
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

    /**
     * @param string $sql
     * @return bool|mysqli_result
     */
    protected function query(string $sql)
    {
        $queryResult = $this->mysqli->query($sql);

        if ($this->mysqli->errno) {
            $this->errorHandler($this->mysqli, $sql);
        }

        return $queryResult;
    }

    /**
     * @param mysqli $mysqli
     * @param string $sql
     * @return void
     */
    protected function errorHandler($mysqli, $sql)
    {
        ($this->errorHandler)($mysqli, $sql);
    }

    /**
     * @param callable $errorHandler
     * @return $this
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;
        return $this;
    }
}

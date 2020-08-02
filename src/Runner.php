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
         * @param mixed[] $error
         */
        $this->errorHandler = function (array $error): void {
            throw new Exception("MySql query error: \n" . join("\n", $error), $error['errno']);
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
            $this->errorHandler([
                'error' => $this->mysqli->error,
                'errno' => $this->mysqli->errno,
                'sql' => $sql
            ]);
        }

        return $queryResult;
    }

    /**
     * @param mixed[] $error
     * @return void
     */
    protected function errorHandler(array $error)
    {
        ($this->errorHandler)($error);
    }

    /**
     * @param callable $errorHandler
     * @return Runner
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;
        return $this;
    }
}

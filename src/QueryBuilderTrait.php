<?php

namespace TexLab\MyDB;

trait QueryBuilderTrait
{

//    public function setSQL(string $name, string $value)
//    {
//        if (array_key_exists($name, self::QUERY_DEFAULT)) {
//            $this->queryCustom[$name] = $value;
//        }
//    }

    public function reset()
    {
        $this->queryCustom = ['FROM' => $this->tableName];
        return $this;
    }

    public function setSelect(string $sql)
    {
        $this->queryCustom['SELECT'] = $sql;
        return $this;
    }

    public function setFrom(string $sql)
    {
        $this->queryCustom['FROM'] = $sql;
        return $this;
    }

    public function setWhere(string $sql)
    {
        $this->queryCustom['WHERE'] = $sql;
        return $this;
    }

    public function addWhere(string $sql)
    {
        $this->queryCustom['WHERE'] .= "AND $sql";
        return $this;
    }

    public function setGroupBy(string $sql)
    {
        $this->queryCustom['GROUP BY'] = $sql;
        return $this;
    }

    public function setHaving(string $sql)
    {
        $this->queryCustom['HAVING'] = $sql;
        return $this;
    }

    public function setOrderBy(string $sql)
    {
        $this->queryCustom['ORDER BY'] = $sql;
        return $this;
    }

    public function setLimit(string $sql)
    {
        $this->queryCustom['LIMIT'] = $sql;
        return $this;
    }
}

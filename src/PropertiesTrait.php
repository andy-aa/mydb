<?php

namespace TexLab\LightDB;

trait PropertiesTrait
{
    public function getColumnsNames(): array
    {
        return array_column(
            $this->runSQL("SHOW COLUMNS FROM $this->tableName;"),
            "Field"
        );
    }

    public function getColumn(string $fieldName): array
    {
        return array_column(
            $this->get(),
            $fieldName,
            $this->getPrimaryKey()
        );
    }

    public function getColumnsComments(): array
    {
        return array_column(
            $this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;"),
            "Comment",
            "Field"
        );
    }

    public function getColumnsProperties(): array
    {
        $array = [];
        foreach ($this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;") as $row) {
            $array[$row['Field']] = $row;
        }
        return $array;
    }

    public function getColumnsPropertiesWithoutId(): array
    {
        return array_diff_key($this->getColumnsProperties(), [$this->getPrimaryKey() => null]);
    }

}
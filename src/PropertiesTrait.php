<?php

namespace TexLab\MyDB;

trait PropertiesTrait
{
    //    protected $primaryKey = 'id';

    public function getPrimaryKey(): ?string
    {
        return $this->seekPrimaryKeyName($this->tableName);
    }

    public function rowCount(): ?int
    {
        return $this->runSQL("SELECT COUNT(*) AS C FROM $this->tableName;")[0]['C'];
    }

    private function seekPrimaryKeyName(string $tableName): ?string
    {
        return $this->runSQL("SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'")[0]['Column_name'];
    }

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

    public function getColumnsTypes(): array
    {
        $array = [];
        foreach ($this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;") as $row) {
            $array[$row['Field']] = preg_replace('/\(.*\)/', '', $row['Type']);
        }
        return $array;
    }

    public function getColumnsTypesLength(): array
    {
        $array = [];
        foreach ($this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;") as $row) {
            preg_match('/\((.*)\)/', $row['Type'], $matches);
            $array[$row['Field']] = $matches[1];
        }
        return $array;
    }

    public function getColumnsPropertiesWithoutId(): array
    {
        return array_diff_key($this->getColumnsProperties(), [$this->getPrimaryKey() => null]);
    }
}

<?php

namespace TexLab\MyDB;

trait PropertiesTrait
{
    //    protected $primaryKey = 'id';

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->seekPrimaryKeyName($this->tableName);
    }

    /**
     * @return int
     */
    public function rowCount(): int
    {
        return (int)$this->runSQL("SELECT COUNT(*) AS C FROM $this->tableName;")[0]['C'];
    }

    /**
     * @param string $tableName
     * @return string
     */
    private function seekPrimaryKeyName(string $tableName): string
    {
        return $this->runSQL("SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'")[0]['Column_name'] ?? '';
    }

    /**
     * @return string[]
     */
    public function getColumnsNames(): array
    {
        return array_column(
            $this->runSQL("SHOW COLUMNS FROM $this->tableName;"),
            "Field"
        );
    }

    /**
     * @param string $fieldName
     * @return mixed[]
     */
    public function getColumn(string $fieldName): array
    {
        return array_column(
            $this->get(),
            $fieldName,
            $this->getPrimaryKey()
        );
    }

    /**
     * @return mixed[]
     */
    public function getColumnsComments(): array
    {
        return array_column(
            $this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;"),
            "Comment",
            "Field"
        );
    }

    /**
     * @return array<mixed, array<mixed>>
     */
    public function getColumnsProperties(): array
    {
        $array = [];
        foreach ($this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;") as $row) {
            $array[$row['Field']] = $row;
        }
        return $array;
    }

    /**
     * @return array<mixed, mixed>
     */
    public function getColumnsTypes(): array
    {
        $array = [];
        foreach ($this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;") as $row) {
            $array[$row['Field']] = preg_replace('/\(.*\)/', '', $row['Type']);
        }
        return $array;
    }

    /**
     * @return array<mixed, mixed>
     */
    public function getColumnsTypesLength(): array
    {
        $array = [];
        foreach ($this->runSQL("SHOW FULL COLUMNS FROM $this->tableName;") as $row) {
            preg_match('/\((.*)\)/', $row['Type'], $matches);
            $array[$row['Field']] = $matches[1];
        }
        return $array;
    }

    /**
     * @return array<mixed, mixed>
     */
    public function getColumnsPropertiesWithoutId(): array
    {
        return array_diff_key($this->getColumnsProperties(), [$this->getPrimaryKey() => null]);
    }
}

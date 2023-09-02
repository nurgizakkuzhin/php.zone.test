<?php

namespace MyProject\Models;

use MyProject\Services\Db;

abstract class ActiveRecordEntity
{
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public static function findAll(): array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    abstract protected static function getTableName(): string;

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();

        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    private function update(array $mappedProperties): void
    {
        $propColumns = [];
        $propValues = [];

        foreach ($mappedProperties as $column => $value) {
            $propValues[':' . $column] = $value;

            if ($column === 'id') {
                continue;
            }
            $propColumns[] = $column . '=:' . $column ;

        }

        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $propColumns) . ' WHERE id=:id';

        $db = Db::getInstance();
        $db->query($sql, $propValues, static::class);

    }

    private function insert(array $mappedProperties): void
    {
        $propColumns = [];
        $propValues = [];

        $filteredProperties = array_filter($mappedProperties);
        foreach ($filteredProperties as $column => $value) {
            $propValues[':' . $column] = $value;
            $propColumns[':' . $column] = '`' . $column . '`';
        }

        $sql = 'INSERT INTO ' . static::getTableName() . ' ('. implode(', ', $propColumns) . ') VALUES (' . implode(', ', array_keys($propColumns)). ');';

        $db = Db::getInstance();
        $db->query($sql, $propValues, static::class);
        $this->id = $db->getLastInsertId();

        $this->refresh();

    }

    private function mapPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $mappedProperties[$propertyName] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    protected function refresh()
    {
        $data = static::getById($this->id);

        $properties = get_object_vars($data);
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }

    public function delete()
    {
        $db = Db::getInstance();
        $db->query('DELETE FROM ' . static::getTableName() . ' WHERE id=:id', [':id' => $this->id], static::class);
        $this->id = null;
    }

    public static function findOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `' . static::getTableName() .  '` WHERE `' . $columnName . '`= :value LIMIT 1;',
        [':value' => $value],
        static::class);

        if ($result === []) {
            return null;
        }

        return $result[0];
    }
}
<?php

namespace Models;

use Core\Database;
use PDO;

/**
 * Model Base - Classe abstrata para todos os models
 */
abstract class Model
{
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Busca todos os registros
     *
     * @param string $orderBy Cláusula ORDER BY (opcional)
     * @return array
     */
    public function all($orderBy = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Busca um registro por ID
     *
     * @param mixed $id
     * @return array|false
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Busca registros com condição WHERE
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public function where($column, $value)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    /**
     * Busca o primeiro registro com condição WHERE
     *
     * @param string $column
     * @param mixed $value
     * @return array|false
     */
    public function first($column, $value)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        return $stmt->fetch();
    }

    /**
     * Insere um novo registro
     *
     * @param array $data
     * @return string ID do registro inserido
     */
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $this->pdo->lastInsertId();
    }

    /**
     * Atualiza um registro
     *
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = ?";
        }
        $setClause = implode(', ', $sets);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }

    /**
     * Deleta um registro
     *
     * @param mixed $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Conta registros
     *
     * @param string|null $column
     * @param mixed|null $value
     * @return int
     */
    public function count($column = null, $value = null)
    {
        if ($column && $value !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE {$column} = ?");
            $stmt->execute([$value]);
        } else {
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->table}");
        }
        
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}

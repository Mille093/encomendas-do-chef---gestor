<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Database - Gerencia conexão com banco de dados (Singleton)
 */
class Database
{
    private static $instance = null;
    private $pdo;

    /**
     * Construtor privado (Singleton)
     */
    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        
        $dsn = sprintf(
            "mysql:host=%s;dbname=%s;charset=%s",
            $config['host'],
            $config['dbname'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Retorna instância única da conexão (Singleton)
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna o objeto PDO
     *
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Previne clonagem
     */
    private function __clone() {}

    /**
     * Previne deserialização
     */
    public function __wakeup()
    {
        throw new \Exception("Não é possível deserializar um Singleton");
    }
}

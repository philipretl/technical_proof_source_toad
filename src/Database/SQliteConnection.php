<?php

namespace Philipretl\TechnicalTestSourcetoad\Database;

use PDO;

class SQliteConnection implements Contracts\DataBaseConnection
{
    private static $instances = [];
    protected PDO $pdo;

    public function __construct(string $database_path)
    {
        $this->pdo = new \PDO("sqlite:" . $database_path);
    }

    public static function connect(string $database_path): SQliteConnection
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static($database_path);
        }

        return self::$instances[$class];
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function createTables()
    {
        $commands = [
            'CREATE TABLE IF NOT EXISTS customers (
                id   INTEGER PRIMARY KEY,
                first_name TEXT NOT NULL,
                last_name TEXT NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS addresses (
                id INTEGER PRIMARY KEY,
                line_1 VARCHAR (255) NOT NULL,
                line_2 VARCHAR (255) NOT NULL,
                city VARCHAR (100) NOT NULL,
                state VARCHAR (100) NOT NULL,
                zip VARCHAR (25) NOT NULL,
                customer_id INTEGER,
                FOREIGN KEY (customer_id) REFERENCES customer(id)
            )',
            'CREATE TABLE IF NOT EXISTS carts (
                id   INTEGER PRIMARY KEY,
                last_active  INTEGER NOT NULL,
                customer_id INTEGER,
                FOREIGN KEY (customer_id) REFERENCES customer(id)
            )',
            'CREATE TABLE IF NOT EXISTS items (
                id   INTEGER PRIMARY KEY,
                name  VARCHAR(120) NOT NULL,
                quantity INTEGER NOT NULL,
                price REAL NOT NULL,
                cart_id INTEGER,
                FOREIGN KEY (cart_id) REFERENCES cart(id)
            )',
            'CREATE TABLE IF NOT EXISTS orders (
                id   INTEGER PRIMARY KEY,
                tax  REAL NOT NULL,
                shipping_rate REAL NOT NULL,
                sub_total REAL NOT NULL,
                total REAL NOT NULL,
                cart_id INTEGER,
                customer_id INTEGER,
                address_id INTEGER,
                FOREIGN KEY (cart_id) REFERENCES cart(id)
                FOREIGN KEY (customer_id) REFERENCES customer(id)
                FOREIGN KEY (address_id) REFERENCES adresses(id)
            )',
        ];

        foreach ($commands as $command) {
            $this->pdo->exec($command);
        }
    }

    public function dropTables(): void
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sentenciaSQL = "DROP TABLE IF EXISTS adresses";
        $this->pdo->exec($sentenciaSQL);
    }
}
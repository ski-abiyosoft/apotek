<?php

namespace Services;

use Connections\Mysql;
use Interfaces\DatabaseInterface;

class Database implements DatabaseInterface
{
    private $connection;

    public function __construct()
    {
        $settings = json_decode(file_get_contents(__DIR__ . "/../../settings.json"));
        
        switch ($settings->driver) {
            case 'mysql':
                $this->connection = new Mysql(
                    $settings->host, 
                    $settings->username, 
                    $settings->password, 
                    $settings->database);
                break;
            default:
                $this->connection = new Mysql(
                    $settings->host, 
                    $settings->username, 
                    $settings->password, 
                    $settings->database);
                break;
        }
    }

    public function test()
    {
        $this->connection->test();
    }

    /**
     * Executing query string
     * 
     * @param string $query_string
     */
    public function exec(string $query_string): bool
    {
        return $this->connection->make_query($query_string);
    }

    /**
     * Init new database connection
     */
    public function get(): array
    {
        return $this->connection->get();
    }
}
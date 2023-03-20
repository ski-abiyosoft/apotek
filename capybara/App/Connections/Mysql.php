<?php

namespace Connections;

use Exception;
use Interfaces\ConnectionInterface;

class Mysql implements ConnectionInterface
{
    private $connection;
    private $result;

    public function __construct(string $host, string $username, string $pwd, string $database)
    {
        $this->connection = mysqli_connect($host, $username, $pwd, $database);
    }

    public function make_query(string $query_string): bool
    {
        try {
            $this->result = mysqli_query($this->connection, $query_string);
            
            return true;
        }catch (Exception $e) {
            return false;
        }
    }

    public function get(): array
    {
        $result = [];
        try {
            $result = mysqli_fetch_all($this->result, MYSQLI_ASSOC);

            return $result;
        }catch (Exception $e) {
            return [];
        }
    }

    public function test()
    {
        if (!$this->connection) {
            echo "Not connected, error: " . mysqli_connect_error();
        }else {
            echo "Connected.";
        }
    }
}
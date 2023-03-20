<?php

namespace Facades;

use Helpers\Table;
use Services\Database;

class Schema
{
    public static function test()
    {
        $database = new Database();

        $database->test();
    }

    private static function exec(string $query_string)
    {
        $database = new Database();

        $database->exec($query_string);
    }

    public static function new(string $table_name, callable $callback)
    {
        $table = Table::create_table($table_name);

        $callback($table);

        // return $table->get_query_string();

        self::exec($table->get_query_string());
    }

    public static function modify(string $table_name, callable $callback)
    {
        $table = Table::modify_table($table_name);

        $callback($table);

        // return $table->get_query_string();

        self::exec($table->get_query_string());
    }
}
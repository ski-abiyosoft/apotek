<?php

class Database
{
    private $DB;

    public function __construct()
    {
        $settings = json_decode(file_get_contents("settings.json"));
        $this->DB = new mysqli($settings->hostname, $settings->user, $settings->password, $settings->database);
    }

    public function test_connection()
    {
        if ($this->DB->connect_errno) {
            return "Your connection failed";
        }else {
            return "Your connection success";
        }
    }

    public function query(string $query_string)
    {
        $this->DB->query($query_string);
    }

    public static function test()
    {
        $DB = new self();
        echo $DB->test_connection();
    }

    public static function exec(string $query_string)
    {
        $DB = new self();
        $DB->query($query_string);
        $DB->fail_or_success();
        $DB->close();
    }

    public function fail_or_success()
    {
        if ($this->DB->error_list) {
            var_dump($this->DB->error_list);
        }else {
            echo "Query has been executed sucessfully";
        }
    }

    public function close()
    {
        $this->DB->close();
    }
}
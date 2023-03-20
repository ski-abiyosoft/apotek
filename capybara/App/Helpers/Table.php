<?php 

namespace Helpers;

class Table
{
    private $query_string;
    private $is_creation = false;

    public function __construct(string $table_name, bool $is_creation)
    {
        if ($is_creation) {
            $this->is_creation = true;
            $this->query_string = "CREATE TABLE $table_name (";
        }else {
            $this->query_string = "ALTER TABLE $table_name ADD (";
        }
    }

    /**
     * Initialize table creation
     * 
     * @param string $table_name
     */
    public static function create_table(string $table_name)
    {
        return new self($table_name, true);
    }

    /**
     * Initialize table modifiying
     * 
     * @param string $table_name
     */
    public static function modify_table(string $table_name)
    {
        return new self($table_name, false);
    }

    /**
     * Creating indentity column
     */
    public function id()
    {
        $this->query_string .= " id BIGINT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id),";
    }

    /**
     * Creating varchar column
     * 
     * @param string $column_name
     * @param int $length
     */
    public function string(string $column_name, int $length = 255)
    {
        $this->query_string .= " $column_name varchar($length),";
    }

    /**
     * Creating tiny text column
     * 
     * @param string $column_name
     */
    public function tinyText(string $column_name)
    {
        $this->query_string .= " $column_name TINYTEXT,";
    }

    /**
     * Creating tiny text column
     * 
     * @param string $column_name
     */
    public function text(string $column_name)
    {
        $this->query_string .= " $column_name TEXT,";
    }

    /**
     * Creating tiny text column
     * 
     * @param string $column_name
     */
    public function mediumText(string $column_name)
    {
        $this->query_string .= " $column_name MEDIUMTEXT,";
    }

    /**
     * Creating tiny text column
     * 
     * @param string $column_name
     */
    public function longText(string $column_name)
    {
        $this->query_string .= " $column_name LONGTEXT,";
    }

    /**
     * Creating integer column
     * 
     * @param string $column_name
     */
    public function integer(string $column_name)
    {
        $this->query_string .= " $column_name INT,";
    }

    /**
     * Creating float column
     * 
     * @param string $column_name
     */
    public function decimal(string $column_name, int $digit = 14, int $precision = 2)
    {
        $this->query_string .= " $column_name decimal($digit,$precision),";
    }

    /**
     * Creating date column
     * 
     * @param string $column_name
     */
    public function date(string $column_name)
    {
        $this->query_string .= " $column_name date,";
    }

    /**
     * Creating datetime column
     * 
     * @param string $column_name
     */
    public function datetime(string $column_name)
    {
        $this->query_string .= " $column_name datetime,";
    }

    /**
     * Return query string 
     */
    public function get_query_string()
    {
        $this->query_string = substr_replace($this->query_string, "", -1);

        $this->query_string .= " )";

        return $this->query_string;
    }
}
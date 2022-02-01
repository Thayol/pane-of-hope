<?php

class DatabaseRecord
{
    protected $table;
    public $id;

    public function __construct($id) {
        $this->id = $id;
    }
}

class DatabaseTable
{
    protected $table;
    protected $columns;

    public function __construct() {
        $this->table = null;
        $this->columns = array("id");
    }

    public function count()
    {
        $table = $this->table;
        $count_result = db_query("SELECT COUNT(id) as count_result FROM {$table};");
        if ($count_result->num_rows > 0)
        {
            return $count_result->fetch_assoc()["count_result"];
        }

        return 0;
    }

    public function all()
    {
        $table = $this->table;
        $columns = implode(",", $this->columns);
        $order_by = $this->columns[0];
        
        $result = db_find_multiple("SELECT {$columns} FROM {$table} ORDER BY {$order_by} ASC;");

        return $result;
    }
    
    public function find_by_id($id)
    {
        return $this->find_by("id", $id);
    }

    public function find_by($column, $value)
    {
        $table = $this->table;
        $columns = implode(",", $this->columns);
        $value = is_string($value) ? "'{$value}'" : strval($value);

        $result = db_find("SELECT {$columns} FROM {$table} WHERE {$column}={$value} ORDER BY {$column} ASC;");

        return $result;
    }

    public function multi_find_by($column, $value)
    {
        $table = $this->table;
        $columns = implode(",", $this->columns);
        $value = is_string($value) ? "'{$value}'" : strval($value);

        $result = db_find_multiple("SELECT {$columns} FROM {$table} WHERE {$column}={$value} ORDER BY {$column} ASC;");

        return $result;
    }
}

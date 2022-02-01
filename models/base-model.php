<?php

class DatabaseRecord
{
    public $id;

    public function __construct($id) {
        $this->id = $id;
    }
}

class DatabaseTable
{
    protected $table;

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
}
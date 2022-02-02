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
    protected $produces;

    public function __construct() {
        $this->table = null;
        $this->columns = array("id");
        $this->produces = "DatabaseRecord";
    }

    public function count()
    {
        $table = $this->table;
        $count_result = Database::query("SELECT COUNT(id) as count_result FROM {$table};");
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
        
        $result = Database::multi_find("SELECT {$columns} FROM {$table} ORDER BY {$order_by} ASC;");

        return $this->parse_multiple($result);
    }

    public function parse(...$raw)
    {
        $class = $this->produces;
        return new $class(...$raw);
    }

    public function parse_multiple($raw_array)
    {
        $parsed = array();
        foreach ($raw_array as $raw)
        {
            $parsed[] = $this->parse(...$raw);
        }

        return $parsed;
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

        $result = Database::find("SELECT {$columns} FROM {$table} WHERE {$column}={$value} ORDER BY {$column} ASC;");

        return $this->parse(...$result);
    }

    public function multi_find_by($column, $value)
    {
        $table = $this->table;
        $columns = implode(",", $this->columns);
        $value = is_string($value) ? "'{$value}'" : strval($value);

        $result = Database::multi_find("SELECT {$columns} FROM {$table} WHERE {$column}={$value} ORDER BY {$column} ASC;");

        return $this->parse_multiple($result);
    }

	public function find_by_raw_id($raw_id_input)
	{
		$id = intval($raw_id_input);
		if ($id > 0)
		{
			try
			{
				$record = $this->find_by_id($id);
                return $record;
			}
			catch (Exception $e)
			{
			}
		}
        return null;
	}	
}

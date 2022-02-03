<?php

class Query
{
    private $parse_as;
    private $main_table;
    private $query_type;

    private $select_fields;

    private $where_conditions;
    private $where_value_types;
    private $where_values;

    private $order_by_field;

    private $query_result;
    private $last_query;

    public function __construct()
    {
        $this->parse_as = null;
        $this->main_table = "";
        $this->query_type = "";

        $this->select_fields = array();

        $this->where_conditions = array();
        $this->where_value_types = array();
        $this->where_values = array();

        $this->order_by_field = "";

        $this->query_result = null;
        $this->last_query = null;
    }

    public static function new($class = null)
    {
        $instance = new Query();

        if ($class !== null)
        {
            $instance->type($class);
            $instance->select($class::fields);
            $instance->from($class::table);
        }

        return $instance;
    }

    public static function convert_value($value)
    {
        switch (gettype($value))
        {
            case "integer":
                return [ "i", intval($value) ];
            case "double":
                return [ "d", doubelval($value) ];
            default:
                return [ "s", strval($value) ];
        }
    }

    public function type($class)
    {
        $this->parse_as = $class;
    }

    public function from($table)
    {
        $this->main_table = $table;

        return $this;
    }

    public function select($fields = null)
    {
        $this->query_type = "SELECT";
        if (is_array($fields))
        {
            $this->select_fields = array_merge($this->select_fields, $fields);
        }
        else if (is_string($fields))
        {
            $this->select_fields = array_merge($this->select_fields, explode(",", str_replace(" ", "", $fields)));
        }
        else if ($fields == null)
        {
            $this->select_fields = "*";
        }
        else
        {
            throw new Exception("Unknown select field variable types passed.");
        }

        return $this;
    }

    public function where(string $clause, $values)
    {
        if (strpos($clause, "?") === false)
        {
            throw new Exception("Where clauses need a question mark for preparation.");
        }

        $this->where_conditions[] = $clause;

        if (!is_array($values))
        {
            $values = array($values);
        }

        foreach ($values as $value)
        {
            list($type, $value) = static::convert_value($value);
            $this->where_value_types[] = $type;
            $this->where_values[] = $value;
        }
        
        return $this;
    }

    public function order_by(string $field, bool $asc = true)
    {
        $this->order_by_field = $field . " " . ($asc ? "ASC" : "DESC");
        
        return $this;
    }

    public function explain()
    {
        return $this->fake_substitute();
    }

    public function find($id)
    {
        $field = $this->select_fields[0];
        $this->where("{$field} = ?", $id);

        return $this->first();
    }

    public function all()
    {
        $this->lazy_execute();
        
        return $this->query_result ?? null;
    }

    public function first($amount = null)
    {
        $this->lazy_execute();

        if ($amount == null)
        {
            return $this->query_result[0] ?? null;
        }

        if (count($this->query_result) < $amount)
        {
            $amount = count($this->query_result);
        }

        return array_slice($this->query_result, 0, $amount) ?? null;
    }

    private function execute()
    {
        $query = $this->build_query();
        $this->last_query = $query;

        $this->query_result = Database::prepared_query($query, $this->build_values());

        if ($this->parse_as !== null)
        {
            $class = $this->parse_as;
            $parsed = array();

            foreach ($this->query_result as $record)
            {
                $parsed[] = new $class(...$record);
            }

            $this->query_result = $parsed;
        }

        return $this;
    }

    private function lazy_execute()
    {
        if ($this->last_query != $this->build_query())
        {
            $this->execute();
        }
    }

    private function fake_substitute()
    {
        $statement = $this->build_query();

        foreach ($this->build_values() as $value)
        {
            $sub = $value;
            if (gettype($value) == "string")
            {
                $sub = "'{$sub}'";
            }

            $pos = strpos($statement, "?");
            $before = substr($statement, 0, $pos);
            $after = substr($statement, $pos + 1);
            $statement = $before . $sub . $after;
        }
        
        return $statement;
    }

    private function build_select()
    {
        $fields = implode(", ", $this->select_fields);
        $main_table = $this->main_table;
        return "SELECT {$fields} FROM {$main_table}";
    }

    private function build_where()
    {
        $clause = implode(" AND ", $this->where_conditions);
        return "WHERE {$clause}";
    }

    private function build_order_by()
    {
        if (empty($this->order_by_field))
        {
            $order = $this->select_fields[0];
        }
        else
        {
            $order = $this->order_by_field;
        }
        
        return "ORDER BY {$order}";
    }

    private function build_query()
    {
        $query_clauses = array();
        
        if ($this->query_type == "SELECT")
        {
            $query_clauses[] = $this->build_select();

            if (!empty($this->where_conditions))
            {
                $query_clauses[] = $this->build_where();
            }

            $query_clauses[] = $this->build_order_by();
        }

        $query = implode(" ", $query_clauses);
        $query .= ";";

        return $query;
    }

    private function build_values()
    {
        $values = array();

        if (!empty($this->where_values))
        {
            $values = array_merge($values, $this->where_values);
        }

        return $values;
    }
}

class Database
{
    public static function connect()
    {
        $db = new mysqli(Config_MySQL::$address, Config_MySQL::$user, Config_MySQL::$password, Config_MySQL::$database);

        if ($db->connect_error)
        {
            throw new Exception("There was a problem while contacting the database.");
        }

        return $db;
    }

    public static function prepared_query($statement, $substitutions)
    {
        $db = static::connect();
        $prepared = $db->prepare($statement);

        $types = "";
        foreach ($substitutions as $key => $substitution)
        {
            switch (gettype($substitution))
            {
                case "integer":
                    $types .= "i";
                    break;
                case "double":
                    $types .= "d";
                    break;
                case "string":
                default:
                    $types .= "s";
                    $substitutions[$key] = strval($substitution);
                    break;
            }
        }

        $prepared->bind_param($types, ...$substitutions);
        $prepared->execute();
        $result = $prepared->get_result();
        $assoc = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $assoc;
    }

    public static function query($query)
    {
        $db = static::connect();
        return $db->query($query);
    }

    public static function insert_query($query)
    {
        $db = static::connect();
        if ($db->query($query) === true)
        {
            return $db->insert_id;
        }

        return false;
    }

    public static function multi_query($queries)
    {
        return static::connect()->multi_query($queries);
    }
}

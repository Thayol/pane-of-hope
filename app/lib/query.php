<?php

class Query
{
    private $parse_as;
    private $main_table;
    private $query_type;

    private $count_mode;
    private $and_mode;

    private $select_fields;
    private $count_field;

    private $where_conditions;
    private $where_value_types;
    private $where_values;

    private $order_by_clause;

    private $query_result;
    private $executed_queries;

    public function __construct()
    {
        $this->parse_as = null;
        $this->main_table = "";
        $this->query_type = "";

        $this->count_mode = false;
        $this->and_mode = false;

        $this->select_fields = array();
        $this->count_field = "";

        $this->where_conditions = array();
        $this->where_value_types = array();
        $this->where_values = array();

        $this->order_by_clause = "";

        $this->limit_clause = null;
        $this->offset_clause = null;

        $this->query_result = null;
        $this->executed_queries = array();
    }

    public static function new($class = null)
    {
        $instance = new Query();

        if ($class !== null)
        {
            $instance->select($class::fields);
            $instance->from($class::table);
            $instance->type($class);
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

    public function and()
    {
        $this->and_mode = true;

        return $this;
    }

    public function type($class)
    {
        $this->parse_as = $class;

        return $this;
    }

    public function from($table)
    {
        $this->main_table = $table;

        return $this;
    }

    public function select($fields = null)
    {
        $this->type(null);
        $this->query_type = "SELECT";

        if (is_string($fields))
        {
            $fields = explode(",", str_replace(" ", "", $fields));
        }

        if (is_array($fields))
        {
            if ($this->and_mode)
            {
                $this->and_mode = false;
                $this->select_fields = array_merge($this->select_fields, $fields);
            }
            else
            {
                $this->select_fields = $fields;
            }
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

    public function count(?string $field = null)
    {
        $this->count_mode = true;

        if ($field == null && count($this->select_fields) > 0)
        {
            $field = $this->select_fields[0];
        }

        $this->count_field = $field;

        $this->lazy_execute();

        $this->count_mode = false;

        return $this->query_result;
    }

    public function pluck($key)
    {
        $class = $this->parse_as;
        $this->type(null);

        $plucked = array_map(
            fn($e) => $e[$key],
            $this->all()
        );

        $this->type($class);
        return $plucked;
    }

    public function where(string $clause, $values, $in = false)
    {
        if (strpos($clause, "?") === false)
        {
            throw new Exception("Where clauses need a question mark for preparation.");
        }

        if (gettype($values) == "object" && get_class($values) == static::class)
        {
            $subquery = substr($values->explain(), 0, -1);
            $clause = str_replace("?", "({$subquery})", $clause);

            $values = array();
        }
        else if ($in)
        {
            $in_values = array();
            foreach ($values as $value)
            {
                list($type, $value) = static::convert_value($value);
                $in_values[] = $value;
            }

            $in_values = implode(", ", $in_values);

            $clause = str_replace("?", "({$in_values})", $clause);
            $values = array();
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

    public function in($field, $values)
    {
        $this->where("{$field} IN ?", $values, true);

        return $this;
    }

    public function order_by(string $field, bool $asc = true)
    {
        $this->order_by_clause = $field . " " . ($asc ? "ASC" : "DESC");
        
        return $this;
    }

    public function limit($limit)
    {
        $this->limit_clause = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->offset_clause = $offset;

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

        $this->query_result = Database::prepared_query($query, $this->build_values());

        if ($this->count_mode)
        {
            $this->query_result = $this->query_result[0]["count"] ?? 0;
        }
        else if ($this->parse_as !== null)
        {
            $class = $this->parse_as;
            $parsed = array();

            foreach ($this->query_result as $record)
            {
                $parsed[] = new $class(...$record);
            }

            $this->query_result = $parsed;
        }

        $this->executed_queries[$query] = $this->query_result;

        return $this;
    }

    private function lazy_execute()
    {
        $query = $this->build_query();
        if (in_array($query, $this->executed_queries))
        {
            $this->query_result = $this->executed_queries[$query];
        }
        else
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
        if ($this->count_mode)
        {
            $field = $this->count_field;
            $fields = "COUNT({$field}) AS count";
        }
        else
        {
            $fields = implode(", ", $this->select_fields);
        }
        
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
        if (empty($this->order_by_clause))
        {
            $order = $this->select_fields[0];
        }
        else
        {
            $order = $this->order_by_clause;
        }
        
        return "ORDER BY {$order}";
    }

    private function build_limit()
    {
        $limit = $this->limit_clause;
        return "LIMIT {$limit}";
    }

    private function build_offset()
    {
        $offset = $this->offset_clause;
        return "OFFSET {$offset}";
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

            if (!$this->count_mode)
            {
                $query_clauses[] = $this->build_order_by();

                if ($this->limit_clause !== null)
                {
                    $query_clauses[] = $this->build_limit();
                }

                if ($this->offset_clause !== null)
                {
                    $query_clauses[] = $this->build_offset();
                }
            }
        }

        $query = implode(" ", $query_clauses);
        $query .= ";";

        if ($this->parse_as !== null)
        {
            $class = $this->parse_as;
            $query .= " /* {$class}::class */";
        }

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

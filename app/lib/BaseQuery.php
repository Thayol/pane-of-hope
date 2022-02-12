<?php

class BaseQuery
{
    const type = "RAW";
    const clause_order = [];

    protected array $fields;
    protected string $table;
    protected string $primary_key;

    protected array $clauses;
    protected array $params;
    protected array $history;

    public function __construct(string $class)
    {
        $this->fields = $class::fields;
        $this->table = $class::table;
        $this->primary_key = $class::primary_key;

        $this->clauses = array();
        $this->params = array();

        $this->history = array();
    }

    public function raw(string $sql) {
        $this->clauses = [ $sql ];

        return $this;
    }

    public function result($statement = null)
    {
        if ($statement == null)
        {
            $statement = $this->build_explanation();
        }

        if (!in_array($statement, $this->statement_history()))
        {
            $this->execute($statement);
        }

        if (empty($this->history[$statement]))
        {
            throw new Exception("Could not execute statement: {$statement}");
        }

        return $this->history[$statement];
    }

    public function statement_history()
    {
        return array_keys($this->history);
    }

    public function history()
    {
        return array_values($this->history);
    }

    public function explain($echo = false)
    {
        $explanation = $this->build_explanation();

        if ($echo)
        {
            echo $explanation;
        }

        return $explanation;
    }

    public function where(string $condition, mixed $values = null)
    {
        if (strpos($condition, "?") === false)
        {
            $this->clauses[] = "WHERE {$condition}";

            return $this;
        }

        if (gettype($values) == "object" && get_class($values) == static::class)
        {
            $subquery = substr($values->explain(), 0, -1);
            $condition = str_replace("?", "({$subquery})", $condition);

            $values = array();
        }

        $this->clauses[] = "WHERE {$condition}";

        $values = static::wrap_if_single_value($values);

        Database::param_types($values); // transforms values

        $this->params = array_merge($this->params, $values);
        
        return $this;
    }

    protected static function wrap_if_single_value($values)
    {
        if (is_array($values))
        {
            return $values;
        }

        return array($values);
    }

    protected function before_build_statement()
    {
    }

    protected function get_clauses($type)
    {
        $clauses = array_filter(
            $this->clauses,
            fn($clause) => strpos($clause, $type) === 0
        );

        if ($type == "WHERE" && count($clauses) > 1)
        {
            for($i = 1; $i < count($clauses); $i++) // skipping the first
            {
                $clauses[$i] = str_replace("WHERE", "AND", $clauses[$i]);
            }
        }

        return $clauses;
    }

    protected function build_statement()
    {
        $this->before_build_statement();

        if (empty(static::clause_order))
        {
            $clauses = $this->clauses;
        }
        else
        {
            $clauses = array();
            foreach (static::clause_order as $type)
            {
                $clauses = array_merge($clauses, $this->get_clauses($type));
            }
        }

        $statement = implode(" ", $clauses);
        if (substr($statement, -1) !== ";")
        {
            $statement .= ";";
        }
        
        return $statement;
    }

    protected function build_params()
    {
        return $this->params;
    }

    protected function execute()
    {
        $explanation = $this->build_explanation();
        $result = Database::query($this->build_statement(), $this->build_params());
        $this->history[$explanation] = $result;

        return $result;
    }

    protected function build_explanation()
    {
        $explanation = $this->build_statement();

        foreach ($this->build_params() as $param)
        {
            $explanation = preg_replace('/\?/', static::quote_strval($param), $explanation);
        }

        return $explanation;
    }

    protected static function quote_strval($value)
    {
        switch (gettype($value))
        {
            case "integer":
                return strval($value);
            case "double":
                return strval($value);
            default:
                $value = strval($value);
                return "'{$value}'";
        }
    }
}

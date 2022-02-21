<?php

class QueryBuilder
{
    const clause_order = array(
        QueryClause::class,
        SelectClause::class,
        FromClause::class,
        WhereClause::class,
        LimitClause::class,
        OffsetClause::class,
    );

    protected array $clauses;
    protected array $history;

    public function __construct()
    {
        $this->clauses = array();
        $this->history = array();
    }

    public function add_clause(string $type, mixed ...$args)
    {
        $class = ucfirst(strtolower($type)) . "Clause";
        $new_clause = new $class(...$args);

        if (!$class::stack)
        {
            foreach ($this->clauses as $key => $clause)
            {
                if ($clause::class == $class)
                {
                    if ($class::combine)
                    {
                        $clause->combine($new_clause);
                    }
                    else
                    {
                        $this->clauses[$key] = $new_clause;
                    }

                    return $this;
                }
            }
        }

        $this->clauses[] = $new_clause;

        return $this;
    }

    public function explanation() : string
    {
        $explanation = $this->statement();

        foreach ($this->params() as $param)
        {
            $explanation = preg_replace('/\?/', static::quote_strval($param), $explanation, 1);
        }

        return $explanation;
    }

    public function statement() : string
    {
        $this->order_clauses();

        $clauses = array_map(
            fn($clause) => $clause->statement(),
            $this->clauses
        );

        $statement = implode(" ", $clauses);
        if (substr($statement, -1) !== ";")
        {
            $statement .= ";";
        }

        return $statement;
    }

    public function params()
    {
        $this->order_clauses();

        $params_array = array_map(
            fn($clause) => $clause->params(),
            $this->clauses
        );

        return array_merge(...$params_array);
    }
    
    public static function quote_strval($value)
    {
        if (gettype($value) == "string")
        {
            return "'{$value}'";
        }

        return strval($value);
    }

    protected function order_clauses()
    {
        $order = array_flip(static::clause_order);

        usort(
            $this->clauses,
            fn($a, $b) => $order[$a::class] <=> $order[$b::class]
        );
    }

    protected function execute()
    {
        $result = Database::query($this->statement(), $this->params());

        $this->history[$this->explanation()] = $result;

        return $result;
    }

    public function result($statement = null)
    {
        $statement = $statement ?? $this->explanation();

        if (in_array($statement, array_keys($this->history)))
        {
            return $this->history[$statement];
        }

        return $this->execute();
    }
}

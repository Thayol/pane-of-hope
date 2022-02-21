<?php

class WhereClause extends QueryClause
{
    const combine = true;

    protected string $condition;
    protected array $params;
    protected ?WhereClause $subclause;
    protected bool $is_subclause;

    public function __construct(string $condition, mixed ...$params)
    {
        $this->subclause = null;
        $this->is_subclause = false;

        $this->condition = $condition;
        $this->params = $params;
    }

    public function make_subclause()
    {
        $this->is_subclause = true;

        return $this;
    }

    public function statement() : string
    {
        $clause = "WHERE {$this->condition}";

        if ($this->is_subclause)
        {
            $clause = " AND {$this->condition}";
        }

        if ($this->subclause != null)
        {
            $clause .= $this->subclause->statement();
        }

        return $clause;
    }

    public function condition() : string
    {
        return $this->condition;
    }

    public function params() : array
    {
        $params = $this->params;

        if ($this->subclause != null)
        {
            $params = array_merge($params, $this->subclause->params());
        }

        return $params;
    }

    public function combine(WhereClause $clause) : static
    {
        $this->subclause = $clause->make_subclause();

        return $this;
    }

    // public static function combine(WhereClause ...$clauses) : string
    // {
    //     $conditions = array_map(
    //         fn($clause) => $clause->condition(),
    //         $clauses
    //     );

    //     return "WHERE " . implode(" AND ", $conditions);
    // }
}

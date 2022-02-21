<?php

class Query
{
    protected QueryBuilder $builder;

    public function __construct(?QueryBuilder $builder = null)
    {
        if ($builder == null)
        {
            $builder = new QueryBuilder();
        }

        $this->builder = $builder;
    }

    public function builder()
    {
        return $this->builder;
    }

    public function add_clause(mixed ...$args)
    {
        $this->builder->add_clause(...$args);

        return $this;
    }

    public function explain($echo = false) : string
    {
        $explanation = $this->builder->explanation();

        if ($echo)
        {
            echo $explanation;
        }

        return $explanation;
    }

    public function result()
    {
        return $this->builder->result();
    }


    // Syntactic sugar below

    public function select(...$args)
    {
        return $this->add_clause("SELECT", ...$args);
    }

    public function from(...$args)
    {
        return $this->add_clause("FROM", ...$args);
    }

    public function where(...$args)
    {
        return $this->add_clause("WHERE", ...$args);
    }

    public function limit(...$args)
    {
        return $this->add_clause("LIMIT", ...$args);
    }

    public function offset(...$args)
    {
        return $this->add_clause("OFFSET", ...$args);
    }
}

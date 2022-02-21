<?php

class LimitClause extends QueryClause
{
    protected string $limit;

    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function statement() : string
    {
        return "LIMIT {$this->limit}";
    }
}

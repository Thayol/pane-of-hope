<?php

class FromClause extends QueryClause
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function statement() : string
    {
        return "FROM {$this->table}";
    }
}

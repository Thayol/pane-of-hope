<?php

class QueryClause
{
    const combine = false;
    const stack = false;

    private string $raw_statement;

    public function __construct(string $raw_statement = "")
    {
        $this->raw_statement = $raw_statement;
    }

    public function statement() : string
    {
        return $this->raw_statement;
    }

    public function params() : array
    {
        return array();
    }
}

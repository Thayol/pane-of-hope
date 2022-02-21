<?php

class OffsetClause extends QueryClause
{
    protected string $offset;

    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    public function statement() : string
    {
        return "OFFSET {$this->offset}";
    }
}

<?php

class SelectClause extends QueryClause
{
    protected array $fields;

    public function __construct(mixed ...$fields)
    {
        $this->fields = $fields;
    }

    public function statement() : string
    {
        $fields = implode(", ", $this->fields);

        return "SELECT {$fields}";
    }
}

<?php

class ClassQuery extends Query
{
    protected string $class;

    public function __construct($class, ...$args)
    {
        parent::__construct(...$args);

        $this->class = $class;
    }

    public function each()
    {
        return $this->result()->class($this->class);
    }

    public function count()
    {
        $count = clone $this;
        return $count->select("COUNT(id) AS count")->result()->assoc()[0]["count"];
    }
}

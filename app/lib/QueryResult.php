<?php

class QueryResult
{
    public $assoc;
    public $affected_rows;

    public function __construct(mysqli_stmt $stmt)
    {
        $this->affected_rows = $stmt->affected_rows;

        $result = $stmt->get_result();
        $this->assoc = $result->fetch_all(MYSQLI_ASSOC);
    }

    public function assoc()
    {
        return $this->assoc;
    }

    public function class($class)
    {
        return $this->map_to_class($this->assoc, $class);
    }

    protected function map_to_class(array $assoc_list, string $class)
    {
        return array_map(
            fn($assoc) => new $class(...$assoc),
            $assoc_list
        );
    }
}

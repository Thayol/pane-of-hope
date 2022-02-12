<?php

class SelectQuery extends BaseQuery
{
    const type = "SELECT";
    const clause_order = [ "SELECT", "FROM", "WHERE", "ORDER BY", "LIMIT", "OFFSET" ];
    const count_alias = "count";

    private string $class;

    public function __construct($class)
    {
        parent::__construct($class);

        $this->class = $class;

        $this->select();
    }

    public function select(array|string $fields = [])
    {
        $fields = static::wrap_if_single_value($fields);

        $this->validate_fields($fields);

        $fields = $this->build_fields($fields);

        $this->select_clause("SELECT {$fields}", "FROM {$this->table}");

        return $this;
    }

    public function count(?string $field = null)
    {
        if ($field == null)
        {
            $field = $this->primary_key;
        }

        $this->validate_fields([$field]);

        $count_alias = static::count_alias;
        $this->select_clause("SELECT COUNT({$field}) as {$count_alias}", "FROM {$this->table}");

        return $this->process_count_result($this->result());
    }

    public function limit(int $limit)
    {
        $this->clauses[] = "LIMIT {$limit}";

        return $this;
    }

    public function offset(int $offset)
    {
        $this->clauses[] = "OFFSET {$offset}";
        
        return $this;
    }

    public function order_by(string $field, bool $asc = true)
    {
        $this->validate_fields([$field]);

        $direction = $asc ? "ASC" : "DESC";

        $this->clauses[] = "ORDER BY {$field} {$direction}";

        return $this;
    }

    public function pluck($field)
    {
        $assoc = $this->result()->assoc();

        return array_map(
            fn($element) => $element[$field],
            $assoc
        );
    }

    public function list()
    {
        return $this->result_class();
    }

    public function first()
    {
        return $this->result_class()[0] ?? null;
    }

    public function result_class()
    {
        return $this->result()->class($this->class);
    }
    
    private function process_count_result($result)
    {
        return $this->result()->assoc()[0][static::count_alias];
    }

    private function select_clause(string $select, string $from = null)
    {
        $this->clauses = [ $select ];

        if (!empty($from))
        {
            $this->clauses[] = $from;
        }
    }

    protected function before_build_statement()
    {
        if (empty($this->get_clauses("ORDER BY")))
        {
            $this->order_by($this->primary_key);
        }
    }

    protected function build_fields(array $fields = [])
    {
        if (empty($fields))
        {
            $fields = $this->fields;
        }

        return implode(", ", $fields);
    }

    protected function validate_fields(array $fields)
    {
        if (empty(array_diff($fields, $this->fields)))
        {
            return true;
        }

        $fields = $this->build_fields($fields);
        $valid_fields = $this->build_fields();
        throw new Exception("Field outside of valid range: {$fields} (Valid fields: {$valid_fields})");
    }
}

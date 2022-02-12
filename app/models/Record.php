<?php

class Record
{
    const new = null;
    const primary_key = "id";
    const fields = [ "id" ];
    const table = "";

    public $id;
    private $new_record;

    public function __construct(?int $id = null) {
        if (empty($id) || $id === static::new)
        {
            $this->new_record = true;
        }
        else
        {
            $this->new_record = false;
            $this->id = $id;
        }
    }

    public function destroy() {
        $table = static::table;
        return static::delete($this->id)->commit() > 0;
    }

    public function save() {
        $values = array();
        foreach (static::fields as $field)
        {
            $values[] = $this->$field;
        }

        $values = array_slice($values, 1);

        if ($this->new_record)
        {
            $this->id = static::insert()->values($values)->commit();
            $this->new_record = false;
            return 1; // "rows affected"
        }

        return static::update($this->id)->values($values)->commit() > -1;
        
    }

    public static function insert() {
        return static::query()->insert();
    }

    public static function select(array $fields = []) {
        // return static::query()->select();
        $select_query = new SelectQuery(static::class);

        if (!empty($fields))
        {
            return $select_query->select($fields);
        }

        return $select_query;
    }

    public static function update(int $id) {
        return static::query()->update($id);
    }

    public static function delete(int $id) {
        return static::query()->delete($id);
    }

    public static function find(int $id) {
        return static::query()->find($id);
    }

    public static function find_by(string $field, mixed $value) {
        return static::query()->find_by($field, $value);
    }

    public static function all() {
        return static::select()->list();
    }

    protected static function query() {
        return Query::new(static::class);
        // return new BaseQuery(static::primary_key);
    }
}

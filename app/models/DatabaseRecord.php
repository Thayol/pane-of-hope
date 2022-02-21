<?php

class DatabaseRecord
{
    const fields = [ "id" ];
    const table = "";

    public int $id;

    public function __construct($id = null) {
        $this->id = $id;
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

        if ($this->id === null)
        {
            $this->id = static::insert()->values($values)->commit();
            return 1; // "rows affected"
        }

        return static::update($this->id)->values($values)->commit() > -1;
        
    }

    public static function insert() {
        return static::old_query()->insert();
    }

    public static function select($fields = null) {
        if (empty($fields))
        {
            $fields = static::fields;
        }

        return static::query()->select(...$fields)->from(static::table);
    }

    public static function update($id) {
        return static::old_query()->update($id);
    }

    public static function delete($id) {
        return static::old_query()->delete($id);
    }

    public static function find($id) {
        return static::old_query()->find($id);
    }

    public static function find_by($field, $value) {
        return static::old_query()->find_by($field, $value);
    }

    public static function all() {
        return static::select()->result()->class(static::class);
    }

    private static function old_query() {
        return OldQuery::new(static::class);
    }

    // TODO: make private
    public static function query(...$args) {
        return new Query(...$args);
    }
}

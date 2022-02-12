<?php

class DatabaseRecord
{
    const fields = [ "id" ];
    const table = "";

    public $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function destroy() {
        $table = static::table;
        return Query::new(static::class)->delete($this->id)->commit() > 0;
    }

    public function save() {
        $values = array();
        foreach (static::fields as $field)
        {
            $values[] = $this->$field;
        }

        $values = array_slice($values, 1);

        return Query::new(static::class)->update($this->id)->values($values)->commit() > -1;
    }

    public static function query() {
        return Query::new(static::class);
    }

    public static function insert() {
        return static::query()->insert();
    }

    public static function select($fields = null) {
        if (empty($fields))
        {
            $fields = static::fields;
        }

        return static::query()->select();
    }

    public static function update($id) {
        return static::query()->update($id);
    }

    public static function delete($id) {
        return static::query()->delete($id);
    }

    public static function find($id) {
        return static::query()->find($id);
    }
}

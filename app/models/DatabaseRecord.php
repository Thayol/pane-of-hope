<?php

class DatabaseRecord
{
    const fields = [ "id" ];
    const table = "";

    public $id;
    private $new_record;

    public function __construct($id = null) {
        if (empty($id) || $id === Record::new)
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

    public static function find_by($field, $value) {
        return static::query()->find_by($field, $value);
    }

    public static function all() {
        return static::query()->all();
    }

    private static function query() {
        return Query::new(static::class);
    }
}

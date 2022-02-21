<?php

class DatabaseRecord
{
    const fields = [ "id" ];
    const table = "";

    public int $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $table = static::table;
        return static::delete($this->id)->commit() > 0;
    }

    public function save()
    {
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

    public static function insert()
    {
        return static::old_query()->insert();
    }

    public static function select(...$fields)
    {
        if (empty($fields))
        {
            $fields = static::fields;
        }

        return static::query()->select(...$fields)->from(static::table);
    }

    public static function update($id)
    {
        return static::old_query()->update($id);
    }

    public static function delete($id)
    {
        return static::old_query()->delete($id);
    }

    public static function find($id)
    {
        return static::select()->where("id = ?", $id)->result()->class(static::class)[0];
    }

    public static function find_by($field, $value)
    {
        return static::select()->where("{$field} = ?", $value)->result()->class(static::class)[0];
    }

    public static function count()
    {
        return static::select("COUNT(id) AS count")->result()->assoc()[0]["count"];
    }

    public static function all()
    {
        return static::select()->result()->class(static::class);
    }

    private static function old_query()
    {
        return OldQuery::new(static::class);
    }

    private static function query(...$args)
    {
        return new ClassQuery(static::class, ...$args);
    }
}

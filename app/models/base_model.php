<?php

class DatabaseRecord
{
    const fields = "id";
    const table = "";

    public $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function destroy() {
        $table = static::table;
        Database::query("DELETE FROM {$table} WHERE id={$this->id};");
    }
}

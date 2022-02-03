<?php

class DatabaseRecord
{
    const fields = "id";
    const table = "";

    public $id;

    public function __construct($id) {
        $this->id = $id;
    }
}

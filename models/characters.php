<?php
require_once __DIR__ . "/base-model.php";

class Character extends DatabaseRecord
{
    public $name;
    public $original_name;
    public $gender;

    public function __construct($id, $name, $original_name, $gender) {
        parent::__construct($id);
        $this->name = $name;
        $this->original_name = $original_name;
        $this->gender = $gender;
    }
}

class Characters extends DatabaseTable
{
    public function __construct() {
        $this->table = "characters";
    }
}
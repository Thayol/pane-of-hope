<?php

class CharacterSourceConnector extends DatabaseRecord
{
    public $character_id;
    public $source_id;

    private $character;
    private $source;

    public function __construct($id, $character_id, $source_id)
    {
        parent::__construct($id);
        $this->table = "conn_character_source";

        $this->character_id = $character_id;
        $this->source_id = $source_id;

        $this->character = null;
        $this->source = null;
    }

    public function character()
    {
        if ($this->character == null)
        {
            $this->character = Database::characters()->find_by_id($this->character_id);
        }

        return $this->character;
    }

    public function source()
    {
        if ($this->source == null)
        {
            $this->source = Database::sources()->find_by_id($this->source_id);
        }

        return $this->source;
    }
}

class CharacterSourceConnectors extends DatabaseTable
{
    public function __construct() {
        parent::__construct();
        $this->table = "conn_character_source";
        $this->produces = "CharacterSourceConnector";
        $this->columns = array(
            "id",
            "character_id",
            "source_id",
        );
    }

    public function multi_find_by_character_id($character_id)
    {
        return $this->multi_find_by("character_id", $character_id);
    }

    public function multi_find_by_source_id($source_id)
    {
        return $this->multi_find_by("source_id", $source_id);
    }
}

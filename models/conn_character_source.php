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
            $character_table = new Characters();
            $this->character = $character_table->find_by_id($this->character_id);
        }

        return $this->character;
    }

    public function source()
    {
        if ($this->source == null)
        {
            $source_table = new Sources();
            $this->source = $source_table->find_by_id($this->source_id);
        }

        return $this->source;
    }
}

class CharacterSourceConnectors extends DatabaseTable
{
    public function __construct() {
        parent::__construct();
        $this->table = "conn_character_source";
        $this->columns = array(
            "id",
            "character_id",
            "source_id",
        );
    }

    public function find_by_id($id)
    {
        return new CharacterSourceConnector(...parent::find_by_id($id));
    }

    public function multi_find_by($column, $value)
    {
        $raw_connections = parent::multi_find_by($column, $value);

        $connections = array();
        foreach($raw_connections as $raw_connection)
        {
            $connections[] = new CharacterSourceConnector(...$raw_connection);
        }

        return $connections;
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

<?php

class CharacterSourceConnector extends DatabaseRecord
{
    const fields = [ "id", "character_id", "source_id" ];
    const table = "conn_character_source";

    public $character_id;
    public $source_id;

    private $character;
    private $source;

    public function __construct($id, $character_id, $source_id)
    {
        parent::__construct($id);

        $this->character_id = $character_id;
        $this->source_id = $source_id;

        $this->character = null;
        $this->source = null;
    }

    public function set_character($character)
    {
        $this->character = $character;
    }

    public function set_source($source)
    {
        $this->source = $source;
    }

    public function character()
    {
        if ($this->character == null)
        {
            $this->character = Character::find($this->character_id);
        }

        return $this->character;
    }

    public function source()
    {
        if ($this->source == null)
        {
            $this->source = Source::find($this->source_id);
        }

        return $this->source;
    }
}

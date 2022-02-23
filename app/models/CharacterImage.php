<?php

class CharacterImage extends DatabaseRecord
{
    const fields = [ "id", "character_id", "path" ];
    const table = "character_images";

    public $character_id;
    public $path;

    public function __construct($id, $character_id, $path)
    {
        parent::__construct($id);

        $this->character_id = $character_id;
        $this->path = $path;
    }
}

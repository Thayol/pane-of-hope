<?php

class Character extends DatabaseRecord
{
    const fields = [ "id", "name", "original_name", "gender" ];
    const table = "characters";
    const gender_map = [
        0 => "N/A",
        1 => "Female",
        2 => "Male",
    ];

    public $name;
    public $original_name;
    public $gender;

    private $images;
    private $sources;

    public function __construct($id, $name, $original_name, $gender)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->original_name = $original_name;
        $this->gender = $gender;

        $this->images = null;
        $this->sources = null;
    }

    public function set_sources($sources)
    {
        $this->sources = $sources;
    }

    public function pretty_gender()
    {
        return static::gender_map[$this->gender];
    }

    public function pretty_name()
    {
        if (empty($this->original_name))
        {
            return $this->name;
        }
        else
        {
            return "$this->name ($this->original_name)";
        }
    }

    public function images()
    {
        if ($this->images == null)
        {
            $this->images = CharacterImage::select()->where("character_id = ?", $this->id)->all();
        }

        return $this->images;
    }

    public function sources()
    {
        if ($this->sources == null)
        {
            $this->sources = array_map(
                fn($conn) => $conn->source(),
                CharacterSourceConnector::select()
                    ->where("character_id = ?", $this->id)
                    ->all()
            ) ?? array();
        }

        return $this->sources;
    }
}

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

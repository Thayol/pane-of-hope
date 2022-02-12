<?php

class Source extends Record
{
    const fields = [ "id", "title" ];
    const table = "sources";

    public $title;

    private $aliases;
    private $characters;

    public function __construct($id, $title)
    {
        parent::__construct($id);

        $this->title = $title;

        $this->aliases = null;
        $this->characters = null;
    }

    public function set_aliases($aliases)
    {
        $this->aliases = $aliases;
    }

    public function set_characters($characters)
    {
        $this->characters = $characters;
    }

    public function aliases()
    {
        if ($this->aliases == null)
        {
            $this->aliases = SourceAlias::select()->where("source_id = ?", $this->id)->list();
        }

        return $this->aliases;
    }

    public function characters()
    {
        if ($this->characters == null)
        {
            $this->characters = array_map(
                fn($conn) => $conn->character(),
                CharacterSourceConnector::select()
                    ->where("source_id = ?", $this->id)
                    ->list()
            ) ?? array();
        }

        return $this->characters;
    }
}

class SourceAlias extends Record
{
    const fields = [ "id", "source_id", "alias" ];
    const table = "source_aliases";

    public function __construct($id, $source_id, $alias)
    {
        parent::__construct($id);

        $this->source_id = $source_id;
        $this->alias = $alias;
    }
}

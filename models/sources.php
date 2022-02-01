<?php

class Source extends DatabaseRecord
{
    public $title;

    private $aliases;

    public function __construct($id, $title)
    {
        parent::__construct($id);
        $this->table = "sources";

        $this->title = $title;

        $this->aliases = null;
    }

    public function aliases()
    {
        if ($this->aliases == null)
        {
            $source_aliases_table = new SourceAliases();
            $this->aliases = $source_aliases_table->multi_find_by_source_id($this->id);
        }
        
        return $this->aliases;
    }
}

class Sources extends DatabaseTable
{
    public function __construct() {
        parent::__construct();
        $this->table = "sources";
        $this->columns = array(
            "id",
            "title",
        );
    }

    public function all()
    {
        $sources = array();
        foreach(parent::all() as $raw)
        {
            $sources[] = new Source(...$raw);
        }

        return $sources;
    }

    public function find_by_id($id)
    {
        return new Source(...parent::find_by_id($id));
    }
}

class SourceAlias extends DatabaseRecord
{
    public $source_id;
    public $alias;

    public function __construct($id, $source_id, $alias)
    {
        parent::__construct($id);
        $this->table = "source_aliases";

        $this->source_id = $source_id;
        $this->alias = $alias;
    }
}

class SourceAliases extends DatabaseTable
{
    public function __construct() {
        parent::__construct();
        $this->table = "source_aliases";
        $this->columns = array(
            "id",
            "source_id",
            "alias",
        );
    }

    public function find_by_id($id)
    {
        return new SourceAlias(...parent::find_by_id($id));
    }

    public function multi_find_by_source_id($source_id)
    {
        $source_aliases = array();
        foreach($this->multi_find_by("source_id", $source_id) as $raw)
        {
            $source_aliases[] = new SourceAlias(...$raw);
        }

        return $source_aliases;
    }
}

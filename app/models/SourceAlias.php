<?php

class SourceAlias extends DatabaseRecord
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

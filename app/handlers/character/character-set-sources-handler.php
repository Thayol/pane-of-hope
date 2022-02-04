<?php

if ($session_is_admin)
{
    $id = $_POST["id"];
    $sources = $_POST["sources"] ?? array();

    $old_sources = array_map(
        fn($conn) => $conn->source_id,
        Query::new(CharacterSourceConnector::class)->where("character_id = ?", $id)->all()
    ) ?? array();

    $removed_sources = array_diff($old_sources, $sources);
    $new_sources = array_diff($sources, $old_sources);

    $query = "";
    foreach ($removed_sources as $source)
    {
        $query .= "DELETE FROM conn_character_source WHERE character_id={$id} AND source_id={$source};";
    }
    foreach ($new_sources as $source)
    {
        $query .= "INSERT INTO conn_character_source (character_id, source_id) VALUES ({$id}, '{$source}');";
    }


    if (!empty($query))
    {
        if (Database::multi_query($query) === true)
        {
            header('Location: ' . Routes::get_action_url("character", "id={$id}&sources_updated"));
        }
        else
        {
            header('Location: ' . Routes::get_action_url("character-set-sources", "id={$id}&error"));
        }
    }
    else
    {
        header('Location: ' . Routes::get_action_url("character", "id={$id}&sources_updated"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

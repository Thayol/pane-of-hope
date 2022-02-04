<?php

if ($session_is_admin)
{
    $id = $_POST["id"];
    $characters = array_map(
        "Sanitize::id",
        $_POST["characters"] ?? array()
    );

    $old_characters = Query::new(CharacterSourceConnector::class)->where("source_id = ?", Sanitize::id($id))->pluck("character_id");

    $removed_characters = array_diff($old_characters, $characters);
    $new_characters = array_diff($characters, $old_characters);

    $query = "";
    foreach ($removed_characters as $character)
    {
        $query .= "DELETE FROM conn_character_source WHERE source_id={$id} AND character_id={$character};";
    }
    foreach ($new_characters as $character)
    {
        $query .= "INSERT INTO conn_character_source (character_id, source_id) VALUES ({$character}, {$id});";
    }


    if (!empty($query))
    {
        if (Database::multi_query($query) === true)
        {
            header('Location: ' . Routes::get_action_url("source", "id={$id}&characters_updated"));
        }
        else
        {
            header('Location: ' . Routes::get_action_url("source-set-sources", "id={$id}&error"));
        }
    }
    else
    {
        header('Location: ' . Routes::get_action_url("source", "id={$id}&characters_updated"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

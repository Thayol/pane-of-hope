<?php

if ($session_is_admin)
{
    $source_id = $_POST["id"];
    $characters = array_map(
        "Sanitize::id",
        $_POST["characters"] ?? array()
    );

    $old_characters = CharacterSourceConnector::select()->where("source_id = ?", Sanitize::id($source_id))->pluck("character_id");

    $removed_characters = array_diff($old_characters, $characters);
    $new_characters = array_diff($characters, $old_characters);

    if (!empty($removed_characters))
    {
        foreach (CharacterSourceConnector::select()->where("source_id = ?", $source_id)->where("character_id IN (?)", $removed_characters)->each() as $conn)
        {
            $conn->destroy();
        }
    }
    foreach ($new_characters as $character_id)
    {
        (new CharacterSourceConnector(null, $character_id, $source_id))->save();
    }
    
    header('Location: ' . Router::get_url("source", "id={$source_id}&characters_updated"));
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

<?php

if ($session_is_admin)
{
    $character_id = $_POST["id"];
    $sources = array_map(
        "Sanitize::id",
        $_POST["sources"] ?? array()
    );

    $old_sources = CharacterSourceConnector::select()->where("character_id = ?", Sanitize::id($character_id))->pluck("source_id");

    $removed_sources = array_diff($old_sources, $sources);
    $new_sources = array_diff($sources, $old_sources);

    if (!empty($removed_sources))
    {
        foreach (CharacterSourceConnector::select()->where("character_id = ?", $character_id)->in("source_id", $removed_sources)->all() as $conn)
        {
            $conn->destroy();
        }
    }
    foreach ($new_sources as $source_id)
    {
        (new CharacterSourceConnector(Record::new, $character_id, $source_id))->save();
    }

    header('Location: ' . Routes::get_action_url("character", "id={$character_id}&sources_updated"));
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

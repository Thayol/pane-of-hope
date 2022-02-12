<?php

if ($session_is_admin)
{
    $source_id = intval($_POST["id"]);
    $title = Sanitize::str($_POST["title"]);
    $aliases = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $_POST["aliases"])));

    if (!empty($title))
    {
        $old_aliases = SourceAlias::select()->where("source_id = ?", Sanitize::id($source_id))->pluck("alias");

        $removed_aliases = array_diff($old_aliases, $aliases);
        $new_aliases = array_diff($aliases, $old_aliases);

        $source = Source::find($source_id);
        $source->title = $title;
        $saved = $source->save();

        if (!empty($removed_aliases))
        {
            foreach (SourceAlias::select()->where("source_id = ?", $source_id)->in("alias", $removed_aliases)->list() as $conn)
            {
                $conn->destroy();
            }
        }
        foreach ($new_aliases as $alias)
        {
            (new SourceAlias(Record::new, $source_id, $alias))->save();
        }

        if ($saved)
        {
            header('Location: ' . Routes::get_action_url("source", "id={$source_id}&edited"));
        }
        else
        {
            header('Location: ' . Routes::get_action_url("source-edit", "id={$source_id}&error"));
        }
    }
    else
    {
        header('Location: ' . Routes::get_action_url("source-edit", "id={$source_id}&invalid"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

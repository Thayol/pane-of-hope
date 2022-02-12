<?php

if ($session_is_admin)
{
    $source_id = intval($_POST["id"]);
    $title = htmlspecialchars($_POST["title"], Config::$htmlspecialchars_flags);
    $aliases = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $_POST["aliases"])));

    if (!empty($title))
    {
        $old_aliases = Query::new(SourceAlias::class)->where("source_id = ?", Sanitize::id($source_id))->pluck("alias");

        $removed_aliases = array_diff($old_aliases, $aliases);
        $new_aliases = array_diff($aliases, $old_aliases);

        Database::query("UPDATE sources SET title = '{$title}' WHERE id={$source_id};");

        if (!empty($removed_aliases))
        {
            foreach (Query::new(SourceAlias::class)->where("source_id = ?", $source_id)->in("alias", $removed_aliases)->all() as $conn)
            {
                $conn->destroy();
            }
        }
        foreach ($new_aliases as $alias)
        {
            Query::new(SourceAlias::class)->insert()->values([ $source_id, $alias ])->commit();
        }

        if (true) // TODO: condition after UPDATE is done
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

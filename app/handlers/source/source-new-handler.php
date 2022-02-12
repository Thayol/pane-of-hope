<?php

if ($session_is_admin)
{
    $title = htmlspecialchars($_POST["title"], Config::$htmlspecialchars_flags);
    $aliases = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $_POST["aliases"])));

    if (!empty($title))
    {
        $source_id = Query::new(Source::class)->insert()->values($title)->commit();

        if (!empty($aliases))
        {
            foreach ($aliases as $alias)
            {
                echo Query::new(SourceAlias::class)->insert()->values([ $source_id, $alias ])->commit();
            }
        }

        if ($source_id !== false)
        {
            header('Location: ' . Routes::get_action_url("source", "id={$source_id}&created"));
        }
        else
        {
            header('Location: ' . Routes::get_action_url("source-new", "error"));
        }
    }
    else
    {
        header('Location: ' . Routes::get_action_url("source-new", "invalid"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

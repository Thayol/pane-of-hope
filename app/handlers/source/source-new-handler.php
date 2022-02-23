<?php

if ($session_is_admin)
{
    $title = htmlspecialchars($_POST["title"], Config::$htmlspecialchars_flags);
    $aliases = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $_POST["aliases"])));

    if (!empty($title))
    {
        $source = new Source(null, $title);

        if ($source->save() > 0)
        {
            if (!empty($aliases))
            {
                foreach ($aliases as $alias)
                {
                    (new SourceAlias(null, $source->id, $alias))->save();
                }
            }
            header('Location: ' . Router::get_url("source", "id={$source->id}&created"));
        }
        else
        {
            header('Location: ' . Router::get_url("source/new", "error"));
        }
    }
    else
    {
        header('Location: ' . Router::get_url("source/new", "invalid"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}

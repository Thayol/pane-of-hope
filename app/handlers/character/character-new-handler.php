<?php

if ($session_is_admin)
{
    $name = htmlspecialchars($_POST["name"], Config::$htmlspecialchars_flags);
    $original_name = htmlspecialchars($_POST["original_name"], Config::$htmlspecialchars_flags);
    $gender = intval($_POST["gender"]);

    if (!empty($name) && $gender >= 0 && $gender < 3)
    {
        $character = new Character(null, $name, $original_name, $gender);

        if ($character->save() > 0)
        {
            header('Location: ' . Router::get_url("character", "id={$character->id}&created"));
        }
        else
        {
            header('Location: ' . Router::get_url("character/new", "error"));
        }
    }
    else
    {
        header('Location: ' . Router::get_url("character/new", "invalid"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}
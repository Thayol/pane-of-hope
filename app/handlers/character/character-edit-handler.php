<?php

if ($session_is_admin)
{
    $id = intval($_POST["id"]);
    $name = htmlspecialchars($_POST["name"], Config::$htmlspecialchars_flags);
    $original_name = htmlspecialchars($_POST["original_name"], Config::$htmlspecialchars_flags);
    $gender = intval($_POST["gender"]);

    if (!empty($name) && $gender >= 0 && $gender < 3)
    {
        $character = Character::find($id);

        $character->name = $name;
        $character->original_name = $original_name;
        $character->gender = $gender;

        if ($character->save())
        {
            header('Location: ' . Routes::get_action_url("character", "id={$id}&edited"));
        }
        else
        {
            header('Location: ' . Routes::get_action_url("character-edit", "id={$id}&error"));
        }
    }
    else
    {
        header('Location: ' . Routes::get_action_url("characters-edit", "id={$id}&invalid"));
    }
}
else
{
    header("Content-Type: application/json");
    echo json_encode([ "status" => "unauthorized" ]);
}
<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

if ($session_is_admin)
{
	$name = htmlspecialchars($_POST["name"], $htmlspecialchars_flags);
	$original_name = htmlspecialchars($_POST["original_name"], $htmlspecialchars_flags);
	$gender = intval($_POST["gender"]);

	if (!empty($name) && $gender >= 0 && $gender < 3)
	{
		if (($id = Database::insert_query("INSERT INTO characters (name, original_name, gender) VALUES ('{$name}', '{$original_name}', {$gender});")) !== false)
		{
			header('Location: ' . action_to_link("character", "id={$id}&created"));
		}
		else
		{
			header('Location: ' . action_to_link("character-new", "error"));
		}
	}
	else
	{
		header('Location: ' . action_to_link("character-new", "invalid"));
	}
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}
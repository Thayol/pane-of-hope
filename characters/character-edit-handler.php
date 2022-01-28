<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

if ($session_is_admin)
{
	$id = intval($_POST["id"]);
	$name = htmlspecialchars($_POST["name"], $htmlspecialchars_flags);
	$original_name = htmlspecialchars($_POST["original_name"], $htmlspecialchars_flags);
	$gender = intval($_POST["gender"]);

	if (!empty($name) && $gender >= 0 && $gender < 3)
	{
		$db = db_connect();
		if ($db->query("UPDATE characters SET name = '{$name}', original_name = '{$original_name}', gender = {$gender} WHERE id={$id};") === true)
		{
			header('Location: ' . action_to_link("character", "id={$id}&edited"));
		}
		else
		{
			header('Location: ' . action_to_link("character-edit", "id={$id}&error"));
		}
	}
	else
	{
		header('Location: ' . action_to_link("characters-edit", "id={$id}&invalid"));
	}	
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}
<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

if ($session_is_admin)
{
	$id = intval($_POST["id"]);
	$name = htmlspecialchars($_POST["name"]);
	$original_name = htmlspecialchars($_POST["original_name"]);
	$gender = intval($_POST["gender"]);

	if (!empty($name) && $gender >= 0 && $gender < 3)
	{
		$db = db_connect();
		if ($db->query("UPDATE characters SET name = '{$name}', original_name = '{$original_name}', gender = {$gender} WHERE id={$id};") === true)
		{
			header('Location: ' . action_to_link("character", "id={$id}"));
		}
	}
	else
	{
		header('Location: ' . action_to_link("characters-new", "invalid"));
	}	
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}
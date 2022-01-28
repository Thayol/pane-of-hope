<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

if ($session_is_admin)
{
	$title = htmlspecialchars($_POST["title"], $htmlspecialchars_flags);
	$aliases = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $_POST["aliases"])));

	if (!empty($title))
	{
		$query = "INSERT INTO sources (title) VALUES ('{$title}');SET @last_source_insert_id = LAST_INSERT_ID();";
		foreach ($aliases as $alias)
		{
			$query .= "INSERT INTO source_aliases (source_id, alias) VALUES (@last_source_insert_id, '{$alias}');";
		}

		$db = db_connect();
		if ($db->multi_query($query) === true)
		{
			$id = $db->insert_id;
			header('Location: ' . action_to_link("source", "id={$id}&created"));
		}
		else
		{
			header('Location: ' . action_to_link("source-new", "error"));
		}
	}
	else
	{
		header('Location: ' . action_to_link("source-new", "invalid"));
	}
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}

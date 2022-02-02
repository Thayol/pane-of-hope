<?php

if ($session_is_admin)
{
	$id = $_POST["id"];
	$characters = $_POST["characters"] ?? array();

	$old_characters = array();

	$db = Database::connect();
	$result = $db->query("SELECT * FROM conn_character_source AS conn WHERE conn.source_id={$id} ORDER BY id ASC;");
	if ($result->num_rows > 0)
	{
		while ($conn = $result->fetch_assoc())
		{
			$old_characters[] = $conn["character_id"];
		}
	}

	$removed_characters = array_diff($old_characters, $characters);
	$new_characters = array_diff($characters, $old_characters);

	$query = "";
	foreach ($removed_characters as $character)
	{
		$query .= "DELETE FROM conn_character_source WHERE source_id={$id} AND character_id={$character};";
	}
	foreach ($new_characters as $character)
	{
		$query .= "INSERT INTO conn_character_source (character_id, source_id) VALUES ({$character}, '{$id}');";
	}


	if (!empty($query))
	{
		$db = Database::connect();
		if ($db->multi_query($query) === true)
		{
			header('Location: ' . Routes::get_action_url("source", "id={$id}&characters_updated"));
		}
		else
		{
			header('Location: ' . Routes::get_action_url("source-set-sources", "id={$id}&error"));
		}
	}
	else
	{
		header('Location: ' . Routes::get_action_url("source", "id={$id}&characters_updated"));
	}
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}

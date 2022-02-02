<?php

if ($session_is_admin)
{
	$id = intval($_POST["id"]);
	$title = htmlspecialchars($_POST["title"], Config::$htmlspecialchars_flags);
	$aliases = array_filter(explode("\n", str_replace(["\r\n", "\r"], "\n", $_POST["aliases"])));

	if (!empty($title))
	{
		$old_aliases = array();
		$db = Database::connect();
		$aliases_result = $db->query("SELECT * FROM source_aliases WHERE source_id={$id} ORDER BY id ASC;");
		if ($aliases_result->num_rows > 0)
		{
			while ($row = $aliases_result->fetch_assoc())
			{
				$old_aliases[$row["id"]] = $row["alias"];
			}
		}

		$removed_aliases = array_diff($old_aliases, $aliases);
		$new_aliases = array_diff($aliases, $old_aliases);

		$query = "UPDATE sources SET title = '{$title}' WHERE id={$id};";
		foreach ($removed_aliases as $alias_id => $alias)
		{
				$query .= "DELETE FROM source_aliases WHERE source_id={$id} AND id={$alias_id};";
		}
		foreach ($new_aliases as $alias)
		{
				$query .= "INSERT INTO source_aliases (source_id, alias) VALUES ({$id}, '{$alias}');";
		}

		$db = Database::connect();
		if ($db->multi_query($query) === true)
		{
			header('Location: ' . Routes::get_action_url("source", "id={$id}&edited"));
		}
		else
		{
			header('Location: ' . Routes::get_action_url("source-edit", "id={$id}&error"));
		}
	}
	else
	{
		header('Location: ' . Routes::get_action_url("source-edit", "id={$id}&invalid"));
	}
}
else
{
	header("Content-Type: application/json");
	echo json_encode([ "status" => "unauthorized" ]);
}

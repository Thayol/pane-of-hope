<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

if ($session_is_admin)
{
	$name = htmlspecialchars($_POST["name"], $htmlspecialchars_flags);
	$original_name = htmlspecialchars($_POST["original_name"], $htmlspecialchars_flags);
	$gender = intval($_POST["gender"]);

	echo "<pre>";print_r($_POST);echo "</pre><pre>";
	echo $name . "\n";
	echo $original_name . "\n";
	echo $gender . "\n";
	if (!empty($name) && $gender >= 0 && $gender < 3)
	{
		$db = db_connect();
		if ($db->query("INSERT INTO characters (name, original_name, gender) VALUES ('{$name}', '{$original_name}', {$gender});") === true)
		{
			$id = $db->insert_id;
			header('Location: ' . action_to_link("character", "id={$id}&created"));
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
<?php
require __DIR__ . "/../session.php";
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

$username = $_POST["username"];
$plain_password = $_POST["password"];

$username_valid = preg_match('/[A-Za-z0-9_\.-]{3,128}/', $username) == 1 ? true : false;
$password_valid = preg_match('/.{8,4000}/', $plain_password) == 1 ? true : false;

$action = "login";

if ($username_valid && $password_valid)
{
	$db = Database::connect();
	$reg_query = $db->query("SELECT id, username, displayname, email, password, permission_level FROM users WHERE username='{$username}' ORDER BY id ASC;");
	
	$is_registered = false;
	if ($reg_query->num_rows > 0)
	{
		$is_registered = true;
	}
	
	if ($is_registered)
	{
		$reg_arr = $reg_query->fetch_assoc();
		$password_hash = $reg_arr["password"];
		
		$password_matches = password_verify($plain_password, $password_hash);
		
		if ($password_matches)
		{
			$perm = $reg_arr["permission_level"];
			if ($perm < 10)
			{
				header('Location: ' . action_to_link($action, "invalid=banned"));
				exit(0);
			}
			
			$is_admin = false;
			if ($perm >= 40) $is_admin = true;
			
			$session_array = array(
				"userid" => $reg_arr["id"],
				"username" => $reg_arr["username"],
				"displayname" => $reg_arr["displayname"],
				"email" => $reg_arr["email"],
				"permission_level" => $perm,
				"admin" => $is_admin,
				"authenticated" => true,
			);
			
			$_SESSION["paneofhope"] = $session_array;
			
			header('Location: ' . action_to_link('profile'));
		}
		else
		{
			header('Location: ' . action_to_link($action, "invalid=wrongpass"));
		}
	}
	else
	{
		header('Location: ' . action_to_link($action, "invalid=unregistered"));
	}
}
else
{
	$invalid_values = array();
	foreach ([ "username" => $username_valid, "password" => $password_valid ] as $key => $value)
	{
		if (!$value) $invalid_values[] = $key;
	}
	
	$invalid_comma_delimited = implode(",", $invalid_values);
	header('Location: ' . action_to_link($action, "invalid={$invalid_comma_delimited}"));
}
<?php
require __DIR__ . "/../settings.php";
require __DIR__ . "/../functions.php";

$displayname = $_POST["displayname"];
$username = $_POST["username"];
$plain_password = $_POST["password"];
$plain_password2 = $_POST["password2"];
$email = $_POST["email"];

$displayname_valid = preg_match('/[A-Za-z0-9_\. -]{1,16}/', $displayname) == 1 ? true : false;
$username_valid = preg_match('/[A-Za-z0-9_\.-]{3,128}/', $username) == 1 ? true : false;
$password_valid = preg_match('/.{8,4000}/', $plain_password) == 1 ? true : false;
$email_valid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? true : false;

$action = "register";

if ($username_valid && $password_valid && $displayname_valid && $email_valid && $plain_password === $plain_password2)
{
	$password = password_hash($plain_password, PASSWORD_DEFAULT);
	
	$is_registered = false;
	$db = Database::connect();
	$reg_query = $db->query("SELECT id, username FROM users WHERE username='{$username}' ORDER BY id ASC;");
	if ($reg_query->num_rows > 0)
	{
		$is_registered = true;
	}
	
	if (!$is_registered)
	{
		$db->query("INSERT INTO users (username, displayname, password, email) VALUES ('{$username}', '{$displayname}', '{$password}', '{$email}')");
		
		header('Location: ' . action_to_link('login', "registered"));
	}
	else
	{
		header('Location: ' . action_to_link($action, "invalid=registered"));
	}
	
}
else
{
	$invalid_values = array();
	foreach ([ "username" => $username_valid, "password" => $password_valid, "displayname" => $displayname_valid, "email" => $email_valid ] as $key => $value)
	{
		if (!$value) $invalid_values[] = $key;
	}
	
	if ($plain_password !== $plain_password2)
	{
		$invalid_values[] = "password2";
	}
	
	$invalid_comma_delimited = implode(",", $invalid_values);
	header('Location: ' . action_to_link($action, "invalid={$invalid_comma_delimited}"));
}
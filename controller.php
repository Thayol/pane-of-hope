<?php
require "session.php";
require "settings.php";
require "functions.php";

if ($error_reporting)
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

$actions = array(
	"" => "home.php",
	"login" => "auth/login.php",
	"register" => "auth/register.php",
	"logout" => "auth/logout.php",
	"profile" => "profile/profile.php",
	"admin" => "admin/admin.php",
	"sitemap" => "sitemap/sitemap.php",
	"characters" => "characters/characters.php",
);

if (in_array($action, array_keys($actions)))
{
	require $actions[$action];
}
else
{
	echo "Unknown action.";
}
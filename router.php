<?php
require __DIR__ . "/session.php";
require __DIR__ . "/settings.php";
require __DIR__ . "/functions.php";

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
	"character" => "characters/character.php",
	"character-new" => "characters/character-new.php",
	"character-add-image" => "characters/character-add-image.php",
	"character-edit" => "characters/character-new.php",
	"sources" => "sources/sources.php",
	"source" => "sources/source.php",
	"source-new" => "sources/source-new.php",
	"source-edit" => "sources/source-edit.php",
	"users" => "profile/profile-list.php",
);

if (in_array($action, array_keys($actions)))
{
	require $actions[$action];
}
else
{
	echo "Unknown action.";
}
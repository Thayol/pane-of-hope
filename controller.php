<?php
require "session.php";
require "settings.php";

if ($error_reporting)
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

if ($action == "")
{
	require "home.php";
}
else if ($action == "login")
{
	require "login.php";
}
else if ($action == "register")
{
	require "register.php";
}
else if ($action == "profile")
{
	require "profile.php";
}
else if ($action == "logout")
{
	require "logout.php";
}
else
{
	echo "Unknown action.";
}
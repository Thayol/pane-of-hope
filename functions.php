<?php
function action_to_link($action = "", $querystring = "") : string
{
	global $absolute_prefix;
	
	if (strpos($action, "?") !== false)
	{
		$temp = explode("?", $action);
		$action = $temp[0];
		$querystring = $temp[1];
	}
	$absolute = '/';
	if (!empty($action))
	{
		$absolute .= str_replace("-", "/", $action) . '/';
	}
	
	if (!empty($querystring))
	{
		$absolute .= "?" . $querystring;
	}
	
	return $absolute_prefix . $absolute;
}

function db_connect()
{
	global $mysql_addr, $mysql_user, $mysql_pass, $mysql_db;
	
	$db = new mysqli($mysql_addr, $mysql_user, $mysql_pass, $mysql_db);

	if ($db->connect_error)
	{
		echo "There was a problem while contacting the database.";
		exit(0);
	}
	
	return $db;
}
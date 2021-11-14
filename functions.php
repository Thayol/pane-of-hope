<?php
function action_to_link($action = "") : string
{
	global $absolute_prefix;
	
	$absolute = '/';
	if (!empty($action))
	{
		$absolute .= $action . '/';
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
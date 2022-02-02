<?php

require_once __DIR__ . "/database.php";

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

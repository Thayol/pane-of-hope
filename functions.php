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

function db_query($query)
{
	$db = db_connect();
    return $db->query($query);
}

function db_insert_query($query)
{
	$db = db_connect();
    if ($db->query($query) === true)
	{
		return $db->insert_id;
	}

	return false;
}

function db_find($query)
{
	$result = db_query($query);
	if ($result !== false && $result->num_rows == 1)
	{
		return $result->fetch_assoc();
	}
	else if ($result !== false && $result->num_rows > 1)
	{
		throw new Exception("More than one matching record found.");
	}
	else
	{
		throw new Exception("Record not found in database.");
	}

	return $result;
}

function db_find_multiple($query)
{
	$result = db_query($query);
	$result_array = array();

	if ($result !== false && $result->num_rows > 0)
	{
		while ($temp = $result->fetch_assoc())
		{
			$result_array[] = $temp;
		}
	}

	return $result_array;
}

function db_multi_query($queries)
{
	$db = db_connect();
    return $db->multi_query($queries);
}

$global_characters_table = null;
function characters_table()
{
	global $global_characters_table;
	if ($global_characters_table == null)
	{
		$global_characters_table = new Characters();
	}

	return $global_characters_table;
}

$global_sources_table = null;
function sources_table()
{
	global $global_sources_table;
	if ($global_sources_table == null)
	{
		$global_sources_table = new Sources();
	}

	return $global_sources_table;
}

$global_conn_character_source_table = null;
function conn_character_source_table()
{
	global $global_conn_character_source_table;
	if ($global_conn_character_source_table == null)
	{
		$global_conn_character_source_table = new CharacterSourceConnectors();
	}

	return $global_conn_character_source_table;
}

$global_character_images_table = null;
function character_images_table()
{
	global $global_character_images_table;
	if ($global_character_images_table == null)
	{
		$global_character_images_table = new CharacterImages();
	}

	return $global_character_images_table;
}

function load_character_or_null($raw_id_input)
{
	$id = intval($raw_id_input);
	if ($id > 0)
	{
		try
		{
			$character = characters_table()->find_by_id($id);
		}
		catch (Exception $e)
		{
			return null;
		}

		return $character;
	}
}

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

class Database
{
	private static $table_singletons = array();

	public static function get_table_singleton($class)
	{
		if (empty(self::$table_singletons[$class]))
		{
			self::$table_singletons[$class] = new $class();
		}

		return self::$table_singletons[$class];
	}

	public static function characters(): Characters
	{
		return self::get_table_singleton("Characters");
	}
	
	public static function sources(): Sources
	{
		return self::get_table_singleton("Sources");
	}
	
	public static function conn_character_source(): CharacterSourceConnectors
	{
		return self::get_table_singleton("CharacterSourceConnectors");
	}
	
	public static function character_images(): CharacterImages
	{
		return self::get_table_singleton("CharacterImages");
	}
	
	public static function source_aliases(): SourceAliases
	{
		return self::get_table_singleton("SourceAliases");
	}

	public static function connect()
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
	
	public static function query($query)
	{
		$db = self::connect();
		return $db->query($query);
	}
	
	public static function insert_query($query)
	{
		$db = self::connect();
		if ($db->query($query) === true)
		{
			return $db->insert_id;
		}
	
		return false;
	}
	
	public static function find($query)
	{
		$result = self::query($query);
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
	
	public static function multi_find($query)
	{
		$result = self::query($query);
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
	
	public static function multi_query($queries)
	{
		return self::connect()->multi_query($queries);
	}
}
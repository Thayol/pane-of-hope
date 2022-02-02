<?php

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
		$db = new mysqli(Config_MySQL::$address, Config_MySQL::$user, Config_MySQL::$password, Config_MySQL::$database);

		if ($db->connect_error)
		{
			throw new Exception("There was a problem while contacting the database.");
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

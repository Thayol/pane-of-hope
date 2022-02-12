<?php

class Database
{
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
        $db = static::connect();
        return $db->query($query);
    }

    public static function insert_query($query)
    {
        $db = static::connect();
        if ($db->query($query) === true)
        {
            return $db->insert_id;
        }

        return false;
    }

    public static function multi_query($queries)
    {
        return static::connect()->multi_query($queries);
    }
}

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
}

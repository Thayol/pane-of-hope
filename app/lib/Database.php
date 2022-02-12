<?php

class Database
{
    public static $connection = null;

    public static function connect()
    {
        if (static::$connection !== null && static::$connection->ping())
        {
            return static::$connection;
        }

        $db = new mysqli(Config_MySQL::$address, Config_MySQL::$user, Config_MySQL::$password, Config_MySQL::$database);

        if ($db->connect_error)
        {
            throw new Exception("There was a problem while contacting the database.");
        }

        static::$connection = $db;
        return $db;
    }

    private static function prepare(string $statement)
    {
        $stmt = static::connect()->prepare($statement);

        if ($stmt === false)
        {
            throw new Exception("Malformed SQL statement: {$statement}");
        }

        return $stmt;
    }

    public static function param_types(array &$params)
    {
        $types = "";
        foreach ($params as $key => $param)
        {
            switch (gettype($param))
            {
                case "integer":
                    $types .= "i";
                    break;
                case "double":
                    $types .= "d";
                    break;
                case "string":
                default:
                    $types .= "s";
                    $params[$key] = strval($param);
                    break;
            }
        }

        return $types;
    }

    public static function query(string $statement, array $params = [])
    {
        $stmt = static::prepare($statement);
        $types = static::param_types($params);

        if (!empty($params))
        {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute())
        {
            throw new Exception("Query error: " . print_r($stmt->error_list, true));
        }

        return new QueryResult($stmt);
    }
}

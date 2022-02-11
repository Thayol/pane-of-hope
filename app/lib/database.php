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

    public static function prepared_query($statement, $substitutions)
    {
        $db = static::connect();
        $prepared = $db->prepare($statement);

        $types = "";
        foreach ($substitutions as $key => $substitution)
        {
            switch (gettype($substitution))
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
                    $substitutions[$key] = strval($substitution);
                    break;
            }
        }

        if (!empty($substitutions))
        {
            $prepared->bind_param($types, ...$substitutions);
        }
        
        $prepared->execute();

        if (strtoupper(explode(" ", $statement)[0]) == "SELECT")
        {
            $result = $prepared->get_result();
            $assoc = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $assoc;
        }
        else if (strtoupper(explode(" ", $statement)[0]) == "INSERT")
        {
            return $prepared->insert_id;
        }

        return null;
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

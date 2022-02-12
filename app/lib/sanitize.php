<?php

class Sanitize
{
    public static function id($raw)
    {
        $id = intval($raw);

        if ($id > 0)
        {
            return $id;
        }

        return null;
    }

    public static function str($raw)
    {
        return trim(htmlspecialchars_decode($raw, Config::$htmlspecialchars_flags));
    }
}

<?php

class Routes
{
    public static $actions = array(
        "" => "app/controllers/home/home.php",
        "sitemap" => "app/controllers/sitemap/sitemap.php",
        "admin" => "app/controllers/admin/admin.php",

        "error" => "app/controllers/error/error.php",

        "login" => "app/controllers/auth/login.php",
        "register" => "app/controllers/auth/register.php",
        "logout" => "app/controllers/auth/logout.php",

        "profile" => "app/controllers/profile/profile.php",
        "users" => "app/controllers/profile/profile-listing.php",

        "characters" => "app/controllers/character/character-listing.php",
        "character" => "app/controllers/character/character.php",
        "character-new" => "app/controllers/character/character-new.php",
        "character-edit" => "app/controllers/character/character-new.php",
        "character-add-image" => "app/controllers/character/character-add-image.php",
        "character-set-sources" => "app/controllers/character/character-set-sources.php",

        "sources" => "app/controllers/source/source-listing.php",
        "source" => "app/controllers/source/source.php",
        "source-new" => "app/controllers/source/source-new.php",
        "source-edit" => "app/controllers/source/source-new.php",
        "source-set-characters" => "app/controllers/source/source-set-characters.php",
    );

    public static $handlers = array(
        "login" => "app/handlers/auth/login-handler.php",
        "register" => "app/handlers/auth/register-handler.php",

        "character-add-image" => "app/handlers/character/character-add-image-handler.php",
        "character-new" => "app/handlers/character/character-new-handler.php",
        "character-edit" => "app/handlers/character/character-edit-handler.php",
        "character-set-sources" => "app/handlers/character/character-set-sources-handler.php",

        "source-new" => "app/handlers/source/source-new-handler.php",
        "source-edit" => "app/handlers/source/source-edit-handler.php",
        "source-set-characters" => "app/handlers/source/source-set-characters-handler.php",
    );

    public static function error()
    {
        return self::get_action("error");
    }

    public static function action_exists($action)
    {
        return in_array($action, array_keys(self::$actions));
    }

    public static function handler_exists($handler)
    {
        return in_array($handler, array_keys(self::$handlers));
    }

    public static function get_action($action)
    {
        if (self::action_exists($action))
        {
            return self::$actions[$action];
        }

        return self::error();
    }

    public static function get_action_url($action = "", $querystring = "") : string
    {
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

        return Config::$absolute_prefix . $absolute;
    }

    public static function get_handler($handler)
    {
        if (self::handler_exists($handler))
        {
            return self::$handlers[$handler];
        }

        return self::error();
    }

    public static function get_handler_url($handler)
    {
        return Config::$absolute_prefix . "/handler-router/?handler={$handler}";
    }
}

<?php

class Router
{
    public static $current_route = "";

    public static $routes = array(
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
        "character/new" => "app/controllers/character/character-new.php",
        "character/edit" => "app/controllers/character/character-new.php",
        "character/add-image" => "app/controllers/character/character-add-image.php",
        "character/set-sources" => "app/controllers/character/character-set-sources.php",

        "sources" => "app/controllers/source/source-listing.php",
        "source" => "app/controllers/source/source.php",
        "source/new" => "app/controllers/source/source-new.php",
        "source/edit" => "app/controllers/source/source-new.php",
        "source/set-characters" => "app/controllers/source/source-set-characters.php",

        // handlers
        "handler/login" => "app/handlers/auth/login-handler.php",
        "handler/register" => "app/handlers/auth/register-handler.php",

        "handler/character/add-image" => "app/handlers/character/character-add-image-handler.php",
        "handler/character/new" => "app/handlers/character/character-new-handler.php",
        "handler/character/edit" => "app/handlers/character/character-edit-handler.php",
        "handler/character/set-sources" => "app/handlers/character/character-set-sources-handler.php",

        "handler/source/new" => "app/handlers/source/source-new-handler.php",
        "handler/source/edit" => "app/handlers/source/source-edit-handler.php",
        "handler/source/set-characters" => "app/handlers/source/source-set-characters-handler.php",
    );

    public static function current_route()
    {
        return static::$current_route;
    }

    public static function set_current_route($raw_route)
    {
        if (!empty($raw_route))
        {
            static::$current_route = $raw_route;

            if (substr(static::$current_route, -1) == "/")
            {
                static::$current_route = substr_replace(static::$current_route, "", -1);
            }
        }

        return static::current_route();
    }

    public static function error()
    {
        return static::resolve_route("error");
    }

    public static function route_exists($route)
    {
        return in_array($route, array_keys(static::$routes));
    }

    public static function resolve_route($route)
    {
        if (static::route_exists($route))
        {
            return static::$routes[$route];
        }

        return static::error();
    }

    public static function get_url($route = "", $querystring = "") : string
    {
        if (strpos($route, "?") !== false)
        {
            $temp = explode("?", $route);
            $route = $temp[0];
            $querystring = $temp[1];
        }
        $absolute = '/' . $route;

        if (!empty($querystring))
        {
            $absolute .= "?" . $querystring;
        }

        return Config::$absolute_prefix . $absolute;
    }
}

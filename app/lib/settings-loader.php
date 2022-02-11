<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Config
{
    public static $absolute_prefix;
    public static $webroot_subfolder;
    public static $site_title;
    public static $error_reporting;
    public static $show_home_button;
    public static $max_seek_page_numbers;
    public static $listing_page_size;
    public static $htmlspecialchars_flags;
    public static $default_permission_level;
}

class Config_MySQL
{
    public static $address;
    public static $user;
    public static $password;
    public static $database;
}

class Config_Uploads
{
    public static $max_file_size;
    public static $allowed_image_extensions;

    public static $path;
    public static $path_absolute;

    public static $character_images_path;
    public static $character_images_path_absolute;
}

class Config_Accounts
{
    public static $displayname_regex;
    public static $username_regex;
    public static $password_regex;
}

require_once __DIR__ . "/../../config/settings.php";

define("_WEBROOT_", $_SERVER['DOCUMENT_ROOT'] . Config::$webroot_subfolder);
Config_Uploads::$path = _WEBROOT_ . Config_Uploads::$path;
Config_Uploads::$character_images_path = _WEBROOT_ . Config_Uploads::$character_images_path;

if (Config::$error_reporting)
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

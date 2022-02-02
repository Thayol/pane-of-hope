<?php
session_start();

$session_authenticated = false;
$session_username = "Guest";
$session_displayname = "Guest";
$session_email = "";
$session_userid = -1;
$session_is_admin = false;
$session_permission_level = 9;

if (isset($_SESSION["paneofhope"]))
{
    if (empty($_SESSION["paneofhope"]["username"])
        || empty($_SESSION["paneofhope"]["displayname"])
        || !isset($_SESSION["paneofhope"]["userid"])
        || !isset($_SESSION["paneofhope"]["admin"])
        || !isset($_SESSION["paneofhope"]["permission_level"])
        )
    {
        unset($_SESSION["paneofhope"]);
    }

    $session_authenticated = true;
    $session_username = $_SESSION["paneofhope"]["username"];
    $session_displayname = $_SESSION["paneofhope"]["displayname"];
    $session_email = $_SESSION["paneofhope"]["email"];
    $session_userid = $_SESSION["paneofhope"]["userid"];
    $session_is_admin = $_SESSION["paneofhope"]["admin"];
    $session_permission_level = $_SESSION["paneofhope"]["permission_level"];
}

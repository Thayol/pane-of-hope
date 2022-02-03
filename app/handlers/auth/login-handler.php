<?php

$username = $_POST["username"];
$plain_password = $_POST["password"];

$username_valid = preg_match(Config_Accounts::$username_regex, $username) == 1 ? true : false;
$password_valid = preg_match(Config_Accounts::$password_regex, $plain_password) == 1 ? true : false;

$action = "login";

if ($username_valid && $password_valid)
{
    $db = Database::connect();
    $reg_query = $db->query("SELECT id, username, displayname, email, password, permission_level FROM accounts WHERE username='{$username}' ORDER BY id ASC;");

    $is_registered = false;
    if ($reg_query->num_rows > 0)
    {
        $is_registered = true;
    }

    if ($is_registered)
    {
        $reg_arr = $reg_query->fetch_assoc();
        $password_hash = $reg_arr["password"];

        $password_matches = password_verify($plain_password, $password_hash);

        if ($password_matches)
        {
            $perm = $reg_arr["permission_level"];
            if ($perm < 10)
            {
                header('Location: ' . Routes::get_action_url($action, "invalid=banned"));
                exit(0);
            }

            $is_admin = false;
            if ($perm >= 40) $is_admin = true;

            $session_array = array(
                "userid" => $reg_arr["id"],
                "username" => $reg_arr["username"],
                "displayname" => $reg_arr["displayname"],
                "email" => $reg_arr["email"],
                "permission_level" => $perm,
                "admin" => $is_admin,
                "authenticated" => true,
            );

            $_SESSION["paneofhope"] = $session_array;

            header('Location: ' . Routes::get_action_url('profile'));
        }
        else
        {
            header('Location: ' . Routes::get_action_url($action, "invalid=wrongpass"));
        }
    }
    else
    {
        header('Location: ' . Routes::get_action_url($action, "invalid=unregistered"));
    }
}
else
{
    $invalid_values = array();
    foreach ([ "username" => $username_valid, "password" => $password_valid ] as $key => $value)
    {
        if (!$value) $invalid_values[] = $key;
    }

    $invalid_comma_delimited = implode(",", $invalid_values);
    header('Location: ' . Routes::get_action_url($action, "invalid={$invalid_comma_delimited}"));
}
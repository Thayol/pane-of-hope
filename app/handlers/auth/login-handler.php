<?php

$username = $_POST["username"];
$plain_password = $_POST["password"];

$username_valid = preg_match(Config_Accounts::$username_regex, $username) == 1 ? true : false;
$password_valid = preg_match(Config_Accounts::$password_regex, $plain_password) == 1 ? true : false;

$action = "login";

if ($username_valid && $password_valid)
{
    $account = Account::find_by("username", $username);

    if ($account != null)
    {
        if ($account->password_verify($plain_password))
        {
            if ($account->permission_level < 10)
            {
                header('Location: ' . Routes::get_action_url($action, "invalid=banned"));
                exit(0);
            }

            $session_array = array(
                "userid" => $account->id,
                "username" => $account->username,
                "displayname" => $account->displayname,
                "email" => $account->email,
                "permission_level" => $account->permission_level,
                "admin" => $account->is_admin(),
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
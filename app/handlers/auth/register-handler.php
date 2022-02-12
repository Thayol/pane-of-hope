<?php

$displayname = $_POST["displayname"];
$username = $_POST["username"];
$plain_password = $_POST["password"];
$plain_password2 = $_POST["password2"];
$email = $_POST["email"];

$displayname_valid = preg_match(Config_Accounts::$displayname_regex, $displayname) == 1 ? true : false;
$username_valid = preg_match(Config_Accounts::$username_regex, $username) == 1 ? true : false;
$password_valid = preg_match(Config_Accounts::$password_regex, $plain_password) == 1 ? true : false;
$email_valid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? true : false;
$permission_level = Config::$default_permission_level;

$action = "register";

if ($username_valid && $password_valid && $displayname_valid && $email_valid && $plain_password === $plain_password2)
{
    $password = password_hash($plain_password, PASSWORD_DEFAULT);
    $account = Query::new(Account::class)->where("username = ?", $username)->first();

    if ($account == null)
    {
        $columns = implode(",", array(
            "username",
            "displayname",
            "password",
            "email",
        ));

        Query::new(Account::class)->insert()->values([ $username, $displayname, $password, $email, $permission_level ])->commit();

        header('Location: ' . Routes::get_action_url('login', "registered"));
    }
    else
    {
        header('Location: ' . Routes::get_action_url($action, "invalid=registered"));
    }

}
else
{
    $invalid_values = array();
    foreach ([ "username" => $username_valid, "password" => $password_valid, "displayname" => $displayname_valid, "email" => $email_valid ] as $key => $value)
    {
        if (!$value) $invalid_values[] = $key;
    }

    if ($plain_password !== $plain_password2)
    {
        $invalid_values[] = "password2";
    }

    $invalid_comma_delimited = implode(",", $invalid_values);
    header('Location: ' . Routes::get_action_url($action, "invalid={$invalid_comma_delimited}"));
}
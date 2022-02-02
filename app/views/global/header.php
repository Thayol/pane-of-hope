<header class="header">
<h1 class="title"><a href="<?= Routes::get_action_url("") ?>"><?= Config::$site_title ?></a></h1>
<nav class="nav">
<?php
$nav_button = '<a class="nav-button" href="[[ LINK ]]">[[ TEXT ]]</a>';
$nav_button_current = '<a class="nav-button nav-button-current" href="[[ LINK ]]">[[ TEXT ]]</a>';

$nav_buttons = array();

if (Config::$show_home_button)
{
    $nav_buttons["Home"] = "";
}

$nav_buttons["Users"] = "users";
$nav_buttons["Characters"] = "characters";
$nav_buttons["Sources"] = "sources";

$nav_buttons_unauthenticated = array(
    "Log in" => "login",
    "Register" => "register",
);

$nav_buttons_authenticated = array(
    "Profile" => "profile",
    "Log out" => "logout",
);

if ($session_authenticated)
{
    $nav_buttons = array_merge($nav_buttons, $nav_buttons_authenticated);
}
else
{
    $nav_buttons = array_merge($nav_buttons, $nav_buttons_unauthenticated);
}

$nav_buttons["More »"] = "sitemap";

foreach ($nav_buttons as $text => $this_action)
{
    echo str_replace(
        [ "[[ TEXT ]]", "[[ LINK ]]" ],
        [ $text, Routes::get_action_url(str_replace("-", "/", $this_action)) ],
        (strtolower($this_action) == strtolower($action)) ? $nav_button_current : $nav_button);
}
?>
</nav>
</header>
<header class="sub-header">
<nav class="nav">
<?php
if (!empty($context_nav_buttons))
{
    foreach ($context_nav_buttons as $text => $this_action)
    {
        echo str_replace(
            [ "[[ TEXT ]]", "[[ LINK ]]" ],
            [ $text, Routes::get_action_url(str_replace("-", "/", $this_action)) ],
            (strtolower($this_action) == strtolower($action)) ? $nav_button_current : $nav_button);
    }
}
?>
</nav>
</header>
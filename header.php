<header class="header">
<nav class="nav">
<h1 class="title">Pane of Hope</h1>
<?php
$nav_button = '<a class="nav-button" href="https://zovguran.net/pane-of-hope/[[ LINK ]]">[[ TEXT ]]</a>';
$nav_button_current = '<a class="nav-button nav-button-current">[[ TEXT ]]</a>';

$nav_buttons = array(
	"Home" => "",
);

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

foreach ($nav_buttons as $text => $link)
{
	echo str_replace([ "[[ TEXT ]]", "[[ LINK ]]" ], [ $text, $link ], (strtolower($link) == strtolower($action)) ? $nav_button_current : $nav_button);
}
?>
</nav>
</header>
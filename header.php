<header class="header">
<h1 class="title"><a href="<?php echo action_to_link(); ?>"><?php echo $site_title; ?></a></h1>
<nav class="nav">
<?php
$nav_button = '<a class="nav-button" href="[[ LINK ]]">[[ TEXT ]]</a>';
$nav_button_current = '<a class="nav-button nav-button-current" href="[[ LINK ]]">[[ TEXT ]]</a>';

$nav_buttons = array();

if ($show_home_button)
{
	$nav_buttons["Home"] = "";
}

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
		[ $text, action_to_link(str_replace("-", "/", $this_action)) ],
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
			[ $text, action_to_link(str_replace("-", "/", $this_action)) ],
			(strtolower($this_action) == strtolower($action)) ? $nav_button_current : $nav_button);
	}
}
?>
</nav>
</header>
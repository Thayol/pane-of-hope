<header class="header">
<nav class="nav">
<h1 class="title"><a href="<?php echo action_to_link(); ?>">Pane of Hope</a></h1>
<?php
$nav_button = '<a class="nav-button" href="[[ LINK ]]">[[ TEXT ]]</a>';
$nav_button_current = '<a class="nav-button nav-button-current" href="[[ LINK ]]">[[ TEXT ]]</a>';

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
	echo str_replace([ "[[ TEXT ]]", "[[ LINK ]]" ], [ $text, action_to_link($this_action) ], (strtolower($this_action) == strtolower($action)) ? $nav_button_current : $nav_button);
}
?>
</nav>
</header>
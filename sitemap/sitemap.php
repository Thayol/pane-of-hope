<?php
require "../locations.php";
?>
<html>
<head>
<?php
require "../head.php";
?>
</head>
<body>
<?php
require "../header.php";
?>
<main class="main">
<?php
foreach ($locations as $category => $actions)
{
	?><p><?php echo $category ?></p><ul><?php
	foreach ($actions as $text => $action)
	{
		$url = action_to_link($action);
		echo "<li><a href=\"{$url}\">{$text}</a></li>";
	}
	?></ul><?php
}
?>
</main>
<?php
require "../footer.php";
?>
</body>
</html>
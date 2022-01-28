<?php
require __DIR__ . "/../locations.php";
?>
<html>
<head>
<?php
require __DIR__ . "/../head.php";
?>
</head>
<body>
<?php
require __DIR__ . "/../header.php";
?>
<main class="main">
<?php
foreach ($locations as $category => $actions)
{
	?><p><?= $category ?></p><ul><?php
	foreach ($actions as $text => $action)
	{
		$url = action_to_link(str_replace("-", "/", $action));
		echo "<li><a href=\"{$url}\">{$text}</a></li>";
	}
	?></ul><?php
}
?>
</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>
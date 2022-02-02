<html>
<head>
<?php
require __DIR__ . "/../head.php";
?>
</head>
<body>
<?php

$context_nav_buttons["Listing"] = "users";

if ($session_authenticated)
{
	$context_nav_buttons["My profile"] = "profile";
}

require __DIR__ . "/../header.php";
?>
<main class="main">
<?php
$page_size = 10;

$db = Database::connect();
$result = $db->query("SELECT id, displayname, username FROM users ORDER BY id ASC LIMIT {$page_size};");

$users = array();
if ($result->num_rows > 0)
{
	while ($user = $result->fetch_assoc())
	{
		$users[$user["id"]] = $user;
	}
}
?>

<?php if (empty($users)): ?>
<p>There are no characters in the database. (Or there is a database error.)</p>
<?php else: ?>

<table class="table-wide alternating-rows">
	<tbody>

<?php
foreach ($users as $user)
{
	$id = $user["id"];
	$displayname = $user["displayname"];
	$username = $user["username"];
	
	$url = action_to_link("profile") . "?u={$id}";
	
	echo '<tr>';
	echo "<td><a href=\"{$url}\">{$displayname}</a> <small>({$username})</small></td>";
	echo '</tr>';
}
echo "</tbody></table>";
endif; ?>

</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>
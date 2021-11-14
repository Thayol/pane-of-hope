<html>
<head>
<?php
require __DIR__ . "/../head.php";
?>
</head>
<body>
<?php
$context_nav_buttons = array();
if ($session_is_admin)
{
	$context_nav_buttons["New"] = "character-new";
}

require __DIR__ . "/../header.php";
?>
<main class="main">
<?php
$page_size = 10;

require __DIR__ . "/../dbconnection.php";
$result = $db->query("SELECT * FROM characters ORDER BY id ASC LIMIT {$page_size};");

$characters = array();
if ($result->num_rows > 0)
{
	while ($character = $result->fetch_assoc())
	{
		$characters[$character["id"]] = $character;
	}
}
?>

<?php if (empty($characters)): ?>
<p>There are no characters in the database. (Or there is a database error.)</p>
<?php else:
echo '<table class="table-wide"><thead><tr><td>Name</td><td>Gender</td><td>ID</td></tr></thead><tbody>';
foreach ($characters as $character)
{
	$id = $character["id"];
	$name = $character["name"];
	$og_name = $character["original_name"];
	$gender_raw = $character["gender"];
	
	if ($og_name != null)
	{
		$name .= " ($og_name)";
	}
	
	$gender = "";
	if ($gender_raw == 1) $gender = "♀";
	if ($gender_raw == 2) $gender = "♂";
	
	$url = action_to_link("character") . "?id={$id}";
	
	echo '<tr>';
	echo "<td><a href=\"{$url}\">{$name}</a></td>";
	echo "<td>{$gender}</td>";
	echo "<td>{$id}</td>";
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
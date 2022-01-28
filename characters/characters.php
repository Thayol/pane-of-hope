<html>
<head>
<?php
require __DIR__ . "/../head.php";
?>
</head>
<body>
<?php

$context_nav_buttons["Listing"] = "characters";

if ($session_is_admin)
{
	$context_nav_buttons["New"] = "character-new";
}

require __DIR__ . "/../header.php";
?>
<main class="main">
<?php
$page = 1; // if not set
$page_count = 1; // default fallback
$page_size = $listing_page_size;

$db = db_connect();
$count_result = $db->query("SELECT COUNT(id) as char_count FROM characters;");
if ($count_result->num_rows > 0)
{
	$page_count = ceil($count_result->fetch_assoc()["char_count"] / $page_size);
}

if (!empty($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
{
	$page = intval($_GET["page"]);
}

$offset = ($page - 1) * $page_size;
$result = $db->query("SELECT * FROM characters ORDER BY id ASC LIMIT {$page_size} OFFSET {$offset};");

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
<?php else: ?>

<table class="table-wide thead-separator alternating-rows">
	<thead>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Gender</td>
		</tr>
	</thead>
	<tbody>

<?php
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
	
	$gender = "?";
	if ($gender_raw == 1) $gender = "♀";
	if ($gender_raw == 2) $gender = "♂";
	
	$url = action_to_link("character") . "?id={$id}";
	
	echo '<tr>';
	echo "<td>{$id}</td>";
	echo "<td><a href=\"{$url}\">{$name}</a></td>";
	echo "<td>{$gender}</td>";
	echo '</tr>';
}
echo "</tbody></table>";

echo "<nav>Page: ";
for ($i = $page - $max_seek_page_numbers; $i <= $page + $max_seek_page_numbers; $i++)
{
	if ($i > 0 && $i <= $page_count)
	{
		$url = action_to_link($action, "page={$i}");
		$class = "nav-button";
		if ($i == $page)
		{
			$class .= " nav-button-current";
		}
		
		echo "<a class=\"{$class}\" href=\"{$url}\">{$i}</a> ";
	}
}
echo "</nav>";

endif; ?>

</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>
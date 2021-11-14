<?php

$character = array();
if (!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	if ($id > 0)
	{
		$db = db_connect();
		$result = $db->query("SELECT * FROM characters WHERE id={$id} ORDER BY id ASC;");
		if ($result->num_rows == 1)
		{
			$character_temp = $result->fetch_assoc();
			
			$character["name"] = $character_temp["name"];
			$character["original_name"] = empty($character_temp["original_name"]) ? "" : " (" . $character_temp["original_name"] . ")";
			
			if ($character_temp["gender"] == 1) $character["gender"] = "Female";
			else if ($character_temp["gender"] == 2) $character["gender"] = "Male";
			else $character["gender"] = "N/A";
		}
	}
}

$context_nav_buttons["List"] = "characters";
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
<?php if (empty($character)): ?>
<p>Character not found.</p>
<?php else:
echo "<h2>{$character["name"]}{$character["original_name"]}</h2>";
echo "<p>Gender: {$character["gender"]}</p>";
endif; ?>
</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>
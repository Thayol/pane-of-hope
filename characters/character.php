<?php
$character_found = false;
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
			$character_found = true;
			$character_temp = $result->fetch_assoc();
			
			$character["name"] = $character_temp["name"];
			$character["original_name"] = empty($character_temp["original_name"]) ? "" : "(" . $character_temp["original_name"] . ")";
			
			if ($character_temp["gender"] == 1) $character["gender"] = "Female";
			else if ($character_temp["gender"] == 2) $character["gender"] = "Male";
			else $character["gender"] = "N/A";
			
			$character["images"] = array();
			
			$db = db_connect();
			$result = $db->query("SELECT * FROM character_images WHERE character_id={$id} ORDER BY id ASC;");
			if ($result->num_rows > 0)
			{
				while ($image_temp = $result->fetch_assoc())
				{
					$character["images"][] = $image_temp["path"];
				}
			}
		}
	}
}

$context_nav_buttons["Listing"] = "characters";

if ($session_is_admin)
{
	$context_nav_buttons["New"] = "character-new";
	if ($character_found)
	{
		$context_nav_buttons["Edit"] = "character-edit?id={$id}";
		$context_nav_buttons["Upload image"] = "character-upload?id={$id}";
	}
}

if (isset($_GET["edited"]))
{
	$notice_success = "Character edited.";
}
else if (isset($_GET["created"]))
{
	$notice_success = "Character created.";
}

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
<?php require __DIR__ . "/../notice.php"; ?>
<?php if (!$character_found): ?>
<p>Character not found.</p>
<?php else: ?>
<h2><?= $character["name"] ?> <?= $character["original_name"] ?></h2>
<p>Gender: <?= $character["gender"] ?></p>
<div>
<?php foreach ($character["images"] as $image): ?>
<div class="character-image">
<img src="<?= $image ?>">
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>
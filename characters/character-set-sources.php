<?php

if (isset($_GET["error"]))
{
	$notice_error = "Database error!";
}

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
		}

		$char_sources = array();

		$db = db_connect();
		$result = $db->query("SELECT * FROM conn_character_source AS conn WHERE conn.character_id={$id} ORDER BY id ASC;");
		if ($result->num_rows > 0)
		{
			while ($conn = $result->fetch_assoc())
			{
				$char_sources[] = $conn["source_id"];
			}
		}

		$sources = array();

		$db = db_connect();
		$result = $db->query("SELECT * FROM sources ORDER BY title ASC;");
		if ($result->num_rows > 0)
		{
			while ($source = $result->fetch_assoc())
			{
				$sources[$source["id"]] = array(
					"id" => $source["id"],
					"title" => $source["title"],
					"checked" => in_array($source["id"], $char_sources),
				);
			}
		}
	}
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

<?php if ($session_is_admin): ?>

<?php

if (!$character_found): ?>
<p>Character not found.</p>
<?php else: ?>

<form class="login-form" action="<?= action_to_link("characters") ?>character-set-sources-handler.php" method="POST">
<h2>Manage sources of <?= $character["name"] ?> <?= $character["original_name"] ?></h2>
<input type="hidden" name="id" value="<?= $id ?>">

<div class="left-align">
<?php foreach ($sources as $source): ?>
<input type="checkbox" name="sources[]" id="source<?= $source["id"] ?>" value="<?= $source["id"] ?>" <?php if ($source["checked"]) echo "checked"; ?>>
<label for="source<?= $source["id"] ?>"><?= $source["title"] ?></label>
<br>
<?php endforeach; ?>
</div>

<input class="input-submit" type="submit" value="Save">
</form>
<?php endif;
else: ?>
<p>Unauthorized.</p>
<?php endif; ?>

</main>
<?php
require __DIR__ . "/../footer.php";
?>
</body>
</html>
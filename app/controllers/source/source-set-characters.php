<?php

if (isset($_GET["error"]))
{
	$notice_error = "Database error!";
}

$source_found = false;
$source = array();

if (!empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	if ($id > 0)
	{
		$db = Database::connect();
		$result = $db->query("SELECT * FROM sources WHERE id={$id} ORDER BY id ASC;");
		if ($result->num_rows == 1)
		{
			$source_found = true;
			$source_temp = $result->fetch_assoc();

			$source["title"] = $source_temp["title"];
		}

		$source_chars = array();

		$db = Database::connect();
		$result = $db->query("SELECT * FROM conn_character_source AS conn WHERE conn.source_id={$id} ORDER BY id ASC;");
		if ($result->num_rows > 0)
		{
			while ($conn = $result->fetch_assoc())
			{
				$source_chars[] = $conn["character_id"];
			}
		}

		$characters = array();

		$db = Database::connect();
		$result = $db->query("SELECT * FROM characters ORDER BY name ASC;");
		if ($result->num_rows > 0)
		{
			while ($character = $result->fetch_assoc())
			{
				$characters[$character["id"]] = array(
					"id" => $character["id"],
					"name" => $character["name"],
					"og_name" => $character["original_name"] ?? "",
					"checked" => in_array($character["id"], $source_chars),
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

if (!$source_found): ?>
<p>Source not found.</p>
<?php else: ?>

<form class="login-form" action="<?= action_to_link("sources") ?>source-set-characters-handler.php" method="POST">
<h2>Manage characters of <?= $source["title"] ?></h2>
<input type="hidden" name="id" value="<?= $id ?>">

<div class="left-align">
<?php foreach ($characters as $character): ?>
<input type="checkbox" name="characters[]" id="character<?= $character["id"] ?>" value="<?= $character["id"] ?>" <?php if ($character["checked"]) echo "checked"; ?>>
<label for="character<?= $character["id"] ?>"><?= $character["name"] ?><?= empty($character["og_name"]) ? "" : " ({$character['og_name']})" ?></label>
<br>
<?php endforeach; ?>
</div>

<input class="input-submit" type="submit" value="Upload">
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
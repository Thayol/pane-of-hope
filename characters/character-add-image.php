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

<?php if ($session_is_admin): ?>

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
		}
	}
}

if (!$character_found): ?>
<p>Character not found.</p>
<?php else: ?>

<form class="login-form" action="<?= action_to_link("characters") ?>character-add-image-handler.php" method="POST" enctype="multipart/form-data">
<h2>Add image for <?= $character["name"] ?> <?= $character["original_name"] ?></h2>
<input type="hidden" name="id" value="<?= $id ?>">
<input class="input-file" type="file" name="uploadfile" value=""><br>

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
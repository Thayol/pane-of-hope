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
	$character = null;

	if (!empty($_GET["id"]))
	{
		$character = Database::characters()->find_by_raw_id($_GET["id"]);
	}

	if ($character == null): ?>
	<p>Character not found.</p>
	<?php else: ?>

	<form class="login-form" action="<?= action_to_link("characters") ?>character-add-image-handler.php" method="POST" enctype="multipart/form-data">
	<h2>Add image for <?= $character->name ?> <?= empty($character->original_name) ? "" : "({$character->original_name})" ?></h2>
	<input type="hidden" name="id" value="<?= $character->id ?>">
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
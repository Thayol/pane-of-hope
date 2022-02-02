<?php

if (isset($_GET["error"]))
{
	$notice_error = "Database error!";
}

$character = null;

if (!empty($_GET["id"]))
{
	$character = Database::characters()->find_by_raw_id($_GET["id"]);
	$sources = Database::sources()->all();

	usort($sources, fn($a, $b) => strnatcmp($a->title, $b->title));
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

if ($character == null): ?>
<p>Character not found.</p>
<?php else: ?>

<form class="login-form" action="<?= action_to_link("characters") ?>character-set-sources-handler.php" method="POST">
<h2>Manage sources of <?= $character->name ?> <?= empty($character->original_name) ? "" : "({$character->original_name})" ?></h2>
<input type="hidden" name="id" value="<?= $character->id ?>">

<div class="left-align">
<?php foreach ($sources as $source): ?>
<input type="checkbox" name="sources[]" id="source<?= $source->id ?>" value="<?= $source->id ?>" <?php if (in_array($source, $character->sources())) echo "checked"; ?>>
<label for="source<?= $source->id ?>"><?= $source->title ?></label>
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
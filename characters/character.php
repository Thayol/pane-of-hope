<?php
$character = null;

if (!empty($_GET["id"]))
{
	$character = load_character_or_null($_GET["id"]);
}

$context_nav_buttons["Listing"] = "characters";

if ($session_is_admin)
{
	$context_nav_buttons["New"] = "character-new";
	if ($character != null)
	{
		$context_nav_buttons["Edit"] = "character-edit?id={$character->id}";
		$context_nav_buttons["Upload image"] = "character-upload?id={$character->id}";
		$context_nav_buttons["Manage sources"] = "character-set-sources?id={$character->id}";
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
else if (isset($_GET["uploaded"]))
{
	$notice_success = "Image uploaded.";
}
else if (isset($_GET["sources_updated"]))
{
	$notice_success = "Sources updated.";
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
<?php if ($character == null): ?>
<p>Character not found.</p>
<?php else: ?>
<h2><?= $character->name ?> <?= empty($character->original_name) ? "" : "({$character->original_name})" ?></h2>
<p>Gender: <?= $character->pretty_gender() ?></p>

<?php if (!empty($character->sources())): ?>
<div>
<?php $sources_text = count($character->sources()) > 1 ? "Sources" : "Source"?>
<p><?= $sources_text ?>:</p>
<ul>
<?php
	foreach ($character->sources() as $source):
	$url = action_to_link("source") . "?id={$source->id}";
?>
<li><a href="<?= $url ?>"><?= $source->title ?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<div>
<?php foreach ($character->images() as $image): ?>
<div class="character-image">
<img src="<?= $image->path ?>">
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
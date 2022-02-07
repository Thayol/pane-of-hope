<?php

if (isset($_GET["error"]))
{
    $notice_error = "Database error!";
}

$character = null;

if (!empty($_GET["id"]))
{
    $character = Query::new(Character::class)->find(Sanitize::id($_GET["id"]));
    $sources = Query::new(Source::class)->all();

    usort($sources, fn($a, $b) => strnatcmp($a->title, $b->title));
}

?>
<html>
<head>
<?php
require _WEBROOT_ . "/app/views/global/head.php";
?>
</head>
<body>
<?php
require _WEBROOT_ . "/app/views/global/header.php";
?>
<main class="main">
<?php require _WEBROOT_ . "/app/views/global/notice.php"; ?>

<?php if ($session_is_admin): ?>

<?php

if ($character == null): ?>
<p>Character not found.</p>
<?php else: ?>

<form class="login-form" action="<?= Routes::get_handler_url("character-set-sources") ?>" method="POST">
<h2>Manage sources of <?= $character->pretty_name() ?></h2>
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
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
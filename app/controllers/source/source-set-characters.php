<?php

if (isset($_GET["error"]))
{
    $notice_error = "Database error!";
}

$source = null;
$characters = null;

if (!empty($_GET["id"]))
{
    $source = Source::find(Sanitize::id($_GET["id"]));
    $characters = Character::all();
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

if ($source == null): ?>
    <p>Source not found.</p>
<?php else: ?>

<form class="login-form" action="<?= Routes::get_handler_url("source-set-characters") ?>" method="POST">
<h2>Manage characters of <?= $source->title ?></h2>
<input type="hidden" name="id" value="<?= $source->id ?>">

<div class="left-align">
<?php foreach ($characters as $character): ?>
<input type="checkbox" name="characters[]" id="character<?= $character->id ?>" value="<?= $character->id ?>" <?php if (in_array($character, $source->characters())) echo "checked"; ?>>
<label for="character<?= $character->id ?>"><?= $character->pretty_name() ?></label>
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
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
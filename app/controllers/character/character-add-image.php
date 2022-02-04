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

<?php if ($session_is_admin): ?>

    <?php
    $character = null;

    if (!empty($_GET["id"]))
    {
        $character = Query::new(Character::class)->find(Sanitize::id($_GET["id"]));
    }

    if ($character == null): ?>
    <p>Character not found.</p>
    <?php else: ?>

    <form class="login-form" action="<?= Routes::get_handler_url("character-add-image") ?>" method="POST" enctype="multipart/form-data">
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
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
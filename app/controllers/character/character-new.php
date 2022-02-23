<?php

if (isset($_GET["invalid"]))
{
    $notice_error = "Could not add/edit character.";
}
else if (isset($_GET["error"]))
{
    $notice_error = "Database error!";
}

$context_nav_buttons["Listing"] = "characters";

if ($session_is_admin)
{
    $context_nav_buttons["New"] = "character/new";
    if (Router::current_route() == "character/edit") $context_nav_buttons["Edit"] = "character/edit";
}

$id = "";
$name = "";
$original_name = "";
$gender = 0;

if (Router::current_route() == "character/edit")
{
    $character = Character::find(Sanitize::id($_GET["id"]));
    $character_found = false;
    if ($character != null)
    {
        $id = $character->id;
        $name = htmlspecialchars_decode($character->name, Config::$htmlspecialchars_flags);
        $original_name = htmlspecialchars_decode($character->original_name, Config::$htmlspecialchars_flags);
        $gender = $character->gender;
    }
}

if (Router::current_route() == "character/edit")
{
    $form_action = Router::get_url("handler/character/edit");
    $title_text = "Edit Character";
    $submit_text = "Update";
}
else
{
    $form_action = Router::get_url("handler/character/new");
    $title_text = "New Character";
    $submit_text = "Create";
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
<form class="login-form" action="<?= $form_action ?>" method="POST">

<h2><?= $title_text ?></h2>

<label class="input-label">Name:</label>
<input class="input-textbox" type="text" name="name" value="<?= htmlspecialchars($name, ENT_COMPAT) ?>" placeholder="English Name" required><br>

<label class="input-label">Original Name:</label>
<input class="input-textbox" type="text" name="original_name" value="<?= htmlspecialchars($original_name, ENT_COMPAT) ?>" placeholder="Original Name"><br>

<label class="input-label">Gender:</label>
<select class="input-select" name="gender">
    <option value="0" <?php if ($gender == 0) echo "selected"; ?>>N/A</option>
    <option value="1" <?php if ($gender == 1) echo "selected"; ?>>Female</option>
    <option value="2" <?php if ($gender == 2) echo "selected"; ?>>Male</option>
</select><br>

<input type="hidden" name="id" value="<?= $id ?>">

<input class="input-submit" type="submit" value="<?= $submit_text ?>">
</form>
<?php else: ?>
<p>Unauthorized.</p>
<?php endif; ?>

</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
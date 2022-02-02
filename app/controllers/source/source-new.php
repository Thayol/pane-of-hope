<?php

if (isset($_GET["invalid"]))
{
    $notice_error = "Could not add source.";
}

if (isset($_GET["error"]))
{
    $notice_error = "Database error!";
}

$context_nav_buttons["Listing"] = "sources";

if ($session_is_admin)
{
    $context_nav_buttons["New"] = "source-new";
    if ($action == "source-edit") $context_nav_buttons["Edit"] = "source-edit";
}

$id = "";
$title = "";
$aliases = array();

if ($action == "source-edit")
{
    $source_found = false;
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

                $id = $source_temp["id"];
                $title = htmlspecialchars_decode($source_temp["title"], Config::$htmlspecialchars_flags);

                $db = Database::connect();
                $result = $db->query("SELECT * FROM source_aliases WHERE source_id={$id} ORDER BY id ASC;");
                if ($result->num_rows > 0)
                {
                    while ($alias = $result->fetch_assoc())
                    {
                        $aliases[] = $alias["alias"];
                    }
                }
            }
        }
    }
}

if ($action == "source-edit")
{
    $form_action = Routes::get_handler_url("source-edit");
    $title_text = "Edit Source";
    $submit_text = "Update";
}
else
{
    $form_action = Routes::get_handler_url("source-new");
    $title_text = "New Source";
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

<label class="input-label">Title:</label>
<input class="input-textbox" type="text" name="title" value="<?= htmlspecialchars($title, ENT_COMPAT) ?>" placeholder="Title" required><br>

<label class="input-label">Alias(es):</label>
<textarea class="input-multiline-textbox" type="text" name="aliases" placeholder="One alias per line..."><?= implode("\n", $aliases) ?></textarea><br>

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
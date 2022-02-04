<?php
$source = null;

if (!empty($_GET["id"]))
{
    $source = Query::new(Source::class)->find(Sanitize::id($_GET["id"]));
}

$context_nav_buttons["Listing"] = "sources";

if ($session_is_admin)
{
    $context_nav_buttons["New"] = "source-new";
    if ($source != null)
    {
        $context_nav_buttons["Edit"] = "source-edit?id={$source->id}";
        $context_nav_buttons["Manage characters"] = "source-set-characters?id={$source->id}";
    }
}

if (isset($_GET["edited"]))
{
    $notice_success = "Source edited.";
}
else if (isset($_GET["created"]))
{
    $notice_success = "Source created.";
}
else if (isset($_GET["characters_updated"]))
{
    $notice_success = "Characters updated.";
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
<?php if ($source == null): ?>
    <p>Source not found.</p>
<?php else: ?>

<h2><?= $source->title ?></h2>
<?php foreach ($source->aliases() as $alias): ?>
<p><?= $alias->alias ?></P>
<?php endforeach; ?>

<?php if (!empty($source->characters())): ?>
<div>
<p>Characters:</p>
<ul>
<?php
    foreach ($source->characters() as $character):
    $url = Routes::get_action_url("character", "id={$character->id}");
?>
<li><a href="<?= $url ?>"><?= $character->name ?></a></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<?php endif; ?>
</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
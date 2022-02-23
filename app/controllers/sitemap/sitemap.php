<?php
require _WEBROOT_ . "/config/locations.php";
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
<?php
foreach ($locations as $category => $actions)
{
    ?><p><?= $category ?></p><ul><?php
    foreach ($actions as $text => $action)
    {
        $url = Router::get_url(str_replace("-", "/", $action));
        echo "<li><a href=\"{$url}\">{$text}</a></li>";
    }
    ?></ul><?php
}
?>
</main>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
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
<ul>
<?php
foreach (Router::$routes as $route => $file)
{
    if (strpos($route, "handler/") === 0)
    {
        continue;
    }

    $text = ucwords(str_replace([ "/", "-" ], [ ": ", " " ], $route));
    if (empty($text))
    {
        $text = "Home";
    }

    $url = Router::get_url($route);
    echo "<li><a href=\"{$url}\">{$text}</a></li>";
}
?>
</main>
</ul>
<?php
require _WEBROOT_ . "/app/views/global/footer.php";
?>
</body>
</html>
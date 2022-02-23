<?php

require_once __DIR__ . "/init.php";

$route = Router::set_current_route($_GET["r"] ?? "error");

// echo "<pre>";
// print_r($_GET);
// exit(0);

if (Router::route_exists($route))
{
    require _WEBROOT_ . "/" . Router::resolve_route($route);
}
else
{
    require _WEBROOT_ . "/" . Router::error();
}



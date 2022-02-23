<?php
if (isset($_SESSION["paneofhope"])) unset($_SESSION["paneofhope"]);

header('Location: ' . Router::get_url("login"));
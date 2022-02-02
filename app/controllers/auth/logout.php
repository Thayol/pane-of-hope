<?php
if (isset($_SESSION["paneofhope"])) unset($_SESSION["paneofhope"]);

header('Location: ' . Routes::get_action_url("login"));